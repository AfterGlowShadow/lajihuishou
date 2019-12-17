<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/25
 * Time: 14:43
 */

namespace app\Models;
require __DIR__ . "/../../vendor/qcloudsms/qcloudsms_php/src/index.php";

use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;

use Qcloud\Sms\VoiceFileUploader;
use Qcloud\Sms\FileVoiceSender;
use Qcloud\Sms\TtsVoiceSender;
use think\facade\Request;

class Sms extends BaseModel
{
    protected $table = "lj_sms";
    // 短信应用SDK AppID
    private $appid = 1400275136; // 1400开头

    // 短信应用SDK AppKey
    private $appkey = "64001b9d5ef4cc62d036124f9679525b";

    // 短信模板ID，需要在短信应用中申请
    private $templateId = 452224;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

    // 签名
    private $smsSign = "石家庄机灵鬼科技"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

    public function index()
    {
        $post = Request::post();
        if (empty($post['mobile'])) {
            $this->error = "手机号必填";
            return false;
        }
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $params = [];
            $params[] = createCode(4);
            $params[] = 5;
            $result = $ssender->sendWithParam("86", $post['mobile'], $this->templateId,
                $params, $this->smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result);

            if ($rsp->result == 0) {
                //加记录
                $post['code'] = $params[0];
                $post['create_time'] = time();
                $post['phone']=$post['mobile'];
                $res = $this->MAdd($post);
                return $res;
            } else {
                $this->error = $rsp->errmsg;
                return false;
            }
        } catch (\Exception $e) {
            echo var_dump($e);
            $this->error = $e->getMessage();
            return false;
        }
    }
    public function SendMessage($phone,$message)
    {
        $post = Request::post();
        if (empty($post['mobile'])) {
            $this->error = "手机号必填";
            return false;
        }
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $params = [];
            $params[] = createCode(4);
            $params[] = 5;
            $result = $ssender->sendWithParam("86", $phone, $this->templateId,
                $params, $this->smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
//            $rsp = json_decode($result);

//            if ($rsp->result == 0) {
//                //加记录
//                $post['code'] = $params[0];
//                $post['create_time'] = time();
//                $post['phone']=$phone;
//                $res = $this->MAdd($post);
//                return $res;
//            } else {
//                $this->error = $rsp->errmsg;
//                return false;
//            }
        } catch (\Exception $e) {
            echo var_dump($e);
            $this->error = $e->getMessage();
            return false;
        }
    }
}