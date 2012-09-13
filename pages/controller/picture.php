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
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		
		$this->view	= new PictureView();
		$user			= new impeesaUser($_SESSION);
		
		switch($this->param[2])
		{
			case "newAlbum":
				if($user->CanAdd() == true)
				{
					return $this->view->NewAlbumView($_POST);
				}
				throw new impeesaPermissionException("Can't create album!");
				break;
			case "uploadFile":
				if($user->CanAdd() == true)
				{
					return json_encode($this->view->UploadPictureView($_FILES['pic'], $this->param[3]));
				}
				throw new impeesaPermissionException("Can't upload picture!");
				break;
			case "deleteFile":
				if($user->CanDelete() == true)
				{
					return $this->view->DeletePictureView($this->param[3], $this->param[4]);
				}
				throw new impeesaPermissionException("Can't delete picture!");
				break;
		}
	}
}