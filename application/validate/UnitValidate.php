<?php
	namespace app\validate;
class UnitValidate extends BaseValidate{
	 protected $rule = [
    //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
        'garbageid' => ['require', 'IsInt'],
         'danweiming' => ['require'],
         'transweight' => ['require'],
    ];
    protected $message = [
        'garbageid.require' => '垃圾id必须填写',
        'garbageid.IsInt' => '垃圾id必须是正整数',
        'danweiming.require' => '单位名不能为空',
        'transweight.IsInt' => '转换重量不能为空',
    ];
   
}
?>