<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <andre@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once(PATH_MODEL."groups.php");
include_once(PATH_CORE_CLASS."impeesaDB.class.php");

class GroupsTest extends UnitTestCase {
	private $model;
	private $group_name;
	private $group_id;
	private $db;
	
	public function __construct() {
		$this->model		= new GroupsModel();
		$this->db			= impeesaDB::getConnection();
		$this->group_name	= "TestGroup 1";
	}
	
	private function GetGroupId() {
		$sth	= $this->db->prepare("SELECT id
									FROM ".MYSQL_PREFIX."groups
									WHERE name = :group_name");
		$sth->bindParam(":group_name",	$this->group_name);
		$sth->execute();
		$id		= $sth->fetch();
		return $id['id'];
	}
	
	public function testCreateGroup() {
		$data	= array(
						"name"			=> $this->group_name,
						"description"	=> "Test 1 Description",
						"youngest"		=> 18,
						"oldest"		=> 84,
						"day"			=> "Montag",
						"begin"			=> "13:12:00",
						"end"			=> "13:37:00",
						"logo"			=> "juffi.jpg",
						"leader"		=> array(1),
						"in_overview"	=> 0,
					);
		
		$this->assertTrue($this->model->SaveGroup($this->group_name, $data['description'], $data['youngest'], $data['oldest'], $data['day'], $data['begin'], $data['end'], $data['logo'], $data['leader'], $data['in_overview']));
		
		$this->group_id	= $this->GetGroupId();
		
		$this->testGroupData($data);
	}
	
	public function testEditGroup() {
		$data	= array(
						"name"			=> "TestGroup 2",
						"description"	=> "Test 2 Description!!!",
						"youngest"		=> 4,
						"oldest"		=> 10,
						"day"			=> "Montag",
						"begin"			=> "13:12:00",
						"end"			=> "13:37:00",
						"logo"			=> "juffi.jpg",
						"leader"		=> array(1),
						"in_overview"	=> 1,
					);
					
		$this->assertTrue($this->model->SaveGroup($data['name'], $data['description'], $data['youngest'], $data['oldest'], $data['day'], $data['begin'], $data['end'], $data['logo'], $data['leader'], $data['in_overview'], $this->group_id));
		
		$this->testGroupData($data);
	}
	
	private function testGroupData($data) {
		$group	= $this->model->GetGroup($this->group_id);
		$this->assertTrue(is_array($group));
		
		//Hack: Add id to $data
		$data["id"] = $this->group_id;
		
		foreach($group as $key=>$value) {
			$this->assertTrue(($value == $data[$key]));
		}
	}
	
	public function testDeleteGroup() {
		$this->assertTrue($this->model->DeleteGroup($this->group_id));
		
		$this->assertFalse($this->model->GetGroup($this->group_id));
	}
}