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
		$email			= (isset($data['email'])) ? $data['email'] : "";
		$form_fields	= array(
				array("fieldset", "Registrieren", array(
						array("text", "Username", "username", $username, True),
						array("password", "Passwort", "pass", "", True),
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
				$user->CreateUser($data['username'], $data['pass'], $data['email']);
		
				$this->tpl->vars("message",		"Registierung war erfolgreich!");
				$content	= $this->tpl->load("_message_success");
			}
		}
		
		$this->tpl->vars("headline",	"User registieren");
		$this->tpl->vars("content",		$content);
		return $this->tpl->load("content");
	}
}