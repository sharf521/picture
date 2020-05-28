<?php

namespace App\Util;

class Visitor
{
    public static function getUserId()
    {
        $uid = Token::getUid();
        if ($uid > 0) {
            return $uid;
        } else {
            $uid = session('user_id');
        }
        return (int)$uid;
    }
}