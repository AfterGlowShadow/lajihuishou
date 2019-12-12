<?php
namespace app\Models;
class City extends BaseModel
{
    protected $table="lj_city";

    /**
     * name:获取省市县列表
     * User: Administrator
     * Date: 2019-11-06 15:03
     * province_list
     * city_list
     * county_list
     */
    public function CityList()
    {
        $where['type']=1;
        $field=array("id,cityname");
        $data['province_list']=$this->formatCity($this->MSelect($where,"",$field));
        $where['type']=2;
        $data['city_list']=$this->formatCity($this->MSelect($where,"",$field));
        $where['type']=3;
        $data['county_list']=$this->formatCity($this->MSelect($where,"",$field));
        if($data['province_list']&&$data['city_list']&&$data['county_list']){
            return $data;
        }else{
            $this->error="网络错误,请稍后再试";
            return false;
        }
    }

    /**
     * name: 格式化city
     * User: Administrator
     * Date: 2019-11-06 15:39
     */
    public function formatCity($data)
    {
        $temp=array();
        if($data){
            foreach ($data as $key =>$value) {

                $temp[$value['id']]=$value['cityname'];
            }
            return $temp;
        }else{
            return $data;
        }
    }
}