<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class PictureView extends AbstractView
{
	public function MainView()
	{
		/**
		 * Dummy
		 */
	}
	
	public function MyPictureView()
	{
		include_once(PATH_MODEL."picture.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
		$this->tpl->addJs("jquery.filedrop.js",		LINK_LIB."html5upload/js/");
		$this->tpl->addJs("script.js",				LINK_LIB."html5upload/js/");
		$this->tpl->addCss("styles.css",			LINK_LIB."html5upload/css/");
		$this->tpl->addJs("jquery.lightbox.js",		LINK_LIB."jquery-lightbox/js/");
		$this->tpl->addCss("jquery.lightbox.css",	LINK_LIB."jquery-lightbox/css/");
		$this->tpl->addJs("picture.js",				LINK_LIB."jquery-lightbox/js/");
		
		$this->tpl->vars("dropbox",			$this->tpl->load("_dropbox", PATH_PAGES_TPL."picture/"));
		
		$gallery	= "";
		foreach($this->model->GetMyPicture(PATH_UPLOAD.$user->GetUserId()) as $picture)
		{
			$this->tpl->vars("can_action",	"Yes");
			$this->tpl->vars("link",		LINK_LIB."thumbnail/thumbnail.php?dir=".$user->GetUserId()."&picture=".$picture."&width=800&height=426");
			$this->tpl->vars("thumbnail",	LINK_LIB."thumbnail/thumbnail.php?dir=".$user->GetUserId()."&picture=".$picture);
			$this->tpl->vars("pictureID",	$picture);
			$gallery	.= $this->tpl->load("_gallery_block", PATH_PAGES_TPL."picture/");
		}
		
		$this->tpl->vars("picture_gallery",		$gallery);
		$this->tpl->vars("content", $this->tpl->load("myPicture", PATH_PAGES_TPL."picture/"));
		$this->tpl->vars("headline",	"Deine Fotos");
		return $this->tpl->load("content");
	}
	
	public function DeletePictureView($user_id, $filename)
	{
		include_once(PATH_MODEL."picture.php");
		$this->model	= new PictureModel();
		
		if($this->model->DeletePicture($user_id, $filename))
		{
			$this->tpl->vars("message",		"Datei wurder erfolgreich gelöscht! :)");
			return $this->tpl->load("_message_success");
		}
		$this->tpl->vars("message",			"Datei konnte nicht gelöscht werden!");
		return $this->tpl->load("_message_error");
	}
	
	public function AllPictureMainView()
	{
		include_once(PATH_MODEL."picture.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
		$user_block		= "";
		foreach($user->GetAllUserIds() as $user_id)
		{
            $count = $this->model->CountPicture(PATH_UPLOAD.$user_id['id']);
            if($count == 0)
            {
                continue;
            }
			$this->tpl->vars("user_id",		$user_id['id']);
			$this->tpl->vars("count",		$count);
			$this->tpl->vars("username",	$user->GetUsernameById($user_id['id']));
			$user_block	.= $this->tpl->load("_userCount", PATH_PAGES_TPL."picture/");
		}
		$this->tpl->vars("users",		$user_block);
		
		return $this->tpl->load("_allPicture", PATH_PAGES_TPL."picture/");
	}
	
	public function AllPictureUserView($user_id)
	{
		include_once(PATH_MODEL."picture.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$this->model	= new PictureModel();
		$user			= new impeesaUser($_SESSION);
		
		$this->tpl->addJs("jquery.lightbox.js",		LINK_LIB."jquery-lightbox/js/");
		$this->tpl->addCss("jquery.lightbox.css",	LINK_LIB."jquery-lightbox/css/");
		$this->tpl->addJs("picture.js",				LINK_LIB."jquery-lightbox/js/");
		
		$this->tpl->vars("dropbox", "");
		
		$gallery	= "";
		foreach($this->model->GetMyPicture(PATH_UPLOAD.$user_id) as $picture)
		{
			$this->tpl->vars("can_action",	"");
			$this->tpl->vars("link",		LINK_LIB."thumbnail/thumbnail.php?dir=".$user_id."&picture=".$picture."&width=800&height=426");
			$this->tpl->vars("thumbnail",	LINK_LIB."thumbnail/thumbnail.php?dir=".$user_id."&picture=".$picture);
			$this->tpl->vars("downloadlink",	LINK_UPLOAD.$user_id."/".$picture);
			$this->tpl->vars("pictureID",	$picture);
			$gallery	.= $this->tpl->load("_gallery_block", PATH_PAGES_TPL."picture/");
		}
		
		$this->tpl->vars("picture_gallery",		$gallery);
		$this->tpl->vars("content", $this->tpl->load("myPicture", PATH_PAGES_TPL."picture/"));
		$this->tpl->vars("headline",	"Alle Fotos von ".$user->GetUsernameById($user_id));
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
