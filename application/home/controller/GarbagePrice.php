<?php


namespace app\home\controller;

use app\Models\GarbagePrice as GarbagePriceModel;
use app\Controllers\BaseController;

class GarbagePrice extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new GarbagePriceModel();
    }
}