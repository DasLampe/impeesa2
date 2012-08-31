<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class ContactController extends AbstractController
{
	public function FactoryController()
	{
		include_once(PATH_VIEW."contact.php");
		$this->view		= new ContactView();
		
		return $this->view->ContactFormView($_POST);
	}
}