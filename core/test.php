<?php

include 'image.class.php';


$imgs=new image();


$imgs->img='1.jpg';

echo $imgs->thumb('sss.jpg',900,900,1);

//resize('1.jpg','thumb2.jpg',300,300);
function resize($src,$target,$width,$height,$rate=100,$markwords=null,$markimage=null)
{
	$img_create = function_exists('imagecreatetruecolor') ? 'imagecreatetruecolor' : 'imagecreate';
    $img_copy = function_exists('imagecopyresampled') ? 'imagecopyresampled' : 'imagecopyresized';
	if(function_exists('imagejpeg'))
	{
		$data = getimagesize($src);
		$srcW = $data[0];
        $srcH = $data[1];

		$im=create($src,$data[2]);


		if ($srcW / $srcH > $width / $height)
		{
			$fdstH = round($srcH*$width/$srcW);
			$dstY = floor(($height-$fdstH)/2);
			$fdstW = $width;
		}
		else
		{
			$fdstW = round($srcW*$height/$srcH);
			$dstX = floor(($width-$fdstW)/2);
			$fdstH = $height;
		}

		$dstX=$dstY=0;
		$ni=$img_create($fdstW,$fdstH);//建立一幅图像
		//$ni=$img_create($width,$height);
		$white = imagecolorallocate($ni,255,255,255);
		$black = imagecolorallocate($ni,0,0,0);
		//imagefilledrectangle($ni,0,0,$width,$height,$white);// 填充背景色
		// 填充背景色2
		if ($img_create == 'imagecreatetruecolor')
        {
            imagefill($ni, 0, 0, imagecolorallocate($ni, 255, 255, 255));
        }
        else
        {
            imagecolorallocate($ni, 255, 255, 255);
        }

		$img_copy($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH);

		if($markwords!=null)
		{
			//imagestring($ni, 5, 0, 0, $markwords, $black); //英文可以不要字体
			imagettftext($ni,20,30,100,100,$black,ROOT."/include/font/simhei.ttf",$markwords); //写入文字水印
			//参数依次为，文字大小|偏转度|横坐标|纵坐标|文字颜色|文字类型|文字内容
		}
		elseif($markimage!=null)
		{
			$wimage_data = getimagesize($markimage);
			$wimage=create($markimage,$wimage_data[2]);
			$img_copy($ni,$wimage,500,560,0,0,88,31); //写入图片水印,水印图片大小默认为88*31
			imagedestroy($wimage);
		}
		imagejpeg($ni,$target,$rate);
		imagedestroy($im);
		imagedestroy($ni);
		return is_file($target) ? $target : false;
	}
	else
	{
		trigger_error("Unable to process picture.", E_USER_ERROR);
	}
}
function create($src,$imagetype)
{
	switch($imagetype)
	{
		case 1 :
		$im = imagecreatefromgif($src);		break;
		case 2 :
		$im = imagecreatefromjpeg($src);	break;
		case 3 :
		$im = imagecreatefrompng($src);		break;
		case 4:
		$im=  imagecreatefromgd($src);
	}
	return $im;
}


