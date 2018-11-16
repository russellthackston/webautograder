<?php

class Check {
	public $id;
	public $name;
	public $description;
	public $xpath;
	public $type;
	public $nodes;
	private $documentSet = false;

	function __construct($id, $name, $description, $xpath, $type) {
		if (empty($id)) {
			throw new Exception('Check ID cannot be empty');
		}
		if (empty($name)) {
			throw new Exception('Name cannot be empty');
		}
		if (empty($description)) {
			throw new Exception('Description cannot be empty');
		}
		if (empty($xpath)) {
			throw new Exception('XPath cannot be empty');
		}
		if (empty($type)) {
			throw new Exception('Type cannot be empty');
		}
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->xpath = $xpath;
		$this->type = $type;
   	}

    public function setDocument($html) {
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$xp = new DOMXPath($doc);
		$nodes = $xp->query($this->xpath);
		$this->nodes = $nodes;
		$this->documentSet = true;
    }

	public function check() {
		if (!$this->documentSet) {
 			throw new Exception('Error: setDocument() must be called before check()');
		}
		if ($this->type == "exists") {
			return $this->nodes->length > 0;
		}
		return false;
	}

   	public static function fromArray($ary) {
	   	return new Check($ary['checkid'], $ary['name'], $ary['description'], $ary['xpath'], $ary['checktype']);
    }

   	public static function listFromArray($ary) {
	   	$list = array();
	    for ($i = 0; $i < sizeof($ary); $i++) {
		    $list[] = Check::fromArray($ary[$i]);
		}
		return $list;
    }
    
}

?>