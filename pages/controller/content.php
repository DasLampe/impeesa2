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
		
		if(($view = $this->view->DatabaseView($this->param[0])) != false)
		{
			return $view;
		}
		elseif(file_exists(PATH_CONTENT.$this->param[0].".php"))
		{
			return $this->view->StaticView($this->param[0]);
		}
		else
		{
			throw new impeesaException("Contentfile don't exists!", 404);
		}
	}

	public function AdminController()
	{
		include_once(PATH_VIEW."content.php");
		$this->view	= new ContentView();
		
		if((!isset($this->param[1]) || $this->param[1] != "content") && isset($_POST['submit']))
		{
			return $this->view->SaveDatabaseView($_POST, $this->param[1]);
		}
		elseif($this->param[1] == "content")
		{
			switch(@$this->param[2])
			{
				case 'addPage': 
					return $this->view->NewPageView($_POST);
					break;
				case 'edit':
					return $this->view->EditPageView($this->param[3], $_POST);
					break;
				case 'editHTML':
					return $this->view->EditHTMLView($this->param[3], $_POST);
					break;
				case 'delete':
					return $this->view->DeleteView($this->param[3]);
					break;
				default:
					return $this->view->MenuEditView($_POST);
					break;
			}
		} 
	}
}
