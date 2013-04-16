<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class TribesView extends AbstractView {
	public function MainView() {
		include_once(PATH_MODEL."tribes.php");
		$this->model	= new TribesModel();
		
		$i		= 0;
		$list	= "<ul>";
		
		foreach($this->model->getAllTribes() as $tribe) {
			$this->tpl->vars("page_url",	LINK_MAIN."tribes/".$tribe['global_id']);
			$this->tpl->vars("page_title",	$tribe['name']);
			$list	.= $this->tpl->load("_nav_li");
			$i++;
		}
		
		$this->tpl->vars("name",	$this->model->getName());
		$this->tpl->vars("count", 	$i);
		$this->tpl->vars("list",	$list);
		return $this->tpl->load("main", PATH_PAGES_TPL."tribes/");
	}
	
	public function TribeView($id) {
		include_once(PATH_MODEL."tribes.php");
		$this->model	= new TribesModel();
		
		$tribe	= $this->model->getTribe($id);
		
		$this->tpl->vars("id",		$id);
		$this->tpl->vars("name", 		$tribe['name']);
		$this->tpl->vars("zip",			$tribe['zip']);
		$this->tpl->vars("city",		$tribe['city']);
		$this->tpl->vars("district",	$tribe['district']);
		$this->tpl->vars("url",			$this->model->getUrl($id));
		$this->tpl->vars("map",			$this->getMap($tribe));
		
		return $this->tpl->load("_tribe", PATH_PAGES_TPL."tribes/");
	}
	
	private function getMap($tribe) {
		if(empty($tribe['zip']) && empty($tribe['city'])) {
			return "";
		}
		return $this->tpl->load("_map", PATH_PAGES_TPL."tribes/");
	}
	
	public function SidebarView() {
		include_once(PATH_MODEL."tribes.php");
		$this->model	= new TribesModel();

		$menu_items	= "";
		foreach($this->model->getAllTribes() as $tribe)
		{
			$this->tpl->vars("page_url",		LINK_MAIN."tribes/".$tribe->global_id);
			$this->tpl->vars("page_title",		$tribe->name);
			$menu_items		.= $this->tpl->load("_nav_li");
		}
	
		$this->tpl->vars("submenu_items",		$menu_items);
		$sidebar	= $this->tpl->load("submenu", PATH_PAGES_TPL."sidebar/");

		$this->tpl->vars("content",		'<p class="bordered dimmed">Daten via ScoutNet API. :)<br/><a href="http://www.scoutnet.de">www.scoutnet.de</a></p>');
		$info		= $this->tpl->load("_content");
		
		return $sidebar.$info;
	}
}