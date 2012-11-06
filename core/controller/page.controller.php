<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class pageController
{
	private $param;

	function __construct($param)
	{
		$this->param	= $param;
		$this->tpl		= impeesaTemplate::getInstance();
	}

	function render()
	{
		try
		{
			$tpl				= $this->tpl;
			$tpl->vars("CURRENT_PAGE",	CURRENT_PAGE);
			$tpl->vars("LINK_ACP",		LINK_ACP);
			$tpl->vars("CONFIG_admin_email",	impeesaConfig::get("adminEmail"));
	
			//Sidebar
			include_once(PATH_CONTROLLER."sidebar.php");
			$sidebar				= new SidebarController($this->param);
			$tpl->vars("sidebar",	$sidebar->factoryController());

			try
			{
				if(file_exists(PATH_CONTROLLER.$this->param[0].".php"))
				{
					include_once(PATH_CONTROLLER.$this->param[0].".php");
					$page_controller	= ucfirst($this->param[0]).'Controller';
					$page_controller	= new $page_controller($this->param);
				}
				else
				{
					include_once(PATH_CONTROLLER."content.php");
					$page_controller	= new ContentController($this->param);
				}

				$page_content	= $page_controller->factoryController();
				$this->tpl->vars("page_content", $page_content);
			}
			catch(impeesa404Exception $e)
			{
				$page_content	= $e->getCustomMessage();
				$this->tpl->vars("page_content", $page_content);
			}
		}
		catch(impeesaException $e)
		{
			$page_content	= $e->getCustomMessage();
			$this->tpl->vars("page_content", $page_content);
		}
		
		/**
		 * Infobar
		 * @info: placed here, because you can set some buttons in the script 
		 */
		include_once(PATH_CONTROLLER."infobar.php");
		$infobar				= new InfobarController($this->param);
		$tpl->vars("infobar",	$infobar->factoryController());
		
		//Info message
		/**
		 * Messages in Infobar
		 */
		include_once(PATH_CORE_CLASS."impeesaLayer.class.php");
		$layer	= impeesaLayer::getInstance();
		$this->tpl->vars("info_msg",			""); //init info_msg
		$msg			= $layer->GetInfoMsg($_SESSION);
		if(!empty($msg['msg']))
		{
			$this->tpl->vars("message",			$msg['msg']);
			$this->tpl->vars("info_status",		$msg['status']);
			$this->tpl->vars("info_msg",		$this->tpl->load("_infoMsg"));
		}
		
		
		/**
		 * Handle JSON
		 */
		if(preg_match('/^\{".*".?:/', $page_content))
		{
			header('Content-type: text/json');
			echo $page_content;
			exit();
		}
		
		/**
		 * Add own js and css files
		 */
		$this->tpl->vars("js_files",		$this->tpl->getJs());
		$this->tpl->vars("css_files",		$this->tpl->getCss());
		
		echo impeesaPostProccess::protectEmail($this->tpl->load("layout"));
	}
}
