<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class MediaView extends AbstractView {
	public function MainView() {
		//Dummy
	}
	
	public function Overview() {
		include_once(PATH_MODEL."media.php");
		$this->model	= new MediaModel();
		
		$return			= "";
		foreach($this->model->getMedia() as $media) {
			$this->tpl->vars("link",		LINK_MAIN."m/".$media['name']);
			$this->tpl->vars("thumb_link",	$media['thumb']);
		
			$return		.= $this->tpl->load("_media_block", PATH_PAGES_TPL."media/");
		}
		
		$this->tpl->vars("media",	$return);
		return $this->tpl->load("overview", PATH_PAGES_TPL."media/");
	}
}