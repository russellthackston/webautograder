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

	public function newSession($userid) {
	
		$user = NULL;
		$sql = "INSERT INTO sessions (sessionid, userid, expires) VALUES (hex(random_bytes(16)), :userid, NOW() + INTERVAL 120 DAY)";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':userid', $userid);
		$stmt->execute();
		if ($stmt->rowCount() == 1) {
			$sessionindex = $this->dbh->lastInsertId();
			$user = $this->getSessionByIndex($sessionindex);
		}
		return $user;

	}
	
	public function deleteSession($sessionid) {
	
		if (isset($sessionid) && $sessionid != null) {
			$sql = "DELETE FROM sessions WHERE expires < NOW() OR sessionid = :id";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(':id', $sessionid);
			$stmt->execute();
		} else {
			$sql = "DELETE FROM sessions WHERE expires < NOW()";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute();
		}

	}
	
	public function getSessionByIndex($index) {
	
		$session = NULL;
		$sql = "SELECT sessionid, userid, expires FROM sessions WHERE sessionindex = :sessionindex AND expires > NOW()";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':sessionindex', $index);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		if (sizeof($ary) > 0) {
			$session = Session::fromArray($ary[0]);
		}
		return $session;

	}
	
	public function getSessionBySessionID($sessionid) {
	
		$session = NULL;
		$sql = "SELECT sessionid, userid, expires FROM sessions WHERE sessionid = :sessionid AND expires > NOW()";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':sessionid', $sessionid);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		if (sizeof($ary) > 0) {
			$session = Session::fromArray($ary[0]);
		}
		return $session;

	}
	
	public function saveResults($userid, $assignmentid, $grade) {
	
		$grades = array();
		$sql = "INSERT INTO results (userid, assignmentid, grade, submitted) VALUES (:userid, :assignmentid, :grade, NOW())";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':userid', $userid);
		$stmt->bindParam(':assignmentid', $assignmentid);
		$stmt->bindParam(':grade', $grade);
		$stmt->execute();
		if ($stmt->rowCount() == 1) {
			$grades = $this->getResultsByUserIDAndAssignment($userid, $assignmentid);
		}
		return $grades;

	}
	
	public function getResultsByUserID($userid) {

		$grades = array();
		$sql = "SELECT userid, assignmentid, grade, submitted FROM results WHERE userid = :id";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':id', $userid);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		$grades = Result::listFromArray($ary);
		
		foreach($grades as &$grade) {
			$assignment = $this->getAssignment($grade->assignmentid);
			$grade->assignment = $assignment;
		}
		
		return $grades;

	}
	
	public function getResultsByUserIDAndAssignment($userid, $assignmentid) {

		$grades = array();
		$sql = "SELECT userid, assignmentid, grade, submitted FROM results WHERE userid = :id AND assignmentid = :assignmentid";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':id', $userid);
		$stmt->bindParam(':assignmentid', $assignmentid);
		$stmt->execute();
		$ary = $stmt->fetchAll();
		$grades = Result::listFromArray($ary);
		
		foreach($grades as &$grade) {
			$assignment = $this->getAssignment($grade->assignmentid);
			$grade->assignment = $assignment;
		}
		
		return $grades;

	}
	
}

?>