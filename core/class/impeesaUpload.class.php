<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaUpload {
	private $type;
	private $param;
	private $encodeFilename;
	
	/**
	* @param $type string type of file. (image, pdf, document, music)
	* @param $param array optional parameter. image		=> array("size"),
	*									pdf			=> array(),
	*									document	=> array(),
	*									music		=> array(),
	* @param $encodeFilename boolean Default: false. Set true if file named by md5 string.
	**/
	public function __construct($type, array $param = array(), $encodeFilename = false) {
		$this->type				= $type;
		$this->param			= $param;
		$this->encodeFilename	= $encodeFilename;
	}
	
	public function uploadFile($dir, &$file, $filename="") {
		if($this->encodeFilename == true) {
			$this->encodeFilename($file);
			$filename = ""; //clear $filename
		}
		
		$filename	= (empty($filename)) ? $file['name'] : $filename;
		
		$dir	= rtrim($dir, "/");
		if(!preg_match("!^".PATH_UPLOAD."!i", $dir)) {
			$dir = PATH_UPLOAD.$dir;
		}
			
		if(!file_exists($dir.'/'.$filename)) {
			switch($this->type) {
				case 'image':
					return $this->uploadImage($dir,$file,$filename);
					break;
				default:
					return $this->uploadMedia($dir,$file,$filename);
					break;
			}
		} else {
			throw new impeesaException($dir.$filename." already exists");
			return false;
		}
	}
	
	private function uploadImage($dir, $file, $filename) {
		$savePath	= $dir.'/'.$filename;
		
		if(isset($this->param['size'])) {
			if($this->resizePicture($file, $savePath, $this->param['size']) === false)
			{//if picture don't need to resize
				return $this->uploadMedia($dir,$file,$filename);
			}
			return true; //if resizePicture() == true
		}
		else {
			return $this->uploadMedia($dir,$file,$filename);
		}
	}
	
	private function uploadMedia($dir, $file, $filename) {
		$savePath	= $dir.'/'.$filename;
		
		if(move_uploaded_file($file['tmp_name'], $savePath)) {
			return true;
		}
		return false;
	}
	
	private function resizePicture($file, $savePath, $image_dimension) {
		$sizeInfo			= getimagesize($file['tmp_name']);
		$image_aspectratio	= $sizeInfo[0] / $sizeInfo[1];
		$scale_mode			= ($image_aspectratio > 1 ? -1 : -2);

		if ($scale_mode == -1)
		{
			$newWidth = $image_dimension;
			$newHeight = round($image_dimension / $image_aspectratio);
		}
		elseif ($scale_mode == -2)
		{
			$newWidth	= round($image_dimension * $image_aspectratio);
			$newHeight	= $image_dimension;
		}
		else
		{//nothing todo
			return false;
		}

		//Resize
		$oldPic			= ImageCreateFromJPEG($file['tmp_name']);
		$newPic			= imagecreatetruecolor($newWidth,$newHeight);
		imagecopyresampled($newPic,$oldPic,0,0,0,0,$newWidth,$newHeight,$sizeInfo[0],$sizeInfo[1]);
		ImageJPEG($newPic, $savePath);
		return true;
	}
	
	private function encodeFilename(&$file)
	{
		$file_ext		= explode('.', $file['name']);
		$file['name']	= uniqid().'.'.$file_ext[count($file_ext)-1];
		return $file['name'];
	}
}