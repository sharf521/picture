<?php
//前台控制器父类

namespace App\Controller\Home;

use App\Controller\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->preventAttack();
        parent::__construct();
    }

    //防cc
    private function preventAttack()
    {
        if(!$_POST){
            $seconds = 10; //时间段[秒]
            $refresh = 50; //刷新次数
            //设置监控变量
            $cur_time = time();
            if(isset($_SESSION['last_time'])){
                $_SESSION['refresh_times'] += 1;
            }else{
                $_SESSION['refresh_times'] = 1;
                $_SESSION['last_time'] = $cur_time;
            }
            //处理监控结果
            if($cur_time - $_SESSION['last_time'] < $seconds){
                if($_SESSION['refresh_times'] >= $refresh){//跳转验证
                    echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                    $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    echo '<title>安全检查</title><h3>检测到CC攻击，正在进行浏览器安全检查！</h3>';
                    exit("<meta http-equiv='refresh' content='5;url={$url}'>");
                    //5是定时跳转的时间，后期可以根据时间段调整跳转时间
                }
            }else{
                $_SESSION['refresh_times'] = 0;
                $_SESSION['last_time'] = $cur_time;
            }
        }
    }
}