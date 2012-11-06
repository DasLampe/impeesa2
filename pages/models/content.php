<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class ContentModel extends AbstractModel
{
	public function GetPageContent($sitename)
	{
		$sth = $this->db->prepare("SELECT title, name, content
									FROM ".MYSQL_PREFIX."content
									WHERE name LIKE :sitename
									LIMIT 1");
		$sth->execute(array(":sitename" => $sitename));
		
		$row	= $sth->fetch();
		if(empty($row))
		{
			throw new impeesaException("Sitename isn't in database", 404);
		}
		return $row;
	}
	
	public function ExistsPage($sitename)
	{
		$result	= $this->db->prepare("SELECT COUNT(id) as count
									FROM ".MYSQL_PREFIX."content
									WHERE name LIKE :sitename
									LIMIT 1");
		$result->execute(array(":sitename" => $sitename));
		
		$row	= $result->fetch();
		if($row['count'] == 1)
		{
			return true;
		}
		return false;
	}
	
	public function SavePage($content, $sitename)
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user			= new impeesaUser($_SESSION);
		
		if($user->CanEdit() == false)
		{
			throw new impeesaException("Permission denied!", 401);
		}
		return $this->EditPage($content, $sitename);
	}
	
	private function EditPage($content, $sitename)
	{
		try
		{
			$sth	= $this->db->prepare("UPDATE ".MYSQL_PREFIX."content SET
										content = :content
										WHERE name = :sitename");
			$sth->bindParam(":content",		$content);
			$sth->bindParam(":sitename",	$sitename);
			return $sth->execute();
		}
		catch(PDOException $e)
		{
			throw new impeesaException("PDO:".$e->getMessage());
		}
	}
	
	public function CreatePage($sitename, $title, $menu_title, $in_nav)
	{
		try
		{
			($in_nav) ? 1 : 0;
			$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."content
										(name, title, menu_title, in_nav, content)
										VALUES
										(:sitename, :title, :menu_title, :in_nav, 'Bitte Text einfügen!')");
			$sth->bindParam(":sitename",	$this->SetValidPageName($sitename));
			$sth->bindParam(":title",		$title);
			$sth->bindParam(":menu_title",	$menu_title);
			$sth->bindParam(":in_nav",		$in_nav);
			return $sth->execute();
		}
		catch(PDOException $e)
		{
			throw new impeesaException($e->getMessage());
		}
	}
	
	public function GetPageInfo($sitename)
	{
		$sth		= $this->db->prepare("SELECT id, title, name, menu_title, in_nav
										FROM ".MYSQL_PREFIX."content
										WHERE name = :sitename");
		$sth->bindParam(":sitename",	$sitename);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function EditPageInfo($old_sitename, $sitename, $title, $menu_title, $in_nav)
	{
		try
		{
			$sth	= $this->db->prepare("UPDATE ".MYSQL_PREFIX."content SET
										name		= :sitename,
										title 		= :title,
										menu_title 	= :menu_title,
										in_nav		= :in_nav
										WHERE name	= :old_sitename");
			$sth->bindParam(":old_sitename",	$old_sitename);
			$sth->bindParam(":sitename",		$this->SetValidPageName($sitename));
			$sth->bindParam(":title",			$title);
			$sth->bindParam(":menu_title",		$menu_title);
			$sth->bindParam(":in_nav",			$in_nav);
			return $sth->execute();
		}
		catch(PDOException $e)
		{
			throw new impeesaException($e->getMessage());
		}
	}
	
	public function DeletePage($sitename)
	{
		try
		{
			$sth	= $this->db->prepare("DELETE FROM ".MYSQL_PREFIX."content WHERE name = :sitename");
			$sth->bindParam(":sitename",	$sitename);
			return $sth->execute();
		}
		catch(PDOException $e)
		{
			throw new impeesaException($e->getMessage());
		}
	}

	public function GetAllMenuEntries($parent_id = 0, $in_nav = True)
	{
		$in_nav = ($in_nav == False) ? 0 : 1;
		
		$sth		= $this->db->prepare("SELECT id, title, name, menu_title, in_nav
										FROM ".MYSQL_PREFIX."content
										WHERE (in_nav = :in_nav OR in_nav = 1)
											AND parent = :parent_id
										ORDER BY nav_order");
		$sth->bindParam(":parent_id",	$parent_id);
		$sth->bindParam(":in_nav",		$in_nav);
		$sth->execute();
		
		return $sth->fetchAll();

	}
	
	public function SetValidPageName($page_name)
	{
		return preg_replace("/\W/i", "", $page_name);
	}
	
	public function SaveMenuOrder($data)
	{
		$this->db->beginTransaction();
		$sth	= $this->db->prepare("UPDATE ".MYSQL_PREFIX."content SET
									nav_order = :nav_order,
									parent = :nav_parent
									WHERE id = :entry_id");
		foreach($data["menu"] as $key=>$value)
		{
			foreach($value as $nav_order=>$menu_entry)
			{
				if($key != $menu_entry)
				{
					$sth->bindParam(":nav_order",	$nav_order);
					$sth->bindParam(":nav_parent",	$key);
					$sth->bindParam(":entry_id",	$menu_entry);
					$sth->execute();
				}
			}
		}
		return $this->db->commit();
	}
}