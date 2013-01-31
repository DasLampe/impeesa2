<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class GroupsModel extends AbstractModel {
	public function GetAllGroups($in_overview=1) {
		$sth	= $this->db->prepare("SELECT id, name, description, day, youngest, oldest, begin, end, logo, in_overview
									FROM ".MYSQL_PREFIX."groups
									WHERE in_overview = 1
										OR in_overview = :in_overview");
		$sth->bindParam(":in_overview",			$in_overview);
		$sth->execute();

		return $sth->fetchAll();
	}
	
	public function GetGroup($group_id) {
		$sth	= $this->db->prepare("SELECT id, name, description, day, youngest, oldest, begin, end, logo, in_overview
									FROM ".MYSQL_PREFIX."groups
									WHERE id = :group_id");
		$sth->bindParam(":group_id",	$group_id);
		$sth->execute();

		return $sth->fetch();
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
	
	public function SaveGroup($name, $description, $youngest, $oldest, $day, $begin, $end, $logo, array $leader, $in_overview, $id=null) {
		$in_overview	= ($in_overview == True) ? 1 : 0;
		
		if($id == null) {
			if( $id = ($this->CreateNewGroup($name, $description, $youngest, $oldest, $day, $begin, $end, $logo, $in_overview) == false)) {
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
								logo		= :logo,
								in_overview	= :in_overview
								WHERE id	= :group_id");
			$sth->bindParam(":name",		$name);
			$sth->bindParam(":description",	$description);
			$sth->bindParam(":youngest",	$youngest);
			$sth->bindParam(":oldest",		$oldest);
			$sth->bindParam(":day",			$day);
			$sth->bindParam(":begin",		$begin);
			$sth->bindParam(":end",			$end);
			$sth->bindParam(":logo",		$logo);
			$sth->bindParam(":in_overview",	$in_overview);
			$sth->bindParam(":group_id",	$id);
			if(!$sth->execute())
			{
				throw new impeesaException("Can't update group");
			}
		}
		
		$this->SetLeaderToGroup($id, $leader);
		
		return true;
	}
	
	public function DeleteGroup($group_id) {
		$this->db->beginTransaction();
		try {
			$sth	= $this->db->prepare("DELETE FROM ".MYSQL_PREFIX."groups WHERE id = :group_id");
			$sth->bindParam(":group_id",		$group_id);
			if($sth->execute() == true && $this->ClearLeaderOfGroup($group_id) == true) {
				$this->db->commit();
				return true;
			}
			return false;
		} catch(PDOException $e) {
			$this->db->rollBack();
			throw new impeesaException("PDO ERROR!! ".$e->getMessage());
		}
	}
	
	private function CreateNewGroup($name, $description, $youngest, $oldest, $day, $begin, $end, $logo, $in_overview) {
		$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."groups
									(name, description, youngest, oldest, day, begin, end, logo, in_overview)
									VALUES
									(:name, :description, :youngest, :oldest, :day, :begin, :end, :logo, :in_overview)");
		$sth->bindParam(":name",		$name);
		$sth->bindParam(":description",	$description);
		$sth->bindParam(":youngest",	$youngest);
		$sth->bindParam(":oldest",		$oldest);
		$sth->bindParam(":day",			$day);
		$sth->bindParam(":begin",		$begin);
		$sth->bindParam(":end",			$end);
		$sth->bindParam(":logo",		$logo);
		$sth->bindParam(":in_overview",	$in_overview);
		
		if($sth->execute()) {
			return $this->db->lastInsertId();
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
		return $sth->execute();
	}
}