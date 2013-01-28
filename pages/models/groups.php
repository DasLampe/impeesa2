<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class GroupsModel extends AbstractModel {
	public function GetAllGroups() {
		$sth	= $this->db->prepare("SELECT id, name, description, day, youngest, oldest, begin, end, logo
									FROM ".MYSQL_PREFIX."groups");
		$sth->execute();
		return $sth->fetchAll();
	}
}