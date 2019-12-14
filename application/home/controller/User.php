<?php
namespace app\home\controller;
use app\Controllers\BaseController;
use app\Models\User as UserModel;
class User extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new UserModel();
    }
    //判断登录
    public function login(){
        $res=$this->Model->login();
        Back($res,"登陆成功",$this->Model->getError());
    }
    //退出登录
    public function logout(){
        $res=$this->Model->logout();
        Back($res,"退出成功",$this->Model->getError());
    }
    //修改登录密码与账号
    public function UpPwd(){
        $res=$this->Model->UpPwd();
        Back($res,"修改成功",$this->Model->getError());
    }
    //获取当前用户的用户信息
    public function GetDQOne()
    {
        $res=$this->Model->GetDQOne();
        Back($res,"查询成功",$this->Model->getError());
    }
    //积分提现
    public function DeleteJiFen()
    {
        $res=$this->Model->DeleteJiFen();
        Back($res,"积分兑换成功",$this->Model->getError());
    }
    //积分审核
    public function JiFenShenhe()
    {
        $res=$this->Model->JiFenShenhe();
        Back($res,"积分兑换成功",$this->Model->getError());
    }


    //根据id修改
    public function UpdateOneById()
    {
        $res=$this->Model->UpdateOneById();
        Back($res,"修改成功",$this->Model->getError());
    }
    //门店注册
    public function Register()
    {
        $res=$this->Model->Register();
        Back($res,"注册成功",$this->Model->getError());
    }
    //通过或驳回审核
    public function Confirm()
    {
        $res=$this->Model->Confirm();
        Back($res,"审核成功",$this->Model->getError());
    }
    //获取用户所有下一级账号
    public function GetByUser()
    {
        $res=$this->Model->GetByUser();
        Back($res,"查询成功",$this->Model->getError());
    }

    /**
     * name: 忘记密码
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function ForgetPass()
    {
        $res=$this->Model->ForgetPass();
        Back($res,"修改成功",$this->Model->getError());
    }
    /**
     * name: 暂存点添加业务员
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function ZAddYWOne()
    {
        // echo "tian";
        $res=$this->Model->ZAddYWOne();
        Back($res,"添加成功",$this->Model->getError());
    }
    /**
     * name: 主管添加暂存点
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function ZAddZCOne()
    {
        $res=$this->Model->ZAddZCOne();
        Back($res,"添加成功",$this->Model->getError());
    }
    /**
     * name: 后天添加任意
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function AddOther()
    {
        $res=$this->Model->AddOther();
        Back($res,"添加成功",$this->Model->getError());
    }
    /**
     * name: 绑定其他微信(没有绑定其他账号则直接绑定)
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function ChangeWx()
    {
        $res=$this->Model->changewx();
        Back($res,"绑定成功",$this->Model->getError());
    }
    /**
     * name: 绑定其他微信(直接修改绑定)
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function QdChangeWx()
    {
        $res=$this->Model->qdchangewx();
        Back($res,"绑定成功",$this->Model->getError());
    }
    /**
     * name: 查询所有下属user(没有分页)
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function GetAllUser()
    {
        $res=$this->Model->GetAllUser();
        Back($res,"查询成功",$this->Model->getError());
    }
    /**
     * name: 转移门店(没有分页)
     * User: Administrator
     * Date: 2019-11-07 17:26
     */
    public function TransferUser()
    {
        $res=$this->Model->TransferUser();
        Back($res,"查询成功",$this->Model->getError());
    }
}