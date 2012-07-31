<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class BlogModel extends AbstractModel
{
	public function GetBlogPosts()
	{
		$sth	= $this->db->query("SELECT id, headline, content, publish, img_name
									FROM ".MYSQL_PREFIX."blog
									ORDER BY id DESC");
		return $sth->fetchAll();
	}
	
	public function GetNextDay()
	{
		$sth	= $this->db->query("SELECT MAX(id) AS day
									FROM ".MYSQL_PREFIX."blog");
		$row	= $sth->fetch();
		return $row['day']+1;
	}
	
	public function InsertBlogPost($headline, $content, $publish)
	{
		$publish	= explode(".", $publish);
		$publish	= mktime(0,0,0,$publish[1],$publish[0],$publish[2]);
		
		$sth		= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."blog
								(headline, content, publish)
								VALUES
								(:headline, :content, :publish)");
		$sth->bindParam(":headline",	$headline);
		$sth->bindParam(":content",		$content);
		$sth->bindParam(":publish",		$publish);
		$sth->execute();
		return true;
	}
	
	public function GetBlogPost($id)
	{
		$sth	= $this->db->prepare("SELECT id, headline, content, publish
				FROM ".MYSQL_PREFIX."blog
				WHERE id = :id");
		$sth->execute(array(":id" => $id));
		return $sth->fetch();
	}
	
	public function UpdateBlogPost($id,$headline, $content, $publish)
	{
		$publish	= explode(".", $publish);
		$publish	= mktime(0,0,0,$publish[1],$publish[0],$publish[2]);
		
		$sth		= $this->db->prepare("UPDATE ".MYSQL_PREFIX."blog SET
						headline	= :headline,
						content		= :content,
						publish		= :publish
						WHERE id	= :id");
		$sth->bindParam(":headline",	$headline);
		$sth->bindParam(":content",		$content);
		$sth->bindParam(":publish",		$publish);
		$sth->bindParam(":id",			$id);
		$sth->execute();
		return true;
	}
}