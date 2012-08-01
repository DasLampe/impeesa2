<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class PictureView extends AbstractView
{
	public function MainView()
	{
		include_once(PATH_MODEL."picture.php");
		$this->model	= new PictureModel();
		
		$albums_return = "";
		foreach($this->model->GetAlbums() as $albums)
		{
			$this->tpl->vars("headline",	substr($albums[0],0,4));
			$tpl_albums	= "";
			
			foreach($albums as $album)
			{
				$info		= $this->model->DecodeAlbumName($album);
				$this->tpl->vars("page_title",	$info['headline']);
				$this->tpl->vars("page_url",	CURRENT_PAGE.'/'.$album);
				$tpl_albums	.= $this->tpl->load("_nav_li");
			}
			
			$this->tpl->vars("content",			$tpl_albums);
			$albums_return	.= $this->tpl->load("_content_box_h2");
		}
		$this->tpl->vars("content",				$albums_return);
		return $this->tpl->load("_content");
	}
	
	public function AlbumView($album)
	{
		include_once(PATH_MODEL."picture.php");
		$this->model	= new PictureModel();
		
		$this->tpl->addJs("jquery.lightbox.js",		LINK_LIB."jquery-lightbox/js/");
		$this->tpl->addCss("jquery.lightbox.css",	LINK_LIB."jquery-lightbox/css/");
		$this->tpl->addJs("picture.js",				LINK_LIB."jquery-lightbox/js/");
		
		$gallery	= "";
		foreach($this->model->GetAlbumPicture($album) as $picture)
		{
			$this->tpl->vars("link",		LINK_LIB."thumbnail/thumbnail.php?dir=picture/".$album."&picture=".$picture."&width=800&height=426");
			$this->tpl->vars("thumbnail",	LINK_LIB."thumbnail/thumbnail.php?dir=picture/".$album."&picture=".$picture);
			$gallery	.= $this->tpl->load("_gallery_block", PATH_PAGES_TPL."picture/");
		}
		
		$this->tpl->vars("picture_gallery",		$gallery);
		$this->tpl->vars("content", 			$this->tpl->load("_gallery", PATH_PAGES_TPL."picture/"));
		$this->tpl->vars("headline",			implode(" - ", $this->model->DecodeAlbumName($album)));
		return $this->tpl->load("content");
	}
	
	public function UploadPictureView(array $file)
	{
		include_once(PATH_MODEL."picture.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
		if($this->model->UploadPicture($file, PATH_UPLOAD.$user->GetUserId()))
		{
			return array('status' => 'File was uploaded successfuly!');
		}
		return array('status' => 'Something went wrong!');
	}
}
