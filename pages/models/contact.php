<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class ContactModel extends AbstractModel
{
	public function GetAllContactUser()
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user		= new impeesaUser($_SESSION);
		
		$contact_user		= array();
		foreach($user->GetAllUserIds() as $user_id)
		{
			$userinfo		= $user->GetUserInfo($user_id['id']);
			$contact_user[]	= array($user_id['id'], $userinfo['first_name']);
		}
		
		return $contact_user;
	}
	
	public function SendEmail($name, $phone, $email, $subject, $message, $user_id)
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user		= new impeesaUser($user_id);
		
		$userinfo	= $user->GetUserInfo($user_id);
		
		$header		= 'MIME-Version: 1.0' . "\r\n";
		$header		.= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header		.= 'From: '. impeesaConfig::get('adminEmail') . "\r\n";
		$header		.= 'Reply-To:' . $email . "\n";
		$header		.= "X-Mailer: php";
		
		$to			= $userinfo['email'];
		$mailsubject	= "[".impeesaConfig::get('unitname')."] Nachricht über Kontaktformular";
		$body		= "Hallo ".$userinfo['first_name'].",<br/>
						jemand hat dir eine Nachricht über das Kontaktformular geschickt.<br/><br/>
						Name: ".$name." <br/>
						Email: ".$email." <br/>
						Betreff: ".$subject."<br/>
						Nachricht:<br/>
						".nl2br(htmlspecialchars($message))."<br/>
						<br/><br/>
						Diese Nachricht wurde Automatisch erstellt, daher kann es sein das diese Nachricht Spam enthält,
						sollte dies der Fall sein, schreibe bitte eine kruze Email an das Webteam (".impeesaConfig::get('adminEmail').")";
		if(mail($to, $mailsubject, $body, $header))
		{
			return true;
		}
		return false;
	}
}