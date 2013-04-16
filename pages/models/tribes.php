<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class TribesModel extends AbstractModel
{
	private $api;
	private $scoutNet_Id;
	/**
	 * Recreate to add ScoutNet-API
	 */
	public function __construct()
	{
		include_once(PATH_LIB."scoutnet-api-client-php/src/scoutnet.php");
		$this->api			= scoutnet();
		
		$this->db			= impeesaDb::getConnection();
		$this->scoutNet_Id	= impeesaConfig::get('scoutNetId');
	}
	
	public function getName() {
		return $this->api->Group($this->scoutNet_Id)->name();
	}
	
	public function getAllTribes() {
		return $this->api->Group($this->scoutNet_Id)->children();
	}
	
	public function getUrl($id) {
		$url	= $this->api->Group($id)->urls();
		if(count($url) > 0) {
			return $url[0]['url'];
		}
		return false;
	}
	
	public function getTribe($id) {
		return $this->api->Group($id);
	}
}