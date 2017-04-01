<?php
require_once("/etc/apache2/encrypted-config/encrypted-config.php");

try {
	$config = readConfig("/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
	$instagram = json_decode($config["instagram"]);

	if(empty($_GET["code"]) === true) {
		$urlglue = "https://api.instagram.com/oauth/authorize/?client_id=" . $instagram->clientId . "&redirect_uri=" . urlencode("https://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"])  . "&response_type=code";
		?>
		<!DOCTYPE html>
		<html>
			<head>
				<title>Authorize Instagram</title>
			</head>
			<body>
				<p><a href="<?php echo $urlglue; ?>">Authenticate with Instagram</a></p>
			</body>
		</html>
		<?php
	} else {
		$options = ["http" => [
			"method" => "POST",
			"header" => "Content-type: application/x-www-form-urlencoded\r\n",
			"content" => http_build_query([
				"client_id" => $instagram->clientId,
				"client_secret" => $instagram->clientSecret,
				"code" => $_GET["code"],
				"grant_type" => "authorization_code",
				"redirect_uri" => "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"]
				])
			]];
		$context = stream_context_create($options);
		$reply = json_decode(file_get_contents("https://api.instagram.com/oauth/access_token", false, $context));
		if($reply === false) {
			throw(new \RuntimeException("Unable to parse Instagram's reply", 400));
		} else if(empty($reply->access_token) === true) {
			throw(new \RuntimeException("Instagram denied access", 403));
		} else {
			$instagram->accessToken = $reply->access_token;
			$config["instagram"] = json_encode($instagram);
			writeConfig($config, "/etc/apache2/encrypted-config/dylanandtonyfiesta.ini");
			echo "Configuration file updated OK";
		}
	}
} catch(\Exception $exception) {
	echo "Exception: " . $exception->getMessage();
}
