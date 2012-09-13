<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class PictureController extends AbstractController
{
	public function FactoryController()
	{
		include_once(PATH_VIEW."picture.php");
		$this->view	= new PictureView();
		
		if(!isset($this->param[1]))
		{
			return $this->view->MainView();
		}
		else
		{
			return $this->view->AlbumView($this->param[1]);
		}
	}
	
	public function AdminController()
	{
		include_once(PATH_VIEW."picture.php");
		
		$this->view	= new PictureView();
		
		switch($this->param[2])
		{
			case "newAlbum":
				return $this->view->NewAlbumView($_POST);
				break;
			case "uploadFile":
				return json_encode($this->view->UploadPictureView($_FILES['pic'], $this->param[3]));
				break;
			case "myPicture":
				if(isset($this->param[2]) && $this->param[2] == "delete")
				{
					return $this->view->DeletePictureView($_SESSION['user_id'], $this->param[3]);
				}
				if(!isset($_FILES['pic']))
				{
					return $this->view->MyPictureView();
				}
				else
				{
					return json_encode($this->view->UploadPictureView($_FILES['pic']));
				}
				break;
			case "allPicture":
				if(!isset($this->param[2]))
				{
					return $this->view->AllPictureMainView();
				}
				return $this->view->AllPictureUserView($this->param[2]);
				break;
		}
	}
}