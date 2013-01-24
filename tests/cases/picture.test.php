<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class TestPicture extends UnitTestCases {
	private $model;
	private $album_name;
	
	public function __construct() {
		include_once(PATH_MODEL."picture.php");
		$this->model		= new PictureModel();
		$this->album_name	= "0000_UnitTestAlbum";
	}
	
	public function testCreateAlbum() {
		$this->assertTrue($this->model->CreateAlbum($this->album_name));
		//realy create?
		$this->assertTrue(file_exists(PATH_UPLOAD."picture/".$this->album_name));
	}
	
	public function testUploadPicture() {
		/**
		* FIND A WAY
		**/
	}
	
	public function testGetAlbumPicture() {
		//Needed Upload Picture
	}
	
	public function testRemoveAlbum() {
		$this->assertTrue($this->model->RemoveAlbum($this->album_name));
		//realy remove?
		$this->assertFalse(file_exists(PATH_UPLOAD."picture/".$this->album_name));
	}
}
?>