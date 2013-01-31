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
			$this->tpl->vars("group_leader",		implode(', ', $this->model->GetLeader($group['id'])));
			$this->tpl->vars("group_logo",			$group['logo']);
			$groups	.= $this->tpl->load("_group", PATH_PAGES_TPL."groups/");
		}
		
		$this->tpl->vars("headline",				"Gruppen");
		$this->tpl->vars("content",					$groups);
		return $this->tpl->load("content");
	}
	
	public function LeaderView() {
		include_once(PATH_MODEL."groups.php");
		$this->model	= new GroupsModel();
		
		$groups	= "";
		foreach($this->model->GetAllGroups(0) as $group) {
			$leaders	= "";
			foreach($this->model->GetLeader($group['id']) as $leader) {
				$this->tpl->vars("leader_name",		$leader);
				$leaders	.= $this->tpl->load("_leader", PATH_PAGES_TPL."groups/");
			}
			$this->tpl->vars("content",		$leaders);
			$this->tpl->vars("headline",	$group['name']);
			$groups		.= $this->tpl->load("_content_box_h2");
		}
		
		$this->tpl->vars("headline",	"Leiterrunde");
		$this->tpl->vars("content",		$groups);
		return $this->tpl->load("content");
	}
	
	public function SidebarView() {
		$menu_array	= array(
				array(
					"page_url"		=> LINK_MAIN."groups/leader",
					"page_title"	=> "Leiterrunde",
				),
			);
		
		$menu_items	= "";
		foreach($menu_array	as $menu_item)
		{
			$this->tpl->vars("page_url",		$menu_item['page_url']);
			$this->tpl->vars("page_title",		$menu_item['page_title']);
			$menu_items		.= $this->tpl->load("_nav_li");
		}
		
		$this->tpl->vars("submenu_items",		$menu_items);
		return $this->tpl->load("submenu", PATH_PAGES_TPL."sidebar/");
	}
}