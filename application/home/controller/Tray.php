<?php
namespace app\home\controller;
use app\Controllers\BaseController;
use app\Models\Tray as TrayModel;
class Tray extends  BaseController
{
    public $Model;

    public function initialize()
    {
        parent::initialize();
        $this->Model = new TrayModel();
    }
    public function TrayOrderList(){
        $res = $this->Model->TrayOrderList();
        Back($res, "查询成功", $this->Model->getError());
    }
    //添加订单
    public function OrderAdd()
    {
        $res = $this->Model->OrderAdd();
        Back($res, "添加成功", $this->Model->getError());
    }
    //出库是获取可以出库垃圾订单列表
    public function OrderList(){
        $res = $this->Model->OrderList();
        Back($res, "查询成功", $this->Model->getError());
    }
}
