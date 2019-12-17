<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/26
 * Time: 16:01
 * 计算订单价格等一系列方法
 */

namespace app\Models;



use think\facade\Request;

class OrderCalculation extends BaseModel
{
    /**
     * @param $garbagelist  提交订单详细数据 计算价格等 本地仓库删除对应垃圾
     * [
     *    {
     *      "id":订单详情的id
     *      "stock_id":1, //本地库存的id
     *      "garbageid":"1,4,7", // 垃圾分类id 都好拼接
     *      "weighting_num":10,  //重量或者数量
     *      "weighting_method":0,  //计重方式  0重量 1个数
     *      "unit_price":10   //单价 没啥用
     *    }
     * ]
     * @return array
     */
    public function Calculation($garbagelist,$orderinfo="")
    {
        $sum_price = 0;
        $sum_number = 0;
        $sum_weight = 0;
        $garbageOrderList = [];  //订单内容详情表
        $retrospect_model = new Retrospect();
        $sum_price1=0;
        $returnData = ['status' => 0, 'data' => [], 'msg' => ''];
        if(!empty($garbagelist)&&array_key_exists(0,$garbagelist)&&array_key_exists("id",$garbagelist[0])){
            $garbageorderM=new GarbageOrder();
            $garbageordercont['id']=$garbagelist[0]['id'];
            $garbageorder=$garbageorderM->MFind($garbageordercont);
            if($garbageorder){
                $orderM=new Order();
                $ordercont['id']=$garbageorder['orderid'];
                $order=$orderM->MFind($ordercont);
                if($order){
                    if($order['isbaozhi']==1){
                        $temp=array();
                        $temp['znumner']=0;
                        $temp['price']=0;
                        $detail=array();
                        foreach ($garbagelist as $key => $value){
                            $garbageordercont['id']=$value['id'];
                            $garbageorder=$garbageorderM->MFind($garbageordercont);
                            $garbageorder['weighting_num']=$value['weighting_num'];
                            array_push($detail,$garbageorder);
                            $temp['znumner']+=$value['weighting_num'];
                            $temp['price']+=$value['weighting_num']*$garbageorder['bprice'];
                        }
                        $temp['detail']=$detail;
                        $returnData = ['status' => 1, 'data' => $temp];
                        return $returnData;
                    }
                }
            }
        }
        foreach ($garbagelist as $k => $v) {
            //根据数量计算价格
            $unit_price = getGarbagePrice($v['garbageid'],$v['danweiming'], new GarbagePrice(),$orderinfo);
//            print_r($unit_price);
            if(isset($v['id'])){
                $garbageOrderList[$k]['id'] = $v['id'] ? $v['id'] : '';
            }else{
                $garbageOrderList[$k]['id']="";
            }
//            $garbageOrderList[$k]['weighting_num'] = $v['weighting_num'];
            $garbageOrderList[$k]['garbageid'] = $v['garbageid'];
            if(isset($v['stock_id'])){
                $garbageOrderList[$k]['stock_id'] = $v['stock_id'];
            }
            if ($unit_price['status'] == 0) {
                $returnData['msg'] = '获取报价失败';
//                return $returnData;
                $sum_price1 = 0;
                $returnData['status']=0;
                $returnData['price']=$sum_price1;
                return $returnData;
            } else {
                $garbageOrderList[$k]['danweiming']=$unit_price['data']['danweiming'];
                $garbageOrderList[$k]['garbageunitid']=$unit_price['data']['garbageunitid'];
//                $garbageOrderList[$k]['trans']=$unit_price['data']['trans'];
                $garbageOrderList[$k]['bprice']=$unit_price['data']['bnumber'];
                $garbageOrderList[$k]['weighting_num'] = $v['weighting_num']*$unit_price['data']['trans'];
                $sum_price1 = bcmul($v['weighting_num'], $unit_price['data']['number'], 1);
                $sum_number += (int)$v['weighting_num']*$unit_price['data']['trans'];
            }
            $garbageOrderList[$k]['price']=$sum_price1;
            $sum_price += $sum_price1;
            //本地仓库移除
            if(isset($v['stock_id'])) {
                $retrospect_w = [];
                $retrospect_w[] = ['id', '=', $v['stock_id']];
                $retrospect_w[] = ['del', '=', 0];
                $update_data = ['del' => 1];
                $retrospect_model->MUpdate($retrospect_w, $update_data);
            }
        }
        $returnData['data']['znumber'] = $sum_number;
        $returnData['data']['price'] = $sum_price;
        $returnData['data']['detail'] = $garbageOrderList;
        $returnData['status'] = 1;
        return $returnData;
    }

