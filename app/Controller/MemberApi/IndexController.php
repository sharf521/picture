<?php

namespace App\Controller\MemberApi;

class IndexController extends MemberApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->returnSuccess();
    }
}