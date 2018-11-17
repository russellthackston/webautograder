<?php

class DB {

	public $conn_string;
	public $dbh;

	function __construct() {

		$config = new Config();
		$conn_string = "mysql:host=".$config->server.";dbname=".$config->db;
		$this->dbh = new PDO($conn_string, $config->username, $config->password);
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}

	public function getAssignments() {
	
		$sql = "SELECT * FROM assignments";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$objs = $stmt->fetchAll();
		return Assignment::listFromArray($objs);

	}
	
	public function getAssignment($id) {
	
		$assignment = null;
		$sql = "SELECT * FROM assignments WHERE assignmentid = :id";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$ary = $stmt->fetch();
		$assignment = Assignment::fromArray($ary);
		$assignment->checks = $this->getChecks($id);
		return $assignment;

	}
	
	public function getChecks($assignmentid) {

		$checks = array();
		$sql = "SELECT * FROM checks WHERE assignmentid = :id";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':id', $assignmentid);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		$checks = Check::listFromArray($ary);
		return $checks;

	}
	
	public function registerUser($username, $password, $studentid) {
	
		$user = NULL;
		$passwordhash = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO users (userid, username, passwordhash, studentid) VALUES (hex(random_bytes(16)), :username, :password, :studentid)";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $passwordhash);
		$stmt->bindParam(':studentid', $studentid);
		$stmt->execute();
		if ($stmt->rowCount() == 1) {
			$user = $this->getUserByUsername($username);
		}
		return $user;

	}
	
	public function getUserByUsername($username) {
	
		$user = NULL;
		$sql = "SELECT userid, username, passwordhash, studentid FROM users WHERE username = :username";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		if (sizeof($ary) > 0) {
			$user = User::fromArray($ary[0]);
		}
		return $user;

	}
	
	public function deleteUser($userid) {
	
		$sql = "DELETE FROM users WHERE userid = :id";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':id', $userid);
		$stmt->execute();
		return $stmt->rowCount() == 1;
	
	}

}

?>