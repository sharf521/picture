<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/29
 * Time: 14:41
 */
define('ROOT', dirname(__FILE__));

$arr=array(
    'file'=>ROOT.'/data/picture/2.jpg',
    'path'=>'/data/picture/'.time().'.jpg',
);
var_dump(curl_file($arr));
function curl_file($data)
{
    $curl_url='http://picture.test.cn:8080/upload.php';
    $post = array();
    $post['sign'] = 'picture_upload_img';
    $post['path'] = $data['path'];
    if (class_exists('\CURLFile')) {
        $post['field'] =  new \CURLFile($data['file']);
    } else {
        $post['field'] = '@' . $data['file'];
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $curl_url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($curl);
    curl_close($curl);
    //if((int)$data['is_del'])	unlink($file); 删除文件
    $result = json_decode($result, true);
    if ($result['status'] == 1) {
        $arr['status'] = 1;
        $arr['file'] =  $result['file'];
        return $arr;
    } else {
        $arr['status'] = 0;
        $arr['error'] = $result['error'];
        return $arr;
    }
}