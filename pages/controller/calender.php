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
		
		if(isset($this->param[1]) && is_numeric($this->param[1]))
		{
			return $this->view->SpecificView($this->param[1]);
		}
		return $this->view->MainView();
	}
}