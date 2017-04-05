<?php
require_once("/etc/apache2/encrypted-config/encrypted-config.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
require_once (dirname(__DIR__) . "/classes/autoload.php");

use Deepdivedylan\DylanAndTonyFiesta\{Media, Message, Profile};
use OwlyCode\StreamingBird\StreamReader;
use OwlyCode\StreamingBird\StreamingBird;

try {
	$config = readConfig("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
	$twitter = json_decode($config["twitter"]);

	$bird = new StreamingBird($twitter->consumerKey, $twitter->consumerSecret, $twitter->accessToken, $twitter->accessTokenSecret);

	$bird
		->createStreamReader(StreamReader::METHOD_FILTER)
		->setTrack(["#dylanandtonyfiesta", "#tonyanddylanfiesta"])
		->consume(function ($tweet) {
			$pdo = connectToEncryptedMySQL("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");

			$timestamp = $tweet["timestamp_ms"] / 1000;
			$messageDateTime = \DateTime::createFromFormat("U.u", $timestamp);

			$profile = Profile::getProfileByProfileId($pdo, $tweet["user"]["id_str"]);
			if($profile === null) {
				$profile = new Profile($tweet["user"]["id_str"], utf8_encode($tweet["user"]["screen_name"]), "T");
				$profile->insert($pdo);
			}

			$message = Message::getMessageByMessageId($pdo, $tweet["id_str"]);
			if($message === null) {
				$message = new Message($tweet["id_str"], $profile->getProfileId(), utf8_encode($tweet["text"]), $messageDateTime);
				$message->insert($pdo);
			}

			$mediaArray = $tweet["entities"]["media"] ?? [];
			foreach($mediaArray as $tweetMedia) {
				$media = Media::getMediaByMediaId($pdo, $tweetMedia["id_str"]);
				if($media === null) {
					$mediaType = $tweetMedia["type"] === "photo" ? "image/jpeg" : "video/mp4";
					$media = new Media($tweetMedia["id_str"], $message->getMessageId(), $mediaType, $tweetMedia["media_url_https"]);
					$media->insert($pdo);
				}
			}
		});
} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . PHP_EOL;
}
