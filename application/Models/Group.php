<?php
namespace app\Models;
use app\validate\LimitValidate;
use app\validate\NameValidate;
use app\validate\TokenValidate;
use think\facade\Request;
class Group extends BaseModel
{
    protected $table = 'lj_group';
    public function rules()
    {
        return $this->belongsToMany('Rule','grouprule','groupid','id');
    }
    //添加一个权限
    public function AddOne()
    {
        $post=Request::post();
        (new NameValidate())->goCheck($post);
        $mcont['name']=$post['name'];
        $fadmin=$this->MFind($mcont);
        if($fadmin){
            $this->error="此名称已经存在";
            return false;
        }else{
            $post['token']=md5(time());
            $this->startTrans();
            $res=$this->MAdd($post);
            if($res){
                if(array_key_exists("rulelist",$post)&&$post['rulelist']){
                    $post['rulelist']=explode(",",$post['rulelist']);
                    $rulelist=array();
                    foreach ($post['rulelist'] as $key => $value){
                        $temp=array();
                        $temp['groupid']=$res;
                        $temp['ruleid']=$value;
                        array_push($rulelist,$temp);
                    }
                    $grouprule=new GroupRule();
                    $res1=$grouprule->MSaveList($rulelist);
                    if($res1){
                        $this->commit();
                        return $res;
                    }else{
                        $this->error="添加失败";
                        $this->rollback();
                        return $res;
                    }
                }else{
                    $this->commit();
                    return $res;
                }
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
        (new NameValidate())->goCheck($post);
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $post['rulelist']=explode(",",$post['rulelist']);
            $mcont['name']=$post['name'];
            $where['del']=0;
            $where['status']=1;
            $fadmin=$this->MFind($mcont);
            if($fadmin){
                if($fadmin['id']!=$post['id']){
                    $this->error="此名称已经存在";
                    return false;
                }else{
                    $res=$this->Change($post);
                    if($res){
                        $this->commit();
                        return $res;
                    }else{
                        $this->rollback();
                        return false;
                    }
                }
            }else{
                $res = $this->Change($post);
                if($res){
                    $this->commit();
                    return $res;
                }else{
                    $this->rollback();
                    $this->error="修改失败";
                    return false;
                }
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }

    public function Change($data)
    {
        $acont['id']=$data['id'];
        $this->startTrans();
        $rulelist1=$data['rulelist'];
        unset($data['rulelist']);
        $res=$this->MUpdate($acont,$data);
        if($res){
            $grouprule=new GroupRule();
            $grwhere['groupid']=$data['id'];
            $res2=$grouprule->MFDelete($grwhere);
            if($res2){
                $rulelist=array();
                foreach ($rulelist1 as $key => $value){
                    $temp=array();
                    $temp['groupid']=$data['id'];
                    $temp['ruleid']=$value;
                    array_push($rulelist,$temp);
                }
                $res1=$grouprule->MSaveList($rulelist);
                if($res1){
                    return $res1;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //分页查询信息
    public function GetList()
    {
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $where['status']=1;
        $where['del']=0;
        $res=$this->MLimitSelect($where,$config,"id desc");
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //删除垃圾
    public function DeleteOne(){
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $mcont['id']=$post['id'];
            $group=$this->MFind($mcont);
            $this->startTrans();
            $res=$this->MDelete($mcont);
            if($res){
                $mcont1['groupid']=$group['id'];
                $grouprule=new GroupRule();
                $res1=$grouprule->MDelete($mcont1);
                if($res1){
                    $this->commit();
                    return $res;
                }else{
                    $this->rollback();
                    $this->error="删除失败";
                    return false;
                }
            }else{
                $this->error="删除失败";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //查询单个权限组
    public function GetOne()
    {
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $where['id']=$post['id'];
            $where['del']=0;
            $where['status']=1;
            $group=$this->MFind($where);
            if($group){
                $grwhere['groupid']=$post['id'];
                $grmodel=new GroupRule();
                $grwhere['del']=0;
                $grinfo=$grmodel->MSelect($grwhere);
                if($grinfo){
                    $ruwhere['id']=array();
                    foreach ($grinfo as $key =>$value){
                        array_push($ruwhere['id'],$value['ruleid']);
                    }
                    $rumodel=new Rule();
                    $ruwhere['del']=0;
                    $rules=$rumodel->MSelect($ruwhere);
                    if($rules){
                        $group['rules']=$rules;
                    }
                    return $group;
                }else{
                    return $group;
                }
            }else{
                $this->error="未找到要查找数据";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
}