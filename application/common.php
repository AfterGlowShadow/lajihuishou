<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 应用公共文件
use app\admin\controller\Push;
use app\Models\City;
use app\Models\Garbage;
use app\Models\GarbageUnit;
use app\Models\User;
use think\facade\Request;

function BackData($code, $msg, $data = "", $type = JSON_UNESCAPED_UNICODE)
{
    $res['code'] = $code;
    if ($code == 200) {
        $res['msg'] = $msg;
    } else {
        $res['msg'] = $msg;
    }
    $res['data'] = $data;
    echo json_encode($res, $type);
    exit;
}

//判断是否为手机
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

//加密方法
function Encryption($str, $type = "md5", $auth_key = '')
{
    if ($type == 'md5') {
        return '' === $str ? '' : md5(sha1($str) . $auth_key);
    }
}

function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0, $strict = true, $filter = array())
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parent_id = $data[$pid];
            if ($parent_id === null || (int)$root === $parent_id) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parent_id])) {
                    $parent =& $refer[$parent_id];
                    $parent[$child][] =& $list[$key];
                } else {
                    if ($strict === false) {
                        $tree[] =& $list[$key];
                    }
                }
            }
        }
        //剔除数据
        if (count($filter) > 0) {
            foreach ($refer as $key => $data) {
                foreach ($data as $k => $v) {
                    if (in_array($k, $filter)) unset($refer[$key][$k]);
                }
            }
        }
    }
    return $tree;
}

//统一返回函数
function Back($res, $successmessage = "成功", $failmessage = "失败")
{
    if ($res) {
//        if($res==1){
//            BackData("200",$successmessage,'success');
//        }else{
        BackData("200", $successmessage, $res);
//        }
    } else {
        BackData("400", $failmessage);
    }
}

//excel表格处理函数
function read_excel($filename)
{
    $reader = PHPExcel_IOFactory::createReader('Excel2007');
    //载入excel文件
    $excel = $reader->load($filename);
    //读取第一张表
    $sheet = $excel->getSheet(0);
    //获取总行数
    $row_num = $sheet->getHighestRow();
    //获取总列数
    $col_num = $sheet->getHighestColumn();

    $data = []; //数组形式获取表格数据
    for ($col = 'A'; $col <= $col_num; $col++) {
        //从第二行开始，去除表头（若无表头则从第一行开始）
        for ($row = 2; $row <= $row_num; $row++) {
            $data[$row - 2][$col] = $sheet->getCell($col . $row)->getValue();
        }
    }
    foreach ($excel->getSheet(0)->getDrawingCollection() as $k => $drawing) {

        $codata = $drawing->getCoordinates(); //得到单元数据 比如G2单元
        $row = substr($codata, 1, 1);
        $col = substr($codata, 0, 1);
        $filename = $drawing->getIndexedFilename();  //文件名
        ob_start();
        if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
            call_user_func(
                $drawing->getRenderingFunction(),
                $drawing->getImageResource()
            );
            $imageContents = ob_get_contents();
            ob_end_clean();
            switch ($drawing->getMimeType()) {
                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_PNG :
                    $extension = 'png';
                    break;
                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_GIF:
                    $extension = 'gif';
                    break;
                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_JPEG :
                    $extension = 'jpg';
                    break;
            }
            $myFileName = '/ExcelPic/' . md5(time()) . '.' . $extension;
            file_put_contents('./' . $myFileName, $imageContents);
            $data[$row - 2][$col] = $myFileName;
        } else {
            $zipReader = fopen($drawing->getPath(), 'r');
            $imageContents = '';
            while (!feof($zipReader)) {
                $imageContents .= fread($zipReader, 1024);
            }
            fclose($zipReader);
            $extension = $drawing->getExtension();
            $myFileName = '/ExcelPic/' . md5(time() . $codata) . '.' . $extension;
            $data[$row - 2][$col] = $myFileName;
            file_put_contents('./' . $myFileName, $imageContents);
        }
    }
    return $data;
}

