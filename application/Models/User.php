<?php
namespace app\Models;
use app\validate\ForgetPass;
use app\validate\LimitValidate;
use app\validate\LoginChange as LoginChangeValidate;
use app\validate\LoginValidate;
use app\validate\TokenValidate;
use app\validate\UserAdd;
use app\validate\UserChange;
use app\wechat\controller\api\Tools;
use app\wechat\service\WechatService;
use think\Exception;
use think\facade\Request;

class User extends BaseModel
{
    protected $table = 'lj_user';
    /**
     * 添加门店
     * @return bool|int|string
     */
    public function AddOne()
    {
        $post = Request::post();
        (new UserAdd())->goCheck($post);
        $SmsModel = new Sms();
        $sms_w = [];
        $sms_w[] = ['phone', '=', $post['phone']];
        $sms_w[] = ['create_time', '>', time() - 300];
        $sms_res = $SmsModel->MFind($sms_w,"id desc");
        if (empty($sms_res) || $sms_res['code'] != $post['code']) {
            $this->error = '验证码错误';
            return false;
        }
        $zcdInfo="";
        $region_user="";
        $mcont = [];
        $mcont['phone'] = $post['phone'];
        $mcont['del'] = 0;
        $fadmin = $this->MFind($mcont);
        if ($fadmin) {
            $this->error = "此用户已存在";
            return false;
        } else {
//            $region = [];
//            $region[] = $post['province'];
//            $region[] = $post['city'];
//            $region[] = $post['county'];
//            $region_ids = [];
//            $region_ids[] = ['regionid', '=', implode(',', $region)];
//            $region_ids[] = ['del', '=', '0'];
//            $region_ids[] = ['status', '=', '1'];
//            $region_model = new ReGroup();
//            $region_user = $region_model->MFind($post['county'], '', 'regroupid');
            $rwhere1[]=['region',"=",$post['county']];
            $rwhere1[] = ['del', '=', 0];
            $rwhere1[]=['groupid','=',"3"];
            $region_user1=$this->MFind($rwhere1);

            if (empty($region_user1)){
                $rwhere2[]=['region',"=",$post['city']];
                $rwhere2[] = ['del', '=', 0];
                $rwhere2[]=['groupid','=',"3"];
                $region_user2=$this->MFind($rwhere2);
                if(empty($region_user2)){
                    $rwhere3[]=['region',"=",$post['province']];
                    $rwhere3[] = ['del', '=', 0];
                    $rwhere3[]=['groupid','=',"3"];
                    $region_user=$this->MFind($rwhere3);
                    if($region_user){
                        $this->error = '该地区暂不开放注册功能';
                        return false;
                    }else{
                        $region_user=$region_user;
                        $zcdInfo=  $region_user2;
                    }
                }else{
                    $region_user=$region_user2;
                    $zcdInfo=  $region_user2;
                }
            }else{
                $region_user=$region_user1;
                $zcdInfo=  $region_user1;
            }
            if ($zcdInfo==""||$region_user=="") {
                $this->error = '该地区暂无暂存点';
                return false;
            }
            $post['token'] = md5(time());
            $post['pwd'] = md5($post['pwd']);
            $post['region'] = $region_user['region'];
            $post['upid'] = $zcdInfo['id'];
            $post['price']=0;
            $post['dprice']=0;
            $temp=$post['longitude'];
            $post['longitude']=$post['latitude'];
            $post['latitude']=$temp;
            $res = $this->MAdd($post);
            if ($res) {
                $Message_model = new Message();
                $str = "用户" . $post['realname'] . "申请注册名为" . $post['zhicheng'] . "的门店";
                $res1 = $Message_model->AddMessage("3", $zcdInfo['id'], '注册申请', $str, 2, $res);
                testmessage("新门店注册申请",$post['realname'],"","",$zcdInfo['openid'],1);
                return $res;
            } else {
                $this->error = "添加失败";
                return false;
            }
        }
    }
    /**
     * 暂存点添加业务员
     * @return bool|int|string
     */
    public function ZAddYWOne()
    {
        $post = Request::post();
        // (new UserAdd())->goCheck($post);
        $mcont = [];
        $mcont['phone'] = $post['phone'];
        $mcont['del'] = 0;
        $fadmin = $this->MFind($mcont);
        if ($fadmin) {
            $this->error = "此用户已存在";
            return false;
        } else {
            $upuser=session($post['token'])['userInfo'];
            $data['token'] = md5(time());
            $data['pwd'] = md5($post['pwd']);
            $data['region'] = $upuser['region'];
            $data['upid'] = $upuser['id'];
            $data['name'] = $post['name'];
            $data['realname'] = $post['realname'];
            $data['phone'] = $post['phone'];
            $data['zhicheng'] = $post['zhicheng'];
            $data['openid'] = $post['openid'];
            $data['status'] = 2;
            $data['groupid'] = 2;
            $res = $this->MAdd($data);
            if ($res) {
                return $res;
            } else {
                $this->error = "添加失败";
                return false;
            }
        }
    }
    /**
     * 主管添加暂存点
     * @return bool|int|string
     */
    public function ZAddZCOne()
    {
        $post = Request::post();
        // (new UserAdd())->goCheck($post);
        if(array_key_exists("pwd",$post)&&array_key_exists("token",$post)&&array_key_exists("region",$post)&&array_key_exists("name",$post)&&array_key_exists("realname",$post)&&array_key_exists("phone",$post)&&array_key_exists("zhicheng",$post)){
            $mcont = [];
            $mcont['phone'] = $post['phone'];
            $mcont['del'] = 0;
            $fadmin = $this->MFind($mcont);
            if ($fadmin) {
                $this->error = "此用户已存在";
                return false;
            } else {
                $mcont = [];
                $mcont['region'] = $post['region'];
                $mcont['del'] = 0;
                $fadmin = $this->MFind($mcont);
                if ($fadmin) {
                    $this->error = "此地区已经存在暂存点,请切换地区";
                    return false;
                } else {
                    $upuser = session($post['token'])['userInfo'];
                    $data['token'] = md5(time());
                    $data['pwd'] = md5($post['pwd']);
                    $data['region'] = $post['county'];
                    $data['upid'] = $upuser['id'];
                    $data['name'] = $post['name'];
                    $data['realname'] = $post['realname'];
                    $data['phone'] = $post['phone'];
                    $data['zhicheng'] = $post['zhicheng'];
                    $data['groupid'] = 3;
                    $data['status'] = 2;
                    $res = $this->MAdd($data);
                    if ($res) {
                        return $res;
                    } else {
                        $this->error = "添加失败";
                        return false;
                    }
                }
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    /**
     * 后台添加任意用户
     * @return bool|int|string
     */
    public function AddOther()
    {
        $post = Request::post();
        if(array_key_exists('token',$post)&&$post['token']!=""){
            $upuser=session($post['token'])['userInfo'];
            if($upuser['groupid']>3){
                // (new UserAdd())->goCheck($post);
                $mcont = [];
                $mcont['phone'] = $post['phone'];
                $mcont['del'] = 0;
                $fadmin = $this->MFind($mcont);
                if ($fadmin) {
                    $this->error = "此用户已存在";
                    return false;
                } else {
                    $post['token'] = md5(time());
                    $post['pwd'] = md5($post['pwd']);
                    $post['region'] = $upuser['regroupid'];
                    $post['upid'] = $upuser['upid'];
                    $post['status'] = 2;
                    $post['price'] = 0;
                    $post['jifen'] = 0;
                    $post['dprice'] = 0;
                    $res = $this->MAdd($post);
                    if ($res) {
                        return $res;
                    } else {
                        $this->error = "添加失败";
                        return false;
                    }
                }
            }else{
                $this->error = "权限不足";
                return false;
            }
        }else{
            $this->error = "缺少必要参数";
            return false;
        }
    }
    /**
     * 后台添加账号
     */
    public function addUser()
    {
        $post = Request::post();
        (new UserAdd())->goCheck($post);
        $twhere['id']=$post['type'];
        $tres=$this->MFind($twhere);
        if(!$tres){
            $this->error = "添加权限不存在";
            return false;
        }
        if ($post['type'] == 2||$post['type'] == 1) { //业务员
            if (empty($post['upid'])) {
                $this->error = "请选择所属用户";
                return false;
            }else{
                $uwhere['id']=$post['upid'];
                $uwhere['status']=2;
                $ures=$this->MFind($uwhere);
                if(!$ures){
                    $this->error = "所属用户不存在";
                    return false;
                }elseif($ures['groupid']!=2&&$ures['groupid']!=3){
                    $this->error = "此用户下不能添加用户";
                    return false;
                }elseif($ures['county']!=$ures['region']){
                    $this->error = "添加用户不在选择用户的所属范围内";
                    return false;
                }
            }
        }
        $mcont['phone'] = $post['phone'];
        $mcont['del'] = 0;
        $fadmin = $this->MFind($mcont);
        if ($fadmin) {
            $this->error = "此用户名或真实姓名已经存在";
            return false;
        } else {
            $post['token'] = md5(time());
            $post['pwd']=md5($post['pwd']);
            $post['openid']="shadow";
            $res = $this->MAdd($post);
            if ($res) {
                return $res;
            } else {
                $this->error = "添加失败";
                return false;
            }
        }
    }

    //组合更新数据(管理层可修改)
    public function CreateData($post)
    {
        $data['name'] = $post['name'];
        $data['phone'] = $post['phone'];
        $data['realname'] = $post['realname'];
        $data['zhicheng'] = $post['zhicheng'];
        if(array_key_exists("city",$post)&&$post['city']!="") {
            $data['province'] = $post['province'];
        }
        if(array_key_exists("city",$post)&&$post['city']!="") {
            $data['city'] = $post['city'];
        }
        if(array_key_exists("county",$post)&&$post['county']!="") {
            $data['county'] = $post['county'];
        }
        if(array_key_exists("address",$post)&&$post['address']!="") {
            $data['address'] = $post['address'];
        }
        if(array_key_exists("longitude",$post)&&$post['longitude']!="") {
            $data['longitude'] = $post['longitude'];
        }
        if(array_key_exists("latitude",$post)&&$post['latitude']!="") {
            $data['latitude'] = $post['latitude'];
        }
        if (array_key_exists("pic", $post) && $post['pic'] != "") {
            $data['pic'] = $post['pic'];
        }
        if (array_key_exists("saleid", $post) && $post['saleid'] != "") {
            $data['saleid'] = $post['saleid'];
        }
        if (array_key_exists('tempid', $post) && $post['tempid'] != "") {
            $data['tempid'] = $post['tempid'];
        }
        if (array_key_exists('upid', $post) && $post['upid'] != "") {
            $data['upid'] = $post['upid'];
        }
        if (array_key_exists('region', $post) && $post['region'] != "") {
            $data['region'] = $post['region'];
        }
        return $data;
    }

    //获取根据城市等数据获取地区组
    public function GetRegionId($data)
    {
        $region = [];
        $region[] = $data['province'];
        $region[] = $data['city'];
        $region[] = $data['county'];
        $region_ids = [];
        $region_ids[] = ['regionid','=',implode(',',$region)];
        $region_ids[] = ['del','=','0'];
        $region_ids[] = ['status','=','1'];
        $region_model = new ReGroup();
        $region_user = $region_model->MFind($region_ids,'','regroupid');
        if(empty($region_user)){
            $this->error = '该地区暂不开放注册功能';
            return false;
        }
        return $region_user;
    }
    //修改管理员信息
    public function ChangeOne()
    {
        $post = Request::post();
        (new UserChange())->goCheck($post);
        $mcont['name'] = $post['name'];
        $mcont['status'] = 2;
        $mcont['del'] = 0;
        $fadmin = $this->MFind($mcont);
        $token['token'] = $post['token'];
        $fadmin1 = $this->MFind($token);
        //组合更新数据
        $data = $this->CreateData($post);
        if ($fadmin) {
            $acont['id'] = $fadmin1['id'];
            $res = $this->MUpdate($acont, $data);
            if ($res) {
                return $res;
            } else {
                $this->error = "修改失败";
                return false;
            }
        } else {
            $acont['id'] = $fadmin1;
            $res = $this->MUpdate($acont, $data);
            if ($res) {
                return $res;
            } else {
                $this->error = "修改失败";
                return false;
            }
        }
    }

    //组合更新数据(普通用户修改)
    public function CreateUserData($post)
    {
        $data['name'] = $post['name'];
        $data['phone'] = $post['phone'];
        $data['realname'] = $post['realname'];
        $data['zhicheng'] = $post['zhicheng'];
        $data['province'] = $post['province'];
        $data['city'] = $post['city'];
        $data['county'] = $post['county'];
        $data['address'] = $post['address'];
        $data['longitude'] = $post['longitude'];
        $data['latitude'] = $post['latitude'];
        if (array_key_exists("pic", $post) && $post['pic'] != "") {
            $data['pic'] = $post['pic'];
        }
        return $data;
    }

    //修改管理员信息
    public function ChangeUserOne()
    {
        $post = Request::post();
        (new UserChange())->goCheck($post);
        $mcont['name'] = $post['name'];
        $mcont['status'] = 2;
        $mcont['del'] = 0;
        $user = session("user");
        $fadmin = $this->MFind($mcont);
        //组合更新数据
        $data = $this->CreateUserData($post);
        if ($fadmin) {
            if ($fadmin['id'] != $user['userInfo']['id']) {
                $this->error = "此用户名已经存在";
                return false;
            } else {
                $acont['id'] = $user['userInfo']['id'];
                $res = $this->MUpdate($acont, $data);
                if ($res) {
                    return $res;
                } else {
                    $this->error = "修改失败";
                    return false;
                }
            }
        } else {
            $acont['id'] = $user['userInfo']['id'];
            $res = $this->MUpdate($acont, $data);
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
        if(array_key_exists("token",$post)&&$post['token']!=""){
            $user=session($post['token']);
            if($user['userInfo']['groupid']==3){
                $where['groupid']=1;
                $where['upid']=$user['userInfo']['id'];
            }
            if($user['userInfo']['groupid']==2){
                $where['groupid']=1;
                $where['upid']=$user['userInfo']['id'];
            }
        }
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $field = array("token", "name", "phone", 'id', 'realname', 'pic', 'zhicheng', 'groupid', 'jifen', 'price', 'txprice','address');
        if(array_key_exists("status",$post)&&$post['status']!="") {
            $where['status'] = $post['status'];
        }
        $where['del'] = 0;
        if (array_key_exists("groupid", $post) && $post['groupid'] != "") {
            $where['groupid'] = $post['groupid'];
        }
        if (array_key_exists("daili", $post) && $post['daili'] != "") {
            $where['daili'] = $post['daili'];
        }

        if(array_key_exists("timesearch", $post) && $post['timesearch'] != ""&&array_key_exists("starttime", $post) && $post['starttime'] != ""&&array_key_exists("endtime", $post) && $post['endtime'] != ""){
            if($post['starttime']<$post['endtime']){
                $res = $this->MBetweenTimeS($where,$post['timesearch'],$post['starttime'],$post['endtime'],$config);
            }else{
                $this->error="所传时间有误";
                return false;
            }
        }else{
            $res = $this->MLimitSelect($where, $config, "id desc", $field);
        }
        if ($res) {
            $GroupM = new Group();
            foreach ($res['data'] as $key => $value) {
                $gwhere['id'] = $value['groupid'];
                $groupinfo = $GroupM->MFind($gwhere);
                if ($groupinfo) {
                    $res['data'][$key]['groupname'] = $groupinfo['name'];
                }
            }
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }

    //删除用户
    public function DeleteOne()
    {
        $post = Request::post();
        if (array_key_exists("token", $post)) {
            $mcont['token'] = $post['token'];
            $res = $this->MDelete($mcont);
            if ($res) {
                return $res;
            } else {
                $this->error = "删除失败";
                return false;
            }
        } else {
            $this->error = "缺少必要参数";
            return false;
        }
    }

    public function login()
    {
        $post = Request::post();
        (new LoginValidate())->goCheck($post);
        if(array_key_exists("openid",$post)&&$post['openid']!=""){
            $acont['name'] = $post['name'];
//            $acont['status'] = 2;
            $acont['del'] = 0;
            $admin = $this->MFind($acont);
            if (!$admin) {
                $this->error = "帐号不存在";
                return false;
            } else{
                if($admin['status']==1){
                    $this->error = "账号待审核";
                    return false;
                }else if($admin['status']!=2){
                    $this->error = "账号异常请联系管理";
                    return false;
                }
                if (md5($post['pwd']) !== $admin['pwd']) {
                    $this->error = "用户名或密码错误";
                    return false;
                } else {
                    //获取权限
                    $tempauthList = (new Rule())->getAuthList($admin['groupid']);
                    $AuthList = [];
                    foreach ($tempauthList as $k => $v) {
                        if($v['url']){
                            $AuthList[] = $v['url'];
                        }
                    }
                    if($post['openid']!="shadow"){
                        $data['openid']=$post['openid'];
                        $this->MUpdate($acont,$data);
                    }
                    //查询最新的公告
//                    $messagem=new Message();
//                    $where['type']=0;
//                    $message=$messagem->MFind($where,"id desc");
//                    if(strlen($message['title'])>26){
//                        $message['title']=mb_substr($message['title'],0,26)."....";
//                    }
//                    $admin['notice']=$message;
                    $upcont['id']=$admin['upid'];
                    $UpUser=$this->MFind($upcont);
                    if($UpUser){
                        $admin['upphone']=$UpUser['phone'];
                    }else{
                        $admin['upphone']="";
                    }
                    $zcont['groupid']=6;
                    $ZUser=$this->MFind($zcont);
                    if($ZUser){
                        $admin['zhuguanphone']=$ZUser['phone'];
                    }else{
                        $admin['zhuguanphone']="";
                    }
                    $cache['userInfo'] = $admin;
//                    $cache['AuthList'] = $AuthList;
                    $cache['time'] = time();
                    $cache['authKey'] = md5($admin['id'] . $admin['name'] . $admin['pwd'] . $cache['time']);
                    session($cache['authKey'], $cache, 'think');
                    $retrospect_model = (new SystemConfig())->getSystemConfig('retrospect_model'); //是否开启溯源模式  1不开启 2开启
                    $cache['retrospect_model'] = $retrospect_model;
                    unset($cache['userInfo']['id']);
                    unset($cache['userInfo']['pwd']);
                    unset($cache['AuthList']);
                    unset($cache['time']);
                    $data['huan']=0;
                    $this->MUpdate($acont,$data);
                    return $cache;
                }
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }

    //退出登录
    public function logout()
    {
        $post = Request::post('token');
        session($post['token'],null);
//        session($post["token"], "");
//        $where['id']=$user['id'];
//        $data['huan']=1;
//        $this->MUpdate($where,$data);
        return 1;
    }
    //更换微信
    public function changewx()
    {
        $post = Request::post();
        if(array_key_exists("openid",$post)&&$post['openid']!=""&&array_key_exists("name",$post)&&array_key_exists("pwd",$post)&&array_key_exists("token",$post)) {
            $where1['name'] = $post['name'];
            $where1['pwd'] = md5($post['pwd']);
            $user11 = $this->MFind($where1);

            if($user11){
                $where2['openid'] = $post['openid'];
                $where2['id'] = ['neq',$post['openid']];
                $user2 = $this->MFind($where2);
                if ($user11 && $user2) {
                    Back(200,"存在已有账号,是否继续替换");
                } else {
                    $this->startTrans();
                    $user = session($post['token']);
                    $where['id'] = $user['userInfo']['id'];
                    $where['openid']=$post['openid'];
                    $data['openid'] = "";
                    $user2 = $this->MFind($where);
                    if($user2){
                        if($user2['openid']!=""){
                            $user2 = $this->MUpdate($where, $data);
                        }
                    }else{
                        Back(200,"请用绑定的微信,登录换绑");
                    }
                    $data['openid'] = $post['openid'];
                    $user1 = $this->MUpdate($where1, $data);
                    if ($user1 && $user2) {
                        session($post['token'], null);
                        $messagem=new Message();
                        $where3['type']=0;
                        $message=$messagem->MFind($where3,"id desc");
                        if(strlen($message['title'])>26){
                            $message['title']=mb_substr($message['title'],0,26)."....";
                        }
                        $user11['notice']=array();
                        $user11['notice']=$message;
                        $cache['userInfo'] = $user11;
                        $cache['time'] = time();
                        $cache['authKey'] = md5($user11['id'] . $user11['name'] . $user11['pwd'] . $cache['time']);
                        session($cache['authKey'], $cache, 'think');
                        $retrospect_model = (new SystemConfig())->getSystemConfig('retrospect_model'); //是否开启溯源模式  1不开启 2开启
                        $cache['retrospect_model'] = $retrospect_model;
                        unset($cache['userInfo']['id']);
                        unset($cache['userInfo']['pwd']);
                        unset($cache['AuthList']);
                        unset($cache['time']);
                        $this->commit();
                        return $cache;
                    } else {
                        $this->rollback();
                        $this->error = "修改失败";
                        return false;
                        exit;
                    }
                }
            }else{
                $this->error = "切换账号不存在";
                return false;
                exit;
            }
        }else{
            $this->error = "缺少参数";
            return false;
            exit;
        }
    }
    //确定更换微信
    public function qdchangewx()
    {
        $post = Request::post();
        if(array_key_exists("openid",$post)&&array_key_exists("name",$post)&&array_key_exists("pwd",$post)&&array_key_exists("token",$post)) {
            $where2['openid'] = $post['openid'];
            $user2 = $this->MFind($where2);
            if ($user2) {
                $data['openid'] = "";
                $this->startTrans();
                $user2 = $this->MUpdate($where2, $data);
                if ($user2) {
                    $data['openid'] = $post['openid'];
                    $where1['name'] = $post['name'];
                    $where1['pwd'] = md5($post['pwd']);
                    $user11 = $this->MFind($where1);
                    if($user11){
                        $user1 = $this->MUpdate($where1, $data);
                        if ($user1) {
                            session($post['token'],null);
                            $messagem=new Message();
                            $where3['type']=0;
                            $message=$messagem->MFind($where3,"id desc");
                            if(strlen($message['title'])>26){
                                $message['title']=mb_substr($message['title'],0,26)."....";
                            }
                            $user11['notice']=array();
                            $user11['notice']=$message;
                            $cache['userInfo'] = $user11;
                            $cache['time'] = time();
                            $cache['authKey'] = md5($user11['id'] . $user11['name'] . $user11['pwd'] . $cache['time']);
                            session($cache['authKey'], $cache, 'think');
                            $retrospect_model = (new SystemConfig())->getSystemConfig('retrospect_model'); //是否开启溯源模式  1不开启 2开启
                            $cache['retrospect_model'] = $retrospect_model;
                            unset($cache['userInfo']['id']);
                            unset($cache['userInfo']['pwd']);
                            unset($cache['AuthList']);
                            unset($cache['time']);
                            $this->commit();
                            return $cache;
                        } else {
                            $this->rollback();
                            $this->error = "修改失败";
                            exit;
                        }
                    }else{
                        $this->rollback();
                        $this->error = "账号密码错误";
                        exit;
                    }
                }else{
                    $this->error = "修改失败";
                    exit;
                }
            } else {
                $data['openid'] = $post['openid'];
                $where1['name'] = $post['name'];
                $where1['pwd'] = md5($post['pwd']);
                $user11 = $this->MFind($where1);
                $user1 = $this->MUpdate($where1, $data);
                if ($user1) {
                    session($post['token'],null);
                    $messagem=new Message();
                    $where3['type']=0;
                    $message=$messagem->MFind($where3,"id desc");
                    if(strlen($message['title'])>26){
                        $message['title']=mb_substr($message['title'],0,26)."....";
                    }
                    $user11['notice']=array();
                    $user11['notice']=$message;
                    $cache['userInfo'] = $user1;
                    $cache['time'] = time();
                    $cache['authKey'] = md5($user11['id'] . $user11['name'] . $user11['pwd'] . $cache['time']);
                    session($cache['authKey'], $cache, 'think');
                    $retrospect_model = (new SystemConfig())->getSystemConfig('retrospect_model'); //是否开启溯源模式  1不开启 2开启
                    $cache['retrospect_model'] = $retrospect_model;
                    unset($cache['userInfo']['id']);
                    unset($cache['userInfo']['pwd']);
                    unset($cache['AuthList']);
                    unset($cache['time']);
                    return $cache;
                } else {
                    $this->error = "修改失败";
                    exit;
                }
            }
        }else{
            $this->error = "缺少参数";
            exit;
        }
    }
    //修改登录密码与账号
    public function UpPwd()
    {
        $post = Request::post();
        (new LoginChangeValidate())->goCheck($post);
        $seadmin = session($post["token"]);
        $acont['id'] = $seadmin['userInfo']['id'];
        $admin = $this->MFind($acont);
        if ($admin) {
            if ($admin['name'] == $post['name'] && $admin['pwd'] == $post['oldpwd']) {
                $this->error = "密码错误";
                return false;
            } else {
                $data['name'] = $post['name'];
                $data['pwd'] = $post['pwd'];
                $res = $this->MUpdate($data, $acont);
                if ($res) {
//                    $cache['userInfo'] = $data;
//                    $cache['time'] = time();
//                    $cache['authKey'] = md5($admin['ad_id'] . $data['name'] . $data['pwd'] . $cache['time']);
//                    session('user', $cache, 'think');
//                    return $cache['authKey'];
                    return $res;
                } else {
                    $this->error = "修改失败";
                    return false;
                }
            }
        } else {
            $this->error = "网络错误,请重新登录";
            return false;
        }
    }
    //根据类型获得
    public function GetTypeList($type, $userid = "", $findusername = "")
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);
        $where['type'] = $type;
        $where['status'] = 1;
        $where['del'] = 0;
        if ($userid != '') {
            $where[$findusername] = $userid;
        }
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
        $res = $this->MLimitSelect($where, $config);
        return $res;
    }
    //根据id修改用户信息
    public function UpdateOneById()
    {
        $post = Request::post();
        $user =session($post['token']);
        $where['id'] = $user['userInfo']['id'];
        $res = $this->MUpdate($where, $post);
        if ($res) {
            return $res;
        } else {
            $this->error = '修改失败';
            return false;
        }
    }

    //注册门店
//    public function Register()
//    {
//
//        $post = Request::post();
//        (new UserAdd())->goCheck($post);
//        if($post['code']=="xihao"){
//            $mcont['name'] = $post['name'];
//            $mcont['realname'] = $post['realname'];
//            $mcont['del'] = 0;
//            $fadmin = $this->MFind($mcont);
//            $tool=new Tools();
//            $tool->oauth();
//            $openid=WechatService::getAppid();
//            if($openid){
//                if ($fadmin) {
//                    $this->error = "此用户名或真实姓名已经存在";
//                    return false;
//                } else {
//                    $post1['token'] = md5(time());
//                    $post1['zhicheng'] = $post['zhicheng'];
//                    $post1['realname'] = $post['realname'];
//                    $post1['address'] = $post['address'];
//                    $post1['pwd'] = $post['pwd'];
//                    $post1['name'] = $post['name'];
//                    $post1['phone'] = $post['phone'];
//                    $post1['status'] = 0;
//                    $post1['openid']=$openid;
//                    $this->startTrans();
//                    $res = $this->MAdd($post1);
//                    $message = new Message();
//                    $str = "用户" . $post['realname'] . "申请注册名为" . $post['zhicheng'] . "的门店";
//                    $res1 = $message->AddMessage("", '', '注册申请', $str);
//                    if ($res && $res1) {
//                        $this->commit();
//                        return $res;
//                    } else {
//                        $this->rollback();
//                        $this->error = "添加失败";
//                        return false;
//                    }
//                }
//            }else{
//                $this->error="请在微信中打开";
//                return false;
//            }
//        }else{
//            $this->error="验证码错误";
//            return false;
//        }
//    }
    //审核
    public function Confirm()
    {
        $post = Request::post();
        (new TokenValidate())->goCheck($post);
        if ((array_key_exists('status', $post) && $post['status'] != "")) {
            if ($post['status'] == 1) {
                $data['status'] = 2;
                $msg="您的注册申请已经被审批通过,请及时登录查看.";
            } else {
                $data['status'] = 0;
                $msg="您的注册申请已经被驳回,请确认信息后重新申请.";
                if(array_key_exists('remark', $post) && $post['remark'] != ""){
                    $data['reason'] = $post['remark'];
                }
            }
            $where['token'] = $post['token'];
            if(array_key_exists("saleid",$post)&&$post['saleid']!=""){
                $data['upid']=$post['saleid'];
            }
            if(array_key_exists("address",$post)&&$post['address']!=""){
                $data['address']=$post['address'];
            }
            if(array_key_exists("region",$post)&&$post['region']!=""){
                $data['region']=$post['region'];
            }
            if(array_key_exists("longitude",$post)&&$post['longitude']!=""){
                $data['longitude']=$post['longitude'];
            }
            if(array_key_exists("latitude",$post)&&$post['latitude']!=""){
                $data['latitude']=$post['latitude'];
            }
            $res = $this->MUpdate($where, $data);
            if ($res) {
                $user=$this->MFind($where);
                $smsM=new Sms();
                $smsM->SendMessage($user['phone'],$msg);
                return $res;
            } else {
                $this->error = "审核操作失败";
                return false;
            }
        } else {
            $this->error = "缺少必要参数";
            return false;
        }
    }
    //根据用户获取账号
    public function GetByUser()
    {
        $post = Request::post();
        (new LimitValidate())->goCheck($post);
        if(array_key_exists("token",$post)&&$post['token']!="") {
            $user = session($post['token']);
            $where['upid'] = $user['userInfo']['id'];
        }
        if(array_key_exists("status",$post)&&$post['status']!=""){
            $where['status'] = $post['status'];
        }
        if(array_key_exists("groupid",$post)&&$post['groupid']!=""){
            $where['groupid'] = $post['groupid'];
        }else{
            $where['groupid']=2;
        }
        $where['del'] = 0;
        $config['page'] = $post['page'];
        $config['list_rows'] = $post['list_rows'];
//        $field = array("upid","ywyid", "name", "phone", 'id', 'realname', 'pic', 'zhicheng');
        $res = $this->MLimitSelect($where, $config, "id desc");
        if ($res) {
            $res['count']=count($res['data']);
            return $res;
        } else {
            $this->error = "查询失败";
            return false;
        }
    }
    public function ForgetPass()
    {
        $post=Request::post();
        (new ForgetPass())->goCheck($post);
        if($post['newpwd']==$post['renewpwd']&&$post['newpwd']!=""){
            $SmsModel = new Sms();
            $sms_w = [];
            $sms_w[] = ['phone', '=', $post['phone']];
            $sms_w[] = ['create_time', '>', time() - 300];
            $sms_res = $SmsModel->MFind($sms_w,"id desc");
            if (empty($sms_res) || $sms_res['code'] != $post['code']) {
                $this->error = '验证码错误';
                return false;
            }
            $data['pwd']=md5($post['newpwd']);
            $where['phone']=$post['phone'];
            $res=$this->MUpdate($where,$data);
            if($res){
                return $res;
            }else{
                $this->error="修改失败,请检查手机号";
                return false;
            }
        }else{
            $this->error="两次密码不一致";
            return false;
        }
    }
    //获取当前用户信息
    public function GetDQOne()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $user=session($post['token']);
        $uwhere['id']=$user['userInfo']['id'];
        $user=$this->MFind($uwhere);
        if($user){
            return $user;
        }else{
            $this->error="用户信息获取失败";
            return false;
        }
    }
//获取其他用户
    public function GetOne()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token']=$post['token'];
        if(array_key_exists("del",$post)&&$post['del']!=""){
            $mcont['del']=$post['del'];
        }
        if(array_key_exists("status",$post)&&$post['status']!="") {
            $mcont['status'] = $post['status'];
        }
        $res=$this->MFind($mcont);
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //减积分
    public function DeleteJiFen()
    {
        $post=Request::post();
        $userid="";
        if(array_key_exists("token",$post)&&$post['token']!=""){
            $user=session($post['token']);
            $userid=$user['userInfo']['id'];
        }else{
            if(array_key_exists("userid",$post)&&$post['userid']!=""){
                $userid=$post['userid'];
            }else{
                $this->error="缺少必要参数";
                return false;
            }
        }
        $price="";
        if(array_key_exists("price",$post)&&$post['price']!=""&&is_numeric($post['price'])){
            $price=$post['price'];
        }else{
            $price=10;
        }
        $where['id']=$userid;
        $userinfo=$this->MFind($where);
        if($userinfo){
            $userdata['id']=$userinfo['id'];
            $userdata['jifen']=$userinfo['jifen'];
            $data['jifen']=(int)$userinfo['jifen']-(int)$price;
            if($data['jifen']>=0){
                $this->startTrans();
                $res=$this->MUpdate($userdata,$data);
                $log['addtime'] = time();
                $log['userid'] = $userid;
                $log['orderid'] = $userid;
                $log['status']=2;
                $log['jifen']=1;
                $log['jfprice']=$price;
                $log['shenhe']=0;
                $res2 = (new OrderLog())->setOrderLog($log);
                if($res2&&$res){
                    $this->commit();
                    return $res2;
                }else{
                    $this->rollback();
                    $this->error="积分兑换失败";
                    return false;
                }
            }else{
                $this->error="用户积分不足";
                return false;
            }
        }else{
            $this->error="未找到用户";
            return false;
        }
    }
    //积分审核
    public function JiFenShenhe()
    {
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""&&array_key_exists("shenhe",$post)&&$post['shenhe']!=""){
            $log['id']=$post['id'];
            $data['shenhe']=$post['shenhe'];
            $orderlogm=new OrderLog();
            $logifno=$orderlogm->MFind($log);
            if($logifno&&$logifno['jifen']==1){
                $this->startTrans();
                $res=$orderlogm->MUpdate($log,$data);
                if($data['shenhe']==0){
                        $userm=new User();
                        $uwhere['id']=$logifno['userid'];
                        $user=$userm->MFind($uwhere);
                        if($user){
                            $ruwhere['id']=$user['id'];
                            $ruwhere['jifen']=$user['jifen'];
                            $data1['jifen']=$ruwhere['jifen']+$logifno['jfprice'];
                            $res2=$userm->MUpdate($ruwhere,$data1);
                            if($res2){
                                $this->commit();
                                return $res2;
                            }else{
                                $this->rollback();
                                $this->error="缺少必要参数";
                                return false;
                            }
                        }else{
                            $this->rollback();
                            $this->error="缺少必要参数";
                            return false;
                        }
                }else if($data['shenhe']==2){
                    $this->commit();
                    return $res;
                }else{
                    $this->error="参数错误";
                    return false;
                }
            }else{
                $this->error="参数错误1";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //查询所有账号
    public function GetAllUser(){
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $user=session($post['token']);
        if($user['userInfo']['groupid']==3){
            $where['upid']=$user['userInfo']['id'];
            $where['status']=2;
            $where['groupid']=2;
            $res=$this->MSelect($where);
            if($res){
                $name=array();
                $id=array();
                foreach ($res as $key => $value){
                    if($value['realname']!=""){
                        array_push($name,$value['realname']);
                        array_push($id,$value['id']);
                    }
                }
                $res['name']=$name;
                $res['id']=$id;
                return $res;
            }else{
                $this->error="查询失败";
                return false;
            }
        }else{
            $this->error="权限不足";
            return false;
        }
    }
    public function TransferUser(){
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $user=session($post['token']);
        if($user['userInfo']['groupid']>=3){
            if($post['touserid']!=""&&$post['userid']!=""){
                $where['id']=$post['userid'];
                $data['upid']=$post['touserid'];
                $res=$this->MUpdate($where,$data);
                if($res){
                    return $res;
                }else{
                    $this->error="修改失败";
                    return false;
                }
            }else{
                $this->error="缺少必要参数";
                return false;
            }
        }else{
            $this->error="权限不够";
            return false;
        }
    }
}