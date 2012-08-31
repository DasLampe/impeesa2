<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaConfig
{
	public static function get($key)
	{
		include_once(PATH_CORE_CLASS."impeesaDB.class.php");
		$db		= impeesaDb::getConnection();
		
		$sth	= $db->prepare("SELECT config_value
								FROM ".MYSQL_PREFIX."config
								WHERE config_key = :key
								LIMIT 1");
		$sth->execute(array(":key"	=> $key));
		if($row	= $sth->fetch())
		{
			return $row['config_value'];
		}
		else
		{
			throw new impeesaException("Can't find key in database:".$key);
		}
		
	}
}