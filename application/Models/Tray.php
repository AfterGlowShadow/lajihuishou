<?php
namespace app\Models;

use app\validate\LimitValidate;
use app\validate\TokenValidate;
use think\facade\Request;
use app\Validate\Tray as TrayValidate;

class Tray extends BaseModel
{
    protected $table="lj_tray";
    //添加一个管理员
    public function AddOne()
    {
        $post=Request::post();
        (new TrayValidate())->goCheck($post);
 //       if(array_key_exists("number",$post)&&$post['number']!=""){
            $mcont['number']=$post['number'];
            $fadmin=$this->MFind($mcont);
            if($fadmin){
                $this->error="此编号已经存在";
                return false;
            }else{
                $post['token']=md5(time());
                $res=$this->MAdd($post);
                if($res){
                    return $res;
                }else{
                    $this->error="添加失败";
                    return false;
                }
            }
   //     }else{
    //        $this->error="托盘编号不能为空";
     //       return false;
      //  }
    }
    //修改管理员信息
    public function ChangeOne()
    {
        $post=Request::post();
//        if(array_key_exists("number",$post)&&$post['number']!="") {
        (new TrayValidate())->goCheck($post);
            $where['token']=$post['token'];
            $tray=$this->MFind($where);
            $mcont['number'] = $post['number'];
            $fadmin = $this->MFind($mcont);
            if ($fadmin) {
                if ($fadmin['token'] != $post['token']) {
                    $this->error = "此编号已经存在";
                    return false;
                } else {
//                    if($post['znumber']>=$tray['cnumber']&&$post['zweight']>=$tray['cweight']) {
                        $acont['token'] = $post['token'];
                        $res = $this->MUpdate($acont, $post);
                        if ($res) {
                            return $res;
                        } else {
                            $this->error = "修改失败";
                            return false;
                        }
//                    }else{
//                        $this->error="修改重量或个数小于现有重量或个数";
//                        return false;
//                    }
                }
            } else {
//                if($post['znumber']>=$tray['cnumber']&&$post['zweight']>=$tray['cweight']){
                    $acont['token'] = $post['token'];
                    $res = $this->MUpdate($acont, $post);
                    if ($res) {
                        return $res;
                    } else {
                        $this->error = "修改失败";
                        return false;
                    }
//                }else{
//                    $this->error="修改重量或个数小于现有重量或个数";
//                    return false;
//                }
            }
       # }else{
       #     $this->error="托盘编号不能为空";
       #     return false;
       # }
    }
    //分页查询信息
    public function GetList()
    {
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        $config['page']=$post['page'];
        $where['status']=1;
        $where['del']=0;
        $config['list_rows']=$post['list_rows'];
        $res=$this->MLimitSelect($where,$config,"id desc");
        if($res){
            $orderM=new Order();
            foreach ($res['data'] as $key => $value){
                $trayordercont['trayid']=$value['id'];
                $orderlist=$orderM->MSelect($trayordercont);
                $res['data'][$key]['weighting']=$this->WeightByOrder($orderlist);
            }
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //根据订单列表获得 订单中所有一级类的垃圾的重量
    public function WeightByOrder($order){
        $garbageorderm=new GarbageOrder();
        $garageunitM=new GarbageUnit();
        $weight=0;
        foreach ($order as $key => $value){
            $garbageorder['orderid']=$value['id'];
            $garbageorder['is_shangjiao']=0;
            $garabageorderinfo=$garbageorderm->MSelect($garbageorder);
            foreach ($garabageorderinfo as $k => $v){
                if($v['danweiming']=='kg'){
                    $weight+=$v['weighting_num'];
                }else{
                    $garbageunitcont['id']=$v['garbageunitid'];
                    $garbageunitcont['danweiming']=$v['danweiming'];
                    $garbageunitinfo=$garageunitM->MFind($garbageunitcont);
                    $weight+=$garbageunitinfo['transweight']*$v['weighting_num'];
                }
            }
        }
        return $weight;
    }
    //获取单个垃圾
    public function GetOne()
    {
        $post = Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token'] = $post['token'];
        $mcont['del'] = 0;
        $mcont['status'] = 1;
        $res = $this->MFind($mcont);
        if ($res) {
            //获取垃圾订单详情
           $temp= $this->TrayOrder($res);
           $res['number']=$temp['number'];
//           $res['weigth']=$temp['weigth'];
           $res['orderlist']=$temp['orderlist'];
           $backdata="";
           if(array_key_exists("child",$post)&&$post['child']){
               foreach ($temp['orderlist'] as $key =>$value){
                   if($value['id']==$post['child']){
                       $backdata=$value['child'];
                   }
               }
           }else{
               $backdata=$temp['orderlist'];
           }
            $res['orderlist']=$backdata;
           if($res!=""){
               return $res;
           }else{
                BackData(200,"没有数据");
           }
        } else {
            $this->error = "查询失败";
            return false;
        }
    }
    //获取垃圾订单详情
    public function TrayOrder($data)
    {
        $number=0;
        $weigth=0;
        $OrderModel=new Order();
        $where['status']=6;
        $where['del']=0;
        $where['trayid']=$data['id'];
        $orderlist=$OrderModel->MSelect($where,'id desc');
        $gargagep=new GarbageOrder();
        $garbagelist=array();
        foreach($orderlist as $key1 => $value1){
            $gpwhere['orderid']=$value1['id'];
            $gpwhere['del']=0;
            $gpwhere['is_shangjiao']=0;
            $gplist=$gargagep->MSelect($gpwhere);
            //根据garbageid与is_上交去掉已将上交的垃圾
            $garbage=new Garbage();
            foreach ($gplist as $key => $value){
                $gwhere['id']=explode(",",$value['garbageid']);
                $gwhere['id']=$gwhere['id'][0];
                $gwhere['del']=0;
                $garbageinfo=$garbage->MFind($gwhere);
                if($garbageinfo['pga']!=0){
                    $gwhere1=array();
                    $gwhere1['id']=$garbageinfo['pga'];
                    $garbageinfo=$garbage->MFind($gwhere1);
                }
                $garbageunitM=new GarbageUnit();
                $garbagecont['danweiming']=$value['danweiming'];
                $garbagecont['garbageid']=$gwhere['id'];
                $garbageunitinfo=$garbageunitM->MFind($garbagecont);
                $garbagepga="";
                $temp1=1;
                if($garbageunitinfo['danweiming']!="kg"){
                    $temp1=0;
                }
                if(array_key_exists($gwhere['id'],$garbagelist)){
                    $garbagelist[$gwhere['id']]['id']=$garbageinfo['id'];
                    $garbagelist[$gwhere['id']]['pga']=$garbageinfo['pga'];
                    $garbagelist[$gwhere['id']]['name']=$garbageinfo['name'];
                    if($temp1){
                        $garbagelist[$gwhere['id']]['number']+=$value['weighting_num'];
                        $number+=$value['weighting_num'];
                    }else{
                        $garbagelist[$gwhere['id']]['number']+=$value['weighting_num']*$garbageunitinfo['transweight'];
                        $number+=$value['weighting_num']*$garbageunitinfo['transweight'];
                    }
                }else{
                    if($garbageinfo){
                        $garbagelist[$gwhere['id']]['id']=$garbageinfo['id'];
                        $garbagelist[$gwhere['id']]['pga']=$garbageinfo['pga'];
                        $garbagelist[$gwhere['id']]['name']=$garbageinfo['name'];
                    }else{
                        $garbagelist[$gwhere['id']]['name']="";
                    }
                    if($temp1){
                        $garbagelist[$gwhere['id']]['number']=$value['weighting_num'];
                        $number=$value['weighting_num'];
                    }else{
                        $garbagelist[$gwhere['id']]['number']=$value['weighting_num']*$garbageunitinfo['transweight'];
                        $number=$value['weighting_num']*$garbageunitinfo['transweight'];
                    }
                }
            }
        }
        $temp['orderlist']=$garbagelist;
        $temp['number']=$number;
        return $temp;
    }
    //获取垃圾订单详情
    public function TrayOrderList()
    {
        $post = Request::post();
        (new TokenValidate())->goCheck($post);
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $mcont['token'] = $post['token'];
            $mcont['del'] = 0;
            $mcont['status'] = 1;
            $number=0;
            $res = $this->MFind($mcont);
            $OrderModel=new Order();
            $where['status']=6;
            $where['del']=0;
            $weigth=0;
            $garbagelist=array();
//            $where['trayid']=$res['id'];
            $orderlist=$OrderModel->MSelect($where,"id desc");
            $gargagep=new GarbageOrder();
            $garbageunitM=new GarbageUnit();
            $orderlist1=array();
            $garbage=new Garbage();
            $post['id']=explode(",",$post['id']);
            $post['id']=$post['id'][0];
            $gwhere1['id']=$post['id'];
            $garbageinfo1=$garbage->MFind($gwhere1);
            $res['garbage']=$garbageinfo1;
            $temporder=array();
            $temporder['garbage']=array();
            foreach($orderlist as $key1 => $value1){
                $gpwhere['orderid']=$value1['id'];
                $gpwhere['del']=0;
                $gpwhere['is_shangjiao']=0;
                $gplist=$gargagep->MSelect($gpwhere);
                if($gplist){
                    //根据garbageid与is_上交去掉已将上交的垃圾
                    $temporder['ordernumne']=$value1['ordernumber'];
                    $temporder['create_time']=$value1['create_time'];
                    $temporder['end_time']=$value1['end_time'];
                    $temporder['goid']=array();
                    $temporder['garbage']=array();
                    $temp=array();
                    $parbagenumber=array();
                    $parbagenumber['weighting_num']=0;
                    foreach ($gplist as $key => $value){
                        if(!strstr($value['garbageid'],$post['id'])){
                            continue;
                        }
                        $temp1=array();
                        $gwhere['id']=explode(",",$value['garbageid']);
                        $gwhere1['id']=$gwhere['id'][0];
                        $garbageinfo=$garbage->MFind($gwhere1);
                        $gwhere['id']=$gwhere['id'][(count($gwhere['id'])-2)];
                        if($gwhere['id']==$post['id']){
                            $gwhere['del']=0;
                            $garbageinfo=$garbage->MFind($gwhere);
                            if(array_key_exists($gwhere['id'],$garbagelist)){
                                $temp1['id']=$garbageinfo['id'];
                                $temp1['goid']=$value['id'];
                                array_push( $temporder['goid'],$value['id']);
                                $temp1['pga']=$garbageinfo['pga'];
                                $temp1['name']=$garbageinfo['name'];
                                $temp1['number']+=$value['weighting_num'];
                                if($value['danweiming']=='kg'){
                                    $number+=$value['weighting_num'];
                                    $parbagenumber['weighting_num']+=$value['weighting_num'];
                                }else{
                                    $garbageunitcont['id']=$value['garbageunitid'];
                                    $garbageunitinfo=$garbageunitM->MFind($garbageunitcont);
                                    if($garbageunitinfo){
                                        $number+=$value['weighting_num']*$garbageunitinfo['transweight'];
                                        $parbagenumber['weighting_num']+=$value['weighting_num']*$garbageunitinfo['transweight'];
                                    }
                                }
                            }else{
                                if($garbageinfo){
                                    $temp1['id']=$garbageinfo['id'];
                                    $temp1['pga']=$garbageinfo['pga'];
                                    array_push( $temporder['goid'],$value['id']);
                                    $temp1['goid']=$value['id'];
                                    $temp1['name']=$garbageinfo['name'];
                                    $parbagenumber['name']=$garbageinfo['name'];
                                }else{
                                    $temp1['name']="";
                                }
                                $temp1['number']=$value['weighting_num'];
                                if($value['danweiming']=='kg'){
                                    $number+=$value['weighting_num'];
                                    $parbagenumber['weighting_num']+=$value['weighting_num'];
                                }else{
                                    $garbageunitcont['id']=$value['garbageunitid'];
                                    $garbageunitinfo=$garbageunitM->MFind($garbageunitcont);
                                    if($garbageunitinfo){
                                        $number+=$value['weighting_num']*$garbageunitinfo['transweight'];
                                        $parbagenumber['weighting_num']+=$value['weighting_num']*$garbageunitinfo['transweight'];
                                    }
                                }
                            }
                        }
                        if(!empty($temp1)){
                            array_push($temp,$temp1);
                        }
                    }
                    $temporder['garbage']=$temp;
                    $temporder['garbageall']=$parbagenumber;
                    if(!empty($temporder['garbage'])){
                        array_push($orderlist1,$temporder);
                    }
                }
            }
            if($orderlist1){
                $res['orderlist']=$orderlist1;
                $res['allnumner']=$number;
                $res['allweight']=$weigth;
                return $res;
            }else{
                BackData(200,"没有数据","");
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //获取垃圾订单详情
    public function OrderList()
    {
        $post = Request::post();
        (new TokenValidate())->goCheck($post);
        if(array_key_exists("id",$post)&&$post['id']!=""){
//            $mcont['token'] = $post['token'];
            $mcont['del'] = 0;
            $mcont['status'] = 1;
            $number=0;
            $res = $this->MSelect($mcont);
            foreach ($res as $key => $value){
                $OrderModel=new Order();
                $where['status']=6;
                $where['del']=0;
                $where['trayid']=$value['id'];
                $weigth=0;
                $garbagelist=array();
//            $where['trayid']=$res['id'];
                $orderlist=$OrderModel->MSelect($where,"id desc");
                $gargagep=new GarbageOrder();
                $orderlist1=array();
                $garbage=new Garbage();
                $post['id']=explode(",",$post['id']);
                $post['id']=$post['id'][0];
                $gwhere1['id']=$post['id'];
                $garbageinfo1=$garbage->MFind($gwhere1);
                $res[$key]['garbage']=$garbageinfo1;
                $temporder=array();
                $temporder['garbage']=array();
                foreach($orderlist as $key1 => $value1){
                    $gpwhere['orderid']=$value1['id'];
                    $gpwhere['del']=0;
                    $gpwhere['is_shangjiao']=0;
                    $gplist=$gargagep->MSelect($gpwhere);
                    if($gplist){
                        //根据garbageid与is_上交去掉已将上交的垃圾
                        $temporder['ordernumne']=$value1['ordernumber'];
                        $temporder['create_time']=$value1['create_time'];
                        $temporder['end_time']=$value1['end_time'];
                        $temporder['goid']=array();
                        $temporder['garbage']=array();
                        $temp=array();
                        $parbagenumber=array();
                        $parbagenumber['weighting_num']=0;
                        foreach ($gplist as $k => $v){
                            $temp1=array();
                            $gwhere['id']=explode(",",$v['garbageid']);
                            $gwhere1['id']=$gwhere['id'][0];
                            $garbageinfo=$garbage->MFind($gwhere1);
                            $garbageinfo=$garbage->MFind($gwhere1);
                            $gwhere['id']=$gwhere['id'][(count($gwhere['id'])-2)];
                            if($gwhere['id']==$post['id']){

                                $gwhere['del']=0;
                                $garbageinfo=$garbage->MFind($gwhere);
                                $garbageinfo=$garbage->MFind($gwhere);
                                if(array_key_exists($gwhere['id'],$garbagelist)){
                                    $temp1['id']=$garbageinfo['id'];
                                    $temp1['goid']=$v['id'];
                                    array_push( $temporder['goid'],$v['id']);
                                    $temp1['pga']=$garbageinfo['pga'];
                                    $temp1['name']=$garbageinfo['name'];
                                    $temp1['number']+=$v['weighting_num'];
                                    $number+=$v['weighting_num'];
                                    $parbagenumber['weighting_num']+=$v['weighting_num'];
                                }else{
                                    if($garbageinfo){
                                        $temp1['id']=$garbageinfo['id'];
                                        $temp1['pga']=$garbageinfo['pga'];
                                        array_push( $temporder['goid'],$v['id']);
                                        $temp1['goid']=$value['id'];
                                        $temp1['name']=$garbageinfo['name'];
                                        $parbagenumber['name']=$garbageinfo['name'];
                                    }else{
                                        $temp1['name']="";
                                    }
                                    $temp1['number']=$v['weighting_num'];
                                    $number+=$v['weighting_num'];
                                    $parbagenumber['weighting_num']+=$v['weighting_num'];
                                }
                            }
                            if(!empty($temp1)){
                                array_push($temp,$temp1);
                            }
                        }
                        $temporder['garbage']=$temp;
                        $temporder['garbageall']=$parbagenumber;
                        if(!empty($temporder['garbage'])){
                            array_push($orderlist1,$temporder);
                        }
                    }
                }
                if($orderlist1){
                    $res[$key]['orderlist']=$orderlist1;
                    $res[$key]['allnumner']=$number;
                    $res[$key]['allweight']=$weigth;
                }
            }
//            if($orderlist1){
//                $res[$key]['orderlist']=$orderlist1;
//                $res[$key]['allnumner']=$number;
//                $res[$key]['allweight']=$weigth;
//            }else{
//                BackData(200,"没有数据","");
//            }
            return $res;
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    public function a_array_unique($array)
    {
        $out = array();
        foreach($array as $key => $value){
            if (!in_array($value, $out)&&$value!=0){
                array_push($out,$value);
            }
        }
        return $out;
    }
    //订单出库
    public function OrderAdd()
    {

        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $user=session($post['token']);
        if(array_key_exists("ids",$post)){
            $post['ids']=json_decode($post['ids']);
            if(!is_array($post['ids'])){
                $this->error="参数错误";
                return false;
            }
            $garbageoM = new GarbageOrder();
            $where['id'] = $post['ids'];
            $where['is_shangjiao'] = 0;
            $data['is_shangjiao'] = 1;
            $this->startTrans();
            $gres = $garbageoM->MSelect($where);
            if($gres){
                $sum_price1 = 0;
                $sum_weight = 0;
                $sum_number = 0;
                foreach ($gres as $key => $value) {
                    $unit_price = getGarbagePrice($value['garbageid'],$value['danweiming'], new GarbagePrice());
                    if ($unit_price['status'] == 0) {
                        $returnData['msg'] = '获取报价失败';
                        $sum_price1 = 0;
                        $returnData['status'] = 0;
                        $returnData['price'] = $sum_price1;
                        BackData("400","获取报价失败");
                    } else {
                        if ($value['weighting_num'] != ""&&$value['weighting_num'] != 0) {  //重量
                            $sum_price1 += bcmul($value['weighting_num'], $unit_price['data']['number'], 1);
                            $sum_number += (int)$value['weighting_num'];
                        }
                    }
                }
                //生成id
                $data1['isbaozhi'] = 0;
                $data1['price'] = $sum_price1;
                $data1['znumber'] = $sum_number;
                $data1['type'] = 6;
                $data1['status'] = 3;
                $data1['create_time'] = time();
                $data1['ordernumber'] = createOrderSn();
                $orderm = new Order();
                $ocont['id']=$post['id'];
                $res = $orderm->MUpdate($ocont,$data1);
                if ($res) {
                    $cgarbageo['outorderid'] = $post['id'];
                    $res2 = $garbageoM->MUpdate($where, $cgarbageo);
                    $gres1 = $garbageoM->MUpdate($where, $data);
                    $twhere['id']=$post['id'];
//                    $trayinfo=$this->MFind($twhere);
//                    $rtwhere['id']=$trayinfo['id'];
//                    $rtwhere['cnumber']=$trayinfo['cnumber'];
//                    $rtwhere['cweight']=$trayinfo['cweight'];
//                    $traydata['cnumber']=$trayinfo['cnumber']-$sum_number;
//                    if($traydata['cnumber']>=0){
//                        $res3=$this->MUpdate($rtwhere,$traydata);
                        if ($res2&&$gres1) {
                            $this->commit();
                            return $res2;
                        } else {
                            $this->rollback();
                            $this->error = "添加失败";
                            return false;
                        }
//                    }else{
//                        $this->rollback();
//                        $this->error = "添加失败";
//                        return false;
//                    }
                } else {
                    $this->rollback();
                    $this->error = "添加失败";
                    return false;
                }
            }else {
                $this->error = "参数错误";
                return false;
            }
        }else{
            $this->error="参数错误";
            return false;
        }
    }
//    public function OrderAdd()
//    {
//
//        $post=Request::post();
//        (new TokenValidate())->goCheck($post);
//        $user=session($post['token']);
//        if(array_key_exists("ids",$post)){
//            $post['ids']=json_decode($post['ids']);
//            if(!is_array($post['ids'])){
//                $this->error="参数错误";
//                return false;
//            }
//            $garbageoM = new GarbageOrder();
//            $where['id'] = $post['ids'];
//            $where['is_shangjiao'] = 0;
//            $data['is_shangjiao'] = 1;
//            $this->startTrans();
//            $gres = $garbageoM->MSelect($where);
//            if($gres){
//                $sum_price1 = 0;
//                $sum_weight = 0;
//                $sum_number = 0;
//                foreach ($gres as $key => $value) {
//                    $unit_price = getGarbagePrice($value['garbageid'],$value['danweiming'], new GarbagePrice());
//                    if ($unit_price['status'] == 0) {
//                        $returnData['msg'] = '获取报价失败';
//                        $sum_price1 = 0;
//                        $returnData['status'] = 0;
//                        $returnData['price'] = $sum_price1;
//                        return $returnData;
//                    } else {
//                        if ($value['weighting_num'] != ""&&$value['weighting_num'] != 0) {  //重量
//                            $sum_price1 = bcmul($value['weighting_num'], $unit_price['data']['number'], 1);
//                            $sum_number += (int)$value['weighting_num'];
//                        }
//                    }
//
//                }
//                //生成id
//                $data1['isbaozhi'] = 0;
//                $data1['price'] = $sum_price1;
//                $data1['znumber'] = $sum_number;
//                $data1['type'] = 6;
//                $data1['user_id'] = $user['userInfo']['id'];
//                $data1['username'] = $user['userInfo']['zhicheng'];
//                $data1['status'] = 2;
//                $data1['create_time'] = time();
//                $data1['uuser'] = $user['userInfo']['upid'];
//                $data1['latitude'] = $user['userInfo']['latitude'];
//                $data1['longitude'] = $user['userInfo']['longitude'];
//                $data1['otype']=1;
//                $data1['ordernumber'] = createOrderSn();
//                $orderm = new Order();
//                $ocont['ordernumber']=$post['orderid'];
//                $res = $orderm->MUpdate($ocont,$data1);
//                if ($res) {
//                    $cgarbageo['outorderid'] = $res;
//                    $res2 = $garbageoM->MUpdate($where, $cgarbageo);
//                    $gres1 = $garbageoM->MUpdate($where, $data);
//                    $twhere['id']=$post['id'];
//                    $trayinfo=$this->MFind($twhere);
//                    $rtwhere['id']=$trayinfo['id'];
//                    $rtwhere['cnumber']=$trayinfo['cnumber'];
//                    $rtwhere['cweight']=$trayinfo['cweight'];
//                    $traydata['cnumber']=$trayinfo['cnumber']-$sum_number;
//                    if($traydata['cnumber']>=0){
//                        $res3=$this->MUpdate($rtwhere,$traydata);
//                        if ($res2&&$gres1&&$res3) {
//                            $this->commit();
//                            return $res2;
//                        } else {
//                            $this->rollback();
//                            $this->error = "添加失败";
//                            return false;
//                        }
//                    }else{
//                        $this->rollback();
//                        $this->error = "添加失败";
//                        return false;
//                    }
//                } else {
//                    $this->rollback();
//                    $this->error = "添加失败";
//                    return false;
//                }
//            }else {
//                $this->error = "参数错误";
//                return false;
//            }
//        }else{
//            $this->error="参数错误";
//            return false;
//        }
//    }
    //删除垃圾
    public function DeleteOne(){
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token']=$post['token'];
        $tres=$this->MFind($mcont);
        if($tres&&$tres['del']==0){
            if($this->DeleteValidate($tres['id'])){
                $res=$this->MDelete($mcont);
                if($res){
                    return $res;
                }else{
                    $this->error="删除失败";
                    return false;
                }
            }else{
                $this->error="此仓库中有垃圾不能删除";
                return false;
            }
        }else{
            $this->error="此托盘不存在";
            return false;
        }
    }
    //判断能不能删除
    public function DeleteValidate($trayid){
        $orderM=new Order();
        $garbageorderM=new GarbageOrder();
        $ocont['trayid']=$trayid;
        $orderlist=$orderM->MSelect($ocont);
        foreach($orderlist as $key => $value){
            $garbagecont['orderid']=$value['id'];
            $garbagecont['is_shangjiao']=0;
            $garbagecont['outend']=0;
            $garbageinfo=$garbageorderM->MFind($garbagecont);
            if($garbageinfo){
                return false;
            }
        }
        return true;
    }
    //主管出库操作
//    public function zhuOutOrder($post){
//        $user=session($post['token']);
//        $data['is_shangjiao'] = 1;
//        $garbageoM = new GarbageOrder();
//        $where['id'] = $post['ids'];
//        $where['is_shangjiao'] = 0;
//        $sum_price1 = 0;
//        $sum_weight = 0;
//        $sum_number = 0;
//        foreach ($post['gres'] as $key => $value) {
//            $unit_price = getGarbagePrice($value['garbageid'],$value['danweiming'], new GarbagePrice());
//            if ($unit_price['status'] == 0) {
//                return false;
//            } else {
//                if ($value['weighting_num'] != ""&&$value['weighting_num'] != 0) {  //重量
//                    $sum_price1 = bcmul($value['weighting_num'], $unit_price['data']['number'], 1);
//                    $sum_number += (int)$value['weighting_num'];
//                }
//            }
//        }
//        //生成id
//        $cgarbageo['outorderid'] = $res;
//        $res2 = $garbageoM->MUpdate($where, $cgarbageo);
//        $gres1 = $garbageoM->MUpdate($where, $data);
//        $twhere['id']=$post['id'];
//        $trayinfo=$this->MFind($twhere);
//        $rtwhere['id']=$post['id'];
//        $rtwhere['cnumber']=$trayinfo['cnumber'];
//        $rtwhere['cweight']=$trayinfo['cweight'];
//        $traydata['cnumber']=$trayinfo['cnumber']-$sum_number;
//        if($traydata['cnumber']>=0){
//            $res3=$this->MUpdate($rtwhere,$traydata);
//            if ($res2&&$gres1&&$res3) {
//                return true;
//            } else {
//                return false;
//            }
//        }else{
//            return false;
//        }
//    }
//    public function CreateOrder($post){
//        $user=session($post['token']);
//        $data['is_shangjiao'] = 1;
//        $sum_price1 = 0;
//        $sum_number = 0;
//        foreach ($post['gres'] as $key => $value) {
//            $unit_price = getGarbagePrice($value['garbageid'],$value['danweiming'], new GarbagePrice());
//            if ($unit_price['status'] == 0) {
//                return false;
//            } else {
//                if ($value['weighting_num'] != ""&&$value['weighting_num'] != 0) {  //重量
//                    $sum_price1 = bcmul($value['weighting_num'], $unit_price['data']['number'], 1);
//                    $sum_number += (int)$value['weighting_num'];
//                }
//            }
//        }
//        //生成id
//        $data1['isbaozhi'] = 0;
//        $data1['price'] = $sum_price1;
//        $data1['znumber'] = $sum_number;
//        $data1['type'] = $user['userInfo']['groupid'];
//        $data1['user_id'] = $user['userInfo']['id'];
//        $data1['username'] = $user['userInfo']['zhicheng'];
//        $data1['status'] = 1;
//        $data1['create_time'] = time();
//        $data1['uuser'] = $user['userInfo']['upid'];
//        $data1['latitude'] = $user['userInfo']['latitude'];
//        $data1['longitude'] = $user['userInfo']['longitude'];
//        $data1['otype']=1;
//        $data1  ['ordernumber'] = createOrderSn();
//        $orderm = new Order();
//        print_r($data1);
//        print_r($post);
//        exit;
//        $res = $orderm->MAdd($data1);
//        return $res;
//    }
}