//对excel导出的数据进行处理 为searchdata
function ExeclDataToSqlData($data)
{
    $resarray = array();
    foreach ($data as $key => $value) {
        $temp = array();
        if ($value['B'] != "" && $value['C'] != "" && $value['D'] != "" && $value['E'] != "") {
            if (array_key_exists('A', $value) && $value['A'] != "") {
                $temp['goods_pic'] = $value['A'];
            }
            $temp['bar_code'] = $value['B'];
            $temp['shop_name'] = $value['C'];
            $temp['goods_number'] = $value['D'];
            $temp['supplier'] = $value['E'];
            $temp['question'] = $value['F'];
            $temp['question_analysis'] = $value['G'];
            $temp['question_solutions'] = $value['H'];
            $temp['arrival_date'] = $value['I'];
            $temp['feedback_date'] = $value['J'];
            $temp['customer_id'] = $value['K'];
            if (array_key_exists('L', $value) && $value['L'] != "") {
                $temp['question_pic1'] = $value['L'];
            }
            if (array_key_exists('M', $value) && $value['M'] != "") {
                $temp['question_pic2'] = $value['M'];
            }
            if (array_key_exists('N', $value) && $value['N'] != "") {
                $temp['question_pic3'] = $value['N'];
            }
            if (array_key_exists('O', $value) && $value['O'] != "") {
                $temp['question_pic4'] = $value['O'];
            }
            $temp['remark1'] = $value['P'];
            $temp['remark2'] = $value['Q'];
            $temp['remark3'] = $value['R'];
            $temp['remark4'] = $value['S'];
        }
        $resarray[] = $temp;
    }
    return $resarray;
}

