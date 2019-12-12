<?php
namespace app\admin\controller;
use app\Controllers\BaseController;
use app\Models\Rule as RuleModel;
class Rule extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new RuleModel();
    }
    public function rulelist(){
        $res=$this->Model->RuleList();
        Back($res,"查询成功",$this->Model->getError());
    }
}