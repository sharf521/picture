<?php
// /index.php/data/files/0/shop_350/201405/13996256741819.jpg_50x50.png
// /index.php/data/files/0/shop_350/201405/13996256741819.jpg_50x50.jpg
//png强制放大，jpg不放大
require 'init.php';

$_path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
if (empty($_path)) {
    return;
}
$_path = substr($_path, 1);
$pic = 'data/' . $base_path . strtolower($_path);
//(strpos($pic,'_')!==false)
if (preg_match_all("/(.*)_(\d+)x(\d+)\.(jpg|jpeg|gif|bmp|png)$/", $pic, $arr)) {
    $pic = $arr[1][0];
    $w = (int)$arr[2][0];
    $h = (int)$arr[3][0];
    $type = $arr[4][0] == 'png' ? 1 : 0;// png强制放大，jpg不放大
    if ($w > 49 && $h > 49 && $w < 1001 && $h < 1001) {
        if ($w % 50 != 0 || $h % 50 != 0) {
            $w = $h = 200;
        }
        check_file($pic);
        $newfile = get_cache_name($pic, $w, $h, $type);
        if (!file_exists($newfile)) {
            include ROOT . 'core/image.class.php';
            $imgs = new image($pic);
            $return['thumb'] = $imgs->thumb($newfile, $w, $h, $type);
            if (empty($return['thumb'])) {
                $newfile = $return['file'];//原图片一样
            } else {
                $newfile = $return['thumb'];
            }
        }
    }
}
if (empty($newfile)) {
    check_file($pic);
    $newfile = $pic;
}
$image = file_get_contents($newfile);

/*
header("Expires: ".gmdate ("D, d M Y H:i:s", time() + 3600 * 24 * 15 )." GMT");  //设置15天过期
header("Last-Modified: " . gmdate ("D, d M Y H:i:s", time()) . " GMT"); // always modified
header("Cache-Control: public"); // HTTP/1.1
header("Pragma: Pragma");   //Pragma: cache       // HTTP/1.0
*/
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
    // if the browser has a cached version of this image, send 304
    header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
    exit;
}
header("Content-type: image/JPEG", true);
echo $image;


function check_file($pic)
{
    if (!file_exists($pic)) {
        echo "no pic";
        exit;
    }
    $ext = strtolower(strrchr($pic, '.'));
    if (in_array($ext, array('.jpg', '.jpeg', '.gif', '.bmp', '.png'))) {
        if (function_exists('exif_imagetype')) {
            if (exif_imagetype($pic) < 1) {
                ob_start();
                ob_get_clean();
                ob_clean();
                echo "not picture type";
                exit;
            }
        }
    } else {
        $pic = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pic;
        header("location:{$pic}");
        exit;
    }
}

function get_cache_name($pic, $w, $h, $type)
{
    $time = filemtime($pic);
    $newfilepath = ROOT . 'cache_' . dirname($pic) . '/';
    if (!file_exists($newfilepath) && !mkdir($newfilepath, 0777, true)) {
        die('无法创建缓存文件夹' . $newfilepath);
    }
    return $newfilepath . "{$time}_{$w}-{$type}-{$h}_" . basename($pic);
}