<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class TribesController extends AbstractController {
	public function factoryController() {
		include_once(PATH_VIEW."tribes.php");
		$this->view		= new TribesView();
		
		if(isset($this->param[1]) && is_numeric($this->param[1])) {
			return $this->view->TribeView($this->param[1]);
		}
		return $this->view->MainView();
	}
}