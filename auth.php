<?php
if(!defined("____GUESS_GAME____")) header("Location: index.php");
session_set_cookie_params (1*24*60*60, '/');
session_start([
	'cookie_lifetime' => 1*24*60*60,
]);