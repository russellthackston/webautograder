<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {

	/*
		Test class definition
	*/	
	public function testClassDefinition() : void {
		$obj = new User("id","name","passwordhash","studentid");
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
		
		$obj = new User("id","username","passwordhash","studentid");
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('username', $obj->username);
		$this->assertEquals('passwordhash', $obj->passwordhash);
		$this->assertEquals('studentid', $obj->studentid);

	}

	public function testConstructorEmptyID() : void {
		$this->expectException(Exception::class);
		$obj = new User("","username","passwordhash","studentid");
	}

	public function testConstructorEmptyUsername() : void {
		$this->expectException(Exception::class);
		$obj = new User("id","","passwordhash","studentid");
	}

	public function testConstructorEmptyPasswordHash() : void {
		$this->expectException(Exception::class);
		$obj = new User("id","username","","studentid");
	}

	public function testConstructorEmptystudentid() : void {
		$this->expectException(Exception::class);
		$obj = new User("id","username","passwordhash","");
	}

	/*
		Test fromArray()
	*/	
	public function testFromArray() : void {
		
		$obj = User::fromArray(["userid"=>"id","username"=>"name","passwordhash"=>"password","studentid"=>"mail"]);
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->username);
		$this->assertEquals('password', $obj->passwordhash);
		$this->assertEquals('mail', $obj->studentid);
	}

	/*
		Test listFromArray()
	*/	
	public function testListFromArray() : void {
		
		$objs = User::listFromArray([
			["userid"=>"id1","username"=>"name1","passwordhash"=>"password1","studentid"=>"mail1"],
			["userid"=>"id2","username"=>"name2","passwordhash"=>"password2","studentid"=>"mail2"]
		]);
		$this->assertEquals(2, sizeof($objs));

		$this->assertEquals('id1', $objs[0]->id);
		$this->assertEquals('name1', $objs[0]->username);
		$this->assertEquals('password1', $objs[0]->passwordhash);
		$this->assertEquals('mail1', $objs[0]->studentid);

		$this->assertEquals('id2', $objs[1]->id);
		$this->assertEquals('name2', $objs[1]->username);
		$this->assertEquals('password2', $objs[1]->passwordhash);
		$this->assertEquals('mail2', $objs[1]->studentid);

	}

	/*
		Test setter and getters
	*/
	public function testSettersAndGetters() : void {
		
		$obj = new User("id","username","passwordhash","studentid");
		$obj->id = "id";
		$obj->username = "username";
		$obj->passwordhash = "passwordhash";
		$obj->studentid = "studentid";
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('username', $obj->username);
		$this->assertEquals('passwordhash', $obj->passwordhash);
		$this->assertEquals('studentid', $obj->studentid);

	}

}

?>