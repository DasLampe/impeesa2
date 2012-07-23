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
				$this->tpl->vars("message",		"Erfolgreich eingeloggt");
				return $this->tpl->load("_message_success");
			}
			else
			{
				$form->SetErrorMsg("Benutzername oder Passwort sind falsch!");
				return $form->GetForm($form_fields, LINK_MAIN."/admin/login");
			}
		}
	}
	
	public function SignUpView($data, $user)
	{
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form		= new impeesaForm();
		
		$username		= (isset($data['username'])) ? $data['username'] : "";
		$email			= (isset($data['email'])) ? $data['email'] : "";
		$form_fields	= array(
								array("fieldset", "", array(
									array("text", "Username", "username", $username, True),
									array("password", "Passwort", "pass", "", True),
									array("email", "Email", "email", $email, True),
									),
								),
								array("fieldset", "", array(
									array("submit", "Registrieren", "submit"),
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
				$user->CreateUser($data['username'], $data['pass'], $data['email']);
				
				$this->tpl->vars("message",		"Registierung war erfolgreich!");
				$content	= $this->tpl->load("_message_success");
			}
		}
		
		$this->tpl->vars("headline",	"Registieren");
		$this->tpl->vars("content",		$content);
		return $this->tpl->load("content");
	}
	
	public function SidebarView()
	{
		return "<h5>Intern</h5>";
	}
}