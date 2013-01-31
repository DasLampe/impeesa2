<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class GroupsController extends AbstractController {
	public function factoryController() {
		include_once(PATH_VIEW."groups.php");
		$this->view		= new GroupsView();
		
		if(isset($this->param[1]) && $this->param[1] == "leader") {
			return $this->view->LeaderView();
		}
		return $this->view->MainView();
	}
	
	public function AdminController() {
		include_once(PATH_VIEW."groups.php");
		$this->view		= new GroupsView();
		
		if(!isset($this->param[2])) {
			return $this->view->AdminView();
		}
		elseif($this->param[2] == "edit")
		{
			if(isset($this->param[3])) {
				return $this->view->EditView($_POST, $this->param[3]);
			}
			else {
				return $this->view->EditView($_POST);
			}
		}
	}
}