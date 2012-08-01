<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class PictureModel extends AbstractModel
{
	public function encodeFilename(&$file)
	{
		$file_ext		= explode('.', $file['name']);
		$file['name']	= md5($file['name'].time()).'.'.$file_ext[count($file_ext)-1];
		return $file['name'];
	}
	
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

	public function GetMyPicture($dir, $picture_start=0, $picture_end=-1)
	{
		if(file_exists($dir))
		{
			$picture	= array();
			$handle		= opendir($dir);
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
}
