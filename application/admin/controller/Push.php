<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14
 * Time: 19:46
 */

namespace app\admin\controller;


use app\Controllers\BaseController;

class Push extends BaseController
{
    private $appid = "wxb4483fd1627ab981";
    private $appsecrt = "0271ad968a1da444396b9d5eb05fe49b";
    private $url="http://ljhs.tiyanclub.com/dist/login.html";
    private $picurl="http://ljhs.tiyanclub.com/logo.jpg";
    /**
     * 发送消息到微信
     * @param string $type 消息类型（text|image|voice|video|music|news|mpnews|wxcard）
     * @param array $data 消息内容数据对象
     * @param boolean $isCustom 是否使用客服消息发送
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function sendMessage1($title,$description,$openid)
    {
        $mes = ["articles" => [
            [
                "title" => $title,
                "description" => $description,
                "url" => $this->url,
                "picurl" => $this->picurl,
            ]
        ]];
        $type = "news";
        $getWxAccessToken = $this->getWxAccessToken();
        try {
            $data = ['touser' => $openid, 'msgtype' => $type, "{$type}" => $mes];
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $getWxAccessToken['access_token'];


            $res =  Tools::json2arr(Tools::post($url, true ? Tools::arr2json($data) : $data));
//            Back($res, "登陆成功", '');
            $res=json_decode($res);
            if($res['errcode']==0&&$res['errmsg']=="ok"){
                return 1;
            }else{
                return 0;
            }
        } catch (\Exception $e) {
            if (in_array($e->getCode(), ['40014', '40001', '41001', '42001'])) {
                return 2;
//                [$this->delAccessToken(), $this->isTry = true];
//                return call_user_func_array([$this, $this->currentMethod['method']], $this->currentMethod['arguments']);
            }else{
                return 0;
            }
//            throw new InvalidResponseException($e->getMessage(), $e->getCode());
//            $back= array(
//                'code' => $e->getCode() . '--------' . __LINE__,
//                'msg' => $e
//            );
//            Back($back, "登陆成功", '');
        }
    }

    //微信公众号 token
    private function getWxAccessToken()
    {

        if (session('?accessToken') && !empty(session('accessToken'))) {
            $tokenData=session('access_token');
            return $tokenData['access_token'];
        }
        $tokeUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->appsecrt;
        $tokenResp = file_get_contents($tokeUrl);
        if (!$tokenResp) $this->_error('token 服务器返回失败');
        $tokenData = json_decode($tokenResp, true);
        if (!isset($tokenData['access_token'])) $this->_error($tokenData['errmsg']);
        session('accessToken', $tokenData);
        return $tokenData['access_token'];
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
    /**
     * 发送消息到微信(登录)
     * @param string $type 消息类型（text|image|voice|video|music|news|mpnews|wxcard）
     * @param array $data 消息内容数据对象
     * @param boolean $isCustom 是否使用客服消息发送
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function sendMessage($title,$username,$time,$status,$remark,$openid,$template_id,$type)
    {
        if($type){
            $mes = [
                "first" => [
                    'value' => $title,
                    'color' => '#173177'
                ],
                "keyword1" =>[
                    'value' => $username,
                    'color' => '#173177'
                ],"keyword2" =>[
                    'value' => $time,
                    'color' => '#173177'
                ],"keyword3" =>[
                    'value' => $status,
                    'color' => '#173177'
                ],"remark" =>[
                    'value' => $remark,
                    'color' => '#173177'
                ],
//                "url" => $this->url,
            ];
        }else{
            $mes = [
                "first" => [
                    'value' => $title,
                    'color' => '#173177'
                ],
                "keyword1" =>[
                    'value' => $username,
                    'color' => '#173177'
                ],"keyword2" =>[
                    'value' => $time,
                    'color' => '#173177'
                ],"keyword3" =>[
                    'value' => $status,
                    'color' => '#173177'
                ],"keyword4" =>[
                    'value' => $remark,
                    'color' => '#173177'
                ],"remark" =>[
                    'value' => "请及时查看处理",
                    'color' => '#173177'
                ],
//                "url" => $this->url,
            ];
        }
        $type = "data";
        $getWxAccessToken = $this->getWxAccessToken();
        try {
            $data = ['touser' => $openid, 'template_id'=>$template_id, "{$type}" => $mes,'url'=>$this->url];
//            $data = ['touser' => $openid, 'template_id'=>'uKlPoHQQOc2zvQKNu9sAnHHH-uWSv_QDqgjg0qOg1WU','msgtype' => $type, "{$type}" => $mes];
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $getWxAccessToken;
            $res =  Tools::json2arr(Tools::post($url, true ? Tools::arr2json($data) : $data));
//            $res=json_decode($res);
            if($res['errcode']==0&&$res['errmsg']=="ok"){
                return 1;
            }else{
                return 0;
            }
        } catch (\Exception $e) {
            if (in_array($e->getCode(), ['40014', '40001', '41001', '42001'])) {
                session('accessToken', null);
                return 2;
            }else{
                return 0;
            }
        }
    }
}