<?php
require_once("/etc/apache2/encrypted-config/encrypted-config.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
require_once(__DIR__ . "/instagram-secure-request.php");

use GuzzleHttp\Client;

try {
	$config = readConfig("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
	$instagram = json_decode($config["instagram"]);
	$endpoint = "/tags/newmexicotrue/media/recent";

	$parameters = [
		"access_token" => $instagram->accessToken,
		"count" => 64
	];
	$parameters["sig"] = generateSignature($endpoint, $parameters, $instagram->clientSecret);

	$guzzle = new Client();
	$response = $guzzle->get("https://api.instagram.com/v1$endpoint", ["query" => $parameters]);
	if($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
		$body = (string)$response->getBody();
		$results = json_decode($body);
		if($results->meta->code !== 200) {
			throw(new RuntimeException("Unable to process Instagram results: " . $results->meta->error_message, $results->meta->code));
		}

		var_dump($results->data);
	} else {
		throw(new RuntimeException("Unable to connect to Instagram: " . $response->getReasonPhrase(), $response->getStatusCode()));
	}

} catch(\Exception $exception) {
	echo "Exception: " . $exception->getMessage();
}
