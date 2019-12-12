<?php
namespace app\Models;
use app\validate\LimitValidate;
use app\validate\TokenValidate;
use think\facade\Log;
use think\facade\Request;
use app\validate\GarbagePriceAdd as GarbagePriceAddValidate;
/**
 * Class Garbage
 * 垃圾(涉及到垃圾表和垃圾价格表)
 * @package app\Models\
 */
use think\model\Pivot;

class GarbagePrice extends  BaseModel
{
    protected $table = 'lj_garbageprice';
    protected $autoWriteTimestamp = true;
    //添加一个垃圾
    public function AddOne($post="")
    {
        if($post==""){
            $post=Request::post();
            (new GarbagePriceAddValidate())->goCheck($post);
        }
        if((array_key_exists("number",$post)&&$post['number']!="")){
            if($post==""){
                $post['garbageid']=substr($post['garbageid'],0,strlen($post['garbageid'])-1);
            }
            $cont['garbageid']=$post['garbageid'];
//            $fadmin1=$this->MBetweenTime($cont,'start_time',$post['start_time'],$post['end_time']);
//            $fadmin2=$this->MBetweenTime($cont,'end_time',$post['start_time'],$post['end_time']);
            $fadmin1=$this->MBetweenTime($post['garbageid'],'start_time',$post['start_time'],$post['end_time'],$post['regionz'],$post['garbageunitid']);
            $fadmin2=$this->MBetweenTime($post['garbageid'],'end_time',$post['start_time'],$post['end_time'],$post['regionz'],$post['garbageunitid']);
            if(!empty($fadmin1)||!empty($fadmin2)){
                $this->error="时间不能重叠";
                return false;
            }else{
                $post['token']=md5(time().$post['garbageid']);
                $post['garbageid']=$post['id'];
                $res=$this->MAdd($post);
                if($res){
                    return $res;
                }else{
                    $this->error="添加失败";
                    return false;
                }
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //修改垃圾信息
    public function ChangeOne()
    {
        $post=Request::post();
        (new  TokenValidate())->goCheck($post);
        $user=session($post['token'])['userInfo'];
        (new GarbagePriceAddValidate())->goCheck($post);
//        $fcont['id']=$post['id'];
        $post['danwei']=json_decode($post['danwei'],true);
        $this->startTrans();
//        $post['garbageid']=substr($post['garbageid'],0,strlen($post['garbageid'])-1);
        $temp=explode(",",$post['garbageid']);
//        $post['garbageid']=$temp[0];
        $post['garbageid']=$post['id'];
        foreach ($post['danwei'] as $key => $value){
            if($user['groupid']==3&&$user['daili']==1){
                $fcont['dlstarttime']=$post['start_time'];
                $fcont['dlendtime']=$post['end_time'];
            }else {
                $fcont['start_time']=$post['start_time'];
                $fcont['end_time']=$post['end_time'];
            }
            $garbageunitM=new GarbageUnit();
            $guncont['garbageid']=$post['id'];
            $guncont['danweiming']=$value['name'];
            $garbageunitinfo=$garbageunitM->MFind($guncont);
            if(!$garbageunitinfo){
                $this->error="所填垃圾单位有误";
                return false;
            }
            $fcont['garbageunitid']=$garbageunitinfo['id'];
            $post['garbageunitid']=$fcont['garbageunitid'];
            $post['number']=$value['price'];
            $fcont['garbageid']=$post['id'];
            $fcont['regionz']=$post['regionz'];
            $fgpres=$this->MFind($fcont);
            if(!$fgpres){
                $res=$this->AddOne($post);
                if(!$res){
                    $this->rollback();
                    $this->error="添加失败";
                    return false;
                }
//                return $res;
            }else{
                $data=[];
                if($user['groupid']==3&&$user['daili']==1){
                    $post['dlnumber']=$value['price'];
//                $data['name']=$post['name'];
//                $data['garbageid']=$post['garbageid'];
                    $post['dlstarttime']=$post['start_time'];
                    $post['dlendtime']=$post['end_time'];
                }else{
//                $data['name']=$post['name'];
                    $post['start_time']=$post['start_time'];
                    $post['end_time']=$post['end_time'];
//                $data['garbageid']=$post['garbageid'];
                    $post['number']=$value['price'];
                }
                $mcont['garbageid']=$post['id'];
                $mcont['regionz']=$post['regionz'];
                $fadmin1=false;
                $fadmin2=false;
                if($user['groupid']==3&&$user['daili']==1){
                    $fadmin1=$this->MBetweenTime($mcont['garbageid'],'dlstarttime',$post['start_time'],$post['end_time'], $mcont['regionz'],$post['garbageunitid']);
                    $fadmin2=$this->MBetweenTime($mcont['garbageid'],'dlendtime',$post['end_time'],$post['end_time'], $mcont['regionz'],$post['garbageunitid']);
                }else {
                    $fadmin1=$this->MBetweenTime($mcont['garbageid'],'start_time',$post['start_time'],$post['end_time'], $mcont['regionz'],$post['garbageunitid']);
                    $fadmin2=$this->MBetweenTime($mcont['garbageid'],'end_time',$post['start_time'],$post['end_time'], $mcont['regionz'],$post['garbageunitid']);
                }
                if($fadmin1||$fadmin2){
                    if($fadmin1['id']&&$fadmin2['id']){
                        unset($post['danwei']);
                        $post['garbageid']=$post['id'];
                        $res=$this->MUpdate($fcont,$post);
                        if($res){
                            $this->commit();
//                        return $res;
                        }else{
                            $this->rollback();
                            $this->error="修改失败";
                            return false;
                        }
                    }else{
                        $this->rollback();
                        $this->error="时间不能重叠!";
                        return false;
                    }
                }else{
                    unset($post['danwei']);
                    $post['garbageid']=$post['id'];
                    $res=$this->MUpdate($fcont,$post);
                    if($res){
                        $this->commit();
//                    return $res;
                    }else{
                        $this->rollback();
                        $this->error="修改失败";
                        return false;
                    }
                }
            }
        }
        $this->commit();
        return "修改成功";
    }
    //分页查询信息
    public function GetList()
    {
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $res=$this->MLimitSelect("",$config,"id desc");
        if($res){
            $RegionG=new RegionGroup();
            $GarbageM=new Garbage();
            foreach ($res['data'] as $key => $value){
                $where['id']=$value['regionz'];
                $region=$RegionG->MFind($where);
                if($region){
                    $res['data'][$key]['region']=$region['name'];
                }else{
                    $res['data'][$key]['region']="";
                }
                $garbageid=explode(",",$value['garbageid']);
                $gwhere['id']=$garbageid[0];
                $garbage=$GarbageM->MFind($gwhere);
                $res['data'][$key]['garbagename']=$garbage['name'];
                $res['data'][$key]['garbage']=$garbage['id'];
                $res['data'][$key]['pga']=$garbage['pga'];
            }

            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //删除垃圾
    public function DeleteOne(){
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token']=$post['token'];
        $res=$this->MDelete($mcont);
        if($res){
            return $res;
        }else{
            $this->error="删除失败";
            return false;
        }
    }
    //获取单个垃圾
    public function GetOne()
    {
        $post=Request::post();
        $mcont['id']=$post['id'];
        $mcont['del']=0;
        $mcont['status']=1;
        $res=$this->MFind($mcont);
        if($res){
            $RegionM=new RegionGroup();
            $rwhere['id']=$res['regionz'];
            $region=$RegionM->MFind($rwhere);
            if($region){
                $res['region']=$region['name'];
            }else{
                $res['region']="";
            }
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
}