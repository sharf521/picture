<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/9
 * Time: 14:28
 */

namespace App\Util;


use App\Helper;
use Firebase\JWT\JWT;
use System\Lib\Request;

class Token
{
    private static $key='my_key_zz';
    public static function createToken($uid,$day=0.5)
    {
        $token = array(
            'uid'=>$uid,
            "iss" => "http://www.abcxxc.org",
            "aud" => "http://www.abcxxc.com",
            "iat" => time(),
            "exp" => time()+3600*24*$day
        );
        $jwt = JWT::encode($token, self::$key);
        return $jwt;
    }

    public static function getUid($token='')
    {
        if($token==''){
            $token=htmlspecialchars($_SERVER['HTTP_AUTHORIZATION']);
        }
        try{
            $decoded = (array)JWT::decode($token, self::$key, array('HS256'));
            $uid=(int)$decoded['uid'];
            return $uid;
        }catch (\Exception $e){
            return 0;
        }
    }

    public static function getLoginUid()
    {
        $token=htmlspecialchars($_SERVER['HTTP_AUTHORIZATION']);
        $uid=self::getUid($token);
        if($uid>0){
            return $uid;
        }
        return 0;
    }

    private static function getAgentMd5()
    {
        return md5($_SERVER['HTTP_USER_AGENT']);
    }
}

/*
iphone HTTP_USER_AGENT
iPhone8,2(iOS/12.4) Uninview(Uninview/1.0.0) Weex/0.26.0 1242x2208
上传：
Mozilla/5.0 (iPhone; CPU iPhone OS 12_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 Html5Plus/1.0 (Immersed/20) uni-app appservice

安卓 HTTP_USER_AGENT
NX589J(Android/7.1.1) (io.dcloud.UNI181F013/1.0.2) Weex/0.26.0 1080x1920
上传：
Dalvik/2.1.0 (Linux; U; Android 7.1.1; NX589J Build/NMF26F)
 * */