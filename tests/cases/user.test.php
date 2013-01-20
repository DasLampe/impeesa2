<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once(PATH_CORE_CLASS."impeesaUser.class.php");

class TestUser extends UnitTestCase {
	private $user;
	private $username;
	
	public function __construct() {
		$this->user		= new impeesaUser($_SESSION);
		$this->username	= "TestUser";
	}
	
	public function testCreateUser() {
		$this->assertTrue($this->user->CreateUser($this->username, "testPasswort123", "Test", "User", "test@test.com", 1));
		//Also check user in database
		$this->assertTrue($this->user->ExistsUsername($this->username));
	}
	
	public function testSetUserId() {
		$this->assertTrue($this->user->SetUserIdByUsername($this->username));
	}
	
	public function testRemoveUser() {
		$this->assertTrue($this->user->RemoveUser($this->user->GetUserId()));
		//Check in database
		$this->assertFalse($this->user->ExistsUsername($this->username));
	}
}
?>