<?php
/**
 * generates a signature for the API request
 *
 * @param string $endpoint API endpoint to connect to
 * @param array $params parameters to sign
 * @param string $secret application secret
 * @return string signature for API request
 * @see https://www.instagram.com/developer/secure-api-requests/ Instagram secure requests
 **/
function generateSignature(string $endpoint, array $params, string $secret) {
	$sig = $endpoint;
	ksort($params);
	foreach ($params as $key => $val) {
		$sig .= "|$key=$val";
	}
	return hash_hmac("sha256", $sig, $secret, false);
}
