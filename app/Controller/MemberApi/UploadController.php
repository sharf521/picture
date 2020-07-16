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
        $type = (new Request())->type;
        if($type=='chat'){
            header('Access-Control-Allow-Origin:*');
        }
        $user_id = $this->uid;
        $path="/data/user-img/".ceil($user_id/2000)."/".$user_id."/".date('Ym').'/';
        $name = time() . rand(100, 999);
        if ($type == 'headImgUrl') {
            $path="/data/user-img/".ceil($user_id/2000)."/".$user_id."/";
            $name = 'face';
        }elseif($type=='category'){
            $path="/data/user-img/category/";
        }elseif ($type=='chat'){
            $path="/data/user-img/chat/".date('Ym').'/';
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
            if($type!='chat'){
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
            if($type=='chat'){
                $data = array(
                    'code' => '0',
                    'data'=>array(
                        'name' => $filename,
                        'src' => $path
                    )
                );
                echo json_encode($data);
                exit;
            }else{
                $data               = array(
                    'id'        => $id,
                    'url'       => $path,
                    'thumb_url' => $path . "_150X150.png"
                );
                return $data;
            }
        }
    }

    public function del(Request $request)
    {
        $id   = (int)$request->id;
        $Log = new UploadLog();
        $Log = $Log->find($id);
        if ($Log->is_exist && $Log->user_id == $this->uid) {
            $Log->status = -1;
            $Log->deleted_at=time();
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
        $type = (new Request())->type;
        if($type=='chat'){
            $data = array(
                'code' => '-1',
                'msg'  => $msg
            );
            echo json_encode($data);
            exit;
        }else{
            $this->returnError($msg);
        }
    }
}