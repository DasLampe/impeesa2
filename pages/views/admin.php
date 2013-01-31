<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class AdminView extends AbstractView {
	public function MainView()
	{
	}
	
	public function ConfigView($data)
	{
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form			= new impeesaForm();
		
		$keys			= array("unitname", "adminEmail", "scoutNetId");
		
		$form_fields	= array(
							array("fieldset", "Konfiguration", array(
									array("static", impeesaConfig::getDescription("version"), "version", impeesaConfig::get("version")),
								),
							),
							array("submit", "Ändern", "submit"),
						);
		foreach($keys as $key)
		{
			$form_fields[0][2][]	= array("text", impeesaConfig::getDescription($key), $key, impeesaConfig::get($key), True);
		}
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		else
		{
			unset($data['submit']); //remove submit
			foreach($data as $key=>$value)
			{
				impeesaConfig::set($key, $value);
			}
			return impeesaLayer::SetInfoMsg($_SESSION, "Konfiguration wurder erfolgreich geändert", CURRENT_PAGE);
		}
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
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user		= new impeesaUser($_SESSION);
		if($user->isLogin() == "")
		{
			return "";
		}
		$menu_array	= array(
				array(
					"page_url"		=> LINK_ACP."userManagement/allUser",
					"page_title"	=> "Benutzer verwalten",
				),
				array(
					"page_url"		=> LINK_ACP."news",
					"page_title"	=> "Neuigkeiten verwalten",
				),
				array(
					"page_url"		=> LINK_ACP."content",
					"page_title"	=> "Seiten verwalten",
				),
				array(
					"page_url"		=> LINK_ACP."groups",
					"page_title"	=> "Gruppen verwalten",
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