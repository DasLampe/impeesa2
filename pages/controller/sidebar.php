<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class SidebarController extends AbstractController {
	function factoryController()
	{
		include_once(PATH_VIEW."sidebar.php");
		$this->view			= new SidebarView();
		
		$return_content		= $this->view->MainView();
		$return_content		.= $this->view->SubmenuView($this->param[0]);
		
		/*
		 * If Page has sidebarview.
		 * @TODO: Check if exists method SidebarView
		 */
		if($this->param[0] == "admin")
		{
			include_once(PATH_VIEW."admin.php");
			$view		= new AdminView();
			$return_content .= $view->SidebarView();
		}
		if($this->param[0] == "calender")
		{
			include_once(PATH_VIEW."calender.php");
			$view		= new CalenderView();
			$return_content	.= $view->SidebarView();
		}
		if($this->param[0] == "groups") {
			include_once(PATH_VIEW."groups.php");
			$view		= new GroupsView();
			$return_content	.= $view->SidebarView();
		}
		if($this->param[0] == "tribes") {
			include_once(PATH_VIEW."tribes.php");
			$view		= new TribesView();
			$return_content	.= $view->SidebarView();
		}
		
		return $return_content;
	}
}