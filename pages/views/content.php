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
			//$this->tpl->addJs("require.js",			LINK_CORE_LIB."aloha/");
			$this->tpl->addCss("jquery.contenteditable.css", LINK_CORE_LIB."jquery.contenteditable/");
			$this->tpl->addJs("shortcut.js",				LINK_CORE_LIB."jquery.contenteditable/");
			$this->tpl->addJs("jquery.contenteditable.js", LINK_CORE_LIB."jquery.contenteditable/");
			//$this->tpl->addJs("aloha-full.min.js",			LINK_CORE_LIB."aloha/");
			//this->tpl->addJs("aloha-jquery-noconflict.js",	LINK_CORE_LIB."aloha/");
			$this->tpl->addJs("editable.js",		LINK_CORE_LIB."editable/");
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

	public function NewPageView($data)
	{
		include_once(PATH_MODEL."content.php");
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form 	= new impeesaForm();
		$this->model 	= new ContentModel();
		
		//Fill old informations into form
		$title 		= (!isset($data['title'])) ? "" : $data['title'];
		$name		= (!isset($data['name'])) ? "" : $data['name'];
		$menu_title = (!isset($data['menu_title'])) ? "" : $data['menu_title'];
		$in_nav		= (!isset($data['in_nav'])) ? False : True;

		$form_fields	= array(
							array("fieldset", "Seiten Informationen", array(
										array("text", "Titel", "title", $title, True),
										array("text", "Interner Seitenname", "name", $name,True),
									),
								),
							array("fieldset", "Menü", array(
									array("text", "Menütitel", "menu_title", $menu_title, True),
									array("checkbox", "In Menü anzeigen", "in_nav", $in_nav, False),
								),
							),
							array("fieldset", "", array(
									array("submit", "Speichern", "submit"),
								),
							),
						);

		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		
		if($this->model->ExistsPage($this->model->SetValidPageName($data['name'])) == True)
		{
			$form->SetErrorMsg("Interner Name ist schon vergeben! (Wurde vorher in eine valide Form gebracht.)");
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		
		if($this->model->CreatePage($data['name'], $data['title'], $data['menu_title'], $in_nav))
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Seite erfolgreich gespeichert.", LINK_MAIN.$this->model->SetValidPageName($data['name']), "success");
		}
		
		return impeesaLayer::SetInfoMsg($_SESSION, "Seite konnte nicht gespeichert werden.", CURRENT_PAGE, "error");
	}
	
	public function EditPageView($sitename, $data)
	{
		include_once(PATH_MODEL."content.php");
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form 	= new impeesaForm();
		$this->model 	= new ContentModel();

		
		$page		= $this->model->GetPageInfo($sitename);
		
		if(!isset($data['submit']) && $page['in_nav'] == 1)
		{
			$data['in_nav'] = True;
		}
		//Fill old informations into form
		$title 		= (!isset($data['title'])) ? $page['title'] : $data['title'];
		$name		= (!isset($data['name'])) ? $page['name'] : $data['name'];
		$menu_title = (!isset($data['menu_title'])) ? $page['menu_title'] : $data['menu_title'];
		$in_nav		= (!isset($data['in_nav'])) ? False : True;
		
		$form_fields	= array(
							array("fieldset", "Seiten Informationen", array(
										array("text", "Titel", "title", $title, True),
										array("text", "Interner Seitenname", "name", $name,True),
									),
								),
							array("fieldset", "Menü", array(
									array("text", "Menütitel", "menu_title", $menu_title, True),
									array("checkbox", "In Menü anzeigen", "in_nav", $in_nav, False),
								),
							),
							array("fieldset", "", array(
									array("submit", "Speichern", "submit"),
								),
							),
						);

		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		elseif($this->model->SetValidPageName($page['name']) != $this->model->SetValidPageName($data['name']) && $this->model->ExistsPage($this->model->SetValidPageName($data['name'])) == true)
		{
			$form->SetErrorMsg("Interner Name ist schon vergeben! (Wurde vorher in eine valide Form gebracht.)");
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		elseif($this->model->EditPageInfo($page['name'], $data['name'], $data['title'], $data['menu_title'], $in_nav))
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Seite wurde erfolgreich geändert.", LINK_MAIN.$this->model->SetValidPageName($data['name']), "success");
		}
		return impeesaLayer::SetInfoMsg($_SESSION, "Es ist ein Fehler aufgetreten. Seite wurde nicht gespeichert!", CURRENT_PAGE, "error");
		
	}
	
	public function MenuEditView($data)
	{	
		include_once(PATH_MODEL."content.php");
		$this->model	= new ContentModel();
		
		if(!isset($data['submit']))
		{
			$layer			= impeesaLayer::getInstance();
		
			$layer->AddButton($this->tpl->load("_saveButton"));
		
			$this->tpl->addJs("jquery-ui.min.js",	LINK_CORE_LIB."js/");
			$this->tpl->addCss("jquery-ui.min.css",	LINK_CORE_LIB."js/css/blitzer/");
			$this->tpl->addJs("menu_order.js",		LINK_MAIN."pages/template/content/");
			$this->tpl->addCss("menu_order.css",	LINK_MAIN."pages/template/content/");
		
			$tabs	= "";
		
			$this->tpl->vars("label",		"Hauptmenü");
			$this->tpl->vars("parent_id",	"0");
			$tabs	= $this->tpl->load("_menu_order_tab", PATH_PAGES_TPL."content/");
		
			foreach($this->model->GetAllMenuEntries(0, False) as $menu_entry)
			{
				$this->tpl->vars("label",		$menu_entry['menu_title']);
				$this->tpl->vars("parent_id",	$menu_entry['id']);
				$tabs	.= $this->tpl->load("_menu_order_tab", PATH_PAGES_TPL."content/");
			}
		
			$this->tpl->vars("menu_tabs",	$tabs);
			$this->tpl->vars("submenu", $this->SubmenuOrder($this->model));
		
			$this->tpl->vars("menu_order", $this->tpl->load("_menu_order", PATH_PAGES_TPL."content/"));
			return $this->tpl->load("menu_edit", PATH_PAGES_TPL."content/");
		}
		elseif($this->model->SaveMenuOrder($data))
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Neue Reihenfolge wurde erfolgreich gespeichert! (ggf. muss die Seite neugeladen werden)", CURRENT_PAGE, "success");
		}
		return impeesaLayer::SetInfoMsg($_SESSION, "Leider ist ein Fehler aufgetreten", CURRENT_PAGE, "error");
	}
	
	public function DeleteView($sitename)
	{
		include_once(PATH_MODEL."content.php");
		$this->model = new ContentModel();
		
		if($this->model->DeletePage($sitename))
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Seite wurder erfolgreich gelöscht.", LINK_ACP."content/", "success");
		}
		return impeesaLayer::SetInfoMsg($_SESSION, "Seite konnte nicht gelöscht werden!", LINK_ACP."content/", "success");
	}
	
	private function SubmenuOrder($model)
	{
		$this->model	= $model;

		/**
		* Create root submenu
		**/
		$this->tpl->vars("parent_id",	0);
		
		$root_menu	= "";	
		foreach($this->model->GetAllMenuEntries(0, False) as $menu_entry)
		{
			$this->tpl->vars("page_menu_title",	$menu_entry['menu_title']);
			$this->tpl->vars("page_id",			$menu_entry['id']);
			$this->tpl->vars("in_menu",			intval($menu_entry['in_nav']));
			$this->tpl->vars("page_name",		$menu_entry['name']);
			
			$root_menu	.= $this->tpl->load("_menu_order_li", PATH_PAGES_TPL."content/");
		}
		
		$this->tpl->vars("menu",	$root_menu);
		$menu 	= $this->tpl->load("_menu_order_ul", PATH_PAGES_TPL."content/");
		
		/**
		* All other submenus
		**/
		foreach($this->model->GetAllMenuEntries(0, False) as $menu_entry)
		{
			$root_menu = "";
			$this->tpl->vars("parent_id", $menu_entry['id']);
			foreach($this->model->GetAllMenuEntries($menu_entry['id'], False) as $submenu_entry)
			{
				$this->tpl->vars("page_menu_title",	$submenu_entry['menu_title']);
				$this->tpl->vars("page_id",			$submenu_entry['id']);
				$this->tpl->vars("in_menu",			intval($submenu_entry['in_nav']));
				$this->tpl->vars("page_name",		$submenu_entry['name']);
			
				$root_menu	.= $this->tpl->load("_menu_order_li", PATH_PAGES_TPL."content/");
			}
			$this->tpl->vars("menu", $root_menu);
			$menu 	.= $this->tpl->load("_menu_order_ul", PATH_PAGES_TPL."content/");
		}
		
		return $menu;
	}
}