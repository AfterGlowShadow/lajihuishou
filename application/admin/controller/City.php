<?php
namespace app\admin\controller;
use app\Controllers\BaseController;
use app\Models\City as CityModel;
class City extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new CityModel();
    }

    public function CityList()
    {
        $res = $this->Model->CityList();
        Back($res, "查询成功", $this->Model->getError());
    }
}