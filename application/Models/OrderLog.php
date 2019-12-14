<?php

namespace app\Models;
class OrderLog extends BaseModel
{
    protected $table="lj_orderlog";

    public function setOrderLog($log){
        $res = $this->MAdd($log);
        if ($res) {
            return $res;
        } else {
            $this->error = "添加失败";
            return false;
        }
    }
    //查询收支明细
    public function GetOrderLog($userid,$config,$search,$post)
    {
//        if(array_key_exists("status",$post)&&$post['status']!=""){
//            $where[] = ['status', '=', $post['status']];
//        }else{
//            if(array_key_exists("jifen",$post)&&$post['jifen']!="") {
//                $where[] = ['status', '<>', 0];
//                $where[] = ['status', '<>', 2];
//            }else{
//                $where[] = ['status', '>=', 1];
//                $where[] = ['status', '<=', 2];
//            }
//        }
        if(array_key_exists("jifen",$post)&&$post['jifen']!=""){
            $where[] = ['jifen', '=', $post['jifen']];
        }else{
            $where[]=['jifen','=',0];
        }
        if(array_key_exists("status",$post)&&$post['status']!="") {
            $where[] = ['status', '=', $post['status']];
        }else{
            $where[] = ['status', '>=', 1];
            $where[] = ['status', '<=', 2];
        }
//        print_r($where);
//        exit;
//        $where[] = ['status', '=', 2];
        $where[] = ['userid', '=', $userid];
        if(!empty($search)){
            $res=$this->MBetweenTimeS($where,'addtime',$search['starttime'],$search['endtime'],$config,"id desc",$field="");
        }else{
            $res=$this->MLimitSelect($where,$config,"id desc");
        }
        if($res){
            $res['sum']=0;
            foreach ($res['data'] as $key => $value){
                if($value['status']==1){
                    $oserM=new Order();
                    $owhere['id']=$value['orderid'];
                    $order=$oserM->MFind($owhere);
                    if($order){
                        $res['data'][$key]['ordernum']=$order['ordernumber'];
                        $res['data'][$key]['price']=$order['price'];
                        $res['sum']+=$order['price'];
                        $res['data'][$key]['orderstatus']=$order['status'];
                    }else{
                        $res['data'][$key]['ordernum']="";
                        $res['data'][$key]['price']=0;
                        $res['data'][$key]['orderstatus']="";
                    }
                }else if($value['status']==2){
                    $oserM=new TiXian();
                    $owhere['id']=$value['orderid'];
                    $order=$oserM->MFind($owhere);
                    if($order){
                        $res['data'][$key]['ordernum']=$order['txnumber'];
                        $res['data'][$key]['price']=$order['price'];
                        $res['data'][$key]['orderstatus']=$order['status'];
                    }else{
                        $res['data'][$key]['ordernum']="";
                        $res['data'][$key]['price']=0;
                        $res['data'][$key]['orderstatus']="";
                    }
                }
            }
        }
        return $res;
    }
}