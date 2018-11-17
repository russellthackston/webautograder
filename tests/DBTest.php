<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DBTest extends TestCase {

	/*
		Test user functions
	*/	
	public function testGetUserByUsername() : void {
		
		$db = new DB();
		$user = $db->getUserByUsername('admin');
		$this->assertNotNull($user);

	}

	public function testRegisterGetAndDeleteUser() : void {

		$db = new DB();
		$this->assertNotNull($db);
		$username = bin2hex(random_bytes(10));
		$password = bin2hex(random_bytes(10));
		$studentid = bin2hex(random_bytes(10));
		$user = $db->registerUser($username, $password, $studentid);
		$this->assertNotNull($user);

		$result = $db->deleteUser($user->id);
		$this->assertTrue($result);

	}

}

?>