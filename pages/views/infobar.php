<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class InfobarView extends AbstractView {
	public function MainView()
	{
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		include_once(PATH_CORE_CLASS."impeesaLayer.class.php");
		$user	= new impeesaUser($_SESSION);
		$layer	= impeesaLayer::getInstance();
		
		$return	= array();
		if($user->IsLogin() == true)
		{
			$layer->AddButton('<a href="'.LINK_MAIN.'/userManagement/profile" class="ym-edit">Profil</a>');
			
			$this->tpl->vars("username",		$user->GetUsernameById($_SESSION['user_id']));
			$return[]	= array($this->tpl->load("_loginInfo", PATH_PAGES_TPL."infobar/"));
		}
		
		
		$buttons		= $layer->GetButtons();
		if(!empty($buttons))
		{
				$this->tpl->vars("buttons",		$this->CreateGrid($buttons));
				$return[] 	= array($this->tpl->load("_buttons", PATH_PAGES_TPL."infobar/"), "right");
		}

		return $this->CreateGrid($return);
	}
	
	/**
	 * Create grids from array.
	 * @param array (2) $content
	 * array can have a second value which define the align of the box
	 */
	private function CreateGrid(array $content)
	{
		if(isset($content[0]))
		{
			$this->tpl->vars("grid_width",		100 / count($content));
			$return	= "";
			foreach($content as $grid)
			{
				$this->tpl->vars("content",		$grid[0]);
				switch(@$grid[1])
				{
					case "right":
						$this->tpl->vars("grid_align",	"r");
						break;
					case "left":
					default:
						$this->tpl->vars("grid_align",	"l");
						break;
				}
				$return		.= $this->tpl->load("_grid");
			}
			return $return;
		}
		return "";
	}
}