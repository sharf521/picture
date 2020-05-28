<?php

namespace App\Controller\MemberApi;

use App\Controller\AppApiController;


class MemberApiController extends AppApiController
{
    protected $user_id;
    public function __construct()
    {
        parent::__construct();
        $user_id=$this->getUserId();
        if($user_id>0){
            $this->user_id=$user_id;
        }else{
            $this->returnError('app_no_login');
        }
    }
}