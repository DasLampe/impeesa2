<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class BlogController extends AbstractController
{
	public function factoryController()
	{
		include_once(PATH_VIEW."blog.php");
		$this->view	= new BlogView();
		
		return $this->view->MainView();
	}
	
	public function AdminController()
	{
		include_once(PATH_VIEW."blog.php");
		$this->view	= new BlogView();
		
		if(isset($_FILES['file']))
		{
			return json_encode($this->view->UploadPictureView($_FILES['file']));
		}
		switch($this->param[2])
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
		}
	}
}