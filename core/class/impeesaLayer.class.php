<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaLayer
{
	private static $instance = NULL;
	private $buttons = array();
	
	public static function getInstance()
	{
		if(null === self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function AddButton($button_content, $align="left")
	{
		if(!empty($button_content))
		{
			$this->buttons[]	= array($button_content, $align);
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an empty arrary or an array, which contains all added buttons ..
	 * @return array
	 */
	public function GetButtons()
	{
		return $this->buttons;
	}
	
	public static function SetInfoMsg(&$session, $msg, $redirect, $status="success")
	{
		if(IS_AJAX == true)
		{
			return json_encode(array(
									"msg"		=> $msg,
									"status"	=> $status,
									));
		}
		else
		{
			$_SESSION['info_msg']		= $msg;
			$_SESSION['info_status']	= $status;
			
			header("Location: ".$redirect);
		}
		return true;
	}
	
	public function GetInfoMsg(&$session)
	{
		if(isset($session['info_msg']))
		{
			$return	= array(
							"msg"		=> $session['info_msg'],
							"status"	=> $session['info_status'],
							);
			
			//Clear Session
			unset($session['info_msg']);
			unset($session['info_status']);
			
			return $return;
		}
		return array("msg" => "", "status" => "");
	}
}