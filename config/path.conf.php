<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2011 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
$dir	= explode("config", dirname(__FILE__));

define("PATH_MAIN",				$dir[0]);

define("PATH_CORE",				PATH_MAIN."core/");
define("PATH_CORE_LIB",			PATH_CORE."lib/");
define("PATH_CORE_CONTROLLER",	PATH_CORE."controller/");
define("PATH_CORE_CLASS",		PATH_CORE."class/");

define("PATH_TPL",				PATH_MAIN."template/");
define("PATH_LIB",				PATH_MAIN."lib/");

define("PATH_PAGES",			PATH_MAIN."pages/");
define("PATH_CONTROLLER",		PATH_PAGES."controller/");
define("PATH_VIEW",				PATH_PAGES."views/");
define("PATH_MODEL",			PATH_PAGES."models/");
define("PATH_PAGES_TPL",		PATH_PAGES."template/");

define("PATH_CONTENT",			PATH_PAGES."content/");
define("PATH_UPLOAD",			PATH_MAIN."uploads/");

define("LINK_MAIN",				"http://localhost/impeesa2/");
define("LINK_TPL",				LINK_MAIN."template/");
define("LINK_LIB",				LINK_MAIN."lib/");
define("LINK_UPLOAD",			LINK_MAIN."uploads/");
define("LINK_ACP",				LINK_MAIN."admin/");

define("CURRENT_PAGE",			LINK_MAIN.substr($_SERVER['QUERY_STRING'], 6));
