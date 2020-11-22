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
            $return['file'] ='http://'.$_SERVER['HTTP_HOST'].$_path . '/' . $name;    //不包含fenecll
        } else {
            $return['error'] = 'Upload file failed';
        }
    }
} else {
    $return['error'] = 'Upload file is empty！';
}
echo json_encode($return);