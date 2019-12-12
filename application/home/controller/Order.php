<?php
namespace app\home\controller;
use app\Controllers\BaseController;
use app\Models\Order as OrderModel;
class Order extends BaseController
{
//    public $Model;
//    public function initialize(){
//        parent::initialize();
//        $this->Model=new ShopSarleOrderModel();
//    }
//    //添加订单
//    public function AddOder(){
//        $res=$this->Model->AddOder();
//        Back($res,"添加成功",$this->Model->getError());
//    }

    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new OrderModel();
    }

    /**
     * 门店下单
     */
    public function ShopAddOne()
    {
        $res=$this->Model->ShopAddOne();
        Back($res,"添加成功",$this->Model->getError());
    }
//    public function SaleAddOne()
//    {
//        $res=$this->Model->SaleAddOne();
//        Back($res,"添加成功",$this->Model->getError());
//    }
//    public function TempAddOne()
//    {
//        $res=$this->Model->TempOrderModel();
//        Back($res,"添加成功",$this->Model->getError());
//    }

    public function ConfirmOrder()
    {
        $res=$this->Model->ConfirmOrder();
        Back($res,"确认成功",$this->Model->getError());
    }

    /**
     * 改价
     */
    public function UpdatePrice()
    {
        $res=$this->Model->UpdatePrice();
        Back($res,"修改成功",$this->Model->getError());
    }
    public function adjust(){

        $res=$this->Model->adjust();
        Back($res,"修改成功",$this->Model->getError());
    }
 /**
     * 取消订单
     */
    public function cancel()
    {
        $res=$this->Model->Cancel();
        Back($res,"订单取消成功",$this->Model->getError());
    }
    /**
     * 获取用户收支记录
     */
    public function GetOrderLog()
    {
        $res=$this->Model->GetOrderLog();
        Back($res,"获取记录成功",$this->Model->getError());
    }
    /**
     * 获取下级订单
     */
    public function NOrder()
    {
        $res=$this->Model->NOrder();
        Back($res,"获取记录成功",$this->Model->getError());
    }
    /**
     * 获取下级订单
     */
    public function Update()
    {
        $res=$this->Model->CUpdate();
        Back($res,"修改成功",$this->Model->getError());
    }
    /**
     * 获取下级订单
     */
    public function ChangeOne()
    {
        $res=$this->Model->ChangeOne();
        Back($res,"修改成功",$this->Model->getError());
    }

}