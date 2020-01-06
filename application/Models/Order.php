<?php

namespace app\Models;

use app\validate\LimitValidate;
use app\validate\Order as OrderValidate;
use app\validate\TokenValidate;
use app\validate\Tracing;
use app\validate\TracingOrder;
use think\Db;
use think\facade\Request;

class Order extends BaseModel
{
    protected $table = "lj_order";

    /**
     * page
     * list_rows
     * status  订单状态 对应个身份传
     * shop_id  门店ID ，门店查看自己订单信息不传或者0
     * salesman_id  业务员ID， 业务员查看自己信息不传或者0
     * temporary_id  暂存点ID  暂存点查看自己信息不传或者0
     * @return bool
     */
    //分页查询信息
    public function GetList()
    {
        $post = Request::post();
        $user = session($post['token']);
        (new LimitValidate())->goCheck($post);
        $where = [];
        $temp=0;
        $whereor=array();
        if(array_key_exists("status",$post)&&$post['status']!="") {
        $temp=1;
        }
        $post['status'] = isset($post['status']) ? $post['status'] : 1;
        if ($user['userInfo']['groupid'] == 1) { //门店
            $where[] = ['user_id', '=', $user['userInfo']['id']];
            $where[] = ['status', '=', $post['status']];
        } elseif ($user['userInfo']['groupid'] == 2) {  //业务员
            if (isset($post['shop_id'])) { //门店
                $where[] = ['status', '=', $post['status']];
                if($post['shop_id']!=""){
                    $where[] = ['user_id', '=', $post['shop_id']];
                }else{
                    $userM=new User();
                    $fuwhere['upid']=$user['userInfo']['id'];
                    $userlist=$userM->MSelect($fuwhere);
                    if($userlist){
                        $userarray=$this->getid($userlist);
                        $where[]=['user_id','in',$userarray];
                        $whereor[]=['uuser','=',$user['userInfo']['id']];
                        $whereor[]=['status','=',$post['status']];
                    }else{
                        $where[]=['uuser','=',$user['userInfo']['id']];
//                        BackData("200","没有数据");
                    }
                }
            } elseif (isset($post['is_shangjiao'])) { //门店
                $where[]=['isshangjiao','=',$post['is_shangjiao']];
                $where[]=['uuser','=',$user['userInfo']['id']];
                $where[] = ['status', '=',5];
                $where[]=['type','=',1];
            }else{ //自己的
                $where[] = ['user_id', '=', $user['userInfo']['id']];
                $where[] = ['status', '=', $post['status']];
            }
        } elseif ($user['userInfo']['groupid'] == 3) { //暂存点
            if (isset($post['salesman_id'])) { //业务员订单
                $where[] = ['status', '=', $post['status']];
                $where[]=['type','=',2];
                if($post['salesman_id']!=""){
                    $where[] = ['user_id', '=', $post['salesman_id']];
                }else{
                    $userM=new User();
                    $fuwhere['upid']=$user['userInfo']['id'];
                    $fuwhere['groupid']=2;
                    $userlist=$userM->MSelect($fuwhere);
                    if($userlist){
                        $userarray=$this->getid($userlist);
                        $where[]=['user_id','in',$userarray];
                        $where[]=['status','=',$post['status']];
//                        $whereor[]=['uuser','in',$userarray];
//                        $whereor[]=['status','=',$post['status']];
                    }else{
                        $where[]=['uuser','in',$user['userInfo']['id']];
//                        BackData("200","没有数据");
                    }
                }
            } elseif (isset($post['shop_id'])) { //门店订单
                $where[]=['type','=',1];
                if($post['shop_id']!=""){
                    $where[] = ['user_id', '=', $post['shop_id']];
                }else{
                    $userM=new User();
                    $fuwhere['upid']=$user['userInfo']['id'];
                    $fuwhere['groupid']=1;
                    $userlist=$userM->MSelect($fuwhere);
                    if($userlist){
                        $userarray=$this->getid($userlist);
                        $where[]=['user_id','in',$userarray];
                        $where[]=['uuser','=',$user['userInfo']['id']];
                        $whereor[]=['uuser','=',$user['userInfo']['id']];
                        $whereor[] = ['status', '=', $post['status']];
                    }else{
                        $where[]=['uuser','=',$user['userInfo']['id']];
//                        BackData("200","没有数据");
                    }
                }
                $where[] = ['status', '=', $post['status']];
            } elseif (isset($post['is_shangjiao'])) { //门店
                $where[]=['isshangjiao','=',$post['is_shangjiao']];
                $where[]=['uuser','=',$user['userInfo']['id']];
                $where[] = ['status', '=', 5];
            } else {  //自己的
                $where[] = ['user_id', '=', $user['userInfo']['id']];
                if($post['status']==1){
                    //2为待库管入库 1为待主管确认 7待会计确认
                    $where[] = ['status', 'in', array(2,1,7)];
                }else{
                    $where[] = ['status', '=', $post['status']];
                }
            }
        }elseif ($user['userInfo']['groupid'] == 5) { //库管
            $where[] = ['status', '=', $post['status']];
            if(array_key_exists("temp_id",$post)){
                if($post['temp_id']!=""){
                    $where[] = ['user_id', '=', $post['temp_id']];
                }else{
//                    $userM=new User();
//                    $fuwhere['upid']=$user['userInfo']['id'];
//                    $userlist=$userM->MSelect($fuwhere);
//                    $userarray=$this->getid($userlist);
//                    $where[]=['user_id','in',$userarray];
                    //能入库会计或者暂存点的订单
                    $where[]=['type','=',3];
                    $whereor[]=['type','=',6];
                    $whereor[]=['status','=',1];
                }
            }else{
                if(array_key_exists("otype",$post)&&$post['otype']==1){
                    $where=array();
                    $where[]=['otype','=',$post['otype']];
                    $where[] = ['status', '=', 1];
                    $where[] = ['type', '=', 6];
                }else{
                    $where[] = ['user_id', '=', $user['userInfo']['id']];
                }
            }
        }elseif ($user['userInfo']['groupid'] == 6) { //会计
            if(array_key_exists("utype",$post)&&$post['utype']!=""){
                $where[]=['type','=',$post['utype']];
            }
            if(array_key_exists("otype",$post)&&$post['otype']!=""){
                $where[]=['otype','=',$post['otype']];
            }else{
                $where[]=['otype','=',0];
            }
            if($temp) {
                $where[] = ['status', '=', $post['status']];
            }
        }elseif ($user['userInfo']['groupid'] == 4) { //平台主管
            if(array_key_exists("otype",$post)&&$post['otype']!=""){
                $where[]=['otype','=',$post['otype']];
            }else{
                if(array_key_exists("utype",$post)&&$post['utype']!=""){
                    $where[]=['type','=',$post['utype']];
                }else{
                    $where[]=['type',"=","3"];
                }
            }
            $where[] = ['status', '=', $post['status']];
        }else if($user['userInfo']['groupid'] == 7){
           if(array_key_exists('type',$post)){
               if($post['type']=='allin'){
                   $where[]=['type','in',array(3,6)];
                   $where[] = ['status', '=', $post['status']];
               }else if($post['type']=='allout'){
                   $where[]=['otype','=',1];
                   $where[]=['type','in',array(6)];
               }
           }else{
               if(array_key_exists("utype",$post)){
                   $where[]=['type','=',$post['utype']];
               }
           }
        }
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $where[] = ['del', '=', 0];
//        print_r($where);
        $filed = 'ordernumber,id,isbaozhi,user_id,delivery_method,znumber,zweight,status,create_time,end_time,type,price,paytype,pay_time';
        if(isset($whereor)&&!empty($whereor)){
            $res = $this->MLimitSelect($where, $config, "id desc", $filed,$whereor);
        }else{
            $res = $this->MLimitSelect($where, $config, "id desc", $filed);
        }
        if ($res['data']) {
//            print_r($res['data']);
//            exit;

                $res1 = (new OrderCalculation())->getOrderDetail($res['data']);
                if ($res1['status'] == 0) {
                    $this->error = $res1['msg'];
                    return false;
                }
                $res['data']=$res1['data'];
            return $res;
        } else {
            BackData("200","没有数据");
        }
    }
    //特定人员的下级订单
    public function NOrder()
    {
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        if(array_key_exists("user_id",$post)&&$post['user_id']!=""){
            if(array_key_exists("status",$post)&&$post['status']!=""){
                $w_where['status']=$post['status'];
            }
            $w_where['uuser']=$post['user_id'];
            $config['page'] = $post['page'];
            $config['list_rows'] = $post['list_rows'];
            $w_where['del'] = 0;
            $filed = 'ordernumber,id,isbaozhi,user_id,delivery_method,znumber,zweight,status,create_time,end_time,type,price,paytype,pay_time';
            $res = $this->MLimitSelect($w_where, $config, "id desc", $filed);
            if ($res['data']) {
                $res1 = (new OrderCalculation())->getOrderDetail($res['data']);
                if ($res1['status'] == 0) {
                    $this->error = $res1['msg'];
                    return false;
                }
                $res['data']=$res1['data'];
                return $res;
            } else {
                BackData("200","没有数据");
            }
        }else{
            BackData("400","缺少必要参数");
        }
    }
    //添加订单
    public function ShopAddOne()
    {
        $post = Request::post();
        $user = session($post['token']);
        if (array_key_exists("garbagelist", $post) && !empty($post['garbagelist'])) {
            $garbagelist = json_decode($post['garbagelist'], true);
            if ($user['userInfo']['groupid'] == 1|| $user['userInfo']['groupid'] == 5|| $user['userInfo']['groupid'] == 6) { //门店提交
                return $this->shopOrder($garbagelist, $user);
            } else if ($user['userInfo']['groupid'] == 2 || $user['userInfo']['groupid'] == 3) { //业务员提交 暂存点提交
                return $this->SaleAddOne($garbagelist, $user);
            }else if($user['userInfo']['groupid']==4||$user['userInfo']['groupid']==7){
//                $garbageorderM=new GarbageOrder();
//                $garbageid=json_decode($post['garbagelist'],true);
//                $temp=array();
//                $this->startTrans();
//                foreach ($garbageid as $key => $value){
//                    array_push($temp,$value['id']);
//                }
//                $gocont['orderid']=$temp;
//                $gocont['is_shangjiao']=0;
//                $garbageorder=$garbageorderM->MSelect($gocont);
//                $post['gres']=$garbageorder;
//                $temp=array();
//                foreach ($garbageorder as  $key => $value){
//                    array_push($temp,$value['id']);
//                }
//                $post['ids']=$temp;
//                $TrayM=new Tray();
//                $ores=$TrayM->CreateOrder($post);
//                print_r($ores);
//                exit;
//                $post['gres']=$garbageorder;
//                $ocont['id']=$value['orderid'];
//                $orderinfo=$this->MFind($ocont);
//                if($orderinfo){
//                    $post['id']=$orderinfo['trayid'];
//                    $res=$TrayM->zhuOutOrder($post);
//                    if(!$res){
//                        $this->rollback();
//                        $this->error="出库失败";
//                        return false;
//                    }
//                }else{
//                    $this->rollback();
//                    $this->error="出库失败";
//                    return false;
//                }
//                $TrayM->CreateOrder($post);
            }
//            return "出库成功";
        } else {
            $this->error = "必须填写垃圾";
            return false;
        }
    }

