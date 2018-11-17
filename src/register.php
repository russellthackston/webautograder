<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('autoloader.php');
$db = new DB();
$registerAttempt = FALSE;
$user = NULL;
$duplicateUsername = FALSE;

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$registerAttempt = TRUE;
	$username = $_POST['username'];
	$password = $_POST['password'];
	$studentid = $_POST['studentid'];
	
	try {
		$user = $db->registerUser($username, $password, $studentid);
	} catch( PDOException $e ) {
		if ($e->errorInfo[1] == 1062) {
			$duplicateUsername = TRUE;
		}
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
		<?php if ($registerAttempt && $user == NULL) { ?>
			<?php if ($duplicateUsername) { ?>
			<div>Username already registered.</div>
			<?php } else { ?>
			<div>Registration failed.</div>
			<?php } ?>
		<?php } ?>
		<?php if ($registerAttempt && $user != NULL) { ?>
			<div>Thank you for registering. You may now <a href="login.php">login</a>.</div>
		<?php } ?>
		<form action="register.php" method="post" name="register">
			<label for="username">Username:</label>
			<input type="text" name="username" id="username">
			<br>
			<label for="username">Password:</label>
			<input type="password" name="password" id="password">
			<br>
			<label for="studentid">Student ID:</label>
			<input type="text" name="studentid" id="studentid">
			<br>
			<input type="submit" name="register" id="register" value="Register">
		</form>
	</body>
</html>


