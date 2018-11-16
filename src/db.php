<?php

require_once('config.php');
require_once('classes/Assignment.php');
require_once('classes/Check.php');

$conn_string = "mysql:host=".WAG_DB_SERVER.";dbname=".WAG_DB_NAME;
$dbh = new PDO($conn_string, WAG_DB_USER, WAG_DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getAssignments() {
	global $dbh;
	global $errors;

	try {
		$sql = "SELECT * FROM assignments";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$objs = $stmt->fetchAll();
		return Assignment::listFromArray($objs);
	} catch (PDOException $e) {
		$errors[] = "Oops! We had a problem with the database. Sorry. Try again later.";
	}
}

function getAssignment($id) {
	global $dbh;
	global $errors;

	$assignment = null;
	try {
		$sql = "SELECT * FROM assignments WHERE assignmentid = :id";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$ary = $stmt->fetch();
		$assignment = Assignment::fromArray($ary);
		$assignment->checks = getChecks($id);
	} catch (PDOException $e) {
		$errors[] = "Oops! We had a problem with the database. Sorry. Try again later.";
	}
	return $assignment;
}

function getChecks($assignmentid) {
	global $dbh;
	global $errors;

	$checks = array();
	try {
		$sql = "SELECT * FROM checks WHERE assignmentid = :id";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id', $assignmentid);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		$checks = Check::listFromArray($ary);
	} catch (PDOException $e) {
		$errors[] = "Oops! We had a problem with the database. Sorry. Try again later.";
	}
	return $checks;
}



?>