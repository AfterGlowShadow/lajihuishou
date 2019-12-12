<?php


namespace app\validate;


class ForgetPass extends BaseValidate
{
    protected $rule = [
        'phone' => ['require'],
        'newpwd' => ['require'],
        'renewpwd' => ['require'],
        'code' => ['require'],
    ];
    protected $message = [
        'phone.require' => '手机号不能为空',
        'newpwd.require' => '新密码不能为空',
        'renewpwd.require' => '确认密码不能为空',
        'code.require' => '手机验证码不能为空',
    ];
}