<?php
require 'init.php';
if ($base_path == ''){
    echo 'Host Error';
    exit;
}

$dir = 'data/' . $base_path ;
$return['status'] = 0;

$filePath =str_replace('//','/',$_POST['path']);
$_path=trim(dirname($filePath),'.');
$name=basename($filePath);
$path = empty($_path) ? $dir : "{$dir}/{$_path}";
if(empty($name)){
    $return['error'] = 'path is error！';
}

if ($_FILES) {
    $tmp_name = $_FILES['field']['tmp_name'];
    if (!file_exists($path)) {
        if (!mkdir($path, 0777, true))
            $return['error'] = 'Can not create file directory';
    }
    if (empty($return['error'])) {
        $file = $path . '/' . $name;// fenecll/sdfdfd/a/aa.gif
        if (move_uploaded_file($tmp_name, $file)) {
            $return['status'] = 1;
            $return['path']= $_path . '/' . $name;
            //$return['file'] ='http://'.$_SERVER['HTTP_HOST'].$_path . '/' . $name;    //不包含fenecll

            require 'vendor/autoload.php';
            $return['file'] = \App\Config::$oss['domain'] .saveOSS($return['path']);
        } else {
            $return['error'] = 'Upload file failed';
        }
    }
} else {
    $return['error'] = 'Upload file is empty！';
}
echo json_encode($return);


function saveOSS($path)
{
    $accessKeyId     = \App\Config::$oss['keyId'];
    $accessKeySecret = \App\Config::$oss['keySecret'];
    $endpoint        = \App\Config::$oss['endpoint'];
    $bucket          = \App\Config::$oss['bucket'];
    try {
        $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $file_name = substr($path, 6);//去除/data
        $file_path = substr($path, 1);//去除/
        if(strpos($_SERVER['HTTP_HOST'],'test.cn')!==false){
            $file_name='test/'.$file_name;
        }
        $file_path='data/picture/'.$file_path;
        $info      = $ossClient->uploadFile($bucket, $file_name, $file_path);
        $arr       = parse_url($info['oss-request-url']);
        return $arr['path'];
    } catch (\OSS\Core\OssException $e) {
        echo ($e->getMessage());
    }
}