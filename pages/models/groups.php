<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class GroupsModel extends AbstractModel {
	public function GetAllGroups($in_overview=1) {
		$sth	= $this->db->prepare("SELECT id, name, description, day, youngest, oldest, begin, end, logo
									FROM ".MYSQL_PREFIX."groups
									WHERE in_overview = :in_overview");
		$sth->bindParam(":in_overview",			$in_overview);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function GetLeader($group_id) {
		$sth	= $this->db->prepare("SELECT user_id
									FROM ".MYSQL_PREFIX."groups_leader
									WHERE group_id = :group_id");
		$sth->bindParam(":group_id",	$group_id);
		$sth->execute();
		
		$leader			= array();
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user	= new impeesaUser($_SESSION);
		
		foreach($sth->fetchAll() as $userid) {
			$userinfo	= $user->GetUserInfo($userid['user_id']);
			$leader[]	= $userinfo['first_name'].' '.$userinfo['name'];
		}
		return $leader;
	}
	
	public function SaveGroup($name, $description, $youngest, $oldest, $day, $begin, $end, $logo, array $leader, $id=null) {
		if($id == null) {
			if( $id = ($this->CreateNewGroup($name, $description, $youngest, $oldest, $day, $begin, $end, $logo) == false)) {
				throw new impeesaException("Can't create group!");
			}
		}
		else
		{
			$sth	= $this->db->prepare("UPDATE ".MYSQL_PREFIX."groups SET
								name		= :name,
								description	= :description,
								youngest	= :youngest,
								oldest		= :oldest,
								day			= :day,
								begin		= :begin,
								end			= :end,
								logo		= :logo
								WHERE id	= :group_id");
			$sth->bindParam(":name",		$name);
			$sth->bindParam(":description",	$description);
			$sth->bindParam(":youngest",	$youngest);
			$sth->bindParam(":oldest",		$oldest);
			$sth->bindParam(":day",			$day);
			$sth->bindParam(":begin",		$begin);
			$sth->bindParam(":end",			$end);
			$sth->bindParam(":logo",		$logo);
			$sth->bindParam(":group_id",	$id);
			if(!$sth->execute())
			{
				throw new impeesaException("Can't update group");
			}
		}
		
		$this->SetLeaderToGroup($id, $leader);
	}
	
	private function CreateNewGroup($name, $description, $youngest, $oldest, $day, $begin, $end, $logo) {
		$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."groups
									(name, description, youngest, oldest, day, begin, end, logo)
									VALUES
									(:name, :descrition, :youngest, :oldest, :day, :begin, :end, :logo)");
		$sth->bindParam(":name",		$name);
		$sth->bindParam(":description",	$description);
		$sth->bindParam(":youngest",	$youngest);
		$sth->bindParam(":oldest",		$oldest);
		$sth->bindParam(":day",			$day);
		$sth->bindParam(":begin",		$begin);
		$sth->bindParam(":end",			$end);
		$sth->bindParam(":logo",		$logo);
		
		if($sth->execute()) {
			return $sth->lastInsertId();
		}
		else {
			return false;
		}
	}
	
	private function SetLeaderToGroup($group_id, array $leader) {
		try {
			$this->db->beginTransaction();

			$this->ClearLeaderOfGroup($group_id);
			
			$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."groups_leader
										(user_id, group_id)
										VALUES
										(:user_id, :group_id)");
			$sth->bindParam(":group_id",	$group_id);
			foreach($leader as $user_id) {
				$sth->bindParam(":user_id",	$user_id);
				$sth->execute();
			}

			$this->db->commit();
		} catch(PDOException $e) {
			$this->db->rollBack();
			throw new impeesaException("PDO ERROR!!".$e->getMessage());
		}
	}
	
	private function ClearLeaderOfGroup($group_id) {
		$sth	= $this->db->prepare("DELETE FROM ".MYSQL_PREFIX."groups_leader WHERE group_id = :group_id");
		$sth->bindParam(":group_id",	$group_id);
		$sth->execute();
	}
}