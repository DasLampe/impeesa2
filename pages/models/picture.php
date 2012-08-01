<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class PictureModel extends AbstractModel
{
	public function UploadPicture(array $file, $dir)
	{
		if(!file_exists($dir))
		{
			mkdir($dir);
		}

		$savePath	= $dir.'/'.$file['name'];
		if(!file_exists($savePath))
		{
			if($this->resize_picture4save($file, $savePath, 1024) === false)
			{//if picture don't need to resize
				if(move_uploaded_file($file['tmp_name'], $savePath))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			return true;
		}
		return false;
	}

	public function GetAlbumPicture($dir, $picture_start=0, $picture_end=-1)
	{
		if(file_exists(PATH_UPLOAD.'picture/'.$dir))
		{
			$picture	= array();
			$handle		= opendir(PATH_UPLOAD.'picture/'.$dir);
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
		if(file_exists(PATH_UPLOAD.$dir.'/'.$filename))
		{
			if(unlink(PATH_UPLOAD.$dir.'/'.$filename) == false)
			{
				return false;
			}
			return true;
		}
		return false;
	}

	private function resize_picture4save($file, $savePath, $image_dimension)
	{
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

	public function CountPicture($dir)
	{
        $picture    = $this->GetMyPicture($dir);
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
	
	public function DecodeAlbumName($album_name)
	{
		$info				= array();
	
		$system				= array('_','oe','ae','ue','Oe','Ae','Ue',);
		$user				= array(' ','ö','ä','ü','Ö','Ä','Ü',);
	
		$info['headline']	= str_replace($system, $user, substr($album_name,5));
		$info['year']		= substr($album_name,0,4);
	
		return $info;
	}
	
}
