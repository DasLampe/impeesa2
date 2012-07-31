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
		if(isset($this->param[1]) && $this->param[1] == "blog")
		{
			include_once(PATH_VIEW."blog.php");
			$view		= new BlogView();
			$return_content .= $view->SidebarView();
		}
		
		return $return_content;
	}
}