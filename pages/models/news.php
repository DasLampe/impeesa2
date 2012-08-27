<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class NewsModel extends AbstractModel
{
	/**
	 * All news
	 * @return array
	 */
	public function GetNewsPosts()
	{
		$sth	= $this->db->query("SELECT id, headline, content, publish
									FROM ".MYSQL_PREFIX."news
									ORDER BY id DESC");
		return $sth->fetchAll();
	}
	
	/**
	 * Single news
	 * @param int $id
	 * @return array
	 */
	public function GetNewsPost($id)
	{
		$sth	= $this->db->prepare("SELECT id, headline, content, publish
				FROM ".MYSQL_PREFIX."news
				WHERE id = :id");
		$sth->execute(array(":id" => $id));
		return $sth->fetch();
	}
	
	/**
	 * Insert news post
	 * @param string $headline
	 * @param string $content
	 * @param date-string (dd.mm.YYYY) $publish
	 */
	public function InsertNewsPost($headline, $content, $publish)
	{
		$publish	= explode(".", $publish);
		$publish	= mktime(0,0,0,$publish[1],$publish[0],$publish[2]);
		
		$sth		= $this->db->prepare("INSERT INTO ".MYSQL_PREFIX."news
								(headline, content, publish)
								VALUES
								(:headline, :content, :publish)");
		$sth->bindParam(":headline",	$headline);
		$sth->bindParam(":content",		$content);
		$sth->bindParam(":publish",		$publish);
		$sth->execute();
		return true;
	}
	
	/**
	 * Update news post
	 * @param int $id
	 * @param string $headline
	 * @param string $content
	 * @param date-string (dd.mm.YYYY) $publish
	 */
	public function UpdateNewsPost($id,$headline, $content, $publish)
	{
		$publish	= explode(".", $publish);
		$publish	= mktime(0,0,0,$publish[1],$publish[0],$publish[2]);
		
		$sth		= $this->db->prepare("UPDATE ".MYSQL_PREFIX."news SET
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