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
}