    /**
     * @param $order 根据订单搜索订单详情数据
     * @return array
     */
    public function getOrderDetail($orderlist = [])
    {
        $post=Request::post();
        $return = array('status' => 0, 'data' => '', 'msg' => '查询成功');
        if (empty($orderlist)) {
            $return['status'] = 1;
            $return['data'] = $orderlist;
            return $return;
        }
        $order_detail = [];
        $detail_res = [];
        $orderDetailModel = new GarbageOrder();
        foreach ($orderlist as $k => $v) {

            $zprice=0;
            $_where = [];
            $_where[] = ['orderid', '=', $v['id']];
            if(array_key_exists("otype",$post)&&$post['otype']==1){
                $_where = [];
                $_where[] = ['orderid', '=', $v['id']];
            }
            $_where[] = ['del', '=', 0];
            //主管自己特有的
            if(array_key_exists('outorder',$post)){
                if(array_key_exists("status",$post)){
                    if($post['status']==6){
                        $_where[]=['is_shangjiao','=','0'];
                    }
                }
            }
            //主管特有结束
            $order_detail = $orderDetailModel->MSelect($_where, 'id desc');
//            print_r($_where);
//            print_r($order_detail);
//            exit;
            if ($v['isbaozhi'] == 0) {
                $detail_res = $this->Calculation($order_detail,$v);
                if ($detail_res['status'] == 0) {
                    return $detail_res;
                }
                $orderlist[$k]['detail'] = $detail_res['data']['detail'];
            } else {
                $orderlist[$k]['detail'] = $order_detail;
            }
            if($orderlist[$k]['detail']){
                foreach ($orderlist[$k]['detail'] as $key =>$value){
                    $zprice+=$value['price'];
                    $garwhere['pgalist']=$value['garbageid'];
                    $garmodel=new Garbage();
                    $garbageinfo=$garmodel->MFind($garwhere);
                    if($garbageinfo){
                        $orderlist[$k]['detail'][$key]['garbagename']=$garbageinfo['name'];
                    }else{
                        $orderlist[$k]['detail'][$key]['garbagename']="";
                    }
                }
            }
            $userm=new User();
            $uwhere['id']=$v['user_id'];
            $userinfo=$userm->MFind($uwhere);
            $orderlist[$k]['username']=$userinfo['zhicheng'];
            $orderlist[$k]['price']=$zprice;
        }
        $return['status'] = 1;
        $return['data'] = $orderlist;
        return $return;
    }

    /**
     * 小订单转换大订单垃圾去重
     * [
     *    {
     *      "id":小订单id
     *      "weighting_num":10,  //重量或者数量
     *      "weighting_method":0,  //计重方式  0重量 1个数
     *      "unit_price":10   //单价 没啥用
     *    }
     * ]
     * @param array $orderlist
     * @return array
     */
    public function filterAllOrder($orderlist = [])
    {
        $return = array('status' => 0, 'data' => [], 'msg' => '');
        $newArr = [];
//        $order_model = new Order();
        $order_detail_model = new GarbageOrder();
        $sum_data = []; //总数量
        $sum_data['znumber'] = 0;
        $sum_data['zweight'] = 0;
        $sum_data['price'] = 0;
        $ids = [];
        foreach ($orderlist as $k => $v) {
//            $_where = [];
//            $_where[] = ['id', '=', $v['id']];
//            $_where[] = ['del', '=', 0];
//            $orderArr = $order_model->MFind($_where, '', 'znumber,zweight,price,id');
//            if($v['weighting_method'] == 1){ //个数
//                $sum_data['znumber'] += $v['weighting_num'];
//            }elseif ($v['weighting_method'] == 0){ //重量
//                $sum_data['zweight'] += $v['weighting_num'];
//                $sum_data['price'] += $orderArr['price'];
//            }

//            $sum_data['price'] += $orderArr['price'];
            $ids[] = $v['id'];
        }
        $_wh = [];
        $_wh[] = ['orderid', 'in', $ids];
        $_wh[] = ['del', '=', 0];

        $orderDetailArr = $order_detail_model->MSelect($_wh);
        foreach ($orderDetailArr as $kk => $vv) {
            $newArr[$vv['garbageid']][] = $vv;
        }
        $temparr = [];
        foreach ($newArr as $key => $val) {
            $temp['garbageid'] = $key;
            $temp['weighting_num'] = 0;
            $temp['price'] = 0;
            foreach ($val as $k => $v) {
                $unit_price = getGarbagePrice($key,$v['danweiming'] ,new GarbagePrice());
                $temp['weighting_method'] = $v['weighting_method'];
                $temp['weighting_num'] = $v['weighting_num'];
                if ($unit_price['status'] == 0) {
//                    $returnData['msg'] = '获取报价失败';
                    $returnData['msg'] = '获取报价失败';
                    $sum_price1 = 0;
                    $returnData['status']=0;
                    return $returnData;
                } else {
                    if ($v['weighting_num'] != ""&&$v['weighting_num'] != 0) {  //数量
                        $sum_price1 = bcmul($v['weighting_num'], $unit_price['data']['number'], 1);
                        $temp['price'] = $sum_price1;
                        $temp['trans']=$unit_price['data']['trans'];
                        $temp['danweiming']=$v['danweiming'];
                        $temp['garbageunitid']=$v['garbageunitid'];
                        $sum_data['znumber'] += $v['weighting_num'];
                    }
                }
                $sum_data['price'] += $temp['price'];
                array_push($temparr,$temp);
            }
        }
        $sum_data['detail'] = $temparr;
        $return['status'] = 1;
        $return['data'] = $sum_data;
        $return['ids'] = $ids; //小订单的id数组
        return $return;
    }

}