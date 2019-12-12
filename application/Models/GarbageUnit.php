<?php
namespace app\Models;
use app\validate\LimitValidate;
use app\validate\UnitValidate;
use think\facade\Request;

class GarbageUnit extends BaseModel
{
    protected $table="lj_garbageunit";
    //添加一个权限
    public function AddOne()
    {
        $post=Request::post();
        (new UnitValidate())->goCheck($post);
        //验证写完了-> 添加单位
        $mcont['danweiming']=$post['danweiming'];
        $mcont['garbageid']=$post['garbageid'];
        $mcont['del']=0;
        $fadmin=$this->MFind($mcont);
        if($fadmin){
            $this->error="此单位已经存在";
            return false;
        }else{
            $data['danweiming']=$post['danweiming'];
            $data['garbageid']=$post['garbageid'];
            $data['transweight']=$post['transweight'];
            $res=$this->MAdd($data);
            if($res){
                $this->error="添加成功";
                return false;
            }else{
                $this->error="添加失败";
                return false;
            }
        }
    }
    //修改管理员信息
    public function ChangeOne()
    {
        $post=Request::post();
        (new UnitValidate())->goCheck($post);
        if(array_key_exists("garbagepriceid",$post)&&$post['garbagepriceid']!=""&&array_key_exists("id",$post)&&$post['id']){
            $post['rulelist']=explode(",",$post['rulelist']);
            $mcont['danweiming']=$post['danweiming'];
            $mcont['garbageid']=$post['garbageid'];
            $mcont['del']=0;
            $fadmin=$this->MFind($mcont);
            if($fadmin){
                if($fadmin['id']!=$post['id']){
                    $this->error="此名称已经存在";
                    return false;
                }else{
                    $res=$this->Change($post);
                    if($res){
                        return $res;
                    }else{
                        return false;
                    }
                }
            }else{
                $this->error="没有此单位";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //删除垃圾
    public function DeleteOne(){
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $mcont['id']=$post['id'];
            $mcont['del']=1;
            $group=$this->MFind($mcont);
            if($group){
                $res=$this->MDelete($mcont);
                if($res){
                    return $res;
                }else{
                    $this->error="删除失败";
                    return false;
                }
            }else{
                $this->error="此单位已经不存在";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //分页查询信息
    public function GetList()
    {
        $post=Request::post();
        $where['del']=0;
        $res=$this->MSelect($where,"id desc");
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //获取单个垃圾
    public function GetOne()
    {
        $post=Request::post();
        $mcont['id']=$post['id'];
        $mcont['del']=0;
        $res=$this->MFind($mcont);
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
}