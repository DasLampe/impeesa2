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
			throw new impeesaException("Error 404. Sitename isn't in database");
		}
		return $row;
	}
	
	private function ExistsPage($sitename)
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
		
		if($this->ExistsPage($sitename) == false)
		{
			if($user->CanAdd() == false)
			{
				throw new impeesaException("Permission denied!");
			}
			return $this->AddPage($content, $sitename);
		}
		else
		{
			if($user->CanEdit() == false)
			{
				throw new impeesaException("Permission denied!");
			}
			return $this->EditPage($content, $sitename);
		}
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
			$sth->execute();
			return true;
		}
		catch(PDOException $e)
		{
			throw new impeesaException("PDO:".$e->getMessage());
		}
	}
	
	private function AddPage($content, $sitename)
	{
		try
		{
			$sth	= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."content
										(name, title, content)
										VALUES
										(:sitename, :title, :content)");
			$sth->bindParam(":sitename",	$sitename);
			$sth->bindParam(":title",		ucfirst($sitename));
			$sth->bindParam(":content",		$content);
			$sth->execute();
			return true;
		}
		catch(PDOException $e)
		{
			throw new impeesaException($e->getMessage());
		}
	}
}