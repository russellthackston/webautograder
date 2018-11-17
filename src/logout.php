<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('autoloader.php');
$db = new DB();

if (isset($_COOKIE['wag_sessionid'])) {
	$sessionid = $_COOKIE['wag_sessionid']);
	$db->deleteSession($sessionid);
}

setcookie("wag_sessionid", "", time()-3600);
header('Location: login.php');
exit();


?>
