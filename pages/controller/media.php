<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class MediaController extends AbstractController {
	public function factoryController() {
		//Show user content - nothing to show ;)
	}
	
	public function AdminController() {
		$user		= new impeesaUser($_SESSION);
		include_once(PATH_VIEW."media.php");
		$this->view	= new MediaView(); 
		
		if($user->CanEdit()) {
			switch(@$this->param[2]) {
				case 'upload':
					return $this->view->UploadView($_FILES);
					break;
				default:
				return $this->view->Overview();
			}
		}
	}
}