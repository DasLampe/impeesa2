<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class ContentController extends AbstractController
{
	public function FactoryController()
	{
		include_once(PATH_VIEW."content.php");
		$this->view	= new ContentView();
		
		if(file_exists(PATH_CONTENT.$this->param[0].".php"))
		{
			return $this->view->StaticView($this->param[0]);
		}
		elseif(($view = $this->view->DatabaseView($this->param[0])) != false)
		{
			return $view;	
		}
		else
		{
			throw new impeesaException("Contentfile doen't exists!");
		}
	}

	public function AdminController()
	{
		include_once(PATH_VIEW."content.php");
		$this->view	= new ContentView();
		
		if(isset($_POST['submit']))
		{
			return $this->view->SaveDatabaseView($_POST, $this->param[1]);
		}
	}
}
