<?php
namespace app\home\controller;
use app\Controllers\BaseController;
use app\Models\Garbage as GarbageModel;

class Garbage extends BaseController
{
    public $Model;
    public function initialize()
    {
        parent::initialize();
        $this->Model = new GarbageModel();
    }
    //查询所有垃圾信息
    public function GetAllList()
    {
        $res=$this->Model->GetAllList();
        Back($res,"查询成功",$this->Model->getError());
    }
}