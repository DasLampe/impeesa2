<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class userManagementController extends AbstractController
{
	public function FactoryController()
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		include_once(PATH_VIEW."userManagement.php");
		$user		= new impeesaUser($_SESSION);
		$this->view	= new userManagementView();
		
		if($user->IsLogin() == false)
		{
			header("Location:".LINK_ACP."login");
			exit();
		}
		
		switch(@$this->param[2])
		{
			default:
				return $this->view->ProfileView($_POST, $_SESSION['user_id'], $user);
				break;
		}
	}
	
	public function AdminController()
	{
		include_once(PATH_VIEW."userManagement.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		
		$user		= new impeesaUser($_SESSION);
		$this->view	= new userManagementView();
		
		
		switch(@strtolower($this->param[2]))
		{
			case 'adduser':
				return $this->view->SignUpView($_POST, $user);
				break;
			case 'removeuser':
				return $this->view->RemoveUserView($this->param[3], $user);
				break;
			case 'edituser':
				return $this->view->ProfileView($_POST, $this->param[3], $user);
				break;
			default:
				return $this->view->AllUserView($user);
				break;
		}
	}
}