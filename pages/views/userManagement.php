<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class userManagementView extends AbstractView
{
	public function MainView()
	{
		return "MAIN";
	}
	
	public function SignUpView($data, $user)
	{
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form		= new impeesaForm();
		
		$username		= (isset($data['username'])) ? $data['username'] : "";
		$first_name		= (isset($data['first_name'])) ? $data['first_name'] : "";
		$name			= (isset($data['name'])) ? $data['name'] : "";
		$email			= (isset($data['email'])) ? $data['email'] : "";
		$form_fields	= array(
				array("fieldset", "Login Informationen", array(
						array("text", "Username", "username", $username, True),
						array("password", "Passwort", "pass", "", True),
					),
				),
				array("fieldset", "Allgemeine Informationen", array(
						array("text", "Vorname", "first_name", $first_name, True),
						array("text", "Nachname", "name", $name, True),
						array("email", "Email", "email", $email, True),
					),
				),
				array("fieldset", "", array(
						array("submit", "Login", "submit"),
					),
				),
			);
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			$content	= $form->GetForm($form_fields, CURRENT_PAGE);
		}
		else
		{
			if($user->ExistsUsername($data['username']) === true)
			{
				$form->SetErrorMsg("Benutzername ist schon vergeben!");
				$content	= $form->GetForm($form_fields, CURRENT_PAGE);
			}
			else
			{
				$user->CreateUser($data['username'], $data['pass'], $data['first_name'], $data['name'], $data['email']);
		
				return impeesaLayer::SetInfoMsg($_SESSION, "Registierung erfolgreich", LINK_MAIN);
			}
		}
		
		$this->tpl->vars("headline",	"User registieren");
		$this->tpl->vars("content",		$content);
		return $this->tpl->load("content");
	}
	
	public function ProfileView($data, $user_id, $user)
	{
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form		= new impeesaForm();
		
		$userinfo	= $user->GetUserInfo($user_id);
		
		$firstname	= (!isset($data['first_name'])) ? $userinfo['first_name'] : $data['first_name'];
		$name		= (!isset($data['name'])) ? $userinfo['name'] : $data['name'];
		$email		= (!isset($data['email'])) ? $userinfo['email'] : $data['email'];
		
		$form_fields	= array(
							array("fieldset", "Aktuelles Profil", array(
									array("static", "Username", "username", $user->GetUsernameById($user_id)),
									array("text", "Vorname", "first_name", $firstname, True),
									array("text", "Nachname", "name", $name, True),
									array("email", "Email", "email", $email, True),
									),
								),
							array("fieldset", "Passwort ändern", array(
									array("password", "Neues Passwort", "pass1"),
									array("password", "Passwort wiederholen", "pass2"),
								),
							),
							array("fieldset", "", array(
									array("submit", "Ändern", "submit"),
								),
							),
						);
		
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			$content	= $form->GetForm($form_fields, CURRENT_PAGE);
		}
		else
		{
			if((!empty($data['pass1']) xor !empty($data['pass2'])) || $data['pass2'] != $data['pass1'] ||
				$user->SaveNewPassword($user->GetUserId(), $data['pass1']) == false)
			{
				$form->SetErrorMsg("Passwörter verschieden!");
				$content	= $form->GetForm($form_fields, CURRENT_PAGE);
			}
			elseif($user->SaveUserData($user->GetUserId(), $data['first_name'], $data['name'], $data['email']) == true)
			{
				return impeesaLayer::SetInfoMsg($_SESSION, "Änderungen erfolgreich gespeichert", CURRENT_PAGE);
			}
			else
			{
				$form->SetErrorMsg("Daten konnten nicht gespeichert werden");
				$content	= $form->GetForm($form_fields, CURRENT_PAGE);
			}
		}
		
		$this->tpl->vars("headline",	"Profil bearbeiten");
		$this->tpl->vars("content",		$content);
		return $this->tpl->load("content");
	}
	
	public function AllUserView($user)
	{
		include_once(PATH_CORE_CLASS."impeesaLayer.class.php");
		$layer	= impeesaLayer::getInstance();
		
		$layer->AddButton('<a href="'.LINK_ACP.'userManagement/addUser" class="ym-add"> Benutzer hinzufügen</a>');
		$return	="";
		foreach($user->GetAllUserIds() as $user_id)
		{
			$userinfo	= $user->GetUserInfo($user_id['id']);
			
			$this->tpl->vars("user_id",		$user_id['id']);
			$this->tpl->vars("username",	$userinfo['username']);
			$this->tpl->vars("first_name",	$userinfo['first_name']);
			$this->tpl->vars("name",		$userinfo['name']);
			$this->tpl->vars("email",		$userinfo['email']);
			
			$return .= $this->tpl->load("_overview", PATH_PAGES_TPL."userManagement/");
		}
		
		$this->tpl->vars("headline",		"Benutzer Verwaltung");
		$this->tpl->vars("users",			$return);
		return $this->tpl->load("overview", PATH_PAGES_TPL."userManagement/");
	}
	
	public function RemoveUserView($user_id, $user)
	{
		if($user->RemoveUser($user_id) == false)
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Benutzer konnte nicht gelöscht werden", LINK_ACP."userManagement/allUser", "error");
		}
		else
		{
			return impeesaLayer::SetInfoMsg($_SESSION, "Benutzer wurde erfolgreich gelöscht", LINK_ACP."userManagement/allUser");
		}
	}
}