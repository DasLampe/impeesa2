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
			$layer->AddButton('<a href="'.LINK_ACP.'/logout" class="ym-next">Abmelden</a>');
		}
		
		
		$buttons		= $layer->GetButtons();
		if(!empty($buttons))
		{
			foreach($buttons as $button)
			{
				$return[]	= $button;
			}
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
		$content	= array_reverse($content);
		if(isset($content) && !empty($content))
		{
			$return	= "";
			foreach($content as $button)
			{
				$this->tpl->vars("button",		$button);
				$return		.= $this->tpl->load("_button", PATH_PAGES_TPL."infobar/");
			}
			return $return;
		}
		return "";
	}
}