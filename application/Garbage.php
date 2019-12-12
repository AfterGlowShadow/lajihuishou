<?php
namespace app\Models;
use app\validate\LimitValidate;
use think\Db;
use think\facade\Request;

/**
 * Class Garbage
 * 垃圾(涉及到垃圾表和垃圾价格表)
 * @package app\Models\
 */
class Garbage extends BaseModel
{
    protected $table = 'lj_garbage';
    public function comments()
    {
        return $this->hasMany('GarbagePrice','garbageid');
    }
    //添加一个垃圾
    public function AddOne()
    {
        $post=Request::post();
        if(array_key_exists("name",$post)&&$post['name']!=""){
            $mcont['name']=$post['name'];
            $mcont['del']=0;
            $mcont['status']=1;
            $fadmin=$this->MFind($mcont);
            if($fadmin){
                $this->error="此垃圾名称存在";
                return false;
            }else{
                $garbage="";
                if(array_key_exists("pga",$post)&&$post['pga']!=""){
                    $where['id']=$post['pga'];
                    $garbage=$this->MFind($where);
//                    if($garbage){
//                        if($garbage['pgalist']){
//                            $post['pgalist']=$garbage['pgalist'].",".$post['pga'];
//                        }else{
//                            $post['pgalist']=$post['pga'];
//                        }
//                    }else{
//                        $this->error = "修改失败";
//                        return false;
//                    }
                }
//                print_r($post['pgalist']);
//                exit;
                $post['token']=md5(time().$post['name']);
                $post['status']=1;
                $this->startTrans();
                $res=$this->MAdd($post);
                $pagelist['pgalist']=$res.",".$garbage['pgalist'];
                $pwhere['id']=$res;
                $res=$this->MUpdate($pwhere,$pagelist);
                if($res){
                    $this->commit();
                    return $res;
                }else{
                    $this->rollback();
                    $this->error="添加失败";
                    return false;
                }
            }
        }else{
            $this->error="垃圾名称必许填写";
            return false;
        }
    }
    //修改垃圾信息
    public function ChangeOne()
    {
        $post=Request::post();
        if(array_key_exists("name",$post)&&$post['name']!="") {
            $mcont['name'] = $post['name'];
            $fadmin = $this->MFind($mcont);
            if ($fadmin) {
                if ($fadmin['token'] != $post['token']) {
                    $this->error = "此垃圾名已经存在";
                    return false;
                } else {
                    //if(array_key_exists("pga",$post)&&$post['pga']!=""){
                    //    $where['id']=$post['pga'];
                    //    $garbage=$this->MFind($where);
                    //    if($garbage){
                    //        if($garbage['pgalist']){
                    //            $post['pgalist']=$garbage['pgalist'].",".$post['pga'];
                    //        }else{
                    //            $post['pgalist']=$post['pga'];
                    //        }
                    //    }else{
                    //        $this->error = "修改失败";
                    //        return false;
                    //    }
                    //}
                    $acont['token'] = $post['token'];
                    $res = $this->MUpdate($acont, $post);
                    if ($res) {
                        return $res;
                    } else {
                        $this->error = "修改失败";
                        return false;
                    }
                }
            } else {
                $acont['token'] = $post['token'];
                $res = $this->MUpdate($acont, $post);
                if ($res) {
                    return $res;
                } else {
                    $this->error = "修改失败";
                    return false;
                }
            }
        }else{
            $this->error="垃圾名称必须填写";
            return false;
        }
    }
    //分页查询信息
    public function GetList()
    {
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        if(array_key_exists("pga",$post)&&$post['pga']!=""){
            $config['page']=$post['page'];
            $config['list_rows']=$post['list_rows'];
//        $field=array("token","name","pga");
            $res=$this->MLimitSelect(["del"=>0,'pga'=>$post['pga']],$config,"id desc");
            if($res){
                $res['data']=$this->GetPrice($res['data'],$post);
                return $res;
            }else{
                $this->error="查询失败";
                return false;
            }
        }else{
            $config['page']=$post['page'];
            $config['list_rows']=$post['list_rows'];
//        $field=array("token","name","pga");
            $res=$this->MLimitSelect(["del"=>0],$config,"id desc");
            if($res){
                $res['data']=$this->GetPrice($res['data'],$post);
                return $res;
            }else{
                $this->error="查询失败";
                return false;
            }
        }
    }
    //查询所有信息
    public function GetAllList()
    {
        $post=Request::post();
        $res=$this->MSelect(["del"=>0],"id desc");
        if($res){
            $res=$this->GetPrice($res,$post);
            $res=list_to_tree($res,'id','pga');
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //根据垃圾查询垃圾当前时间的价格
    public function GetPrice($data,$post)
    {
        $regionG=new RegionGroup();
        $GarbagePrice=new GarbagePrice();
        foreach($data as $key => $value){
            $where['garbageid']=$value['id'];
            $where['status']=1;
            $where['del']=0;
            if(array_key_exists("status",$post)&&$post['status']==1){
                if(array_key_exists("regionz",$post)&&$post['regionz']!=""){
                        $where['regionz']=$post['regionz'];
                }else{
                    if(array_key_exists("token",$post)&&$post['token']!=""){
                        $user=session($post['token']);
                        if($user){
                            $where['regionz']=$user['region'];
                        }
                    }
                }
                if(array_key_exists("start_time",$post)&&$post['start_time']!=""&&array_key_exists("end_time",$post)&&$post['end_time']!=""){
                    //带时间的查询
//                    $price=$GarbagePrice->MBetweenTimeS($where,"");
                    $price = Db::execute("select * from lj_garbageprice where regionz=".$post['regionz']." and status=1 and del=0 and ((start_time<=".strtotime($post['start_time'])." and ".strtotime($post['start_time'])."<=end_time)  or (start_time<=".strtotime($post['end_time'])." and ".strtotime($post['end_time'])."<=end_time))  order by id desc  limit ".$post['page'].",".$post['list_rows']);
                }else{
                    $price=$GarbagePrice->MFHTime($where,"");
                }
            }else{
                $config['page']=$post['page'];
                $config['list_rows']=$post['list_rows'];
//                $price = Db::execute("select * from lj_garbageprice where regionz=".$post['regionz']." and status=1 and del=0 and ((start_time<=".strtotime($post['start_time'])." and ".strtotime($post['start_time'])."<=end_time)  or (start_time<=".strtotime($post['end_time'])." and ".strtotime($post['end_time'])."<=end_time))  order by id desc  limit ".$post['page'].",".$post['list_rows']);
                $price=$GarbagePrice->MLimitSelect($where,$config,"id desc");
                $price=$price['data'];
            }
            if($price){
                $rwhere['id']=$price[0]['regionz'];
                $region=$regionG->MFind($rwhere);
                $price[0]['region']=$region;
                $data[$key]['price']=$price;
                $data[$key]['weight']=$price[0]['weight'];
                $data[$key]['number']=$price[0]['number'];
                $data[$key]['garbageid']=$price[0]['id'];
            }
        }
        return $data;
    }
    //删除垃圾
    public function DeleteOne(){
        $post=Request::post();
        if(array_key_exists("id",$post)){
            $mcont['id']=$post['id'];
            $this->startTrans();
            $res=$this->MDelete($mcont);
            if($res){
                $GarPriceModel=new GarbagePrice();
                $where['garbageid']=$mcont['id'];
                $res1=$GarPriceModel->MFind($where);
                if($res1){
                    $res2=$GarPriceModel->MDelete($where);
                    if($res2){
                        $this->commit();
                        return $res2;
                    }else{
                        $this->rollback();
                        $this->error="删除失败";
                        return false;
                    }
                }else{
                    $this->commit();
                    return $res;
                }
            }else{
                $this->rollback();
                $this->error="删除失败";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //获取单个垃圾
    public function GetOne()
    {
        $post=Request::post();
        if(array_key_exists("token",$post)){
            $mcont['token']=$post['token'];
            $res=$this->MFind($mcont);
            if($res){
                $GarbagePrice=new GarbagePrice();
                $where['garbageid']=$res['id'];
                $where['status']=1;
                $where['del']=0;
                //$field=array("id,weightnumber,start_time,end_time,token,regionz,weightmothed");
                $price=$GarbagePrice->MFHTime($where,"");
                if($price){
                    $regionG=new RegionGroup();
                    $rewhere['id']=$price[0]['regionz'];
                    $region=$regionG->MFind($rewhere);
                    if($region){
                        $price[0]['regionz']=$region['name'];
                        $res['price']=$price;
                    }
                }
                return $res;
            }else{
                $this->error="查询失败";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
}