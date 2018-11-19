<?php

class User {
	public $id;
	public $username;
	public $passwordhash;
	public $studentid;
	public $isinstructor;

	function __construct($id, $username, $passwordhash, $studentid, $isinstructor = FALSE) {
		if (empty($id)) {
			throw new Exception('User ID cannot be empty');
		}
		if (empty($username)) {
			throw new Exception('Username cannot be empty');
		}
		if (empty($passwordhash)) {
			throw new Exception('Password hash cannot be empty');
		}
		if (empty($studentid)) {
			throw new Exception('Student ID cannot be empty');
		}
		$this->id = $id;
		$this->username = $username;
		$this->passwordhash = $passwordhash;
		$this->studentid = $studentid;
		$this->isinstructor = $isinstructor;
   	}

   	public static function fromArray($ary) {
	   	return new User($ary['userid'], $ary['username'], $ary['passwordhash'], $ary['studentid'], ($ary['isinstructor'] == 1));
    }

   	public static function listFromArray($ary) {
	   	$list = array();
	    for ($i = 0; $i < sizeof($ary); $i++) {
		    $list[] = User::fromArray($ary[$i]);
		}
		return $list;
    }
    
    public static function justUsernames($ary) {
	    $list = array();
	    for ($i = 0; $i < sizeof($ary); $i++) {
		    $list[] = $ary[$i]->username;
		}
		return $list;
    }

}

?>