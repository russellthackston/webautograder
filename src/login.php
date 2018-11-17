<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('autoloader.php');
$db = new DB();
$loginAttempt = FALSE;
$user = NULL;

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$loginAttempt = TRUE;
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	try {
		$user = $db->getUserByUsername($username);
		$passwordhash = $user->passwordhash;
		$goodPassword = password_verify($password, $passwordhash);
		if ($goodPassword) {
			$session = $db->newSession($user->id);
			setcookie('wag_sessionid', $session->id, time()+(3600*24*120));
			header('Location: index.php');
			exit();

		}
	} catch( PDOException $e ) {
		$user = NULL;
	}
}

?>
<!doctype html>
<html lang="en">
	<head>
	  <meta charset="utf-8">
	
	  <title>Webpage Autograder</title>
	  <meta name="description" content="An experimental web page autograder based on XPath queries">
	  <meta name="author" content="Russell Thackston">
	
	</head>
	
	<body>
		<?php require('nav.php'); ?>
		<?php if ($loginAttempt && ($user == NULL || !$goodPassword)) { ?>
			<div>Login failed.</div>
		<?php } ?>
		<?php if ($loginAttempt && $user != NULL && $goodPassword) { ?>
			<div>Login successful.</div>
		<?php } ?>
		<form action="login.php" method="post" name="login">
			<label for="username">Username:</label>
			<input type="text" name="username" id="username">
			<br>
			<label for="username">Password:</label>
			<input type="password" name="password" id="password">
			<br>
			<input type="submit" name="login" id="login" value="Login">
		</form>
	</body>
</html>


