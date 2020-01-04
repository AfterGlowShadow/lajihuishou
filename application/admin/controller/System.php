<?php


namespace app\admin\controller;


use app\Controllers\BaseController;
use app\Models\SystemConfig as SystemModel;

class System extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new SystemModel();
    }
    public function getSystemConfigList(){
        $res = $this->Model->getSystemConfigList();
        Back($res, "查询成功", $this->Model->getError());
    }
}