<?php
namespace app\validate;
class GarbagePriceAdd extends BaseValidate
{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
//        'garbageid' => ['require'],
//        'regionz' => ['require'],\
        'danwei'=>['require'],
        'start_time' => ['require'],
        'end_time' => ['require'],
    ];
    protected $message = [
//        'garbageid.require' => '必须选择垃圾分类',
//        'regionz.require' => '必须选择范围',
        'start_time.require' => '开始时间不能为空',
        'danwei.require' => '报价不能为空',
        'end_time.IsInt' => '结束时间不能为空',
    ];
}