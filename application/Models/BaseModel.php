<?php
namespace app\Models;

use app\validate\TokenValidate;
use think\Db;
use think\facade\Log;
use think\facade\Request;
use think\Model;
use think\model\Pivot;
class BaseModel extends Pivot{
	protected $autoWriteTimestamp = true;     //开启自动写入时间戳
    protected $createTime = "create_time";            //数据添加的时候，create_time 这个字段不自动写入时间戳
    protected $updateTime = "update_time";
    protected $table = '';

	//添加单个数据
    public function MAdd($data)
    {
        $data=$this->removeid($data);
        return Db::table($this->table)->strict(false)->insertGetId($data);
    }
    //批量添加
    public function MBulkAdd($data)
    {
        foreach ($data as $key =>$value){
            $data[$key]=$this->removeid($value);

        }
        return Db::table($this->table)->insertAll($data);
    }
    public function MFDelete($where)
    {
        return Db::table($this->table)->where($where)->delete();
    }
    //删除单个
    public function MDelete($where)
    {
        return Db::table($this->table)->where($where)->setField("del",1);
    }
    //批量删除
    public function MBulkDelete($where)
    {
        return Db::table($this->table)->where($where)->setField("del",1);
    }
    //修改单个
    public function MUpdate($where,$data)
    {
        $data=$this->removeid($data);
        return Db::table($this->table)->where($where)->data($data)->update();
    }
    //查询单个数据
    public function MFind($where,$order=array(),$field=array())
    {
        return Db::table($this->table)->where($where)->order($order)->field($field)->find();
    }
    //查询所有数据
    public function MSelect($where,$order=array(),$field=array())
    {
        return Db::table($this->table)->where($where)->order($order)->field($field)->select();
    }
    //分页查询数据
    public function MLimitSelect($where=array(),$config,$order=array(),$field=array(),$whereor="")
    {
        if($whereor!=""){
            $res['data']=Db::table($this->table)->where([$where])->whereOr([$whereor])->order($order)->field($field)->limit($config['page']*$config['list_rows'],$config['list_rows'])->select();
            $cont=Db::table($this->table)->where([$where])->whereOr([$whereor])->order($order)->field($field)->limit(0,$config['list_rows'])->count();
        }else{
            $res['data']=Db::table($this->table)->where($where)->order($order)->field($field)->limit($config['page']*$config['list_rows'],$config['list_rows'])->select();
            $cont=Db::table($this->table)->where($where)->order($order)->field($field)->limit(0,$config['list_rows'])->count();
        }
//        print_r($res['data']);
        $res['count']=$cont;
        $res['page']=$config['page'];
        $res['list_rows']=$config['list_rows'];
        $res['total']=ceil($cont/$config['list_rows']);
        if($res){
            return $res;
        }else{
            return "";
        }
    }
    //聚合函数
    public function MSumPrice($where,$search){
        $res="";
        if(!empty($search)){
            $res=Db::table($this->table)
                ->whereBetweenTime("addtime", $search['starttime'], $search['endtime'])->where($where)
                ->sum('jfprice');
        }else{
            $res=Db::table($this->table)
                ->where($where)
                ->sum('jfprice');
        }
        return $res;
    }
    //带时间的查询一个数据
    public function MgetOneByTime($mcont,$time,$field="")
    {
        $find=$this->where($time[0],$time[1],$time[2],'and')->where($mcont[0],$mcont[1])->field($field)->find();
        if($find){
            $find=$find->toArray();
            return $find;
        }else{
            return "";
        }
    }
    //带时间的查询一个数据
    public function MBetweenTime($where,$mcont,$starttime,$endtime,$regionz,$garbageunitid)
    {
        $find=Db::query("SELECT * FROM `lj_garbageprice` WHERE garbageunitid= ".$garbageunitid." AND ".$mcont." >= ".$endtime." AND ".$mcont." < ".$starttime." AND `regionz`='".$regionz."' AND `garbageid` = '".$where."' LIMIT 1");
//        $find=Db::table($this->table)
//            ->whereBetweenTime($mcont,  $endtime,$starttime)->where($where)->order($order)->field($field)
//            ->find();
        return $find;
    }
    //带时间的查询一个数据
    public function MBetweenTimeS($where,$mcont,$starttime,$endtime,$config,$order="id desc",$field="")
    {
        $res=Db::table($this->table)
            ->whereBetweenTime($mcont, $starttime, $endtime)->where($where)->order($order)->field($field)->limit($config['page']*$config['list_rows'],$config['list_rows'])
            ->select();
        $cont=Db::table($this->table)->whereBetweenTime($mcont, $starttime, $endtime)->where($where)->order($order)->field($field)->limit(0,$config['list_rows'])->count();
        $data['count']=$cont;
        $data['page']=$config['page'];
        $data['list_rows']=$config['list_rows'];
        $data['total']=ceil($cont/$config['list_rows']);
        $data['data']=$res;
        if($data){
            return $data;
        }else{
            return "";
        }
    }
    //获取当前时间数据库中有无符合条件时间段
    public function MFHTime($where,$order="",$field="")
    {
        return Db::table($this->table)
            ->whereBetweenTimeField('start_time','end_time')->where($where)->order($order)->field($field)
            ->select();
    }
    public function MFHTimeT($where,$order="",$field="")
    {
        $data=Db::table($this->table)
            ->whereBetweenTimeField('dlstarttime','dlendtime')->where($where)->order($order)->field($field)
            ->select();
        return $data;
    }
// 查询2017年6月1日
    //多表查询数据
    public function MDBSelect($table1,$table2,$table1n,$table2n,$where,$config,$order=array('a.id','asc'),$field=array(),$join="leftjoin"){
        $res['data']=Db::table($table1)
            ->where($where)
            ->alias('a')
            ->$join($table2.' w','a.'.$table1n.' = w.'.$table2n)
            ->order($order[0],$order[1])
            ->limit($config['page']*$config['list_rows'],$config['list_rows'])
            ->field($field)
            ->select();
        $cont=Db::table($table1)
            ->where($where)
            ->alias('a')
            ->$join($table2.' w','a.'.$table1n.' = w.'.$table2n)
            ->count();
        foreach ($res['data'] as $key => $value){
            if(array_key_exists("create_time",$value)){
                $res['data'][$key]['create_time']=date("Y-m-d",$value['create_time']);
            }
        }
        $res['count']=$cont;
        $res['list_row']=$config['list_rows'];
        $res['total']=ceil($cont/$config['list_rows']);
        if($res){
            return $res;
        }else{
            return "";
        }
    }
    //多表查询数据不带分页
    public function MDBSelectAll($table1,$table2,$table1n,$table2n,$where,$order=array('a.id','asc'),$field=array(),$join="leftjoin"){
        $res=Db::name($table1)
            ->where($where)
            ->alias('a')
            ->$join($table2.' w','a.'.$table1n.' = w.'.$table2n)
            ->order($order[0],$order[1])
            ->field($field)
            ->select();
        foreach ($res as $key => $value){
            if(array_key_exists("create_time",$value)){
                $res[$key]['create_time']=date("Y-m-d",$value['create_time']);
            }
        }
        return $res;
    }
    private function removeid($data){
        if(array_key_exists("id",$data)){
            unset($data['id']);
        }
        return $data;
    }
//提出的相同方法
//删除垃圾
    public function DeleteOne(){
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token']=$post['token'];
        $res=$this->MDelete($mcont);
        if($res){
            return $res;
        }else{
            $this->error="删除失败";
            return false;
        }
    }
    //获取单个垃圾
    public function GetOne()
    {
        $post=Request::post();
        (new TokenValidate())->goCheck($post);
        $mcont['token']=$post['token'];
        $mcont['del']=0;
        $mcont['status']=1;
        $res=$this->MFind($mcont);
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //添加一组数据
    public function MSaveList($dataarray){
//        $this->table="lj_".$this->table;
        $res=$this->saveAll($dataarray);
        if($res){
            return $res;
        }else{
            return 0;
        }
    }

    /**
     * 根据某个字段获取某个值
     * @param $_where
     * @param $value
     */
    public function getValueById($_where,$value){
        return Db::table($this->table)->where($_where)->value($value);
    }
}
?>