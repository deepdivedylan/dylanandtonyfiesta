<?php
require_once("/etc/apache2/encrypted-config/encrypted-config.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use OwlyCode\StreamingBird\StreamReader;
use OwlyCode\StreamingBird\StreamingBird;

try {
	$config = readConfig("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
	$twitter = json_decode($config["twitter"]);

	$bird = new StreamingBird($twitter->consumerKey, $twitter->consumerSecret, $twitter->accessToken, $twitter->accessTokenSecret);

	$bird
		->createStreamReader(StreamReader::METHOD_FILTER)
		->setTrack(["#gopdnd"])
		->consume(function ($tweet) {
			echo "------------------------" . PHP_EOL;
			echo json_encode($tweet) . PHP_EOL;
		});
} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . PHP_EOL;
}
