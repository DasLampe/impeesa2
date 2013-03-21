<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class MediaModel extends AbstractModel {
	public function getMedia() {
		$media		= array();
		
		$handle	= opendir(PATH_UPLOAD."media/");
		if($handle === false) {
			throw new impeesaException("Can't open ".PATH_UPLOAD."picture/");
		}
		
		while(false !== ($file	= readdir($handle)))
		{
			if(!preg_match("/^\./", $file) && $file != "README")
			{
				$media[]	= array(
									'file'	=> PATH_UPLOAD."media/".$file,
									'name'	=> $file,
									'thumb'	=> $this->getThumb(PATH_UPLOAD."media/".$file),
								);
			}
		}
		return $media;
	}
	
	private function getThumb($file) {
		$filepath	= explode("/", $file);
		return LINK_LIB."thumbnail/thumbnail.php?dir=media&picture=".$filepath[count($filepath)-1];
	}
}