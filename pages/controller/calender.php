<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class CalenderController extends AbstractController
{
	public function FactoryController()
	{
		include_once(PATH_VIEW."calender.php");
		$this->view	= new CalenderView();
		
		return $this->view->MainView();
	}
}