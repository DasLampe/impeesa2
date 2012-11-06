<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class SidebarView extends AbstractView {
	public function MainView()
	{
		return "";
	}
	
	public function SubmenuView($sitename)
	{
		include_once(PATH_CORE_CLASS."impeesaMenu.class.php");
		$submenu	= new impeesaMenu();
		
		$submenu_item	= "";
		foreach($submenu->GetAllSubMenuEntries($sitename) as $submenu_entry)
		{
			$this->tpl->vars("page_url",	LINK_MAIN.$submenu_entry['name']);
			$this->tpl->vars("page_title",	$submenu_entry['menu_title']);
			$submenu_item	.= $this->tpl->load("_nav_li");
		}
		$this->tpl->vars("submenu_items",	$submenu_item);
		return $this->tpl->load("submenu",			PATH_PAGES_TPL."sidebar/");
	}
}