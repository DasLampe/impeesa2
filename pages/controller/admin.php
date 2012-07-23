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
		elseif($this->param[1] == "login")
		{ //Already login, but go to login page
			header("Location: ".LINK_MAIN."admin/home/");
		}
		
		switch($this->param[1])
		{
			default:
				return $this->view->MainView();
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
				
			$page_class	= new PictureController($this->param);
				
			if(method_exists($page_class, "AdminController"))
			{
				return $page_class->AdminController();
			}
			else
			{
				throw new impeesaException("No AdminController method");
			}
		}
	}
}