    /**
     *修改订单的重量和个数
     */
    public function adjust()
    {
        $post = Request::post();
        if (array_key_exists("garbagelist", $post) && !empty($post['garbagelist']) && array_key_exists('ordernumber', $post) && $post['ordernumber'] != "") {
            $garbagelist = json_decode($post['garbagelist'], true);
            $orderCalculation = (new OrderCalculation())->Calculation($garbagelist);
            if ($orderCalculation['status'] == 0) {
                $this->error = $orderCalculation['msg'];
                return false;
            }
            $where['ordernumber'] = $post['ordernumber'];
            $order = $this->MFind($where);
            $GarbageOrderModel = new GarbageOrder();
            $gocont['orderid']=$order['id'];
            $gocont['del']=0;
            $yuangarbagelist=$GarbageOrderModel->MSelect($gocont);
            $order_list = $orderCalculation['data'];
            $this->startTrans();
            if ($order) {

                    if ($this->ValidatenNumber($yuangarbagelist,$order_list,$order,$post)) {
                        $data['ischange']=1;
                        if ($order['type'] == 1) {
                            $data['status'] = 4;
                        } else if ($order['type'] == 2) {
                            $data['status'] = 2;
                        } else if ($order['type'] == 3) {
                            $data['status'] = 2;
                        }
                    }else{
                        $user=session($post['token']);
                        if ($order['type'] == 1) {
                            $data['status'] = 5;
                        } else if ($order['type'] == 2) {
                            $data['status'] = 5;
                        } else if ($order['type'] == 3) {
                            $data['status'] = 6;
                        }
                        $res1 = $this->MUpdate($where, $data);
                        $res=$this->UpJiFen($user,$order);
                        if($res&&$res1){
                            $this->commit();
                            return $res;
                        }else{
                            $this->rollback();
                            $this->error="修改失败";
                            return false;
                        }
                    }
                    $res1 = $this->ChangeGarbage($garbagelist, $post['ordernumber']);
                    if ($res1) {
                        if($order['isbaozhi']!=1){
                            $data['price'] = $order_list['price'];
                            $data['znumber'] = $order_list['znumber'];
                        }
//                        $data['weight'] = $order_list['zweight'];
//                        $data['zweight'] = $order_list['zweight'];
                        $data['create_time'] = time();
                        if(array_key_exists("paytype",$post)&&$post['paytype']!=""){
                            $data['paytype']=$post['paytype'];
                        }
                        if(array_key_exists("delivery_method",$post)&&$post['delivery_method']!=""){
                            $data['delivery_method']=$post['delivery_method'];
                        }
                        $res = $this->MUpdate($where, $data);
                        if ($res) {
                            $this->commit();
                            return true;
                        } else {
                            $this->rollback();
                            $this->error = "订单修改失败";
                            return false;
                        }
                    } else {
                        $data['create_time'] = time();
                        if(array_key_exists("paytype",$post)&&$post['paytype']!=""){
                            $data['paytype']=$post['paytype'];
                        }
                        if(array_key_exists("delivery_method",$post)&&$post['delivery_method']!=""){
                            $data['delivery_method']=$post['delivery_method'];
                        }
                        $res = $this->MUpdate($where, $data);
                        if ($res) {
                            $this->commit();
                            return true;
                        } else {
                            $this->rollback();
                            $this->error = "订单修改失败";
                            return false;
                        }
                    }
//                }
            } else {
                $this->error = "修改订单不存在";
                return false;
            }
        } else {
            $this->error = "必须选定垃圾";
            return false;
        }
    }
    //验证订单是否被修改了
    public function ValidatenNumber($garbagelist,$order_list,$order,$post){
        if($order['paytype']!=$post['paytype']||$post['delivery_method']!=$order['delivery_method']){
            return true;
        }
        foreach ($order_list['detail'] as $key => $value){
            foreach ($garbagelist as $k=> $v){
                if($value['garbageunitid']==$v['garbageunitid']&&$value['id']==$v['id']&&$value['danweiming']==$v['danweiming']&&$value['weighting_num']!=$v['weighting_num']){
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * 修改订单垃圾表  重量或个数
     */
    public function ChangeGarbage($data, $ordernu)
    {
        $post = Request::post();
        $user = session($post['token']);
        $garbage = new GarbageOrder();
        $temp = false;
        foreach ($data as $key => $value) {
            if (array_key_exists("id", $value) && $value['id'] != "") {
                $where['id'] = $value['id'];
                $garbageorder = $garbage->MFind($where);

                if ($garbageorder['weighting_num'] != $value['weighting_num']) {
                    $temp = true;
                    $changedata['weighting_num'] = $value['weighting_num'];
                    $res = $garbage->MUpdate($where, $changedata);
                    if (!$res) {
                        return false;
                    } else {
                        //记录订单修改到orderchangelog表中
                        $logdata['ordernumber'] = $ordernu;
                        $logdata['garbageid'] = $value['id'];
                        $logdata['oldweighting_num'] = $garbageorder['weighting_num'];
                        $logdata['newweighting_num'] = $value['weighting_num'];
                        $logdata['userid'] = $user['userInfo']['id'];
                        $orderChangelog = new OrderChangeLog();
                        $res1 = $orderChangelog->MAdd($logdata);
                        if (!$res1) {
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 门店添加订单
     * @param $garbagelist
     * @return bool
     * @throws \think\exception\PDOException
     */
    private function shopOrder($garbagelist, $user)
    {
        $post = Request::post();
        if($user['userInfo']['groupid']!=6){
            $orderCalculation = (new OrderCalculation())->Calculation($garbagelist);
//        print_r($orderCalculation);
//        exit;
            if ($orderCalculation['status'] == 0) {
                $this->error = $orderCalculation['msg'];
                return false;
            }
        }else{
            $orderCalculation['data']['detail']=array();
            $orderCalculation['data']['znumber']=0;
            foreach ($garbagelist as $key => $value){
                if(array_key_exists($value['garbageid'], $orderCalculation['data']['detail'])){
                    $orderCalculation['data']['znumber']+=$value['weighting_num'];
                    $orderCalculation['data']['detail'][$value['garbageid']]['weighting_num']+=$value['weighting_num'];
                }else{
                    unset($value['id']);
                    $orderCalculation['data']['znumber']+=$value['weighting_num'];
                    $orderCalculation['data']['detail'][$value['garbageid']]=$value;
                }
            }
            $orderCalculation['data']['price']=0;
        }
        $GarbageOrderModel = new GarbageOrder();
        $data['status'] = 1;
        if($user['userInfo']['groupid']!=6){
            $data['isbaozhi'] = $post['is_protect'];
            $data['status'] = 1;
        }else{
            if(array_key_exists("type",$post)&&$post['type']=="chuku"){
                $data['otype'] = 1;
            }else if(array_key_exists("type",$post)&&$post['type']=="ruku"){
                $data['otype'] = 0;
            }
        }
        $order_list = $orderCalculation['data'];
        $data['price'] = $order_list['price'];
        $data['znumber'] = $order_list['znumber'];
        $data['type'] = $user['userInfo']['groupid'];
        $data['user_id'] = $user['userInfo']['id'];
        $data['username']=$user['userInfo']['zhicheng'];
        $data['create_time'] = time();
        $data['uuser']=$user['userInfo']['upid'];
        $data['latitude'] = $user['userInfo']['latitude'];
        $data['longitude'] = $user['userInfo']['longitude'];
        $userm=new User();
        $uwhere['id']=$user['userInfo']['upid'];
        $uuser=$userm->MFind($uwhere);
        if(array_key_exists("remark",$post)&&$post['remark']!=""){
            $data['remark']=$post['remark'];
        }
        //溯源模式 扫码添加订单 订单号必填
        if ($post["is_tracing"] == 2) {
            if (array_key_exists("order_sn", $post) && !empty($post['order_sn'])) {
                $_where = [];
                $_where[] = ['ordernumber', '=', $post['order_sn']];
                $_where[] = ['status', '=', 0];
                $_where[] = ['del', '=', 0];
                $res = $this->MFind($_where, '', 'id');
                if ($res) {
                    $this->startTrans();
                    $res1 = $this->MUpdate($_where, $data);
                    if ($res1) {
                        $saledata = PushGarbage($order_list['detail'], $res['id'], 'orderid', 'garbageid');
                        $res2 = $GarbageOrderModel->MBulkAdd($saledata);
                        if ($res2) {
                            //记入日志
                           $log['addtime'] = time();
                           $log['userid'] = $user['userInfo']['id'];
                           $log['orderid'] = $res;
                           $log['type'] = $user['userInfo']['groupid'];
                           $res3 = (new OrderLog())->setOrderLog($log);
                           if ($res3) {
                                $this->commit();
                               (new OrderOrder())->setOrderMsg(1,[$res]);
//                                // 标题,订单号,详情,金额,时间
                               testmessage("下单通知",$post['order_sn'],$user['userInfo']['zhicheng']."用户下单",$data['price'],$uuser['openid'],0);
                                return $res1;
                           } else {
                               $this->rollback();
                               return false;
                           }
                        } else {
                            $this->rollback();
                            $this->error = "添加失败";
                            return false;
                        }
                    } else {
                        $this->rollback();
                        $this->error = "添加失败";
                        return false;
                    }
                } else {
                    $this->error = "订单信息有误";
                    return false;
                }
            } else {
                $this->error = "缺少必填参数";
                return false;
            }
        } else {  //正常添加订单
//            (new OrderValidate())->goCheck($post);
            $this->startTrans();
            $data['ordernumber'] = createOrderSn();
            $res = $this->MAdd($data);
            if ($res) {
                $saledata = PushGarbage($order_list['detail'], $res, 'orderid', 'garbageid');
                $res1 = $GarbageOrderModel->MBulkAdd($saledata);
                if ($res1) {
                    //记入日志
                   $log['addtime'] = time();
                   $log['userid'] = $user['userInfo']['id'];
                   $log['orderid'] = $res;
                   $log['type'] = $user['userInfo']['groupid'];
                   $res2 = (new OrderLog())->setOrderLog($log);
                   if ($res2) {
                       (new OrderOrder())->setOrderMsg(1,[$res]);
                       testmessage("下单通知",$data['ordernumber'],$user['userInfo']['zhicheng']."用户下单",$data['price'],$uuser['openid'],0);
                        $this->commit();
                        $fwhere['id']=$res;
                        $res=$this->MFind($fwhere);
                        return $res;
                   } else {
                       $this->rollback();
                       return false;
                   }
                } else {
                    $this->rollback();
                    $this->error = "添加失败";
                    return false;
                }
            } else {
                $this->rollback();
                $this->error = "添加失败";
                return false;
            }
        }
    }

    //业务员\暂存点\总库出库添加订单
    public function SaleAddOne($garbagelist, $user)
    {
        $post = Request::post();
        $orderCalculation = (new OrderCalculation())->filterAllOrder($garbagelist);
        if ($orderCalculation['status'] == 1) {
            $GarbageOrderModel = new GarbageOrder();
            $orderInfo = $orderCalculation['data'];
//            $data['isbaozhi'] = $post['is_protect'];
            $data['price'] = $orderInfo['price'];
            $data['znumber'] = $orderInfo['znumber'];
            $data['type'] = $user['userInfo']['groupid'];
//            $data['type'] = 2;
//            $data['zweight'] = $orderInfo['zweight'];
            $data['user_id'] = $user['userInfo']['id'];
//            $data['user_id'] = 7;
            $data['status'] = 1;
            $data['create_time'] = time();
            $this->startTrans();
            $data['uuser']=$user['userInfo']['upid'];
            $data['username']=$user['userInfo']['zhicheng'];
            $data['ordernumber'] = createOrderSn();
            $data['norder']=json_encode($garbagelist);
            $res = $this->MAdd($data);
            foreach ($garbagelist as $k => $v) {
                $ids[] = $v['id'];
            }
            $_wh = [];
            $_wh[] = ['id', 'in', $ids];
            $changdata['isshangjiao']= 1;
            $res4 = $this->MUpdate($_wh,$changdata);
            if ($res) {
                $saledata = PushGarbage($orderInfo['detail'], $res, 'orderid', 'garbageid');
                $res1 = $GarbageOrderModel->MBulkAdd($saledata);
                if ($res1) {
                    //记入日志
                    $log['addtime'] = time();
                    $log['userid'] = $user['userInfo']['id'];
//                    $log['userid'] = 4;
                    $log['orderid'] = $res;
                    $log['type'] = $user['userInfo']['groupid'];
//                    $log['type'] = 2;
                    $res2 = (new OrderLog())->setOrderLog($log);
                    if ($res2) {
                        (new OrderOrder())->setOrderMsg(2,$orderCalculation['ids'],$res);
                        $this->commit();
                        return $res;
                    } else {
                        $this->rollback();
                        return false;
                    }
                } else {
                    $this->rollback();
                    $this->error = "添加失败";
                    return false;
                }
            } else {
                $this->rollback();
                $this->error = "添加失败";
                return false;
            }
        } else {
            $this->error = "添加失败";
            return false;
        }
    }
    //获取单个订单
    public function GetOne()
    {

        $post = Request::post();
        if (array_key_exists("ordernumber", $post) && $post['ordernumber'] != "") {
            $mcont['ordernumber'] = $post['ordernumber'];
            $mcont['del'] = 0;
//            $mcont['status'] = 1;
//            $filed = 'ordernumber,id,isbaozhi,delivery_method,znumber,zweight,status,create_time,end_time,type,price,paytype,pay_time';
            $res = $this->MFind($mcont);

            if ($res) {
                $orderDetailModel = new GarbageOrder();
                $_where = [];
                $_where[] = ['orderid', '=', $res['id']];
                $_where[] = ['del', '=', 0];
                $order_detail = $orderDetailModel->MSelect($_where, 'id desc', 'id,orderid,garbageid ,weighting_num,weighting_method,danweiming,garbageunitid,price');
                if ($res['isbaozhi'] == 0) {
                    $userM=new User();
                    $usercont['id']=$res['user_id'];
                    $user=$userM->MFind($usercont);
                    if($user['groupid']<=3){
                        $detail_res = (new OrderCalculation())->Calculation($order_detail,$res);
                        if ($detail_res['status'] == 0) {
                            $this->error = $detail_res['msg'];
                            return false;
                        }
                        $res['detail'] = $detail_res['data']['detail'];
                    }else{
                        $res['detail'] = $order_detail;
                    }
                } else {
                    $res['detail'] = $order_detail;
                }
                if($res['detail']){
                    foreach ($res['detail'] as $key =>$value){
                        $garwhere['pgalist']=$value['garbageid'];
                        $garbageM=new Garbage();
                        $garbageinfo=$garbageM->MFind($garwhere);
                        $res['detail'][$key]['garbageid']=$garbageinfo['id'];
                        $res['detail'][$key]['garbagename']=$garbageinfo['name'];
                        $res['detail'][$key]['garbagepgalist']=$garbageinfo['pgalist'];
                        $res['detail'][$key]['garbagepga']=$garbageinfo['pga'];
                        $tempres=getGarbagePrice($garbageinfo['pgalist'],$value['danweiming'],new GarbagePrice(),$res);
                        if($tempres['status'] == 0) {
                            $temp['price']=0;
                        }else{
                            $temp['price']=bcmul($value['weighting_num'], $tempres['data']['number'], 2);
                            $res['detail'][$key]['price']= $temp['price'];
                        }
                    }
                }
                $userm=new User();
                //获取上级大订单信息
                $orderorderm=new OrderOrder();
                $oowhere['orderid']=$res['id'];
                $ooinfo=$orderorderm->MFind($oowhere);
                $res['orderlist']=array();
                $res['yewuyuan']="";
                $res['zancundian']="";
                if($ooinfo){
                    $owhere['id']=explode(",",$ooinfo['norderid']);
                    $orderlist=$this->MSelect($owhere);
                    $temp=array();
                    $filed=array('id','name','zhicheng');
                    foreach ($orderlist as $key => $value){
                        $uwhere['id']=$value['user_id'];
                        $userinfo=$userm->MFind($uwhere);
                        array_push($temp,$userinfo);
                        if($key==1){
                            $res['yewuyuan']=$userinfo['zhicheng'];
                        }
                        if($key==2){
                            $res['zancundian']="";
                        }
                    }
                    $res['orderlist']=$temp;
                }
                //获取所属用户信息
                $uswhere['id']=$res['user_id'];
                $userinfo=$userm->MFind($uswhere);
                if($userinfo){
                    $res['userzhicheng']=$userinfo['zhicheng'];
                }else{
                    $res['userzhicheng']="";
                }
                //获取上级用户信息
                $uswhere['id']=$res['uuser'];
                $userinfo=$userm->MFind($uswhere);
                if($userinfo){
                    $res['shangjiname']=$userinfo['zhicheng'];
                }else{
                    $res['shangjiname']="";
                }
                if(!$res['username']){
                    $uswhere['id']=$res['user_id'];
                    $userinfo=$userm->MFind($uswhere);
                    if($userinfo){
                        $res['username']=$userinfo['zhicheng'];
                    }else{
                        $res['username']="未知";
                    }
                }
                return $res;
            } else {
                $this->error = "查询失败";
                return false;
            }
        } else {
            $this->error = "缺少必要参数";
        }
    }

    //订单确认
    public function ConfirmOrder()
    {
        $post = Request::post();
        $user = session($post['token']);
        if (array_key_exists("ordernumber", $post) && $post['ordernumber'] != '') {
            $where = [];
            $where[] = ['ordernumber','=',$post['ordernumber']];
            $old_order = $this->MFind($where,'','id,status,price,znumber,zweight,type,uuser,user_id');
            if($old_order){
                if($user['userInfo']['groupid'] == 1){ //门店
                        if($old_order['status'] == 5){
                            return true;
                        }else if($old_order['status']==4){
                            $updata['status'] = 5;
                        }else{
                            return false;
                        }
                }elseif ($user['userInfo']['groupid'] == 2){ //业务员
                    if($old_order['type']==1){
                        if($old_order['status'] == 3){
//                            return true;
                            $updata['status'] = 5;
                        }else if($old_order['status'] == 1){
                            $updata['status'] = 3;
                        }else if($old_order['status']==5){
                            return true;
                        }else{
                            return false;
                        }
                    }else if($old_order['type']==2){
                        if($old_order['status'] == 2){
                            $updata['status'] = 5;
                        }else if($updata['status'] = 5){
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }elseif ($user['userInfo']['groupid'] == 3) { //暂存点
                    if($old_order['type']==1){
                        if($old_order['status'] ==3){
                            $updata['status'] = 5;
                        }else if($old_order['status'] ==5){
                            return true;
                        }else{
                            return false;
                        }
                    }else if($old_order['type']==2){
                        if($old_order['status'] == 1){
                            $updata['status'] = 4;
                        }else if($old_order['status'] == 2){
                            $updata['status'] = 4;
                        }else if($old_order['status'] == 4){
                            return true;
                        }else{
                            return false;
                        }
                    }else if($old_order['type']==3){
                        if($old_order['status'] == 3){
                            $updata['status'] = 6;
                        }else if($updata['status'] = 6){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }elseif($user['userInfo']['groupid'] == 4){
                    if($old_order['type']==1){
                        if($old_order['status'] ==1){
                            $updata['status'] = 3;
                        }else if($old_order['status'] ==3){
                            $updata['status'] = 5;
                        }else if($old_order['status'] ==5){
                            return true;
                        }else{
                            return false;
                        }
                    }else if($old_order['type']==2){
                        if($old_order['status'] == 1){
                            $updata['status'] = 4;
                        }else if($old_order['status'] == 2){
                            $updata['status'] = 4;
                        }else if($old_order['status'] == 4){
                            return true;
                        }else{
                            return false;
                        }
                    }else if($old_order['type']==3){
                        if($old_order['status'] == 3){
                            $updata['status'] = 5;
                        }else if($updata['status'] = 5){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }elseif($user['userInfo']['groupid'] == 5){

                }elseif($user['userInfo']['groupid'] == 6){

                }
                $updata['end_time'] = time();
                $this->startTrans();
                $_wher = [];
                $_wher[] = ['id','=',$old_order['id']];
                $res = $this->MUpdate($_wher, $updata);
                if ($res) {
                    //加钱  积分

//                $order = $this->MFind($where);
//                    if ($old_order['type'] == 1) {
//                        //门店订单确定
//                        $res1 = $this->ShopConfirm($order);
//                    } else if ($order['type'] == 2) {
//                        //业务员订单
//                        $res1 = $this->SaleConfirm($order);
//                    } else if ($order['type'] == 3) {
//                        //暂存点订单
//                        $res1 = $this->TempConfirm($order);
//                    }
                    $res3=$this->UpJiFen($user,$old_order);
                    if ($res3) {
                        $this->commit();
                        return $res;
                    } else {
                        $this->rollback();
                        $this->error = "确认失败";
                        return false;
                    }
                } else {
                    $this->rollback();
                    $this->error = "确认失败";
                    return false;
                }
            }else{
                BackData(200,"未找到此订单");
            }
        } else {
            $this->error = "缺少参数";
            return false;
        }
    }

    public function  UpJiFen($user,$old_order)
    {
        $userM=new User();
//        if($user['userInfo']['groupid']==1){
//            $where['id']=$old_order['uuser'];
//        }else{
//            $where['id']=$old_order['user_id'];
//        }
        $where['id']=$old_order['user_id'];
        $twouser=$userM->MFind($where);
        if($twouser){
            $_wh = [];
//            $_wh[] =['id','=',$user['userInfo']['id']];
            $_wh[] =['id','=',$old_order['user_id']];
            $old_user = (new User())->MFind($_wh,'');
            if($user['userInfo']['groupid']!=1) {
                $_update['dprice'] = $old_user['dprice'] - $old_order['price'];
            }
//            else{
//                $_update['price'] = $old_user['price'] + $old_order['price'];
//            }
            $_update['price'] = $old_user['price'] + $old_order['price'];
            $_update['zregionnumber'] = $old_user['zregionnumber'] + $old_order['znumber'];
            $_update['zregionweight'] = $old_user['zregionweight'] + $old_order['zweight'];
            $_update['jifen'] = $old_user['jifen'] + intval($old_order['price']);
            $res1 = (new User())->MUpdate($_wh,$_update);
            //写入日志
            $log['addtime'] = time();
            $log['confirmuserid'] = $user['userInfo']['id'];
            $log['userid'] = $old_order['user_id'];
            $log['orderid'] = $old_order['id'];
            $log['status']=1;
            $log['type'] = $user['userInfo']['groupid'];
            $log['jifen']=1;
            $log['jfprice']=$old_order['price'];
            $res2 = (new OrderLog())->setOrderLog($log);
            $log['addtime'] = time();
            $log['confirmuserid'] = $user['userInfo']['id'];
            $log['userid'] = $old_order['user_id'];
            $log['orderid'] = $old_order['id'];
            $log['status']=1;
            $log['jifen']=0;
            $log['type'] = $user['userInfo']['groupid'];
            $log['jfprice']=$old_order['price'];
            $res3 = (new OrderLog())->setOrderLog($log);
            $_wh = [];
            $where['id']=$old_order['uuser'];
            $twouser=$userM->MFind($where);
            $_wh[] =['id','=',$twouser['id']];
            $old_user = (new User())->MFind($_wh,'');
//            if($twouser['groupid']==1) {
                $_update1['dprice'] = $old_user['dprice'] + $old_order['price'];
//            }
//            else{
//                $_update1['dprice'] = $old_user['dprice'] - $old_order['price'];
//            }
//            $_update1['zregionnumber'] = $old_user['zregionnumber'] + $old_order['znumber'];
//            $_update1['zregionweight'] = $old_user['zregionweight'] + $old_order['zweight'];
//            $_update1['jifen'] = $old_user['jifen'] + intval($old_order['price']);
            $res4 = (new User())->MUpdate($_wh,$_update1);
            //写入日志
//            $log1['addtime'] = time();
//            $log1['userid'] = $twouser['id'];
//            $log1['orderid'] = $old_order['id'];
//            $log1['status']=1;
//            $log1['type'] = $twouser['groupid'];
//            $log1['jfprice']=$old_order['price'];
//            $res5 = (new OrderLog())->setOrderLog($log1);
            if($res1&&$res2&&$res3&&$res4){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //门店订单确定
    private function ShopConfirm($data)
    {
        $where['orderid'] = $data['id'];
        $data['status'] = 5;
        $data['confirmtime'] = time();
        $orderlog = new OrderLog();
        $res = $orderlog->MAdd($where, $data);
        return $res;
    }

    //业务员订单
    private function SaleConfirm($data)
    {
        $where['salenumber'] = $data['id'];
        $data['status'] = 4;
        $data['confirmtime'] = time();
        $orderlog = new OrderLog();
        $res = $orderlog->MAdd($where, $data);
        return $res;
    }

    //暂存点订单
    private function TempConfirm($data)
    {
        $where['orderid'] = $data['id'];
        $data['status'] = 6;
        $data['confirmtime'] = time();
        $orderlog = new OrderLog();
        $res = $orderlog->MAdd($where, $data);
        return $res;
    }

    /**
     * 改价
     *[
     *    {
     *      "id":1, //订单详情对应的数据的id
     *      "garbageid":"1,4,7", // 垃圾分类id 都好拼接
     *      "weighting_num":10,  //重量或者数量
     *      "weighting_method":0,  //计重方式  0重量 1个数
     *      "unit_price":10   //单价 没啥用
     *    }
     * ]
     */
    public function UpdatePrice()
    {
        $post = Request::post();
        if (array_key_exists("garbagelist", $post) && !empty($post['garbagelist'])) {
            $garbagelist = $post['garbagelist'];
            $this->startTrans();
            foreach ($garbagelist as $k => $v) {
                $_where = [];
                $_where[] = ['id', '=', $v['id']];
                $_update['weighting_num'] = $v['weighting_num'];
                $res = $this->MUpdate($_where, $_update);
                if (!$res) {
                    $this->rollback();
                    $this->error = "操作失败";
                    return false;
                }
            }
            $this->commit();
            return $res;
        } else {
            $this->error = "操作失败";
            return false;
        }
    }

    /**
     * 取消订单
     */
    public function Cancel()
    {
        $post = Request::post();
        if (array_key_exists("ordernumber", $post) && $post['ordernumber'] != "") {
            $user = session($post["token"]);
            $where['ordernumber'] = $post['ordernumber'];
            $where['user_id'] = $user['userInfo']['id'];
            $where['type'] = $user['userInfo']['groupid'];
            $Order = $this->MFind($where);
            if ($Order) {
                if($Order['isbaozhi']==1){
                    $this->error = "此订单开启了价格保护不能取消";
                    return false;
                }
                else if($user['userInfo']['groupid']==1){
                    if ($Order['status'] == 1) {
                        $data['status'] = 6;
                    } else {
                        $this->error = "此订单已经无法取消";
                        return false;
                    }
                }else{
                    if ($Order['status'] == 2) {
                        if($user['userInfo']['groupid']==2){
                            $data['status'] = 5;
                        }else if($user['userInfo']['groupid']=3){
                            $data['status'] = 7;
                        }
                    } else {
                        $this->error = "此订单已经无法取消";
                        return false;
                    }
                }
                $res = $this->MUpdate($where, $data);
                if ($res) {
                    return $res;
                } else {
                    $this->error = "取消失败";
                    return false;
                }
            } else {
                $this->error = "没有此订单";
                return false;
            }
        } else {
            $this->error = "缺少必要参数";
            return false;
        }
    }

    /**
     * User: Administrator
     * Date: 2019-11-06 09:45
     * name: 查询业务员订单地图
     */
    public function OrderMap()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $seadmin = session($post["token"]);
        $UserModel=new User();
        if($seadmin['userInfo']['groupid']>3){
            $where['status']=1;
            $where['otype']=0;
            $order=$this->MSelect($where);
            if($order){
                $temporder=array();
                foreach ($order as $key => $value){
                    $where1['id']=$value['user_id'];
                    $user=$UserModel->MFind($where1);
                    if($user){
                        $order[$key]['username']=$user['zhicheng'];
                    }
                    if(array_key_exists($order[$key]['username'],$temporder)){
                        $temporder[$order[$key]['username']]['znumber']+=$value['znumber'];
                        $temporder[$order[$key]['username']]['zweight']+=$value['zweight'];
                    }else{
                        $temporder[$order[$key]['username']]=$order[$key];
                    }
                }
                $result=array();
                foreach ($temporder as $key=>$value){
                    array_push($result,$value);
                }
                return $result;
            }else{
                BackData(200,"没有待收取订单");
            }
        }else{

            $uwhere['upid']=$seadmin['userInfo']['id'];
            $userinfo=$UserModel->MSelect($uwhere);
            if($userinfo){
                $userid=$this->getid($userinfo);
                if(!empty($userid)){
                    $where['user_id']=$userid;
                    $where['status']=1;
                    $order=$this->MSelect($where);
                    if($order){
                        foreach ($order as $key => $value){
                            $where1['id']=$value['user_id'];
                            $user=$UserModel->MFind($where1);
                            if($user){
                                $order[$key]['username']=$user['zhicheng'];
                            }
                        }
                        return $order;
                    }else{
                        BackData(200,"没有待收取订单");
                    }
                }else{
                    $this->error="网络错误,请稍后再试";
                    return false;
                }
            }else{
                BackData(200,"请先申请添加管理门店");
            }
        }
    }

    public function getid($data)
    {
        $temp=array();
        foreach ($data as $key => $value){
            if(array_key_exists("id",$value)&&$value['id']!=""){
                array_push($temp,$value['id']);
            }
        }
        return $temp;
    }

    //收支记录查询
    public function GetOrderLog()
    {
        $orderlog=new OrderLog();
        $post=Request::post();
        $userid="";
        (new LimitValidate())->goCheck($post);
        if(array_key_exists("token",$post)&&$post['token']!=""){
            $user=session($post['token']);
            $userid=$user['userInfo']['id'];
        }else if(array_key_exists("userid",$post)&&$post['userid']!=""){
            $userid=$post['userid'];
        }else{
            $this->error="缺少必要参数";
            return false;
        }
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $search=array();
        if(array_key_exists("starttime",$post)&&$post['starttime']!=""){
            $search['starttime']=$post['starttime'];
        }if(array_key_exists("endtime",$post)&&$post['endtime']!=""){
            $search['endtime']=$post['endtime'];
        }

        $res=$orderlog->GetOrderLog($userid,$config,$search,$post);
        if($res){
            return $res;
        }else{
            BackData(200,"未找到数据");
        }
    }
    //获得订单垃圾的总重量
    public function GetWeight($order){
        $garbageorderM=new GarbageOrder();
        $where['orderid']=$order['id'];
        $garbageorderinfo=$garbageorderM->MSelect($where);
        $zweight=0;
        $garbageunitM=new GarbageUnit();
        foreach ($garbageorderinfo as $key =>$value){
            if($value['danweiming']=="kg"){
                $zweight+=$value['weighting_num'];
            }else{
                $garbageunit['id']=$value['garbageunitid'];
                $garbageunit['danweiming']=$value['danweiming'];
                $garbageunitinfo=$garbageunitM->MFind($garbageunit);
                if($garbageunitinfo){
                    $zweight+=$value['weighting_num']*$garbageunitinfo['transweight'];
                }
            }
        }
        return $zweight;
    }
    //修改订单
    public function CUpdate()
    {
        $post=Request::post();
        $data=array();
        if(array_key_exists("type",$post)&&$post['type']!=""&&array_key_exists("id",$post)&&$post['id']!=""&&array_key_exists("token",$post)&&$post['token']!=""){
            $user=session($post['token']);
            if($post['type']=="ruku"&&$user['userInfo']['groupid']==5){
                if(array_key_exists("trayid",$post)&&$post['trayid']!=""){
                    $where['id']=$post['id'];
                    $data['trayid']=$post['trayid'];
                    $data['status']=6;
                    $data['end_time']=time();
                    $this->startTrans();
                    $res=$this->MUpdate($where,$data);
                    $orderinfo=$this->MFind($where);
                    $weight=$this->GetWeight($orderinfo);
                    $traym=new Tray();
                    $traywhere['id']=$post['trayid'];
                    $trwhere=$traym->MFind($traywhere);
                    $ctraydata['znumber']=$trwhere['znumber']+$weight;
                    $ctraydata['cnumber']=$trwhere['cnumber']+$weight;
                    $res2=$traym->MUpdate($trwhere,$ctraydata);
                    $res3=$this->UpJiFen($user,$orderinfo);
                    if($res&&$res2&&$res3){
                        $this->commit();
                        return $res;
                    }else{
                        $this->rollback();
                        $this->error="修改失败";
                        return $res;
                    }
                }else{
                    $this->error="缺少必要参数";
                    return false;
                }
            }else if($post['type']=="shenhe"&&($user['userInfo']['groupid']==4||$user['userInfo']['groupid']==6)){
                $data['kuaijitime']=time();
                if($user['userInfo']['groupid']==4){
                    $data['status']=7;
                }else{
                    $data['status']=2;
                }
                $data['kuaijitime']=time();
                $where['id']=$post['id'];
                $res=$this->MUpdate($where,$data);
                if($res){
                    return $res;
                }else{
                    $this->error="修改失败";
                    return $res;
                }
            }else if($post['type']=="fenpei"&&$user['userInfo']['groupid']>=3&&array_key_exists("upid",$post)&&$post['upid']!=""){
                $where['id']=$post['id'];
                $data['uuser']=$post['upid'];
                $res=$this->MUpdate($where,$data);
                if($res){
                    return $res;
                }else{
                    $this->error="修改失败";
                    return $res;
                }
            }else if($post['type']=="shenheoutorder"&&$user['userInfo']['groupid']==6){
                if(array_key_exists("status",$post)&&($post['status']==4||$post['status']==2)){
                    if($this->OutOrderConfirm($post['id'],$post['status'])){
                        $data['status']=$post['status'];
                        $data['kuaijitime']=time();
                        $where['id']=$post['id'];
                        $res=$this->MUpdate($where,$data);
                        if($res){
                            return $res;
                        }else{
                            $this->error="修改失败";
                            return $res;
                        }
                    }else{
                        $this->error="修改失败";
                        return false;
                    }
                }else{
                    $this->error="修改失败";
                    return false;
                }
            }else{
                $this->error="请确认参数是否正确,或者权限不足";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //会计确定订单或者驳回订单4为驳回2为确定
    public function OutOrderConfirm($id,$status){
        if($status==2){
            //修改订单垃圾关联表中的数据outend 标识为已经处理完了的垃圾订单(托盘删除根据这个决定)
            $garbageorderM=new GarbageOrder();
            $garbageordercont['outorderid']=$id;
            $data['outend']=1;
            $res=$garbageorderM->MUpdate($garbageordercont,$data);
            return $res;
        }else if($status==4){
            //修改订单垃圾关联表中的is_shangjiao 和outorderid 删除其中数据 将这些订单垃圾标识为未出库状态 由库管再次处理
            $garbageorderM=new GarbageOrder();
            $garbageordercont['outorderid']=$id;
            $data['outend']=0;
            $data['is_shangjiao']=0;
            $data['outorderid']=0;
            $res=$garbageorderM->MUpdate($garbageordercont,$data);
            return $res;
        }else{
            return false;
        }
    }
    /**
     * 批量生成时间戳
     */
    public function BulkOrderNumber()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        if($post['groupid']==4&&$post['groupid']==7){
            if(array_key_exists("number",$post)&&is_numeric($post['number'])){
                $time=$post['number'];
                $data=array();
                while($time){
                    $temp=array();
                    $temp['ordernumber']=BulkOrderSn();
                    array_push($data,$temp);
                    $time--;
                }
                $res=$this->MBulkAdd($data);
                if($res){
                    return $res;
                }else{
                    $this->error="添加失败";
                    return false;
                }
            }else{
                $this->error="参数错误";
                return false;
            }
        }else{
            $this->error="权限不足";
            return false;
        }
    }
    /**
     *修改信息
     */
    public function ChangeOne()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $where['id']=$post['id'];
            unset($post['id']);
            $res=$this->MUpdate($where,$post);
            if($res){
                return $res;
            }else{
                $this->error="修改失败";
                return false;
            }
        }else{
            $this->error="参数错误";
            return false;
        }
//        $res=
//        $post['number']=Request::post();
//        if(array_key_exists("number",$post)&&is_numeric($post['number'])){
//            $time=$post['number'];
//            $data=array();
//            while($time){
//                $temp=array();
//                $temp['ordernumber']=BulkOrderSn();
//                array_push($data,$temp);
//                $time--;
//            }
//            $res=$this->MBulkAdd($data);
//            if($res){
//                return $res;
//            }else{
//                $this->error="添加失败";
//                return false;
//            }
//        }else{
//            $this->error="参数错误";
//            return false;
//        }
    }
}