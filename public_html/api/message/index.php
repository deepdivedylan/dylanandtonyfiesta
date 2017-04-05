<?php

require_once("/etc/apache2/encrypted-config/encrypted-config.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

use Deepdivedylan\DylanAndTonyFiesta\{JsonObjectStorage, Media, Message, Profile};

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
	$reply->data = Message::emergencyBeamOut($pdo);
} catch(\Exception | \TypeError $exception ) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);