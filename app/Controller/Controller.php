<?php
namespace App\Controller;

use System\Lib\Controller as BaseController;

class Controller extends BaseController
{
    protected $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->dbfix=DB_CONFIG_FIX;
    }
}