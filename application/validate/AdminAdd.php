<?php
namespace app\validate;
class AdminAdd extends BaseValidate
{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
        'name' => ['require'],
        'pwd' => ['require'],
        'realname'=>['require'],
        'phone'=>['require'],
        'zhicheng'=>['require'],
        'groupid'=>['require']
    ];
    protected $message = [
        'name.require' => '用户名不能为空',
        'pwd.require' => '密码不能为空',
        'phone.require'=>'手机号不能为空',
        'zhicheng.require'=>'职称不能为空',
        'realname.require'=>'真实姓名不能为空',
        'groupid.require'=>'所属组必须填写'
    ];
}