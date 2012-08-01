<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class CalenderModel extends AbstractModel
{
	private $api;
	/**
	 * Recreate to add ScoutNet-API
	 */
	public function __construct()
	{
		include_once(PATH_LIB."scoutnet-api-client-php/src/scoutnet.php");
		$this->api	= scoutnet();
		
		$this->db	= impeesaDb::getConnection();
	}
	
	public function GetAllEvents()
	{
		return $this->api->group(7)->events('start_date > "'.date("Y-m-d", time()).'"');
	}
	
	public function FilterGroupFromKeywords($keywords) {
		$keywords	= explode("--.--", $keywords);
		$group	= array();
		foreach($keywords as $keyword)
		{
			switch($keyword)
			{
				case 'Wölflinge':
				case 'Jungpfadfinder':
				case 'Pfadfinder':
				case 'Rover':
				case 'Leiter':
					$group[]	= $keyword;
					break;
			}
		}
		return $group;
	}
	
	public function FilterCategorieFromKeywords($keywords)
	{
		$keywords	= explode("--.--", $keywords);
		$categorie	= array();
		foreach($keywords as $keyword)
		{
			switch($keyword)
			{
				case 'Wölflinge':
				case 'Jungpfadfinder':
				case 'Pfadfinder':
				case 'Rover':
				case 'Leiter':
					continue;
					break;
				default:
					$categorie[]	= $keyword;
			}
		}
		return $categorie;
	}
	
	public function CreateReadableDateTime($date, $time="")
	{
		$date	= explode("-", $date);
		return $date[2].'.'.$date[1].'.'.$date[0].' '.substr($time, 0,5);
	}
}