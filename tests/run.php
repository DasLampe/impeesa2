<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once("../config/config.php");

include_once(dirname(__FILE__)."/simpletest/autorun.php");

//Tests
include_once(dirname(__FILE__)."/cases/user.test.php");

class AllTests extends TestSuite {
	function _construct() {
		parent::_construct();
		$this->addTest(new TestUser());
	}
}

new AllTests();