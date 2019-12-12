<?php
 header('Content-type:text/html;charset=utf-8');
 $n_=$_GET['n'];
 $b_=$_GET['b'];

//  $url 修改成你站点的相应index.php地址
//  示例： http://你的域名/index.php

 $url='http://shuerte.hbweipai.com/index.php?n='.$n_.'&b='.$b_;  //需改
 $url_val=urlencode(mb_convert_encoding("$url", 'utf-8'))."\n";
 $url_='alipays://platformapi/startapp?saId=10000007&qrcode='.$url_val;
?>
<!DOCTYPE html>
<html>
<head>
<title>H5支付</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<style>
.center{
    text-align: center;
}
#inp,#inpbz {
    width: 76%;
    border: 1px solid #ff6a00;
    height: 32px;
    border-radius: 5px;
}
#inpbz {
    margin-top: 8px;
}
#h5,#btn {
    background: #00BCD4;
    border: none;
    margin: 0 auto;
    text-align: center;
    padding: 10px;
    color: #fff;
    border-radius: 18px;
    margin-top: 23px;
    text-decoration: none;
    width: 71%;
    display: none;
}
#btn{
    display: inline-block;
    width: 76%;
    -webkit-appearance：none;
}
.ding{
    display: none;
}
</style>
</head>
<body>
<div class="center">
<form action='index.php' method="get" name="form_" id="loginForm">
     <input id="inp" type="text" name="n" placeholder="请输入支付金额">
     <input id="inpbz" type="text" name="b" placeholder="请输入备注信息">
     <div>
       <a id='h5' href='<?php echo $url_; ?>'>立即1支付</a>
       <button type="submit" id="btn">提1交订单</button>
     </div>
</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
    //获取url中的参数
    function getRequest() {
       var url = window.location.search;
       var theRequest = new Object();
       if (url.indexOf("?") != -1) {
          var str = url.substr(1);
          strs = str.split("&");
          for(var i = 0; i < strs.length; i ++) {
             theRequest[strs[i].split("=")[0]]=decodeURI(strs[i].split("=")[1]);
          }
       }
       return theRequest;
    };

    var arr = getRequest();
    var money = arr['n'];
    if(money){
       $('#h5').css({'display':'inline-block'});
       $('#inp').css({'display':'none'});
       $('#inpbz').css({'display':'none'});
       $('#btn').css({'display':'none'});
    };


    var system ={};
    var p = navigator.platform;
    system.win = p.indexOf("Win") == 0;
    system.mac = p.indexOf("Mac") == 0;
    system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);
    if(system.win||system.mac||system.xll){
        $('#h5').html('请在手机浏览器打开').attr('href','#');
        $('#h5').css({'display':'inline-block'});
        $('#inp').css({'display':'none'});
        $('#inpbz').css({'display':'none'});
        $('#btn').css({'display':'none'});
    }else{
        var ua = window.navigator.userAgent.toLowerCase();
        if (ua.match(/AlipayClient/i) == 'alipayclient') {
            $('#h5').html('').css({'display':'none'});
            $('#inp').css({'display':'none'});
            $('#inpbz').css({'display':'none'});
            $('#btn').css({'display':'none'});
            Alipay();
        }else if (ua.match(/MicroMessenger/i) == "micromessenger") { 
            $('#h5').html('点击上方选择浏览器打开');
        } else if (ua.match(/QQ/i) == "qq") { 
            $('#h5').html('立即支付');
        }
    };


    //   money   是金额
   
    //   bz      是备注信息
    
    //   u_      的值要改成你支付宝的   PID
    //  PID 查看地址（mapi网关产品密钥） https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
       
    function Alipay(){

        var arr = getRequest();

        var money = arr['n'];  //金额
        var bz = arr['b'];    //备注
        var u_ = '2546946304@qq.com';   //改成你支付宝的   PID
        var pay;

        pay = {
            actionType: 'scan',
            u: u_,
            a: money,
            m: bz,
            biz_data: {
                s: 'money',
                u: u_,
                a: money,
                m: bz
            }
        };
        AlipayJSBridge.call('startApp', {
            appId: '20000123',
            param: pay
        });
    };
    


});       
</script>
</body>
</html>
