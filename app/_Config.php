<?php

namespace App;

class _Config
{
    // 数据库实例1
    public static $db1 = array(
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'user'     => 'root',
        'password' => 'root',
        'dbname'   => 'picture',
        'charset'  => 'utf8mb4',
        'dbfix'    => ''
    );

    public static $oss = array(
        'endpoint'  => 'http://oss-cn-hangzhou.aliyuncs.com',
        'bucket'    => '5-58',
        'keyId'     => '',
        'keySecret' => '',
        'domain'    => 'http://cdn.5-58.com'
    );
}