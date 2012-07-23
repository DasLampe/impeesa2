<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class InfobarView extends AbstractView {
	public function MainView()
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		$user	= new impeesaUser($_SESSION);
		
		$return	= "";
		if($user->IsLogin() == true)
		{
			$this->tpl->vars("username",		$user->GetUsernameById($_SESSION['user_id']));
			$return .= $this->tpl->load("_loginInfo", PATH_PAGES_TPL."infobar/");
		}
		
		return $return;
	}
}