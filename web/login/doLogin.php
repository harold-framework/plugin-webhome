<?php

include_once("../config.php");
include_once("../authenticationManager.php");

$redirect_uri = urlencode(utf8_encode(($CONFIG["BASEURL"]."/login")));

setcookie("user_token", false, time() - 604800);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!(isset($_GET["code"]))) {

	header("Location: https://discord.com/api/oauth2/authorize?client_id=" . $CONFIG["DISCORD_APP_ID"] . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=identify");
	die();

} else {
	
	$x = file_get_contents($CONFIG["APIURL"]."/token/" . $_GET["code"] . "?key=" . $CONFIG["KEY"]);
	$output = (json_decode($x));

	if (!($output->success)) {
		header("Location: ../?e=".urlencode($output->error_message));
		die();
	}
	setcookie("user_token", $output->token, time() + 604800, "/"); // Set it for the token expiration date.
	header("Location: " . $CONFIG["BASEURL"]);
	die();

}
