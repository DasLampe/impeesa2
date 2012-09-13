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
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
		if($user->CanEdit() == true)
		{
			$layer			= impeesaLayer::getInstance();
		
			$layer->AddButton('<a href="'.LINK_ACP.'picture/newAlbum">Neues Album</a>');
		}
		
		$albums_return = "";
		foreach($this->model->GetAlbums() as $albums)
		{
			$tpl_albums	= "";
			foreach($albums as $album)
			{
				if($this->model->CountPicture($album) > 0 || $user->CanEdit() == true)
				{ //do not show empty albums for normal users
					$info		= $this->model->ConvertAlbumName($album);
					$this->tpl->vars("page_title",	$info['headline']);
					$this->tpl->vars("page_url",	CURRENT_PAGE.'/'.$album);
					$tpl_albums	.= $this->tpl->load("_nav_li");
				}
			}
			
			if(!empty($tpl_albums))
			{ //Do not show empty years
				$this->tpl->vars("headline",	substr($albums[0],0,4));
				$this->tpl->vars("content",			$tpl_albums);
				$albums_return	.= $this->tpl->load("_content_box_h2");
			}
				
		}
		$this->tpl->vars("content",				$albums_return);
		return $this->tpl->load("_content");
	}
	
	public function AlbumView($album)
	{
		include_once(PATH_MODEL."picture.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
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
		$content	= $this->tpl->load("_gallery", PATH_PAGES_TPL."picture/");
		
		if($user->canEdit() == true)
		{ //if user can upload picture, show dropbox zone
			$this->tpl->addJs("jquery.filedrop.js",	LINK_LIB."html5upload/js/");
			$this->tpl->addJs("script.js",			LINK_LIB."html5upload/js/");
			$this->tpl->addCss("styles.css",		LINK_LIB."html5upload/css/");
			$content .= $this->tpl->load("_dropbox", PATH_PAGES_TPL."picture/");
		}

		$this->tpl->vars("content", 			$content);
		$this->tpl->vars("headline",			implode(" - ", $this->model->ConvertAlbumName($album)));
		return $this->tpl->load("content");
	}
	
	public function UploadPictureView(array $file, $album)
	{
		include_once(PATH_MODEL."picture.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
		if($user->CanEdit() == true)
		{
			if($this->model->UploadPicture($file, PATH_UPLOAD."picture/".$album))
			{
				return array('status' => 'File was uploaded successfuly!');
			}
			return array('status'	=> "Something went wrong!");
		}
		return array('status' => 'Something went wrong!');
	}
	
	public function NewAlbumView($data)
	{
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		include_once(PATH_MODEL."picture.php");
		
		$this->model	= new PictureModel();
		$form			= new impeesaForm();
		
		$form_fields	= array(
								array("fieldset", "Album informationen", array(
										array("text", "Album", "name", "", True),
										array("year", "Jahr", "year", "", True),
										),
									),
								array("submit", "Album erstelen", "submit"),
								);
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		else
		{
			$album_name	= implode("", $this->model->ConvertAlbumName($data['year'].' '.$data['name'], "system"));
			if($this->model->CreateAlbum($album_name))
			{
				return impeesaLayer::SetInfoMsg($_SESSION, "Album erfolgreich erstellt", LINK_MAIN."picture/".$album_name);
			}
			return impeesaLayer::SetInfoMsg($_SESSION, "Album konnte nicht erstellt werden! Evtl. existiert es bereits.", CURRENT_PAGE, "error");
		}
	}
}
