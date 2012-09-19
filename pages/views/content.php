<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class ContentView extends AbstractView {
	public function MainView()
	{
		/**
		 * Hack! Don't need this function!
		 */
	}
	
	public function StaticView($sitename)
	{
		ob_start();
		include(PATH_CONTENT.$sitename.".php");
		$content=ob_get_contents();
		ob_end_clean();
		
		$this->tpl->vars("content", $content);
		return $this->tpl->load("_content");
	}
	
	public function DatabaseView($sitename)
	{
		include_once(PATH_MODEL."content.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user			= new impeesaUser($_SESSION);
		$this->model	= new ContentModel();
		
		$content		= $this->model->GetPageContent($sitename);
		
		$this->tpl->vars("content", $content['content']);
		
		if($user->CanEdit() == true)
		{ //Use inline edit
			include_once(PATH_CORE_CLASS."impeesaLayer.class.php");
			$layer		= impeesaLayer::getInstance();
			
			$layer->AddButton($this->tpl->load("_saveButton"));
			$this->tpl->addJs("editable.js",	LINK_CORE_LIB."editable/");
			return $this->tpl->load("_edit_content");
		}
		return $this->tpl->load("_content");
	}
	
	public function SaveDatabaseView($data, $sitename)
	{
		include_once(PATH_MODEL."content.php");
		$this->model	= new ContentModel();
		
		if($this->model->SavePage($data['content'], $sitename) == true)
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Seite erfolgreich gespeichert!", LINK_MAIN.$sitename);
		}
	}
}