<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class AdminController extends AbstractController {
	public function FactoryController()
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		include_once(PATH_VIEW."admin.php");
		
		$user		= new impeesaUser($_SESSION);
		$this->view	= new AdminView();
		
		/**
		 * User isn't login now
		 */
		if($user->isLogin() === false)
		{
			return $this->view->LoginView($_POST, $user);
		}
		elseif(!isset($this->param[1]))
		{
			return $this->view->ConfigView($_POST);
		}		
		elseif($this->param[1] == "login")
		{ //Already login, but go to login page
			header("Location: ".LINK_MAIN."admin/home/");
		}
		
		switch(strtolower($this->param[1]))
		{
			case 'logout':
				return $this->view->LogoutView($user);
				break;
			case 'usermanagement':
				$site	= "userManagement";
				break;
			case 'news':
				$site	= "news";
				break;
			case 'picture':
				$site	= 'picture';
				break;
			default:
				return $this->view->ConfigView($_POST);
				break;
		}
		
		if(isset($site))
		{
			if(file_exists(PATH_CONTROLLER.$site.".php"))
			{
				include_once(PATH_CONTROLLER.$site.".php");
			}
			else
			{
				throw new impeesaException("Unknown site. File not found!");
			}
				
			$page_class	= ucfirst($site)."Controller";
			$page_class	= new $page_class($this->param);
				
			if(method_exists($page_class, "AdminController"))
			{
				return $page_class->AdminController();
			}
			else
			{
				throw new impeesaException("No AdminController method");
			}
		}
		else
		{
			include_once(PATH_CONTROLLER."content.php");
			$page_class	= new ContentController($this->param);
			return $page_class->AdminController();
		}
	}
}