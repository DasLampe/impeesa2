<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class BlogView extends AbstractView
{
	public function MainView()
	{
		include_once(PATH_MODEL."blog.php");
		$this->model	= new BlogModel();
		
		
		$posts="";
		foreach($this->model->GetBlogPosts() as $blog_post)
		{
			$this->tpl->vars("day",				$blog_post['id']);
			$this->tpl->vars("headline",		$blog_post['headline']);
			$this->tpl->vars("content",			$blog_post['content']);
			$this->tpl->vars("publish",			date("d.m.Y", $blog_post['publish']));
			
			$this->tpl->vars("content",			$this->tpl->load("blogPost", PATH_PAGES_TPL."blog/"));
			$posts	.= $this->tpl->load("_box");
		}
		return $posts;
	}
	
	public function AddView($data)
	{
		include_once(PATH_MODEL."blog.php");
		$this->model	= new BlogModel();
		
		if(!isset($data['submit']))
		{			
			$this->tpl->addJs("editable.js",		LINK_CORE_LIB."editable/");
			
			$this->tpl->vars("day",				$this->model->GetNextDay());
			$this->tpl->vars("headline",		"Überschrift");
			$this->tpl->vars("content",			"Hier Text eingeben");
			$this->tpl->vars("publish",			date("d.m.Y", time()));
				
			$this->tpl->vars("content",			$this->tpl->load("edit_blogPost", PATH_PAGES_TPL."blog/"));
			return $this->tpl->load("_box");
		}
		else
		{
			$this->model->InsertBlogPost($data['headline'], $data['content'], $data['publish']);
			return array('msg' => "Erfolgreich gespeichert!");
		}
	}
	
	public function EditView($id, $data)
	{
		include_once(PATH_MODEL."blog.php");
		$this->model	= new BlogModel();
		
		if(!isset($data['submit']))
		{
			$blog_post		= $this->model->GetBlogPost($id);
			$this->tpl->addJs("editable.js",		LINK_CORE_LIB."editable/");
				
			$this->tpl->vars("day",				$blog_post['id']);
			$this->tpl->vars("headline",		$blog_post['headline']);
			$this->tpl->vars("content",			$blog_post['content']);
			$this->tpl->vars("publish",			date("d.m.Y", $blog_post['publish']));
		
			$this->tpl->vars("content",			$this->tpl->load("edit_blogPost", PATH_PAGES_TPL."blog/"));
			return $this->tpl->load("_box");
		}
		else
		{
			$this->model->UpdateBlogPost($id, $data['headline'], $data['content'], $data['publish']);
			return array('msg' => "Erfolgreich gespeichert!");
		}
	}
	
	public function UploadPictureView($file)
	{
		include_once(PATH_MODEL."picture.php");
		$this->model	= new PictureModel();
		
		if($this->model->UploadPicture($file, PATH_UPLOAD."blog/"))
		{
			return array('picture_link' => LINK_LIB."thumbnail/thumbnail.php?dir=blog&picture=".$file['name'], 'status' => 'File was uploaded successfuly!');
		}
		return array('status' => 'Something went wrong!');
	}
	
	public function SidebarView()
	{
		$buttons	= "";
		$this->tpl->vars("label",					"Foto hinzufügen");
		$buttons	.= $this->tpl->load("_form_file_upload");
		
		$buttons	.= $this->tpl->load("_saveButton", PATH_PAGES_TPL."blog/");
		
		$this->tpl->vars("content",		$buttons);
		
		return $this->tpl->load("_actionButton", PATH_PAGES_TPL."infobar/");
	}
}