<?php

class Session {
	public $id;
	public $userid;

	function __construct($id, $userid, $expires) {
		if (empty($id)) {
			throw new Exception('Session ID cannot be empty');
		}
		if (empty($userid)) {
			throw new Exception('User ID cannot be empty');
		}
		$this->id = $id;
		$this->userid = $userid;
		$this->expires = $expires;
   	}

   	public static function fromArray($ary) {
	   	return new Session($ary['sessionid'], $ary['userid'], $ary['expires']);
    }

   	public static function listFromArray($ary) {
	   	$list = array();
	    for ($i = 0; $i < sizeof($ary); $i++) {
		    $list[] = Session::fromArray($ary[$i]);
		}
		return $list;
    }
    
}

?>