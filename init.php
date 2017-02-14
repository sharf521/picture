<?php
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(7);
session_cache_limiter('public');//缓存必须设置session
session_start();
date_default_timezone_set('Asia/Shanghai');//时区配置
header('Content-type: text/html; charset=utf-8');
header('X-Powered-By: JAVA');
set_time_limit(30);//# 设置执行时间


define('ROOT', dirname(__FILE__) . '/');
//define('ROOT', dirname($_SERVER['SCRIPT_FILENAME']).'/');
require ROOT . 'core/config.php';
$base_path = '';
foreach ($config['site'] as $site) {
    if ($site['domain'] == strtolower($_SERVER['HTTP_HOST'])) {
        $base_path = $site['dir'] . '/';
        break;
    }
}

