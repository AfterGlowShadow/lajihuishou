<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/1
 * Time: 17:07
 */

namespace app\Models;




use think\facade\Request;

class SystemConfig extends BaseModel
{
    protected $table="lj_system_config";

    /**
     * 根据配置名获取配置参数
     * @param string $name
     */
    public function getSystemConfig($name=''){
        if(empty($name)){
            return false;
        }
        $_where = [];
        $_where[] = ['name','=',$name];
        $res = $this->getValueById($_where,'value');
        return $res;
    }
    /**
     * 获取所有配置信息
     * @param string $name
     */
    public function getSystemConfigList(){
        $_where = [];
        $res=$this->MSelect($_where);
        return $res;
    }
    /**
     * 修改单个配置信息
     * @param string $name
     */
    public function ChangeOneSystemConfigList(){
        $post=Request::post();
        if(!empty($post)&&array_key_exists("value",$post)&&$post['value']!=""&&array_key_exists("name",$post)&&$post['name']!=""){
            $_where['name'] = $post['name'];
            $data['value'] = $post['value'];
            $res=$this->MUpdate($_where,$data);
            if($res){
                return $res;
            }else{
                $this->error="修改失败";
                return false;
            }
        }else{
            $this->error="缺少参数";
            return false;

        }
    }
}