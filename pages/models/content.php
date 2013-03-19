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
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page	= new impeesaPage();
		return $page->ExistsPage($sitename);
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
		$content	= stripslashes($content);
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
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page	= new impeesaPage();
		return $page->CreatePage($sitename, $title, $menu_title, $in_nav);
	}
	
	public function GetPageInfo($sitename)
	{
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page	= new impeesaPage();
		return $page->GetPageInfo($sitename);
	}
	
	public function EditPageInfo($old_sitename, $sitename, $title, $menu_title, $in_nav)
	{
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page	= new impeesaPage();
		return $page->EditPageInfo($old_sitename, $sitename, $title, $menu_title, $in_nav);
	}
	
	public function DeletePage($sitename)
	{
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page	= new impeesaPage();
		return $page->DeletePage($sitename);
	}

	public function GetAllMenuEntries($parent_id = 0, $in_nav = True)
	{
		include_once(PATH_CORE_CLASS."impeesaMenu.class.php");
		$menu	= new impeesaMenu();
		return $menu->GetAllMenuEntries($parent_id, $in_nav);
	}
	
	public function SetValidPageName($page_name)
	{
		include_once(PATH_CORE_CLASS."impeesaPage.class.php");
		$page	= new impeesaPage();
		return $page->SetValidPageName($page_name);
	}
	
	public function SaveMenuOrder($data)
	{
		include_once(PATH_CORE_CLASS."impeesaMenu.class.php");
		$menu	= new impeesaMenu();
		return $menu->SaveMenuOrder($data);
	}
}