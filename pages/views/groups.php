<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class GroupsView extends AbstractView {
	public function MainView() {
		include_once(PATH_MODEL."groups.php");
		$this->model	= new GroupsModel();
		
		$groups	= "";
		foreach($this->model->GetAllGroups() as $group) {
			$this->tpl->vars("group_name",			$group['name']);
			$this->tpl->vars("group_description",	$group['description']);
			$this->tpl->vars("group_youngest",		$group['youngest']);
			$this->tpl->vars("group_oldest",		$group['oldest']);
			$this->tpl->vars("group_day",			$group['day']);
			$this->tpl->vars("group_begin",			date("H:i", strtotime($group['begin'])));
			$this->tpl->vars("group_end",			date("H:i", strtotime($group['end'])));
			//$this->tpl->vars("group_leader",		implode(',', $this->model->GetLeader($group['id']));
			$this->tpl->vars("group_logo",			$group['logo']);
			$groups	.= $this->tpl->load("_group", PATH_PAGES_TPL."groups/");
		}
		
		$this->tpl->vars("headline",				"Gruppen");
		$this->tpl->vars("content",					$groups);
		return $this->tpl->load("content");
	}
}