//增加数组中一个元素为指定字符
function PushGarbage($data, $res, $tkey, $validate)
{
    foreach ($data as $key => $value) {
        if (array_key_exists($validate, $value) && $value[$validate] != "") {
            if ((array_key_exists("weighting_num", $value) && $value['weighting_num'] != "") || (array_key_exists("weighting_method", $value) && $value['weighting_method'] != "")) {
                $data[$key][$tkey] = $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    return $data;
}

//Redis操作
function AddArray($array, $name = "redisarray")
{
    $redis = new Redis();//创建对象
    $redis->connect(config('redisconfig')['host'], config('redisconfig')['post']);//建立连接
    $redis->set($name, json_encode($array, JSON_UNESCAPED_UNICODE));
}

function GetArray($name = "redisarray")
{
    $redis = new Redis();
    $redis->connect(config('redisconfig')['host'], config('redisconfig')['post']);//建立连接
    $array = $redis->get($name);
    return json_decode($array, JSON_UNESCAPED_UNICODE);
}

/**
 * 获取报价
 * @param String $ids 垃圾分类  1,2,3,4
 * @param \think\Model $Model garbageprice
 */
function getGarbagePrice($ids = "",$danweiming, $Model,$orderinfo="")
{
    $post = Request::post();
    $return = array('status' => 0, 'data' => array());
    if (empty($ids)) {
        return $return;
    }
    $returnData =  array('status' => 1 ,'data' => array());
    $ids_arr = explode(',', $ids);
    $user=session($post['token']);
    if($user['userInfo']['groupid']>3){
        $ucont['id']=$orderinfo['user_id'];
        $userM=new User();
        $user1=$userM->MFind($ucont);
        $region = $user1['region'];
    }else{
        $region = session($post['token'])['userInfo']['region'];
    }
    $cityM=new City();
    $cwhere['id']=$region;
    $cityinfo=$cityM->MFind($cwhere);
    $garbageum=new GarbageUnit();
    if($cityinfo){
        do {
            foreach ($ids_arr as $key => $value) {
                $gwhere['danweiming']=$danweiming;
                $gwhere['garbageid']=$ids_arr[$key];
                $garbageuinfo=$garbageum->MFind($gwhere);
                $res="";
                if($garbageuinfo){
                    $where = [];
                    $where[] = ['garbageid', '=', $ids_arr[$key]];
                    $where[] = ['regionz', '=', $region];
                    $where[]=['garbageunitid','=',$garbageuinfo['id']];
                    if ($user['userInfo']['groupid'] < 3 && $user['userInfo']['daili'] == 1) {
                        $where[] = ['dlstarttime', 'lt', time()];
                        $where[] = ['dlendtime', 'gt', time()];
                    }else{
                        $where[] = ['start_time', 'lt', time()];
                        $where[] = ['end_time', 'gt', time()];
                    }
                    $res = $Model->MFind($where, '');
                    if($res){
                        if ($user['userInfo']['groupid'] < 3 && $user['userInfo']['daili'] == 1) {
                            $res['danweiming']=$danweiming;
                            $res['number'] = $res['dlnumber'];
                            $res['bnumber']=$res['dlnumber'];
                        }else{
                            $res['danweiming']=$danweiming;
                            $res['number'] = $res['number'];
                            $res['bnumber']=$res['number'];
                            $res['garbageunitid'] = $garbageuinfo['id'];
                        }
                    }
                }
                if($res){
                    $res['trans']=1;
                    $returnData = array('status' => 1 ,'data' => $res);
                    break;
                }
            }
            if(!empty($returnData['data'])){
                return $returnData;
//                break;
            }
            $cwhere1['id'] = $cityinfo['pid'];
            $cityinfo = $cityM->MFind($cwhere1);
            if ($cityinfo) {
                $region = $cityinfo['id'];
            }
        }while($cityinfo);
        if($user['userInfo']['groupid']>3){
            $ucont['id']=$orderinfo['user_id'];
            $userM=new User();
            $user1=$userM->MFind($ucont);
            $region = $user1['region'];
        }else{
            $region = session($post['token'])['userInfo']['region'];
        }
        $cityM=new City();
        $cwhere['id']=$region;
        $cityinfo=$cityM->MFind($cwhere);
        if($cityinfo){
            //没有这个单位 就获取这个单位详情和上级kg价格(因为就两级所以就是第一个与最后一个)
            if(count($ids_arr)>1){
                $end=end($ids_arr);
                $gwhere['danweiming']=$danweiming;
                $gwhere['garbageid']=$ids_arr[0];
                $endinfo=$garbageum->MFind($gwhere);
                //查看自己重量有没有设置价格
                $gwhere['danweiming']='kg';
                $gwhere['garbageid']=$ids_arr[0];
                $endinfo1=$garbageum->MFind($gwhere);

                //查看自己重量有没有设置价格
                    $where = [];
                    $where[] = ['garbageid', '=', $ids_arr[0]];
                    $where[] = ['regionz', '=', $region];
                    $where[]=['garbageunitid','=',$endinfo1['id']];
                    if ($user['userInfo']['groupid'] < 3 && $user['userInfo']['daili'] == 1) {
                        $where[] = ['dlstarttime', 'lt', time()];
                        $where[] = ['dlendtime', 'gt', time()];
                    }else{
                        $where[] = ['start_time', 'lt', time()];
                        $where[] = ['end_time', 'gt', time()];
                    }
                    $res = $Model->MFind($where, '');
                    if($res){
                        $res['garbageunitid'] = $endinfo['id'];
                        if ($user['userInfo']['groupid'] < 3 && $user['userInfo']['daili'] == 1) {
                            $res['number'] = $res['dlnumber']*$endinfo['transweight'];
                            $res['danweiming']='kg';
                            $res['bnumber']=$res['dlnumber'];
                        }else{
                            $res['bnumber']=$res['number'];
                            $res['danweiming']='kg';
                            $res['number'] = $res['number']*$endinfo['transweight'];
                        }
                    }else{
                        //自己没有价格找上级 看上级kg价格是否设置
                        $gwhere['danweiming']='kg';
                        $gwhere['garbageid']=$ids_arr[1];
                        $garbageuinfo=$garbageum->MFind($gwhere);
                        $where = [];
                        $where[] = ['garbageid', '=', $ids_arr[1]];
                        $where[] = ['regionz', '=', $region];
                        $where[]=['garbageunitid','=',$garbageuinfo['id']];
                        if ($user['userInfo']['groupid'] < 3 && $user['userInfo']['daili'] == 1) {
                            $where[] = ['dlstarttime', 'lt', time()];
                            $where[] = ['dlendtime', 'gt', time()];
                        }else{
                            $where[] = ['start_time', 'lt', time()];
                            $where[] = ['end_time', 'gt', time()];
                        }
                        $res = $Model->MFind($where, '');
                        if($res){
                            $res['garbageunitid'] = $garbageuinfo['id'];
                            if ($user['userInfo']['groupid'] < 3 && $user['userInfo']['daili'] == 1) {
                                $res['bnumber']=$res['dlnumber'];
                                $res['danweiming']='kg';
                                $res['number'] = $res['dlnumber']*$endinfo['transweight'];
                            }else{
                                $res['bnumber']=$res['number'];
                                $res['danweiming']='kg';
                                $res['number'] = $res['number']*$endinfo['transweight'];
                            }
                        }
                    }
                if(!empty($res)){
                    $res['trans']=$endinfo['transweight'];
                    $returnData = array('status' => 1 ,'data' => $res);
                    return $returnData;
                }else{
                    return $return;
                }
            }else{
                return $return;
            }
            $cwhere1['id'] = $cityinfo['pid'];
            $cityinfo = $cityM->MFind($cwhere1);
            if ($cityinfo) {
                $region = $cityinfo['id'];
            }
        }while($cityinfo);
    }
//    for ($i = count($ids_arr) - 1; $i >= 0; $i--) {
//        $where = [];
//        $where[] = ['garbageid', '=', $ids_arr[$i]];
//        $where[] = ['start_time', 'lt', time()];
//        $where[] = ['end_time', 'gt', time()];
//        $where[] = ['regionz', '=', $region];
//        $res = $Model->MFind($where, '', 'number,weight');
//        echo $i;
//        print_r($res);
////        print_r($where);
//        if ($res) {
//            $returnData = $res;
//            break;
//        }
//    }
    if (empty($returnData)) {
        return $return;
    } else {
        $return['data'] = $returnData;
        $return['status'] = 1;
        return $return;
    }
}

/**
 * 生成随机数
 * @param $num 位数
 * @return int
 */
function createCode($num = 4)
{
    $res = [];
    for ($i = 0; $i < $num; $i++) {
        $res[] = rand(0, 9);
    }
    return implode('', $res);
}

/**
 * @param $num1 提交的重量或数量
 * @param $num2 报价设置的数量或重量
 * @param $num3 报价设置的价格
 * @return float
 */
function getPrice($num1, $num2, $num3)
{
    if ($num2 == 0) {
        return 0;
    } else {
        return explode('.', ($num1 / $num2 * $num3 * 100))[0] / 100;
    }
}

/**
 * 生成订单号
 */
function createOrderSn()
{
    return date('YmdHis') . time() . createCode(4);
}

/**
 * curl 请求
 */
/**
 * @param $url
 * @param $data
 * @param string $method
 * @param string $type
 * @return bool|string
 */
function curlData($url, $data, $method = 'GET', $type = 'json')
{
    //初始化
    $ch = curl_init();
    $headers = [
        'form-data' => ['Content-Type: multipart/form-data'],
        'json' => ['Content-Type: application/json'],
    ];
    if ($method == 'GET') {
        if ($data) {
            $querystring = http_build_query($data);
            $url = $url . '?' . $querystring;
        }
    }
    // 请求头，可以传数组
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers[$type]);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);         // 执行后不直接打印出来
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');     // 请求方式
        curl_setopt($ch, CURLOPT_POST, true);               // post提交
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);              // post的变量
    }
    if ($method == 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    if ($method == 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
    $output = curl_exec($ch); //执行并获取HTML文档内容
    curl_close($ch); //释放curl句柄
    return $output;
}

/**
 * 获取access_token
 * @param $appid
 * @param $appsecrt
 * @param $code
 */
function getAccessToken($appid = '', $appsecrt = '', $code = '')
{
    if (session('?access_token') && !empty(session('access_token'))) {
        return session('access_token');
    } else {
        if (empty($code)) return false;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecrt&code=$code&grant_type=authorization_code";
        $r = curlData($url, ''); //http_curl 是写在common.php里面的一个方法，本文末将附上源码
        $accessObj = json_decode($r, true);
        $accessTokenList = array(
            "access_token" => $accessObj['access_token'],
            "openid" => $accessObj['openid'],
            "refresh_token" => $accessObj['refresh_token']
        );
        session('access_token', $accessTokenList);
        return $accessTokenList;
    }
}
/**
 *  生成毫秒时间戳
 * Date: 2019-11-15 09:13
 */
function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}
/**
 * 批量订单
 */
