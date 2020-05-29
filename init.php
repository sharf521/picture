<?php
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(7);

date_default_timezone_set('Asia/Shanghai');//时区配置
header('Content-type: text/html; charset=utf-8');
header('X-Powered-By: JAVA');
header('Cache-control: private, must-revalidate');  //支持页面回跳
set_time_limit(30);//# 设置执行时间

//session_cache_limiter('public');//缓存必须设置session
//ini_set('session.cookie_domain', '.a.com');
session_start();
//require 'vendor/autoload.php';

define('ROOT', __DIR__);
require ROOT . '/core/config.php';
$base_path = '';
foreach ($config['site'] as $site) {
    if ($site['domain'] == strtolower($_SERVER['HTTP_HOST'])) {
        $base_path = $site['dir'] . '/';
        break;
    }
}

