<?php

namespace App;

class Helper
{
    public static function log($name='error',$data)
    {
        $path = ROOT . "/public/data/logs/";
        if (!file_exists($path)) {
            mkdir($path,0777,true);
        }
        $log_file = fopen($path.$name.'_'.date('Ym').".txt", "a+");
        if(is_array($data)){
            $data=json_encode($data);
        }
        $file = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
        fwrite($log_file, '【'.date('Y-m-d H:i:s').'】'."\t file:{$file}\t".$data."\r\n");
        fclose($log_file);
    }
}