function BulkOrderSn(){
    return msectime().createCode(4);
}

/**
 * 发送消息
 **/
function testmessage($title,$username,$status,$remark,$openid,$type){
    $push =new Push();
    $temp=0;
    $time=3;
    while($time){
        if($type){
            //新用户注册
            $res=$push->sendMessage($title,$username,date("Y-m-d H:i:s",time()),"待审核","请及时处理",$openid,'ADl0zP0HATdFHrPUlAanAw6lg1n_2AcV4JmNgtFx-js',$type);
        }else{
            //下订单  标题,订单号,详情,金额,时间
            $res=$push->sendMessage($title,$username,$status,$remark,date("Y-m-d H:i:s",time()),$openid,'dDp0o5I0vAJ6YhePRopVi057fLs5cScJBESoLqLeGU8',$type);
        }
        if($res==1){
//            myLog("消息推送",$title."###".$description."###".json_encode($res,true),__METHOD__,3);
            return 1;
        }else if($res==0){
            return 0;
        }
        $time--;
    }
    return $temp;
}
//日志函数
/**
 * @param $title 标题
 * @param $description 内容
 * @param $type类型(1内部日志(程序函数之间的日志),2(外部日志由程序接收或者发出的消息))
 * @param $function方法名
 * @param $type 1内部函数记录 2外部来回消息记录 3两个都记录
 * User: Administrator
 */
