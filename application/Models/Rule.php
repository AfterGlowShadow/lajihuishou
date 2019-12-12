<?php

namespace app\Models;

use app\validate\LimitValidate;
use app\validate\NameValidate;
use app\validate\TokenValidate;
use think\facade\Request;

class Rule extends BaseModel
{
    protected $table = 'lj_rule';

    //添加一个权限
    public function AddOne()
    {
        $post = Request::post();
        (new NameValidate())->goCheck($post);
        $mcont['name'] = $post['name'];
        $fadmin = $this->MFind($mcont);
        if ($fadmin) {
            $this->error = "此名称已经存在";
            return false;
        } else {
            $post['token'] = md5(time());
            $res = $this->MAdd($post);
            if ($res) {
                return $res;
            } else {
                $this->error = "添加失败";
                return false;
            }
        }
    }

    //修改管理员信息
    public function ChangeOne()
    {
        $post = Request::post();
        (new NameValidate())->goCheck($post);
        (new TokenValidate())->goCheck($post);
        $mcont['name'] = $post['name'];
        $fadmin = $this->MFind($mcont);
        if ($fadmin) {
            if ($fadmin['token'] != $post['token']) {
                $this->error = "此名称已经存在";
                return false;
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
    }

    //分页查询信息
    public function GetList()
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $field = array("name", "prule", "controller", "action", "url", "status", "token");
        $res = $this->MLimitSelect("", $config, "id desc", $field);
        if ($res) {
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }
 //获取权限列表
    public function RuleList()
    {
        $where['status']=1;
        $where['del']=0;
        $res=$this->MSelect($where);
        if($res){
            $res=list_to_tree($res, $pk = 'id', $pid = 'prule', $child = 'child', $root = 0, $strict = true, $filter = array());
            foreach ($res as $key =>$value){
                if(!(array_key_exists("child",$value)&&!empty($value['child']))){
                    unset($res[$key]);
                }
            }
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    /**
     * 获取指定身份的权限
     * @param $post operation_id
     */
    public function getAuthList($id=0)
    {
        if($id == 0) return false;
        $where = [];
        $where[] = ['a.groupid','=',$id];
        $where[] = ['a.del','=',0];
        $res = $this->MDBSelectAll('grouprule','rule','ruleid','id',$where);
        return $res;
    }
}