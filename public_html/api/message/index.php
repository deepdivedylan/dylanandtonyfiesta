<?php
require_once("/etc/apache2/encrypted-config/encrypted-config.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

use Deepdivedylan\DylanAndTonyFiesta\{Media, Message, Profile};

function createCard(?Media $media, Message $message, Profile $profile) : string {

	if($media !== null) {
		$mediaUrl = $media->getMediaUrl();
		if(strpos($media->getMediaType(), "image") === 0) {
			$mediaHtml = <<< EOF
			<div class="card-image">
				<img class="img-responsive center-block" src="$mediaUrl" alt="" />
			</div>
EOF;
		} else if(strpos($media->getMediaType(), "video") === 0) {
			$mediaType = $media->getMediaType();
			$mediaHtml = <<< EOF
			<div class="card-image">
				<div class="embed-responsive-4by3 embed-responsive">
					<video controls loop>
						<source src="$mediaUrl" type="$mediaType" />
					</video>
				</div>
			</div>
EOF;
		} else {
			$mediaHtml = "";
		}
	} else {
		$mediaHtml = "";
	}

	$profileName = $profile->getProfileName();
	$messageContent = $message->getMessageContent();
	$messageDateTime = $message->getMessageDateTime()->format("Y-m-d H:i:s");
	$cardHtml = <<< EOF
	<div class="col-md-6">
		<div class="card">
			<h2 class="card-title"><a href="https://twitter.com/$profileName">@$profileName</a></h2>
			<div class="card-body">
				$mediaHtml
				<div class="card-text">$messageContent</div>
		</div>
			<div class="card-footer">
				<span class="card-footer-author"></span>
				<span class="card-footer-separator"></span>
				<span class="card-footer-date">$messageDateTime</span>
			</div>
		</div>
	</div>
EOF;

	return($cardHtml);
}

//prepare an empty reply
$html = "";

try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
	$results = Message::emergencyBeamOut($pdo);

	$html = "";
	$index = 0;
	foreach($results  as $message) {
		if($index % 2 === 0) {
			$html = $html . "<div class=\"row\">" . PHP_EOL;
		}

		$html = $html . createCard($results[$message]["media"], $message, $results[$message]["profile"]);
		$index++;

		if($index % 2 === 0) {
			$html = $html . "</div>" . PHP_EOL;
		}
	}

	if($index % 2 === 1) {
		$html = $html . PHP_EOL . <<< EOF
	<div class="col-md-6">
		&nbsp;
	</div>
</div>
EOF;

	}
	echo $html;
} catch(\Exception | \TypeError $exception ) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}