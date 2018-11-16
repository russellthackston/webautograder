<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {

	/*
		Test class definition
	*/	
	public function testClassDefinition() : void {
		$obj = new User("id","name","passwordhash","email");
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
		
		$obj = new User("id","username","passwordhash","email");
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('username', $obj->username);
		$this->assertEquals('passwordhash', $obj->passwordhash);
		$this->assertEquals('email', $obj->email);

	}

	public function testConstructorEmptyID() : void {
		$this->expectException(Exception::class);
		$obj = new User("","username","passwordhash","email");
	}

	public function testConstructorEmptyUsername() : void {
		$this->expectException(Exception::class);
		$obj = new User("id","","passwordhash","email");
	}

	public function testConstructorEmptyPasswordHash() : void {
		$this->expectException(Exception::class);
		$obj = new User("id","username","","email");
	}

	public function testConstructorEmptyEmail() : void {
		$this->expectException(Exception::class);
		$obj = new User("id","username","passwordhash","");
	}

	/*
		Test fromArray()
	*/	
	public function testFromArray() : void {
		
		$obj = User::fromArray(["userid"=>"id","username"=>"name","passwordhash"=>"password","email"=>"mail"]);
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->username);
		$this->assertEquals('password', $obj->passwordhash);
		$this->assertEquals('mail', $obj->email);
	}

	/*
		Test listFromArray()
	*/	
	public function testListFromArray() : void {
		
		$objs = User::listFromArray([
			["userid"=>"id1","username"=>"name1","passwordhash"=>"password1","email"=>"mail1"],
			["userid"=>"id2","username"=>"name2","passwordhash"=>"password2","email"=>"mail2"]
		]);
		$this->assertEquals(2, sizeof($objs));

		$this->assertEquals('id1', $objs[0]->id);
		$this->assertEquals('name1', $objs[0]->username);
		$this->assertEquals('password1', $objs[0]->passwordhash);
		$this->assertEquals('mail1', $objs[0]->email);

		$this->assertEquals('id2', $objs[1]->id);
		$this->assertEquals('name2', $objs[1]->username);
		$this->assertEquals('password2', $objs[1]->passwordhash);
		$this->assertEquals('mail2', $objs[1]->email);

	}

	/*
		Test setter and getters
	*/
	public function testSettersAndGetters() : void {
		
		$obj = new User("id","username","passwordhash","email");
		$obj->id = "id";
		$obj->username = "username";
		$obj->passwordhash = "passwordhash";
		$obj->email = "email";
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('username', $obj->username);
		$this->assertEquals('passwordhash', $obj->passwordhash);
		$this->assertEquals('email', $obj->email);

	}

}

?>