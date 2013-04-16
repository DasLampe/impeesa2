<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaPage
{
	private $db;
	
	public function __construct()
	{
		$this->db	= impeesaDb::getConnection();
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
	
	public function CreatePage($sitename, $title, $menu_title, $in_nav)
	{
		try
		{
			($in_nav) ? 1 : 0;
			$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."content
										(name, title, menu_title, in_nav, content)
										VALUES
										(:sitename, :title, :menu_title, :in_nav, 'Bitte Text einfügen!')");
			$sth->bindValue(":sitename",	$this->SetValidPageName($sitename));
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
	
	public function SetValidPageName($page_name)
	{
		return preg_replace("/\W/i", "", $page_name);
	}
	
	public function GetPageId($sitename)
	{
		$sth	= $this->db->prepare("SELECT id
									FROM ".MYSQL_PREFIX."content
									WHERE name = :sitename");
		$sth->bindParam(":sitename",	$sitename);
		$sth->execute();
		$row	= $sth->fetch();
		return $row['id'];
	}
}