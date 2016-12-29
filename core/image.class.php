<?php
class image{
	var $img;
	function image($img='')
	{		
		$this->img=$img;
		if(! function_exists('imagejpeg'))
		{
			trigger_error("Unable to process picture.", E_USER_ERROR);	
		}
	}
	// thumb(新图地址, 宽, 高, 裁剪,fill:1填充空白)
	function thumb($target,$width,$height,$fill=0,$rate=100)
	{ 
		$img_create = function_exists('imagecreatetruecolor') ? 'imagecreatetruecolor' : 'imagecreate';
		$img_copy = function_exists('imagecopyresampled') ? 'imagecopyresampled' : 'imagecopyresized';
		$data = getimagesize($this->img); 
		$srcW = $data[0];
		$srcH = $data[1];
		$dstX=$dstY=0;
		// 如果原图比缩略图小
		if($srcW < $width && $srcH < $height && !$fill){
		   return false;
		}			
		$im=$this->create($this->img,$data[2]);		
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
		if($fill==0)
		{
			$dstX=$dstY=0;
			$ni=$img_create($fdstW,$fdstH);//建立一幅图像
		}
		else
		{
			$ni=$img_create($width,$height);
			// 填充背景色1				  
			//imagefilledrectangle($ni,0,0,$width,$height,imagecolorallocate($ni,255,255,255));
			// 填充背景色2
			if ($img_create == 'imagecreatetruecolor')
			{
				imagefill($ni, 0, 0, imagecolorallocate($ni, 255, 255, 255));
			}
			else
			{
				imagecolorallocate($ni, 255, 255, 255);
			}
		}
		$img_copy($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH);		
		imagejpeg($ni,$target,$rate);
		imagedestroy($im); 
		imagedestroy($ni);
		return is_file($target) ? $target : false;
	}
	function water()
	{
		if($markwords!=null) 
		{ 
			//imagestring($ni, 5, 0, 0, $markwords, $black); //英文可以不要字体
			$black = imagecolorallocate($ni,0,0,0); 
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
	}
	private function create($src,$imagetype)
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

}