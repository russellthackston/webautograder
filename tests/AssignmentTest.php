<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AssignmentTest extends TestCase {

	/*
		Test class definition
	*/	
	public function testClassDefinition() : void {
		$obj = new Assignment("id","name","desc","example","createdby");
		$this->assertTrue(
			method_exists($obj, 'fromArray'), 
			'Class does not have method fromArray'
		);
		$this->assertTrue(
			method_exists($obj, 'listFromArray'), 
			'Class does not have method listFromArray'
		);
		$this->assertTrue(
			method_exists($obj, 'justUsernames'), 
			'Class does not have method justUsernames'
		);
	}

	/*
		Test constructor
	*/	
	public function testConstructor() : void {
		
		$obj = new Assignment("id","name","desc","example","createdby");
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->name);
		$this->assertEquals('desc', $obj->description);
		$this->assertEquals('example', $obj->example);
		$this->assertEquals('createdby', $obj->createdby);
		$this->assertEmpty($obj->checks);

	}

	public function testConstructorEmptyID() : void {
		$this->expectException(Exception::class);
		$obj = new Assignment("","name","desc","example","createdby");
	}

	public function testConstructorEmptyName() : void {
		$this->expectException(Exception::class);
		$obj = new Assignment("id","","desc","example","createdby");
	}

	public function testConstructorEmptyDescription() : void {
		$this->expectException(Exception::class);
		$obj = new Assignment("id","name","","example","createdby");
	}

	public function testConstructorEmptyExample() : void {
		$this->expectException(Exception::class);
		$obj = new Assignment("id","name","desc","","createdby");
	}

	public function testConstructorEmptyCreatedby() : void {
		$this->expectException(Exception::class);
		$obj = new Assignment("id","name","desc","example","");
	}

	/*
		Test fromArray()
	*/	
	public function testFromArray() : void {
		
		$obj = Assignment::fromArray(["assignmentid"=>"id","name"=>"name","description"=>"desc","example"=>"example","createdby"=>"createdby"]);
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->name);
		$this->assertEquals('desc', $obj->description);
		$this->assertEquals('example', $obj->example);
		$this->assertEquals('createdby', $obj->createdby);
	}

	/*
		Test listFromArray()
	*/	
	public function testListFromArray() : void {
		
		$objs = Assignment::listFromArray([
			["assignmentid"=>"id1","name"=>"name1","description"=>"description1","example"=>"example1","createdby"=>"createdby1"],
			["assignmentid"=>"id2","name"=>"name2","description"=>"description2","example"=>"example2","createdby"=>"createdby2"]
		]);
		$this->assertEquals(2, sizeof($objs));

		$this->assertEquals('id1', $objs[0]->id);
		$this->assertEquals('name1', $objs[0]->name);
		$this->assertEquals('description1', $objs[0]->description);
		$this->assertEquals('example1', $objs[0]->example);
		$this->assertEquals('createdby1', $objs[0]->createdby);

		$this->assertEquals('id2', $objs[1]->id);
		$this->assertEquals('name2', $objs[1]->name);
		$this->assertEquals('description2', $objs[1]->description);
		$this->assertEquals('example2', $objs[1]->example);
		$this->assertEquals('createdby2', $objs[1]->createdby);

	}

	/*
		Test setter and getters
	*/
	public function testSettersAndGetters() : void {
		
		$obj = new Assignment("id","name","desc","example","createdby");
		$obj->id = "id";
		$obj->name = "name";
		$obj->description = "description";
		$obj->example = "example";
		$obj->createdby = "createdby";
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->name);
		$this->assertEquals('description', $obj->description);
		$this->assertEquals('example', $obj->example);
		$this->assertEquals('createdby', $obj->createdby);

	}
	
	public function testChecksArray() : void {
		$obj = new Assignment("id","name","desc","example","createdby");
		$checks = array("1"=>"one","2"=>"two");
		$obj->checks = $checks;
		$this->assertCount(2, $obj->checks);
	}

}

?>