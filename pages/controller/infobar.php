<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
/**
 * Show an infobar for some actions on every page. e.g. logout.
 * Every Viewclass can add a feature to this. (like sidebar)
 */
class InfobarController extends AbstractController
{
	function factoryController()
	{
		include_once(PATH_VIEW."infobar.php");
		$this->view			= new InfobarView();
		
		$return_content		= $this->view->MainView();
		
		/*
		 * If Page has sidebarview.
		 * @TODO: Check if exists method SidebarView
		 */
		if($this->param[0] == "admin")
		{
			include_once(PATH_VIEW."admin.php");
			$view		= new AdminView();
			$return_content .= $view->InfobarView();
		}
		
		return $return_content;
	}
}