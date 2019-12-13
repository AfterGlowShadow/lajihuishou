<?php


namespace app\validate;


class Tray extends BaseValidate
{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
        'number' => ['require'],
//        'zweight' => ['require','number'],
//        'znumber' => ['require','number'],
    ];
    protected $message = [
        'number.require' => '仓库编号不能为空',
//        'zweight.require' => '仓库总重量不能为空',
//        'znumber.require' => '仓库总数量不能为空',
//        'zweight.number' => '仓库总重量必须为数字',
//        'zweight.number' => '仓库总数量必须为数字',
    ];
}