<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class userManagementController extends AbstractController
{
	public function FactoryController()
	{
		/**
		 * DON'T USE!!
		 */
	}
	
	public function AdminController()
	{
		include_once(PATH_VIEW."userManagement.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		
		$user		= new impeesaUser($_SESSION);
		$this->view	= new userManagementView();
		
		
		switch(@$this->param[2])
		{
			case 'signup':
				return $this->view->SignUpView($_POST, $user);
				break;
		}
	}
}