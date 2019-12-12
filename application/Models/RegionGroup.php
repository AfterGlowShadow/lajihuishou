<?php
namespace app\Models;
use app\validate\LimitValidate;
use app\validate\NameValidate;
use app\validate\TokenValidate;
use think\facade\Request;

class RegionGroup extends  BaseModel
{
    protected $table="lj_regiongroup";
    public function rules()
    {
        return $this->belongsToMany('regroup','regroup ','regroupid','id');
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
                if(array_key_exists("regionlist",$post)&&$post['regionlist']){
                    $post['regionlist']=json_decode($post['regionlist'],true);
                    $rulelist=array();
                    foreach ($post['regionlist'] as $key => $value){
                        $temp=array();
                        $temp['regroupid']=$res;
                        $temp['regionid']=$value;
                        array_push($rulelist,$temp);
                    }
                    $grouprule=new ReGroup();
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
        (new TokenValidate())->goCheck($post);
        $mcont['name']=$post['name'];
        $fadmin=$this->MFind($mcont);
        $mcont1['token']=$post['token'];
        $fadmin1=$this->MFind($mcont1);
        $data=$post;
        if(array_key_exists("regionlist",$post)) {
            unset($data['regionlist']);
        }
        if($fadmin){
            if($fadmin['token']!=$post['token']){
                $this->error="此名称已经存在";
                return false;
            }else{
                $acont['token']=$post['token'];
                $res=$this->MUpdate($acont,$data);
                if($res){
                    if(array_key_exists("regionlist",$post)&&$post['regionlist']){
                        $post['regionlist']=json_decode($post['regionlist'],true);
                        $rulelist=array();
                        foreach ($post['regionlist'] as $key => $value){
                            $temp=array();
                            $temp['regroupid']=$fadmin1['id'];
                            $temp['regionid']=$value;
                            array_push($rulelist,$temp);
                        }
                        $grouprule=new ReGroup();
                        $dgwhere['regroupid']=$fadmin1['id'];
                        $res2=$grouprule->MFDelete($dgwhere);
                        $res1=$grouprule->MSaveList($rulelist);
                        if($res1&&$res2){
                            $this->commit();
                            return $res;
                        }else{
                            $this->error="修改失败";
                            $this->rollback();
                            return false;
                        }
                    }else{
                         $this->error="修改失败";
                         $this->rollback();
                         return false;
                    }
                }else{
                    $this->error="修改失败";
                    $this->rollback();
                    return false;
                }
            }
        }else{
            $acont['token']=$post['token'];
            $res=$this->MUpdate($acont,$data);
            if($res){
                if(array_key_exists("regionlist",$post)&&$post['regionlist']){
                    $post['regionlist']=json_decode($post['regionlist'],true);
                    $rulelist=array();
                    foreach ($post['regionlist'] as $key => $value){
                        $temp=array();
                        $temp['regroupid']=$fadmin1['id'];
                        $temp['regionid']=$value;
                        array_push($rulelist,$temp);
                    }
                    $grouprule=new ReGroup();
                    $dgwhere['regroupid']=$fadmin1['id'];
                    $res2=$grouprule->MFDelete($dgwhere);
                    $res1=$grouprule->MSaveList($rulelist);
                    if($res1&&$res2){
                        $this->commit();
                        return $res;
                    }else{
                        $this->error="修改失败";
                        $this->rollback();
                        return false;
                    }
                }else{
                    $this->commit();
                    return $res;
                }
            }else{
                $this->error="修改失败";
                return false;
            }
        }
    }
    //分页查询信息
    public function GetList()
    {
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $field=array("name","remark","token,id");
        $res=$this->MLimitSelect("",$config,"id desc",$field);
        if($res){
            foreach ($res['data'] as $key =>$value){
                $res['data'][$key]['area']=array();
                //跟剧组查询地区
                $regroup=new ReGroup();
                $where['regroupid']=$value['id'];
                $res1=$regroup->MSelect($where);
                if($res1){
                    foreach ($res1 as $k =>$v){
                        $temp=array();
                        $city=new City();
                        $cwhere['id']=explode(",",$v['regionid']);
                        $res2=$city->MSelect($cwhere);
                        if($res2&&count($res2)>1){
                            $temp['province']=$res2['0']['cityname'];
                            $temp['city']=$res2['1']['cityname'];
                            $temp['area']=$res2['2']['cityname'];
                            array_push($res['data'][$key]['area'],$temp);
                        }
                    }
                }
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
        $group=$this->MFind($mcont);
        $this->startTrans();
        $res=$this->MDelete($mcont);
        if($res){
            $mcont1['regroupid']=$group['id'];
            $grouprule=new ReGroup();
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
    }
    //获取单个垃圾
    public function GetOne()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token']=$post['token'];
        $mcont['del']=0;
        $mcont['status']=1;
        $res=$this->MFind($mcont);
        if($res){
            //跟剧组查询地区
            $regroup=new ReGroup();
            $where['regroupid']=$res['id'];
            $res1=$regroup->MFind($where);
            if($res1){
                $city=new City();
                $cwhere['id']=explode(",",$res1['regionid']);
                $res2=$city->MSelect($cwhere);
                if($res2&&count($res2)>1){
                    $res['province']=$res2['0']['cityname'];
                    $res['city']=$res2['1']['cityname'];
                    $res['area']=$res2['2']['cityname'];
                }
            }
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }

}