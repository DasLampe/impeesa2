<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class TestPicture extends UnitTestCase {
	private $model;
	private $album_name;
	
	public function __construct() {
		include_once(PATH_MODEL."picture.php");
		$this->model		= new PictureModel();
		$this->album_name	= "0000_UnitTestAlbum";
		$this->filename		= "pictureTest.jpg";
	}
	
	public function testCreateAlbum() {
		$this->assertTrue($this->model->CreateAlbum($this->album_name));
		//realy create?
		$this->assertTrue(file_exists(PATH_UPLOAD."picture/".$this->album_name));
	}
	
	public function testUploadPicture() {
		//Fake $_FILE
		$array = array(
					'name'		=> $this->filename,
					'type'		=> "image/jpeg",
					'tmp_name'	=> dirname(__FILE__)."/share/".$this->filename,
					'error'		=> 0,
					'size'		=> filesize(dirname(__FILE__)."/share/".$this->filename),
				);
		$this->assertTrue($this->model->UploadPicture($array, PATH_UPLOAD."picture/".$this->album_name));
		//Realy uploaded?
		$this->assertTrue(file_exists(PATH_UPLOAD."picture/".$this->album_name."/".$this->filename));
	}
	
	public function testGetAlbumPicture() {
		$album	= $this->model->GetAlbumPicture($this->album_name);

		$this->assertFalse(empty($album));
	}
	
	public function testRemovePicture() {
		$this->assertTrue($this->model->DeletePicture(PATH_UPLOAD."picture/".$this->album_name, $this->filename));
		
		$this->assertFalse(file_exists(PATH_UPLOAD."picture/".$this->album_name."/".$this->filename));
	}
	
	public function testRemoveAlbum() {
		$this->assertTrue($this->model->RemoveAlbum($this->album_name));
		//realy remove?
		$this->assertFalse(file_exists(PATH_UPLOAD."picture/".$this->album_name));
	}
}
?>