<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class NewsView extends AbstractView
{
	public function MainView()
	{
		include_once(PATH_MODEL."news.php");
		$this->model	= new NewsModel();
		
		
		$posts="";
		foreach($this->model->GetNewsPosts() as $news_post)
		{
			$this->tpl->vars("headline",		$news_post['headline']);
			$this->tpl->vars("content",			$news_post['content']);
			$this->tpl->vars("publish",			date("d.m.Y", $news_post['publish']));
			
			$this->tpl->vars("content",			$this->tpl->load("newsPost", PATH_PAGES_TPL."news/"));
			$posts	.= $this->tpl->load("_box");
		}
		return $posts;
	}
	
	public function OverviewView()
	{
		include_once(PATH_MODEL."news.php");
		$this->model	= new NewsModel();
		$layer			= impeesaLayer::getInstance();
		
		$layer->AddButton('<a href="'.LINK_MAIN.'admin/news/add" class="ym-add">Neuigkeiten hinzufügen</a>');
		
		$posts="";
		foreach($this->model->GetNewsPosts() as $news_post)
		{
			$this->tpl->vars("id",				$news_post['id']);
			$this->tpl->vars("headline",		$news_post['headline']);
			$this->tpl->vars("published",		date("d.m.Y", $news_post['publish']));

			$posts	.= $this->tpl->load("_overviewPost", PATH_PAGES_TPL."news/");
		}
		$this->tpl->vars("posts", $posts);
		return $this->tpl->load("overview", PATH_PAGES_TPL."news/");
	}
	
	public function AddView($data)
	{
		include_once(PATH_MODEL."news.php");
		$this->model	= new NewsModel();
		$layer			= impeesaLayer::getInstance();
		
		//Add Buttons
		$this->tpl->vars("label",					"Bild hinzufügen");
		$layer->AddButton($this->tpl->load("_form_file_upload"), "right");
		$layer->AddButton($this->tpl->load("_saveButton", PATH_PAGES_TPL."news/"));
		
		
		if(!isset($data['submit']))
		{			
			$this->tpl->addJs("editable.js",		LINK_CORE_LIB."editable/");
			
			$this->tpl->vars("headline",		"Überschrift");
			$this->tpl->vars("content",			"Hier Text eingeben");
			$this->tpl->vars("publish",			date("d.m.Y", time()));
				
			$this->tpl->vars("content",			$this->tpl->load("edit_newsPost", PATH_PAGES_TPL."news/"));
			return $this->tpl->load("_box");
		}
		else
		{
			$this->model->InsertNewsPost($data['headline'], $data['content'], $data['publish']);
			return impeesaLayer::SetInfoMsg($_SESSION, "Erfolgreich gespeichert!", CURRENT_PAGE, "success");
		}
	}
	
	public function EditView($id, $data)
	{
		include_once(PATH_MODEL."news.php");
		$this->model	= new NewsModel();
		$layer			= impeesaLayer::getInstance();
		
		//Add Buttons
		$this->tpl->vars("label",					"Bild hinzufügen");
		$layer->AddButton($this->tpl->load("_form_file_upload"));
		$layer->AddButton($this->tpl->load("_saveButton", PATH_PAGES_TPL."news/"));
		
		if(!isset($data['submit']))
		{
			$news_post		= $this->model->GetNewsPost($id);
			$this->tpl->addJs("editable.js",		LINK_CORE_LIB."editable/");

			$this->tpl->vars("headline",		$news_post['headline']);
			$this->tpl->vars("content",			$news_post['content']);
			$this->tpl->vars("publish",			date("d.m.Y", $news_post['publish']));
		
			$this->tpl->vars("content",			$this->tpl->load("edit_newsPost", PATH_PAGES_TPL."news/"));
			return $this->tpl->load("_box");
		}
		else
		{
			$this->model->UpdateNewsPost($id, $data['headline'], $data['content'], $data['publish']);
			return impeesaLayer::SetInfoMsg($_SESSION, "Erfolgreich gespeichert!", CURRENT_PAGE, "success");
		}
	}
	
	public function UploadPictureView($file)
	{
		include_once(PATH_MODEL."picture.php");
		$this->model	= new PictureModel();
		
		if(preg_match("/(.*)\.jpg$/", $file['name']) == false)
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Es wird nur *.jpg unterstützt!", "", "error");
		}
		
		//Not run into name conflict
		$this->model->encodeFilename($file);
		
		if($this->model->UploadPicture($file, PATH_UPLOAD."news/"))
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Bild erfolgreich hochgeladen.", "", "success", array('picture_link' => LINK_LIB."thumbnail/thumbnail.php?dir=news&picture=".$file['name']));
		}
		return impeesaLayer::SetInfoMsg($_SESSION, "Bild konnte nicht hochgeladen werden", "", "error");
	}
}