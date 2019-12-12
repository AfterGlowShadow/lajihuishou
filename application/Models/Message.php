<?php

namespace app\Models;

use app\validate\LimitValidate;
use app\validate\Message as MessageValidate;
use app\validate\TokenValidate;
use think\Db;
use think\facade\Request;

class Message extends BaseModel
{
    protected $table = "lj_message";

    //添加一个权限
    public function AddOne()
    {
        $post = Request::post();
        $res = "";
        (new MessageValidate())->goCheck($post);
        if (array_key_exists("type", $post) && $post['type'] != "") {
            if ($post['type']!=0) {
                if (!(array_key_exists("utype", $post) && $post['utype'] != "" && array_key_exists("userid", $post) && $post['userid'] != "")) {
                    $this->error = "缺少必要参数";
                    return false;
                }
            }
        }
        $res=false;
        if((array_key_exists("utype", $post)&&is_array($post['utype']))){
            $data=array();
            foreach ($post['utype'] as $key => $value){
                $temp=$post;
                $temp['utype']=$value;
                array_push($data,$temp);
            }
            $res=$this->MBulkAdd($data);
        }else{
            $post['token'] = md5(time());
            $post['create_time'] = time();
            $res = $this->MAdd($post);
        }
        if ($res) {
            return $res;
        } else {
            $this->error = "添加失败";
            return false;
        }
    }

    /**
     * 其他方法调用的添加消息方法
     * @param $utype  用户类型
     * @param $userid  要看消息的人的id
     * @param $title
     * @param $info
     * @param $type 分系统0通知(所有人)和1系统公告(特殊组)和2单个人的
     * @param $shop_id 新注册的店的id
     * @return bool|int|string
     */
    public function AddMessage($utype, $userid, $title, $info, $type, $shop_id = 0)
    {
        $res=false;
        if(is_array($utype)){
            $dataarray=array();
            foreach ($utype as $key => $value){
                $data['utype'] = $value;
                $data['userid'] = $userid;
                $data['title'] = $title;
                $data['info'] = $info;
                $data['token'] = md5(time());
                $data['create_time'] = time();
                array_push($dataarray,$data);
            }
            $res = $this->MBulkAdd($dataarray);
        }else{
            $data['utype'] = $utype;
            $data['userid'] = $userid;
            $data['title'] = $title;
            $data['info'] = $info;
            $data['token'] = md5(time());
            $data['create_time'] = time();
            $res = $this->MAdd($data);
//            $message['type'] = $type;
//            $message['isread'] = 0;
//            $message['shop_id'] = $shop_id;
        }
        if ($res) {
            return $res;
        } else {
            $this->error = "添加失败";
            return false;
        }
    }

    //修改管理员信息
    public function ChangeOne()
    {
        $post = Request::post();
        (new MessageValidate())->goCheck($post);
        (new TokenValidate())->goCheck($post);
        if (array_key_exists("type", $post) && $post['type'] != "") {
            if ($post['type']) {
                if (!(array_key_exists("utype", $post) && $post['utype'] != "" && array_key_exists("userid", $post) && $post['userid'] != "")) {
                    $this->error = "缺少必要参数";
                    return false;
                }
            }
        }
        $acont['token'] = $post['token'];
        $res = $this->MUpdate($acont, $post);
        if ($res) {
            return $res;
        } else {
            $this->error = "修改失败";
            return false;
        }
    }

    //分页查询信息
    public function GetList()
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $where['status'] = 1;
        $where['del'] = 0;
        $res = $this->MLimitSelect($where, $config, "id desc");
        if ($res) {
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }

    //单个查询信息
    public function GetOne()
    {
        $post = Request::post();
        (new TokenValidate())->goCheck($post);
        $where['token'] = $post['token'];
        $where['status'] = 1;
        $where['del'] = 0;
        $res = $this->MFind($where);
        if ($res) {
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }

    //查询公告
    public function GetNotice()
    {
        $post = Request::post();
//        print_r($post);
//        exit;
        (new LimitValidate())->goCheck($post);
        $where['type'] = 2;
        $user=session($post['token']);
        $where['userid']=$user['userInfo']['id'];
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $res = $this->MLimitSelect($where, $config,"id desc");
        if ($res) {
            foreach ($res['data'] as $key => $value){
                if(strlen($value['title'])>16){
                    $res['data'][$key]['title']=mb_substr($value['title'],0,16)."....";
                }
            }
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }

    //根据用户身份获取公告
    public function TypeNotice()
    {
        $post = Request::param();
        (new TokenValidate())->goCheck($post);
        (new LimitValidate())->goCheck($post);
        $user = session($post['token']);
        $res = Db::execute("select * from lj_message where type=0 or (type=1 and utype=".$user['userInfo']['groupid'].") order by id desc limit ".$post['page'].",".$post['list_rows']);
//        $where['type'] = 0;
//        $where['utype'] = $user['userInfo']['groupid'];
//        $config['page'] = $post['page'];
//        $config['list_rows'] = $post['list_rows'];
//        $res = $this->MLimitSelect($where, $config);
        if ($res) {
            foreach ($res['data'] as $key => $value){
                if(strlen($value['title'])>16){
                    $res['data'][$key]['title']=mb_substr($value['title'],0,16)."....";
                }
            }
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }
    //管理查询消息
    public function GetGNotice()
    {
        $post = Request::post();
//        print_r($post);
//        exit;
        (new LimitValidate())->goCheck($post);
        $where['type'] = 0;
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        unset($post['page']);
        unset($post['list_rows']);
        $res = $this->MLimitSelect($post, $config,"id desc");
        if ($res) {
            foreach ($res['data'] as $key => $value){
                if(strlen($value['title'])>16){
                    $res['data'][$key]['title']=mb_substr($value['title'],0,16)."....";
                }
            }
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }
    //查询用户单独发送信息
    public function GetUserNotice()
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);
        $user = session("user");
        $where['utype'] = $user['userInfo']['groupid'];
        $where['userid'] = $user['userInfo']['id'];
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $res = $this->MLimitSelect($where, $config);
        if ($res) {
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }

    //查询没读过的用户公告个数
    public function GetNoRead()
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);
        $user = session($post['token']);
//        print_r($user);
        $where['utype'] = $user['userInfo']['groupid'];
        $where['type'] = 2;
        $where['userid'] = $user['userInfo']['id'];
        $where['isread'] = 0;
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $res = $this->MLimitSelect($where, $config);
        if ($res) {
            return $res;
        } else {
            return BackData(200,"没有数据");
        }
    }

    //查询消息详情
    public function Notice()
    {
        $post = Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $where['id'] = $post['id'];
            $res = $this->MFind($where);
            if ($res) {
                if ($res['type'] == 2 && $res['isread'] == 0) {
                    $updata['isread'] = 1;
                    $this->MUpdate($where, $updata);
                }
                return $res;
            } else {
                $this->error = "查询失败";
                return false;
            }
        }else{
            $this->error = "缺少必要参数";
            return false;
        }
    }
}