<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24
 * Time: 11:41
 */

namespace app\Models;


use app\validate\LimitValidate;
use app\validate\RetrospectAdd;
use app\validate\TokenValidate;
use think\facade\Request;
use think\facade\Validate;

class Retrospect extends BaseModel
{
    protected $table = 'lj_retrospect';
    public function comments()
    {
        return $this->hasMany('GarbagePrice','garbageid');
    }
    //添加一个本地库存
    public function AddOne()
    {
        $post=Request::post();
        (new RetrospectAdd())->goCheck($post);
        (new TokenValidate())->goCheck($post);

        $userinfo=session($post['token']);
        $post['u_id']=$userinfo['userInfo']['id'];
        unset($post['token']);
        $res=$this->MAdd($post);
        if ($res) {
            return $res;
        } else {
            $this->error = "添加失败";
            return false;
        }
    }
//    //修改垃圾信息
//    public function ChangeOne()
//    {
//        $post=Request::post();
//        if(array_key_exists("name",$post)&&$post['name']!="") {
//            $mcont['name'] = $post['name'];
//            $fadmin = $this->MFind($mcont);
//            if ($fadmin) {
//                if ($fadmin['token'] != $post['token']) {
//                    $this->error = "此垃圾名已经存在";
//                    return false;
//                } else {
//                    $acont['token'] = $post['token'];
//                    $res = $this->MUpdate($acont, $post);
//                    if ($res) {
//                        return $res;
//                    } else {
//                        $this->error = "修改失败";
//                        return false;
//                    }
//                }
//            } else {
//                $acont['token'] = $post['token'];
//                $res = $this->MUpdate($acont, $post);
//                if ($res) {
//                    return $res;
//                } else {
//                    $this->error = "修改失败";
//                    return false;
//                }
//            }
//        }else{
//            $this->error="垃圾名称必须填写";
//            return false;
//        }
//    }
    //分页查询门店垃圾库存信息
    public function GetList()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        (new LimitValidate())->goCheck($post);
        $userinfo=session($post['token']);
        $config['u_id']=$userinfo['userInfo']['id'];
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $field=array("u_id","garbageid","weighting_num","weighting_method,id");
        $res=$this->MLimitSelect(["u_id"=>$config['u_id'],'status'=>0,'del'=>0],$config,"id desc",$field);
        if($res['data']){
            foreach ($res['data'] as $key =>$value){
                $garwhere['pgalist']=$value['garbageid'];
                $garbageM=new Garbage();
                $garbageinfo=$garbageM->MFind($garwhere);
                $res['data'][$key]['garbageid']=$garbageinfo['id'];
                $res['data'][$key]['garbagename']=$garbageinfo['name'];
                $res['data'][$key]['garbagepgalist']=$garbageinfo['pgalist'];
                $res['data'][$key]['garbagepga']=$garbageinfo['pga'];
            }
            return $res;
        }else{
            BackData(200,"仓库尚无数据");
        }
    }
    //删除门店垃圾库存
    public function DeleteOne(){
        $post=Request::post();
        if(array_key_exists("id",$post)){
            $mcont['id']=$post['id'];
            $res=$this->MDelete($mcont);
            if($res){
                return $res;
            }else{
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
        if(array_key_exists("id",$post)){
            $mcont['id']=$post['id'];
            $res=$this->MFind($mcont);
            if($res){
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
    //获取单个垃圾
    public function BulkDelete()
    {
        $post=Request::post();
        $post['stocklist']=json_decode($post['stocklist']);
        if(array_key_exists("stocklist",$post)&&is_array($post['stocklist'])&&count($post['stocklist'])>0){
            $mcont['id']=$post['stocklist'];
            $res=$this->MBulkDelete($mcont);
            if($res){
                return $res;
            }else{
                $this->error="删除失败";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
}