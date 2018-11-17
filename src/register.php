<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('db.php');
require_once('classes/User.php');

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$username = $_POST['username'];
	$password = $_POST['password'];
	$studentid = $_POST['studentid'];
	
	$user = new User($username, $password, $studentid);

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
			<input type="submit" name="register" id="register">
		</form>
	</body>
</html>


