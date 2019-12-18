<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24
 * Time: 13:17
 */

namespace app\validate;


class RetrospectAdd extends BaseValidate
{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
//        'u_id'  => 'require',
        'garbageid' => 'require',
        'number' => 'require',
        'danweiming' => 'require',
    ];
    protected $message = [
//        'u_id.require' => '门店id必填',
        'garbageid.require' => '垃圾分类id必填',
        'number.require' => '垃圾数量必填',
        'danweiming.require' => '单位名必填',
    ];
}