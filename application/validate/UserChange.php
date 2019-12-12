<?php
namespace app\validate;
class UserChange extends BaseValidate
{
    protected $rule = [
        'name' => ['require'],
        'realname'=>['require'],
        'phone'=>['require'],
        'zhicheng'=>['require'],
        'province'=>['require'],
        'city'=>['require'],
        'county'=>['require'],
        'address'=>['require'],
        'longitude'=>['require'],
        'latitude'=>['require'],
    ];
    protected $message = [
        'name.require' => '用户名不能为空',
        'phone.require'=>'手机号不能为空',
        'zhicheng.require'=>'门店名称不能为空',
        'realname.require'=>'真实姓名不能为空',
        'province.require'=>'省不能为空',
        'city.require'=>'市不能为空',
        'county.require'=>'县不能为空',
        'address.require'=>'详细地址不能为空',
        'longitude.require'=>'经度不能为空',
        'latitude.require'=>'纬度不能为空',
    ];
}