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
	
			//Sidebar
			include_once(PATH_CONTROLLER."sidebar.php");
			$sidebar				= new SidebarController($this->param);
			$tpl->vars("sidebar",	$sidebar->factoryController());

			//Content
			if(file_exists(PATH_CONTENT.$this->param[0].".php") && !file_exists(PATH_CONTROLLER.$this->param[0].".php"))
			{
				include_once(PATH_CONTROLLER."content.php");
				$page_controller	= new ContentController($this->param);
			}
			elseif(file_exists(PATH_CONTROLLER.$this->param[0].".php"))
			{
				include_once(PATH_CONTROLLER.$this->param[0].".php");
				$page_controller	= ucfirst($this->param[0]).'Controller';
				$page_controller	= new $page_controller($this->param);
			}
			else
			{
				throw new impeesaException("Error 404");
			}
			$page_content	= $page_controller->factoryController();
			$this->tpl->vars("page_content", $page_content);
		}
		catch(impeesaException $e)
		{
			$this->tpl->vars("page_content", $e->getCustomMessage());
		}
		
		/**
		 * Infobar
		 * @info: placed here, because you can set some buttons in the skript 
		 */
		include_once(PATH_CONTROLLER."infobar.php");
		$infobar				= new InfobarController($this->param);
		$tpl->vars("infobar",	$infobar->factoryController());
		
		
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
