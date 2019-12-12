<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24
 * Time: 11:59
 */

namespace app\home\controller;
use app\Controllers\BaseController;
use app\Models\GarbagePrice;
use app\Models\Retrospect as MRetrospect;
use think\facade\Request;

class Retrospect extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new MRetrospect();
    }

    /**
     * 预估报价列表
     * ids门店库存id (逗号分隔)
     */
    public function GetRetrospectList(){
        $post=Request::post();
        if(empty($post['ids'])){
            Back(false,'',"缺少必传参数");
        }
        $return = array();
        $ids_arr = explode(',',$post['ids']);
        foreach ($ids_arr as $k=>$v){
            $mcont['id'] = $v;
            $return[$k]['data'] = $this->Model->MFind($mcont);
            $tempRes = getGarbagePrice($return[$k]['data']["garbageid"],new GarbagePrice());
            if($tempRes['status'] == 0){
                Back(false,'',"暂无报价信息，请稍候再试");
            }
            $return[$k]['data']['price'] = $tempRes['data'];
        }
        Back($return,"获取成功",'');
    }

    /**
     * name: 根据垃圾分类获取报价
     * 参数:ids(分类id 逗号隔开)
     * User: Administrator
     * Date: 2019-11-06 20:02
     */
    public function GetPrice()
    {
        $post=Request::post();
        if(array_key_exists("ids",$post)&&$post['ids']!=""){
            $garbagelist=json_decode($post['ids'],true);
            $back['price']=0;
            $back['garbagelist']=array();
            foreach($garbagelist as $key =>$value){
                $temp=array();
                $temp['danweiming']=$value['danweiming'];
                $temp['id']=$value['id'];
                $value['garbageid']=substr($value['garbageid'],0,strlen($value['garbageid'])-1);
                $tempres=getGarbagePrice($value['garbageid'],$value['danweiming'],new GarbagePrice());
                if($tempres['status'] == 0) {
                    $temp['price']=0;
                }else{
//                    print_r($tempres);
//                    print_r($value);
//                    exit;
                    if($tempres['data']['number']!=""){
                        $temp['price']=bcmul($value['number'], $tempres['data']['number'], 1);
                        $back['price']+= $temp['price'];
                    }else{
                        $temp['price']=0;
                        $back['price']+= 0;
                    }
                }
                array_push($back['garbagelist'],$temp);
            }
            Back($back,"查询成功");
        }else{
            Back(false,'',"缺少必传参数");
        }
    }
    //批量删除
    public function BulkDelete()
    {
        $res = $this->Model->BulkDelete();
        Back($res, "删除成功", $this->Model->getError());
    }
}