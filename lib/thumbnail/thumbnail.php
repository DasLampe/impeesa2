<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2010 DasLampe <dasLampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
include_once("../../config/config.php");
$file = PATH_UPLOAD.$_GET['dir'].'/'.$_GET['picture'];

$width = (!isset($_GET['width'])) ? 250 : $_GET['width'];
$height = (!isset($_GET['height'])) ? 200 : $_GET['height'];

resize_picture($file, $width, $height);
function resize_picture($file, $width, $height)
{
	if(!file_exists($file))
	{
		return false;
	}

	try
	{
		header('Content-type: image/jpeg');
		$info = getimagesize($file);
		if($info[2] == 1)
		{
			$image = imagecreatefromgif($file);
		}
		elseif($info[2] == 2)
		{
			$image = imagecreatefromjpeg($file);
		}
		elseif($info[2] == 3)
		{
			$image = imagecreatefrompng($file);
		}
		else
		{
			return false;
		}
			
		if ($width && ($info[0] < $info[1]))
		{
			$width = ($height / $info[1]) * $info[0];
		}
		else
		{
			$height = ($width / $info[0]) * $info[1];
		}
			
		$imagetc = imagecreatetruecolor($width, $height);
			
		imagecopyresampled($imagetc, $image, 0, 0, 0, 0, $width, $height,
				$info[0], $info[1]);
			
		imagejpeg($imagetc, null, 100);
	}
	catch (Exception $e)
	{
		echo "Fail: ".$e->getMessage();
	}
}
