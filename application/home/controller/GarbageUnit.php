<?php
namespace app\home\controller;
use app\Controllers\BaseController;
use app\Models\GarbageUnit as GarbageUnityModel;
class GarbageUnit extends  BaseController
{
    public $Model;

    public function initialize()
    {
        parent::initialize();
        $this->Model = new GarbageUnityModel();
    }
//    public function GarbageUnitList(){
//        $res = $this->Model->TrayOrderList();
//        Back($res, "查询成功", $this->Model->getError());
//    }
//    //添加订单
//    public function GarbageUnitAdd()
//    {
//        $res = $this->Model->OrderAdd();
//        Back($res, "添加成功", $this->Model->getError());
//    }
}