function myLog($title,$description,$function,$type){
//    $str=date("YmdHis",time())."------".$title."------".$description."------";
    if($type==1){
        file_put_contents("../public/log/function/".date("Ymd",time()),$title.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/function/".date("Ymd",time()),date("Y-m-d H:i:s",time())." ---  name:".$function.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/function/".date("Ymd",time()),$description.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/function/".date("Ymd",time()),"------------".PHP_EOL, FILE_APPEND);
    }else if($type==2){
        file_put_contents("../public/log/message/".date("Ymd",time()),$title.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/message/".date("Ymd",time()),date("Y-m-d H:i:s",time())." ---  name:".$function.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/message/".date("Ymd",time()),$description.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/message/".date("Ymd",time()),"------------".PHP_EOL, FILE_APPEND);
    }else if($type==3){
        //函数
        file_put_contents("../public/log/function/".date("Ymd",time()),$title.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/function/".date("Ymd",time()),date("Y-m-d H:i:s",time())." ---  name:".$function.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/function/".date("Ymd",time()),$description.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/function/".date("Ymd",time()),"------------".PHP_EOL, FILE_APPEND);
        //消息
        file_put_contents("../public/log/message/".date("Ymd",time()),$title.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/message/".date("Ymd",time()),date("Y-m-d H:i:s",time())." ---  name:".$function.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/message/".date("Ymd",time()),$description.PHP_EOL, FILE_APPEND);
        file_put_contents("../public/log/message/".date("Ymd",time()),"------------".PHP_EOL, FILE_APPEND);
    }
}