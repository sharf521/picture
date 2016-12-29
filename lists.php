<?php
require 'init.php';
require 'core/global.func.php';


if ($base_path == '') return '';

$dir = 'data/' . $base_path ;
$_path = trim($_POST['filepath'], '/');
$path = empty($_path) ? $dir : "{$dir}/{$_path}";
$files = getfiles($path);
if (count($files) > 0) {
    $new_file = array();
    foreach ($files as $file) {
        $new_file[filemtime($file)] = str_replace("{$dir}/", '', $file);//路径里去除fenecll
    }
    krsort($new_file, SORT_NUMERIC);//key降序ue_separate_ue
    $str = implode('[#]', $new_file);
    $arr = explode('[#]', $str);
    echo json_encode($arr);
}