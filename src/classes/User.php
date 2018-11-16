<?php

class User {
	public $id;
	public $username;
	public $passwordhash;
	public $email;

	function __construct($id, $username, $passwordhash, $email) {
		if (empty($id)) {
			throw new Exception('User ID cannot be empty');
		}
		if (empty($username)) {
			throw new Exception('Username cannot be empty');
		}
		if (empty($passwordhash)) {
			throw new Exception('Password hash cannot be empty');
		}
		if (empty($email)) {
			throw new Exception('Email cannot be empty');
		}
		$this->id = $id;
		$this->username = $username;
		$this->passwordhash = $passwordhash;
		$this->email = $email;
   	}

   	public static function fromArray($ary) {
	   	return new User($ary['userid'], $ary['username'], $ary['passwordhash'], $ary['email']);
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