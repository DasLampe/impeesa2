<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once("../config/config.php");

include_once(dirname(__FILE__)."/simpletest/autorun.php");

//Tests
include_once(dirname(__FILE__)."/cases/user.test.php");
include_once(dirname(__FILE__)."/cases/pageAction.test.php");
include_once(dirname(__FILE__)."/cases/picture.test.php");

class AllTests extends TestSuite {
	function _construct() {
		parent::_construct();
		$this->addTest(new TestUser());
		$this->addTest(new TestPageAction());
		$this->addTest(new TestPicture());
	}
}

new AllTests();