<?php

class Assignment {
	public $id;
	public $name;
	public $description;
	public $example;
	public $createdby;
	public $checks;

	function __construct($id, $name, $description, $example, $createdby) {
		if (empty($id)) {
			throw new Exception('Assignment ID cannot be empty');
		}
		if (empty($name)) {
			throw new Exception('Name cannot be empty');
		}
		if (empty($description)) {
			throw new Exception('Description cannot be empty');
		}
		if (empty($example)) {
			throw new Exception('Example cannot be empty');
		}
		if (empty($createdby)) {
			throw new Exception('Created By cannot be empty');
		}
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->example = $example;
		$this->createdby = $createdby;
		$this->checks = array();
   	}
   	
   	function setDocument($html) {
	   	foreach($this->checks as $check) {
		   	$check->setDocument($html);
	   	}
   	}

   	public static function fromArray($ary) {
	   	return new Assignment($ary['assignmentid'], $ary['name'], $ary['description'], $ary['example'], $ary['createdby']);
    }

   	public static function listFromArray($ary) {
	   	$list = array();
	    for ($i = 0; $i < sizeof($ary); $i++) {
		    $list[] = Assignment::fromArray($ary[$i]);
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