<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == "xmlhttprequest")
{
	define("IS_AJAX", true);
}
else
{
	define("IS_AJAX", false);
}


//Classes
include_once(PATH_CORE_CLASS."impeesaDB.class.php");
include_once(PATH_CORE_CLASS."impeesaConfig.class.php");
include_once(PATH_CORE_CLASS."impeesaTemplate.class.php");
include_once(PATH_CORE_CLASS."impeesaPostProccess.class.php");
include_once(PATH_CORE_CLASS."impeesaException.class.php");
include_once(PATH_CORE_CLASS."impeesaLayer.class.php");

//Lib
include_once(PATH_CORE_LIB."DasLampe-alternate/alternate.inc.php");

//Abstract Controller
include_once(PATH_CORE."abstract/controller.php");
include_once(PATH_CORE."abstract/view.php");
include_once(PATH_CORE."abstract/model.php");

//Controller
include_once(PATH_CORE_CONTROLLER."page.controller.php");
