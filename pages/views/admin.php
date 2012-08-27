<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class AdminView extends AbstractView {
	public function MainView()
	{
		$this->tpl->vars("content",		'<h1>Interner Bereich</h1>');
		return $this->tpl->load("_content");
	}
	
	public function LoginView($data, $user)
	{
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form			= new impeesaForm();
		
		$username		= (isset($data['username'])) ? $data['username'] : "";
		$form_fields	= array(
								array("fieldset", "Login", array(
									array("text", "Username", "username", $username, True),
									array("password", "Passwort", "pass", "", True)
									),
								),
								array("fieldset", "", array(
									array("submit", "Login", "submit"),
									),
								),
							);
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == False)
		{
			return $form->GetForm($form_fields, LINK_MAIN."/admin/login");
		}
		else
		{
			if($user->SetUserIdByUsername($data['username']) === true && $user->GetPassword() == $user->GetNewPasswordHash($data['pass'], $data['username']))
			{
				$user->SetLogin();
				return impeesaLayer::SetInfoMsg($_SESSION, "Erfolgreich eingeloggt", LINK_MAIN."admin/home/");
			}
			else
			{
				$form->SetErrorMsg("Benutzername oder Passwort sind falsch!");
				return $form->GetForm($form_fields, LINK_MAIN."/admin/login");
			}
		}
	}
	
	public function LogoutView($user)
	{
		if($user->SetLogout())
		{
			return impeesaLayer::SetinfoMsg($_SESSION, "Erfolgreich ausgeloggt", LINK_MAIN);
		}
		
		return impeesaLayer::SetInfoMsg($_SESSION, "Logout fehlgeschlagen!", LINK_MAIN."admin/home/", "error");
	}
	
	public function SidebarView()
	{
		$menu_array	= array(
				array(
					"page_url"		=> LINK_MAIN."admin/userManagement/signup",
					"page_title"	=> "User hinzufügen",
				),
				array(
					"page_url"		=> LINK_MAIN."admin/news",
					"page_title"	=> "Neuigkeiten verwalten",
				),
		);
		
		$menu_items	= "";
		foreach($menu_array	as $menu_item)
		{
			$this->tpl->vars("page_url",		$menu_item['page_url']);
			$this->tpl->vars("page_title",		$menu_item['page_title']);
			$menu_items		.= $this->tpl->load("_nav_li");
		}
		
		$this->tpl->vars("submenu_items",		$menu_items);
		return $this->tpl->load("submenu", PATH_PAGES_TPL."sidebar/");
	}
	
	public function InfobarView()
	{
		/**
		 * @TODO: Add nice actions
		 */
		return "";
	}
}