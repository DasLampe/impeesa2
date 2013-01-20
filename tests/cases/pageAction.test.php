<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once(PATH_MODEL."content.php");

class TestPageActions extends UnitTestCase {
	private $pageModel;
	private $sitename;
	
	public function __construct() {
		$this->pageModel	= new ContentModel();
		$this->sitename		= "testpage";
	}
	
	public function testCreatePage() {
		$this->assertTrue($this->pageModel->CreatePage($this->sitename, $this->sitename, $this->sitename, True));
		//Check database
		$this->assertTrue($this->pageModel->ExistsPage($this->sitename));
	}
	
	public function testDeletePage() {
		$this->assertTrue($this->pageModel->DeletePage($this->sitename));
		//Check database
		$this->assertFalse($this->pageModel->ExistsPage($this->sitename));
	}
}
?>