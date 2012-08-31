<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class ContactView extends AbstractView
{
	public function MainView()
	{
	}
	
	public function ContactFormView($data)
	{
		include_once(PATH_MODEL."contact.php");
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$this->model	= new ContactModel();
		$form			= new impeesaForm();
		
		$name		= (isset($data['name'])) ? $data['name'] : "";
		$phone		= (isset($data['phone'])) ? $data['phone'] : "";
		$email		= (isset($data['email'])) ? $data['email'] : "";
		$subject	= (isset($data['subject'])) ? $data['subject'] : "";
		$message	= (isset($data['message'])) ? $data['message'] : "";
		
		$form_fields	= array(
							array("fieldset", "Kontaktinformationen", array(
									array("text", "Name", "name", $name, True),
									array("text", "Telefon", "phone", $phone, False),
									array("email", "Email", "email", $email, True),
								),
							),
							array("fieldset", "Kontaktperson", array(
								array("select", "", "user", array(
										array("option", "Bitte auswählen", "", True, True),
										),
									),
								),
							),
							array("fieldset", "Anliegen", array(
									array("text", "Betreff", "subject", $subject, True),
									array("textarea", "Nachricht", "message", $message, True),
								),
							),
							array("fieldset", "", array(
									array("submit", "Kontaktieren", "submit"),
								),
							),
						);
		
		//Insert select options into list
		foreach($this->model->GetAllContactUser() as $contact_user)
		{
			//Shows a little bit crazy ;)
			$form_fields[1][2][0][3][]	= array("option", $contact_user[1], $contact_user[0]);
		}
		
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false)
		{
			return $form->GetForm($form_fields, CURRENT_PAGE);
		}
		else
		{
			if($this->model->SendEmail($name, $phone, $email, $subject, $message, $data['user']) == false)
			{
				$form->SetErrorMsg("Email konnte nicht gesendet werden.");
				return $form->GetForm($form_fields, CURRENT_PAGE);
			}
			return impeesaLayer::SetInfoMsg($_SESSION, "Email wurder erfolgreich versendet", CURRENT_PAGE);
		}
	}
}