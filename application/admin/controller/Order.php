<?php
namespace app\admin\controller;
use app\Controllers\BaseController;
use app\Models\Order as OrderModel;
class Order extends BaseController
{
    public $Model;
        public function initialize(){
        parent::initialize();
        $this->Model=new OrderModel();
    }
    public function ShopAddOne()
    {
        $res=$this->Model->ShopAddOne();
        Back($res,"添加成功",$this->Model->getError());
    }
    public function SaleAddOne()
    {
        $res=$this->Model->SaleAddOne();
        Back($res,"添加成功",$this->Model->getError());
    }
    public function TempAddOne()
    {
        $res=$this->Model->TempOrderModel();
        Back($res,"添加成功",$this->Model->getError());
    }

    /**
     * User: Administrator
     * Date: 2019-11-06 09:44
     * name 业务员订单地图
     */
    public function OrderMap()
    {
        $res=$this->Model->OrderMap();
        Back($res,"查询成功",$this->Model->getError());
    }
    /**
     * 批量创建订单
     */
    public function BulkOrderNumber(){
        $res=$this->Model->BulkOrderNumber();
        Back($res,"批量生成成功",$this->Model->getError());
    }
}