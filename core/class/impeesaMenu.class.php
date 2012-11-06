<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaMenu
{
	private $db;
	
	public function __construct()
	{
		$this->db	= impeesaDb::getConnection();
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
	
	public function GetAllSubMenuEntries($sitename, $in_nav = True)
	{
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page		= new impeesaPage();
		
		$in_nav		= ($in_nav == False) ? 0 : 1;
		$parent_id	= $page->GetPageId($sitename);
		
		//Fix: if $parent_id has submenu => $parent_id = 0
		$sth	= $this->db->prepare("SELECT parent
									FROM ".MYSQL_PREFIX."content
									WHERE name = :sitename");
		$sth->bindParam(":sitename",	$sitename);
		$sth->execute();
		$row	= $sth->fetch();
		if($row['parent'] != 0)
		{
			$parent_id = $row['parent'];
		}
		//End: Fix
			
		$sth	= $this->db->prepare("SELECT name, menu_title
									FROM ".MYSQL_PREFIX."content
									WHERE parent = :parent_id
										AND in_nav = :in_nav");
		$sth->bindParam(":parent_id",	$parent_id);
		$sth->bindParam(":in_nav",		$in_nav);
		$sth->execute();
		return $sth->fetchAll();
	}
}