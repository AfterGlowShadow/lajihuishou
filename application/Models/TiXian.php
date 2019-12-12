<?php
namespace app\Models;
use app\validate\LimitValidate;
use  app\Models\User as UserModel;
use app\validate\TokenValidate;
use think\facade\Request;

class TiXian extends BaseModel
{
    protected $table = "lj_tixian";

    //添加单个
    public function AddOne()
    {
        $post = Request::post();
        (new TokenValidate())->goCheck($post);
        if (array_key_exists("price", $post) && $post['price'] != "" && is_numeric($post['price'])) {
            $data['price'] = $post['price'];
            $user = session($post['token']);
            $data['userid']=$user['userInfo']['id'];
            $data['type']=$user['userInfo']['groupid'];
            $data['txnumber'] = md5($data['userid'] . $data['type'] . time());
            $data['token'] = md5($data['userid'] . time());
            $data['status']=1;
            $data['del'] = 0;
            $userModel = new UserModel();
            $uwhere['id'] = $user['userInfo']['id'];
            $uwhere['status'] = 2;
            $uwhere['del'] = 0;
            $user = $userModel->MFind($uwhere);
            if ($user && $user['price'] - $data['price'] >= 0) {
                $changeuser['price'] = $user['price'] - $data['price'];
                $changeuser['txprice'] = $user['txprice'] + $data['price'];
//                $changeuser['jifen']=$user['jifen']+$data['price'];
                $uwhere['price'] = $user['price'];
                $this->startTrans();
                $res = $userModel->MUpdate($uwhere, $changeuser);
                if ($res) {
                    $res = $this->MAdd($data);
                    //记入日志
                    $log['addtime'] = time();
                    $log['userid'] = $user['id'];
                    $log['orderid'] = $res;
                    $log['status']=2;
                    $log['type'] = $user['groupid'];
                    $res3 = (new OrderLog())->setOrderLog($log);
                    if ($res3) {
                        $this->commit();
                        return $res;
                    } else {
                        $this->rollback();
                        $this->error = "添加失败";
                        return false;
                    }
                } else {
                    $this->rollback();
                    $this->error = "网络错误";
                    return false;
                }
            } else {
                $this->error = "余额不足,不能提现";
                return false;
            }
        } else {
            $this->error = "缺少必要参数";
            return false;
        }
    }

    /**
     * 获取提现列表
     * @return bool|string
     */
    public function GetList()
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);

        $where = [];
        $where[] = ['a.del','=',0];
        if (isset($post['operation']) ){
            if($post['operation'] == 1) { //提现列表
                $where[] = ['a.operation','=',0];
            }else if($post['operation'] == 2) { //积分列表
                $where[] = ['a.operation','=',3];
            }else if($post['operation'] == 3) { //收入列表
                $where[] = ['a.operation','=',1];
            }
        }else{ //收入和提现
            $where[] = ['a.operation','in','0,1'];
        }
        if(isset($post['start_time'])) $where[] = ['a.create_time','>',strtotime($post['start_time'])];
        if(isset($post['end_time'])) $where[] = ['a.create_time','<',strtotime($post['end_time'])];

        if (array_key_exists("status", $post) && $post['status'] != "") {
            $where['a.status'] = $post['status'];
        }
        if (array_key_exists("token", $post) && $post['token'] != "") {
            $user = session($post['token']);
            $where['a.userid'] = $user['userInfo']['userid'];
        }
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $table1 = "lj_tixian";
        $table2 = 'lj_user';
        $table1n = "userid";
        $table2n = "id";
        $field = array("a.id,a.token,a.txnumber,a.create_time,w.name,w.price,w.txprice");
        $res = $this->MDBSelect($table1, $table2, $table1n, $table2n, $where, $config, $order = array('a.id', 'desc'), $field);
        if ($res) {
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }

}