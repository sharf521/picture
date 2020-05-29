<?php

namespace App\Controller\MemberApi;

use System\Lib\Request;
use App\Model\UploadLog;

class UploadController extends MemberApiController
{
    private $uid;
    public function __construct()
    {
        parent::__construct();
        $this->uid=$this->user_id;
    }

    public function save()
    {
        $data=$this->saveFile('file');
        $this->returnSuccess($data);
    }

    public function saveMore()
    {
        $data=[];
        foreach ($_FILES as $file=>$fileArr){
            $data[]=$this->saveFile($file);
        }
        $this->returnSuccess($data);
    }

    private function saveFile($file='file')
    {
        if($_FILES[$file]['size']<=0){
            return $this->_error('error');
        }
        $type = $_REQUEST['type'];
        $user_id = $this->uid;
        $base_path="/data/user-img/".ceil($user_id/2000)."/".$user_id."/";
        $name = time() . rand(1000, 9000);
        $path=$base_path.date('Ym').'/';
        if ($type == 'headImgUrl') {
            $name = 'face';
            $path=$base_path;
        }
        $_path = ROOT  . $path;
        if (!file_exists($_path)) {
            if (!mkdir($_path, 0777, true)) {
                return $this->_error('Can not create directory');
            }
        }
        if (empty($_FILES[$file]['tmp_name'])) {
            return $this->_error('文件大小超过最大限额');
        }
        if ($_FILES['file']['size'] > 1048576 * 5) {
            return $this->_error('文件超过限额，最大5M');
        }
        $ext = $this->getExt($_FILES[$file]['name']);
        if ($_FILES['file']['name'] != '') {
            if (function_exists('exif_imagetype')) {
                if (exif_imagetype($_FILES[$file]['tmp_name']) < 1) {
                    return $this->_error('not a image file');
                }
            } else {
                if (!in_array($ext, array(".gif", ".png", ".jpg", ".jpeg", ".bmp"))) {
                    return $this->_error('type error');
                }
            }
        }
        $filename = $name . $ext;
        if (!move_uploaded_file($_FILES[$file]['tmp_name'], $_path . $filename)) {
            $this->_error('can not move to path');
        } else {
            $path               = $path . $filename;
            $UploadLog          = new UploadLog();
            $UploadLog->user_id = $user_id;
            $UploadLog->path    = $path;
            $UploadLog->type    = $_FILES['file']['type'];
            $UploadLog->module  = $type;
            $UploadLog->status  = 1;
            $id                 = $UploadLog->save(true);
            $path               = 'http://' . $_SERVER['HTTP_HOST'] . $path;
            $data               = array(
                'id'        => $id,
                'url'       => $path,
                'thumb_url' => $path . "_150X150.png"
            );
            return $data;
        }
    }

    public function del(Request $request)
    {
        $id   = (int)$request->get('id');
        $Log = new UploadLog();
        $Log = $Log->find($id);
        if ($Log->is_exist && $Log->user_id == $this->uid) {
            $Log->status = -1;
            $Log->save();
            $this->returnSuccess();
        } else {
            $this->returnError('异常！');
        }
    }

    private function getExt($filename)
    {
        return strtolower(strrchr($filename, "."));
    }

    private function _error($msg = '')
    {
        $this->returnError($msg);
    }
}