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
                $this->startTrans();
                $post['weight']=isset($post['weight'])?$post['weight']:0;
                $post['number']=isset($post['number'])?$post['number']:0;
                $post['token']=md5(time().$post['name']);
                $post['status']=1;
                $res=$this->MAdd($post);
                $garpageunit=array();
                if(array_key_exists("danwei",$post)&&$post['danwei']!=""){
                    $post['danwei']=json_decode($post['danwei'],256);
                    foreach ($post['danwei'] as $key => $value){
                        $temp=array();
                        if(array_key_exists("danweiming",$value)&&$value['danweiming']!=""&&array_key_exists("transweight",$value)&&$value['transweight']!=""){
                            $temp['danweiming']=$value['danweiming'];
                            $temp['transweight']=$value['transweight'];
                            $temp['garbageid']=$res;
                            array_push($garpageunit,$temp);
                        }
                    }
                }
                $temp['danweiming']="kg";
                $temp['transweight']=1;
                $temp['garbageid']=$res;
                array_push($garpageunit,$temp);
                if(!empty($garpageunit)){
                    $garbageunitM=new GarbageUnit();
                    $res1=$garbageunitM->insertAll($garpageunit);
                }else{
                    $res1=true;
                }
                if($garbage!=""){
                    $pagelist['pgalist']=$res.",".$garbage['pgalist'];
                }else{
                    $pagelist['pgalist']=$res.",";
                }
                $pwhere['id']=$res;
                $res2=$this->MUpdate($pwhere,$pagelist);
                if($res&&$res1&&$res2){
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
        if(array_key_exists("id",$post)&&$post['id']!=""&&array_key_exists("name",$post)&&$post['name']!="") {
            $mcont['name'] = $post['name'];
            $fadmin = $this->MFind($mcont);
            $this->startTrans();
            if ($fadmin) {
                $garbageunitM=new GarbageUnit();
                $gun['garbageid']=$post['id'];
                $res3=$garbageunitM->MFind($gun);
                if($res3){
                    $res3=$garbageunitM->MFDelete($gun);
                }else{
                    $res3=true;
                }
                $garpageunit=array();
                if(array_key_exists("danwei",$post)&&$post['danwei']!=""){
                    $post['danwei']=json_decode($post['danwei'],256);
                    foreach ($post['danwei'] as $key => $value){
                        $temp=array();
                        if(array_key_exists("danweiming",$value)&&$value['danweiming']!=""&&array_key_exists("transweight",$value)&&$value['transweight']!=""){
                            $temp['danweiming']=$value['danweiming'];
                            $temp['transweight']=$value['transweight'];
                            $temp['garbageid']=$post['id'];
                            array_push($garpageunit,$temp);
                        }
                    }
                    $temp['danweiming']="kg";
                    $temp['transweight']=1;
                    $temp['garbageid']=$post['id'];
                    array_push($garpageunit,$temp);
                }
                if(!empty($garpageunit)){
                    $garbageunitM=new GarbageUnit();
                    $res4=$garbageunitM->insertAll($garpageunit);
                }else{
                    $res4=true;
                }
                if ($fadmin['id'] != $post['id']) {
                    $this->error = "此垃圾名已经存在";
                    return false;
                } else {
                    $acont['id'] = $post['id'];
                    unset($post['danwei']);
                    $res = $this->MUpdate($acont, $post);
                    if ($res||($res4&&$res3)) {
                        $this->commit();
                        return true;
                    } else {
                        $this->rollback();
                        $this->error = "修改失败";
                        return false;
                    }
                }
            } else {
                $this->error = "修改数据不存在";
                return false;
//                $garpageunit=array();
//                if(array_key_exists("danwei",$post)&&$post['danwei']!=""){
//                    $post['danwei']=json_decode($post['danwei'],256);
//                    foreach ($post['danwei'] as $key => $value){
//                        $temp=array();
//                        if(array_key_exists("danweiming",$value)&&$value['danweiming']!=""&&array_key_exists("transweight",$value)&&$value['transweight']!=""){
//                            $temp['danweiming']=$value['danweiming'];
//                            $temp['transweight']=$value['transweight'];
//                            $temp['garbageid']=$post['id'];
//                            array_push($garpageunit,$temp);
//                        }
//                    }
//                }
//                if(!empty($garpageunit)){
//                    $garbageunitM=new GarbageUnit();
//                    $res4=$garbageunitM->insertAll($garpageunit);
//                }else{
//                    $res4=true;
//                }
//                $acont['id'] = $post['id'];
//                unset($post['danwei']);
//                $res = $this->MUpdate($acont, $post);
//                if ($res||$res4) {
//                    $this->commit();
//                    return $res;
//                } else {
//                    $this->rollback();
//                    $this->error = "修改失败";
//                    return false;
//                }
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
//            if($res){
//                if($post['pga']!=0){
//                    $res['data']=$this->GetPrice($res['data'],$post);
//                }
//            }else{
//                $this->error="查询失败";
//                return false;
//            }
        }else{
            $config['page']=$post['page'];
            $config['list_rows']=$post['list_rows'];
//        $field=array("token","name","pga");
            $res=$this->MLimitSelect(["del"=>0],$config,"id desc");
//            if($res){
//                if($post['pga']!=0){
//                    $res['data']=$this->GetPrice($res['data'],$post);
//                }
////                return $res;
//            }else{
//                $this->error="查询失败";
//                return false;
//            }
        }
        $garbageunit=new GarbageUnit();
        foreach ($res['data'] as  $key => $value){
            $guncont['garbageid']=$value['id'];
            $garbage=$garbageunit->MSelect($guncont);
            $temp=array();
            foreach ($garbage as $k => $v){
                if(array_key_exists("regionz",$post)||array_key_exists("token",$post)) {
                    array_push($temp,$v);
                }else{
                    if($v['danweiming']!='kg'){
                        array_push($temp,$v);
                    }
                }
            }
            $res['data'][$key]['danwei']=$temp;
        }
//        print_r($garbage);
//        exit;
        if(array_key_exists("token",$post)&&$post['token']!=""){
            $user=session($post['token']);
            $post['regionz']=$user['userInfo']['region'];
        }
        $res['data']=$this->GetPrice($res['data'],$post);
        return $res;
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
        $garbageunitM=new GarbageUnit();
        foreach($data as $key => $value){
            $where['garbageid']=$value['id'];
            $where['status']=1;
            $where['del']=0;
            foreach ($value['danwei'] as $k => $v){
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
                        $price = Db::query("select * from lj_garbageprice where garbageunitid=".$v['id']." and regionz=".$post['regionz']." and status=1 and del=0 and ((start_time<=".strtotime($post['start_time'])." and ".strtotime($post['start_time'])."<=end_time)  or (start_time<=".strtotime($post['end_time'])." and ".strtotime($post['end_time'])."<=end_time))  order by id desc  limit ".$post['page'].",".$post['list_rows']);
                    }else{
                        $where['garbageunitid']=$v['id'];
                        $price=$GarbagePrice->MFHTime($where,"");
                    }
                }else{
                    $config['page']=$post['page'];
                    $config['list_rows']=$post['list_rows'];
                    $where['garbageunitid']=$v['id'];
//                    $where['garbageid']=substr($value['pgalist'],0,strlen($value['pgalist'])-1);
                    $where['garbageid']=$value['id'];
//                $price = Db::execute("select * from lj_garbageprice where regionz=".$post['regionz']." and status=1 and del=0 and ((start_time<=".strtotime($post['start_time'])." and ".strtotime($post['start_time'])."<=end_time)  or (start_time<=".strtotime($post['end_time'])." and ".strtotime($post['end_time'])."<=end_time))  order by id desc  limit ".$post['page'].",".$post['list_rows']);
                    $price=$GarbagePrice->MLimitSelect($where,$config,"id desc");
                    $price=$price['data'];
                }
                if($price){
//                $rwhere['id']=$price[0]['regionz'];
//                $region=$regionG->MFind($rwhere);
                    if(array_key_exists("regionz",$post)&&$post['regionz']!=""){
                        $regionz=$post['regionz'];
                    }else{
                        if(array_key_exists("token",$post)&&$post['token']!=""){
                            $user=session($post['token']);
                            if($user){
                                $regionz=$user['region'];
                            }
                        }
                    }
//                $price[0]['region']=$regionz;
                    $data[$key]['danwei'][$k]['price']=$price[0]['number'];
//                    $data[$key]['weight']=$price[0]['price'];
//                    $data[$key]['number']=$price[0]['number'];
                    $data[$key]['danwei'][$k]['garbageunitid']=$price[0]['id'];
                }else{
                    //次垃圾的此单位不存在价格查看父亲
                    if($value['pga']==0){
                        $data[$key]['danwei'][$k]['price']=0;
                    }else{
                        //不是最顶级垃圾 找上级是否有同名
                        if($v['danweiming']=='kg'){
                            $pgawhere['garbageid']=$value['pga'];
                            $pgawhere['danweiming']='kg';
                            $garbageunitinfo=$garbageunitM->MFind($pgawhere);
                            $where['garbageunitid']=$garbageunitinfo['id'];
                            $where['garbageid']=$value['pga'];
                            $price=$GarbagePrice->MLimitSelect($where,$config,"id desc");
                            if($price['data']){
                                $data[$key]['danwei'][$k]['price']=$price['data'][0]['number'];
                            }else{
                                $data[$key]['danwei'][$k]['price']=0;
                            }
                        }else{
                            $where1['danweiming']=$v['danweiming'];
                            $where1['id']=$value['pga'];
                            $garbageinfo=$garbageunitM->MFind($where1);
                            //上级存在就按上级的下个算
                            if($garbageinfo){
                                $data[$key]['danwei'][$k]['price']=$garbageinfo['number'];
                            }else{
                                //上级不存在 兑换成重量并查看本级是否有价格 没有查询上级价格
                                $pgawhere['garbageid']=$value['pga'];
                                $pgawhere['danweiming']='kg';
                                $garbageunitinfo=$garbageunitM->MFind($pgawhere);
                                $where['garbageunitid']=$garbageunitinfo['id'];
                                $where['garbageid']=$value['pga'];
                                $price=$GarbagePrice->MLimitSelect($where,$config,"id desc");
                                if($price['data']){
                                    $data[$key]['danwei'][$k]['price']=$price['data'][0]['number']*$v['transweight'];
                                }else{
                                    $data[$key]['danwei'][$k]['price']=0;
                                }
                            }
                        }
                    }
                }
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
                    $garbageunitM=new GarbageUnit();
                    $gun['garbageid']=$post['id'];
                    $res3=$garbageunitM->MFind($gun);
                    if($res3){
                        $res3=$garbageunitM->MDelete($gun);
                    }else{
                        $res3=true;
                    }
                    if($res2&&$res3){
                        $this->commit();
                        return $res2;
                    }else{
                        $this->rollback();
                        $this->error="删除失败";
                        return false;
                    }

                }else{
                    $garbageunitM=new GarbageUnit();
                    $gun['garbageid']=$post['id'];
                    $res3=$garbageunitM->MDelete($gun);
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