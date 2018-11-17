<?php

class Result {
	public $userid;
	public $assignmentid;
	public $grade;
	public $submitted;
	public $assignment;

	function __construct($userid, $assignmentid, $grade, $submitted) {
		if (empty($userid)) {
			throw new Exception('User ID cannot be empty');
		}
		if (empty($assignmentid)) {
			throw new Exception('Assignment ID cannot be empty');
		}
		if (empty($grade)) {
			throw new Exception('Grade cannot be empty');
		}
		if (empty($submitted)) {
			throw new Exception('Date submitted cannot be empty');
		}
		$this->userid = $userid;
		$this->assignmentid = $assignmentid;
		$this->grade = $grade;
		$this->submitted = $submitted;
   	}

   	public static function fromArray($ary) {
	   	return new Result($ary['userid'], $ary['assignmentid'], $ary['grade'], $ary['submitted']);
    }

   	public static function listFromArray($ary) {
	   	$list = array();
	    for ($i = 0; $i < sizeof($ary); $i++) {
		    $list[] = Result::fromArray($ary[$i]);
		}
		return $list;
    }
    
}

?>