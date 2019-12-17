<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/23
 * Time: 14:31
 */

namespace app\admin\controller;


use app\Controllers\BaseController;
use app\Models\Message;
use app\Models\SystemConfig;
use EasyWeChat\Factory;
use think\facade\Cache;

class Index extends BaseController
{

    private $appid = "wxb4483fd1627ab981";
    private $appsecrt = "0271ad968a1da444396b9d5eb05fe49b";

    public function index()
    {
        $post = $this->request->param();
        if (isset($post['code']) && !empty($post['code'])) {
            $accessToken = getAccessToken($this->appid, $this->appsecrt, $post['code']);
//            return $accessToken['openid'];
            $userm=new \app\Models\User();
            $where['openid']=$accessToken['openid'];
            $user=$userm->MFind($where);
            if($user){
                $url="https://api.weixin.qq.com/cgi-bin/user/info";
                $data['access_token']="";
                if (session('accessToken') && !empty(session('accessToken'))) {
                    $temp=session('accessToken');
                    $data['access_token']=$temp['access_token'];
                }else{
                    $temp=$this->getWxAccessToken();
                    $data['access_token']=$temp['access_token'];
                }
                $data['openid']=$accessToken['openid'];
                $data['lang']="zh_CN";
                $back1=curlData($url, $data, $method = 'GET', $type = 'json');
                $back=json_decode($back1,256);
                if($back&&array_key_exists('nickname',$back)){
                    $updata['userinfo']=$back1;
                    $updata['pic']=$back['headimgurl'];
                    $userm->MUpdate($where,$updata);
                }
            }
            if ($accessToken['openid']) {
                if($user&&$user['huan']==0){
                    //查询最新的公告
//                    $messagem=new Message();
//                    $where1['type']=0;
//                    $message=$messagem->MFind($where1,"id desc");
//                    if(strlen($message['title'])>26){
//                        $message['title']=mb_substr($message['title'],0,26)."....";
//                    }
//                    $user['notice']=$message;
                    $upcont['id']=$user['upid'];
                    $UpUser=$this->MFind($upcont);
                    if($UpUser){
                        $user['upphone']=$UpUser['phone'];
                    }else{
                        $user['upphone']="";
                    }
                    $zcont['groupid']=6;
                    $ZUser=$this->MFind($zcont);
                    if($ZUser){
                        $user['zhuguanphone']=$ZUser['phone'];
                    }else{
                        $user['zhuguanphone']="";
                    }
                    $cache['userInfo'] = $user;
                    $cache['time'] = time();
                    $cache['authKey'] = md5($user['id'] . $user['name'] . $user['pwd'] . $cache['time']);
                    session($cache['authKey'], $cache, 'think');
                    $retrospect_model = (new SystemConfig())->getSystemConfig('retrospect_model'); //是否开启溯源模式  1不开启 2开启
                    $cache['retrospect_model'] = $retrospect_model;
                    unset($cache['userInfo']['id']);
                    unset($cache['userInfo']['pwd']);
                    // unset($cache['AuthList']);
                    unset($cache['time']);
                    BackData(200, "获取成功", $cache);
                }else{
                    $data['huan']=0;
                    $userm->MUpdate($where,$data);
                    BackData(200, "获取成功", $accessToken['openid']);
                }
            } else {
                BackData(200, "获取成功", $accessToken['openid']);
            }
//            $this->redirect('http://' . $_SERVER['HTTP_HOST'] . '/dist?openid=' . $accessToken['openid'], 302);
        } else {
            $redirect_uri = urlencode('http://' . $_SERVER['HTTP_HOST'] . '/api/wechat/index');//回调地址
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=123#wechat_redirect"; //state 可任意
            $this->redirect($url, 302);
        }
    }

    public function oauth()
    {
        $tool = new Tools();
        $tool->oauth();
//        $map = ['appid' => WechatService::getAppid()];
        $openid = WechatService::getAppid();
        if ($openid) {
            BackData(200, "获取成功", $openid);
        } else {
            BackData(400, "获取失败", $openid);
        }
    }

    public function jssdk()
    {
        $tool = new \app\wechat\controller\api\Tools();
        $tool->jssdk();
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];

        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = "4f5ds6fd5sfds3afd4165v4w6FGaa45";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function getWxShareSign()
    {
//        $signData = input('');
//        if( !$signData['url'] ){
//            $this->_error('参数错误');
//        }
        $ticket = '';
//        if($redis_ticket = cache::get('wx_share_ticket')){
//            $ticket = $redis_ticket;
//        }else{
        $access_token = $this->getWxAccessToken(); //获取微信access_token
        $ticket = $this->getWxTicket($access_token); //获取微信ticket
//        }
        $signData['appId'] = $this->appid;
        $signData['jsapi_ticket'] = $ticket;
        $signData['noncestr'] = createOrderSn();
        $signData['timestamp'] = time();
        $signData['url']=$_POST['url'];
        $sign = $this->makeWxSha1Sign($signData); // 生成微信签名
        $signData['signature'] = $sign;

        $this->_success($signData); // jsapi_ticket, noncestr, timestamp, sign 都返回给前端，供前端页面微信验签使用
    }

    //生成 sha1 签名
    private function makeWxSha1Sign($arr)
    {
        $str = "";
        //升序数组的键
        $keyArr = [];
        foreach ($arr as $k => $v) {
            array_push($keyArr, $k);
        }
        sort($keyArr);
        reset($keyArr);

        //升序数组的字符串拼接，删除signature
        foreach ($keyArr as $key => $value) {
            if($key!=0){
                $linker = '';
                if ($key != 0) {
                    $linker = '&';
                }
                $str .= $linker . $value . '=' . $arr[$value];
            }
        }
        $str=substr($str,1,strlen($str));
//        log_result('wxshare signStr='.$str);

        //字符串SHA1
        $signature = sha1($str);
        return $signature;
    }

    //微信公众号 票据
    private function getWxTicket($access_token)
    {
        $ticketUrl = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token['access_token'] . '&type=jsapi';
        $ticketResp = file_get_contents($ticketUrl);
        if (!$ticketResp) $this->_error('ticket 获取失败');
        $ticketData = json_decode($ticketResp, true);
        if (isset($ticketData['ticket'])) {
            $ticket = $ticketData['ticket'];
            cache::set('wx_share_ticket', $ticket, 7200);
//            log_result('ticket='.$ticket);
            return $ticket;
        } else {
            $this->_error('ticket 解析错误');
        }

    }

    //微信公众号 token
    private function getWxAccessToken()
    {
//        if (session('accessToken') && !empty(session('accessToken'))) {
//            return session('accessToken');
//        }
        $tokeUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecrt;
        $tokenResp = file_get_contents($tokeUrl);
        if (!$tokenResp) $this->_error('token 服务器返回失败');
        $tokenData = json_decode($tokenResp, true);
        if (!isset($tokenData['access_token'])) $this->_error($tokenData['errmsg']);
        session('accessToken', $tokenData);
        return $tokenData;
    }

// 成功返回封装  json格式的数据给前端
    protected function _success($data = null, $info = '操作成功', $status = 200)
    {
        $result = array(
            'code' => $status,
            'msg' => $info,
            'data' => $data,
        );
        die(json_encode($result));
    }

// 失败返回封装  json格式的数据给前端
    protected function _error($info = '系统错误', $status = 400)
    {
        $result = array(
            'code' => $status,
            'msg' => $info,
        );
        die(json_encode($result));
    }
    public function test(){
        testmessage("门店申请","ceshi ","omnAdwVazqCLkokBzS9DBR8kiDoc");
    }
}