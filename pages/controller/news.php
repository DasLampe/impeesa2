<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class NewsController extends AbstractController
{
	public function factoryController()
	{
		include_once(PATH_VIEW."news.php");
		$this->view	= new NewsView();
		
		return $this->view->MainView();
	}
	
	public function AdminController()
	{
		include_once(PATH_VIEW."news.php");
		$this->view	= new NewsView();
		
		if(isset($_FILES['file']))
		{
			return json_encode($this->view->UploadPictureView($_FILES['file']));
		}
		switch(@$this->param[2])
		{
			case 'add':
				if(!isset($_POST['submit']))
				{
					return $this->view->AddView($_POST);
				}
				return json_encode($this->view->AddView($_POST));
				break;
			case 'edit':
				if(!isset($_POST['submit']))
				{
					return $this->view->EditView($this->param[3], $_POST);
				}
				return json_encode($this->view->EditView($this->param[3], $_POST));
				break;
			default:
				return $this->view->OverviewView();
		}
	}
}