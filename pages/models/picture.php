<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class PictureModel extends AbstractModel
{	
	public function UploadPicture(array $file, $dir)
	{
		include_once(PATH_CORE_CLASS."impeesaUpload.class.php");
		$upload			= new impeesaUpload("image", array("size" => 640), true);
		$upload->uploadFile($dir, $file);
	}

	public function GetAlbumPicture($dir, $picture_start=0, $picture_end=-1)
	{
		if(file_exists(PATH_UPLOAD.'picture/'.$dir))
		{
			$picture	= array();
			$handle		= opendir(PATH_UPLOAD.'picture/'.$dir);
			if($handle === false) {
				throw new impeesaException("Can't open ".PATH_UPLOAD."picture/");
			}
			
			while(false !== ($file	= readdir($handle)))
			{
				if(preg_match("/.(jpg|jpeg)$/i",$file))
				{
					$picture[]	= $file;
				}
			}

			sort($picture);

			//Cut not needed picture
			if($picture_start != 0)
			{
				array_splice($picture, 0, $picture_start);
			}

			if($picture_end != -1)
			{
				array_splice($picture, $picture_end-$picture_start);
			}

			return $picture;
		}
		return array();
	}

	public function DeletePicture($dir, $filename)
	{
		if(file_exists($dir.'/'.$filename))
		{
			if(unlink($dir.'/'.$filename) == false)
			{
				return false;
			}
			return true;
		}
		return false;
	}

	public function CountPicture($dir)
	{
        $picture	= $this->GetAlbumPicture($dir);
        if(!is_array($picture))
        {
            return 0;
        }
        return count($picture);
	}
	
	public function GetAlbums()
	{
		$year		= '';
		$handle		= opendir(PATH_UPLOAD.'picture/');
		while(false !== ($dir	= readdir($handle)))
		{
			if(preg_match('/[0-9]{4}_(.*)/', $dir))
			{
				if($year != substr($dir, 0,4))
				{
					$year	= substr($dir,0,4);
				}
	
				$album[$year][]		= $dir;
			}
		}
	
		krsort($album);
	
		return $album;
	}
	
	public function ConvertAlbumName($album_name, $to="text")
	{
		$info				= array();

		$system				= array('_','oe','ae','ue','Oe','Ae','Ue',);
		$user				= array(' ','ö','ä','ü','Ö','Ä','Ü',);
	
		$info['year']		= substr($album_name,0,4);
		$info['headline']	= ($to == "text") ? str_replace($system, $user, substr($album_name,5)) : "_".str_replace($user,$system, substr($album_name,5)); //Hack: Add "_", else wrong name format
	
		return $info;
	}
	
	public function CreateAlbum($album_name)
	{
		if(!file_exists(PATH_UPLOAD."picture/".$album_name))
		{
			if(mkdir(PATH_UPLOAD."picture/".$album_name) == false)
			{
				throw new impeesaException("Can't create picture album! Permission denied?");
			}
			return true;
		}
		return false;
	}
	
	public function RemoveAlbum($album_name) {
		if(file_exists(PATH_UPLOAD."picture/".$album_name)) {
			if($this->RemoveAlbumRecursive(PATH_UPLOAD."picture/".$album_name)) {
				return true;
			}
		}
		return false;
	}
	
	private function RemoveAlbumRecursive($filename) {
		if(is_dir($filename)) {
			$dir		= dir($filename);
			while(false !== ($file	= $dir->read())) {
				if($file != "." && $file != "..") {
					//Delte all files. Throw exception if can't delte any file
					if($this->RemoveAlbumRecursive($dir->path."/".$file) == false) {
						throw new impeesaException("Can't delete ".$dir->path."/".$file);
					}
				}
			}

			//Delete dir
			if(rmdir($filename) == true) {
				return true;
			}
			return false;
		}
		else
		{
			if(unlink($filename) == false) {
				return false;
			}
			return true;
		}
		throw new impeesaException("WTF? File isn't a dir or file. Something crazy!");
	}
}
