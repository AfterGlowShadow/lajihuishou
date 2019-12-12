<?php
//----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//use think\Route;

use think\facade\Route;

Route::group('api', function () {
    /**
     * @name 文件上传
     * @isshow 0
     */
    Route::group('file', function () {
        /**
         * @name 上传图片
         * @isshow 0
         */
        Route::post("pic", 'admin/File/Upfile');
        /**
         * @name 批量上传
         * @isshow 0
         */
        Route::post("databulk", "admin/File/Bulk");
        /**
         * @name 上传视频
         * @isshow 0
         */
        Route::post("video", 'admin/File/Video');
    })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain()->middleware(['UserCheck','CheckAuth']);
    /**
     * @menu 用户管理
     * @name 用户管理
     * @isshow 1
     */
    Route::group('admin', function () {
        /**
         * @name 登录
         * @isshow 0
         */
        Route::post("login", 'admin/User/login')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
         /**
          * @name 后台添加用户
          * @prule 用户管理
          * @isshow
          */
        Route::post("adduser", 'admin/User/AddUser');
        /**
         * @name 添加用户
         * @prule 用户管理
         * @isshow 0
         */
        Route::post("add", 'admin/User/AddOne')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
        /**
         * @name 修改用户
         * @prule 用户管理
         * @isshow 1
         */
        Route::post("change", 'admin/User/UpdateOne')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
        /**
         * @name 用户列表
         * @prule 用户管理
         * @isshow 1
         */
        Route::post("getlist", 'admin/User/GetList')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
        /**
         * @name 获取单个用户
         * @prule 用户管理
         * @isshow 1
         */
        Route::post("getone", 'admin/User/GetOne')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
        /**
         * @name 删除用户
         * @prule 用户管理
         * @isshow 1
         */
        Route::post("delete", 'admin/User/DeleteOne')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
        /**
         * @name 退出登录
         * @isshow 0
         */
        Route::post("logout", 'admin/User/logout')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 修改密码
         * @isshow 0
         */
        Route::post("uppwd", 'admin/User/UpPwd')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 修改自己密码
         */
        /**
         * 减积分
         */

        Route::post("changeself", 'admin/User/ChangeSelf')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 垃圾管理
         * @name 垃圾分类管理
         * @isshow 1
         */
        Route::group('garbage', function () {
            /**
             * @name 添加垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("add", 'admin/Garbage/AddOne');
            /**
             * @name 修改垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("change", 'admin/Garbage/UpdateOne');
            /**
             * @name 删除垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("delete", 'admin/Garbage/DeleteOne');
            /**
             * @name 获取单个垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("getone", 'admin/Garbage/GetOne');
            /**
             * @name 垃圾分类列表
             * @menu 垃圾分类管理
             */
            Route::post("getlist", 'admin/Garbage/GetList');
            /**
             * @name 所有垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("getalllist", 'admin/Garbage/GetAllList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 垃圾管理
         * @name 垃圾报价管理
         * @isshow 1
         */
        Route::group('garbageprice', function () {
            /**
             * @name 添加垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("add", 'admin/GarbagePrice/AddOne');
            /**
             * @name 修改垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("change", 'admin/GarbagePrice/UpdateOne');
            /**
             * @name 删除垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("delete", 'admin/GarbagePrice/DeleteOne');
            /**
             * @name 获得一个垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("getone", 'admin/GarbagePrice/GetOne');
            /**
             * @name 垃圾报价列表
             * @menu 垃圾报价管理
             */
            Route::post("getlist", 'admin/GarbagePrice/GetList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 权限管理
         * @name 权限管理
         * @isshow 1
         */
        Route::group('rule', function () {
            /**
             * @name 添加权限
             * @menu 权限管理
             */
            Route::post("add", 'admin/Rule/AddOne');
            /**
             * @name 修改权限
             * @menu 权限管理
             */
            Route::post("change", 'admin/Rule/UpdateOne');
            /**
             * @name 删除权限
             * @menu 权限管理
             */
            Route::post("delete", 'admin/Rule/DeleteOne');
            /**
             * @name 获得单个权限
             * @menu 权限管理
             */
            Route::post("getone", 'admin/Rule/GetOne');
            /**
             * @name 权限列表
             * @menu 权限管理
             */
            Route::post("getlist", 'admin/Rule/GetList');
            /**
             * @name 查询所有权限
             * @menu 权限组管理
             */
            Route::post("rulelist", 'admin/Rule/rulelist');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 权限组管理
         * @name 权限组管理
         * @isshow 1
         */
        Route::group('group', function () {
            /**
             * @name 添加权限组
             * @menu 权限组管理
             */
            Route::post("addgroup", 'admin/Group/AddOne');
            /**
             * @name 修改权限组
             * @menu 权限组管理
             */
            Route::post("changegroup", 'admin/Group/UpdateOne');
            /**
             * @name 删除权限组
             * @menu 权限组管理
             */
            Route::post("deletegroup", 'admin/Group/DeleteOne');
            /**
             * @name 获得一个权限组
             * @menu 权限组管理
             */
            Route::post("getone", 'admin/Group/GetOne');
            /**
             * @name 查询权限组
             * @menu 权限组管理
             */
            Route::post("rulegroup", 'admin/Group/GetList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 区域管理
         * @name 区域管理
         * @isshow 1
         */
        Route::group('regiongroup', function () {
            /**
             * @name 添加区域组
             * @menu 区域管理
             */
            Route::post("add", 'admin/RegionGroup/AddOne');
            /**
             * @name 修改区域组
             * @menu 区域管理
             */
            Route::post("change", 'admin/RegionGroup/UpdateOne');
            /**
             * @name 删除区域组
             * @menu 区域管理
             */
            Route::post("delete", 'admin/RegionGroup/DeleteOne');
            /**
             * @name 获取单个区域组
             * @menu 区域管理
             */
            Route::post("getone", 'admin/RegionGroup/GetOne');
            /**
             * @name 区域组列表
             * @menu 区域管理
             */
            Route::post("getlist", 'admin/RegionGroup/GetList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 仓库管理
         * @name 仓库管理
         * @isshow 1
         */
        Route::group('tray', function () {
            /**
             * @name 添加仓库
             * @menu 仓库管理
             */
            Route::post("add", 'admin/Tray/AddOne');
            /**
             * @name 修改仓库
             * @menu 仓库管理
             */
            Route::post("change", 'admin/Tray/UpdateOne');
            /**
             * @name 删除仓库
             * @menu 仓库管理
             */
            Route::post("delete", 'admin/Tray/DeleteOne');
            /**
             * @name 获取一个仓库
             * @menu 仓库管理
             */
            Route::post("getone", 'admin/Tray/GetOne');
            /**
             * @name 仓库列表
             * @menu 仓库管理
             */
            Route::post("getlist", 'admin/Tray/GetList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 订单管理
         * @name 订单管理
         * @isshow 1
         */
        Route::group('order', function () {
            /**
             * @name 添加订单
             * @menu 订单管理
             */
            Route::post("shopadd", "admin/Order/ShopAddOne");
            /**
             * @name 修改订单
             * @menu 订单管理
             */
            Route::post("change", 'admin/Order/UpdateOne'); /**

            /**
             * @name 删除订单
             * @menu 订单管理
             */
            Route::post("delete", 'admin/Order/DeleteOne');
            /**
             * @name 获取一个订单
             * @menu 订单管理
             */
            Route::post("getone", 'admin/Order/GetOne');
            /**
             * @name 订单列表
             * @menu 订单管理
             */
            Route::post("getlist", 'admin/Order/GetList');
            /**
             * @name 批量订单
             * @menu 订单管理
             */
            Route::post("bulkordernumber", 'admin/Order/BulkOrderNumber');


//            Route::post("tempadd", "admin/Order/TempAddOne");
//            Route::post("tempprice", "admin/Order/TempPrice");
//            Route::post("updateprice", "admin//UpdatePrice");
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 提现管理
         * @name 提现管理
         * @isshow 1
         */
        Route::group('tixian', function () {
            /**
             * @name 提现
             * @menu 提现管理
             */
            Route::post("add", "admin/TiXian/AddOne");
            /**
             * @name 获取单个提现
             * @menu 提现管理
             */
            Route::post("getone", 'admin/TiXian/GetOne');
            /**
             * @name 提现列表
             * @menu 提现管理
             */
            Route::post("getlist", "admin/TiXian/GetList");
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @menu 消息管理
         * @name 消息管理
         * @isshow 1
         */
        Route::group('message', function () {
            /**
             * @name 添加消息
             * @menu 消息管理
             */
            Route::post("add", "admin/Message/AddOne");
            /**
             * @name 获取单个消息
             * @menu 消息管理
             */
            Route::post("getone", 'admin/Message/GetOne');
            /**
             * @name 修改单个消息
             * @menu 消息管理
             */
            Route::post("change", 'admin/Message/UpdateOne');
            /**
             * @name 消息列表
             * @menu 消息管理
             */
            Route::post("getlist", "admin/Message/GetList");
            /**
             * @name 删除消息
             * @menu 消息管理
             */
            Route::post("delete", 'admin/Message/DeleteOne');
        });
//    })->middleware(['UserCheck','CheckAuth']);
    });

    Route::group('home', function () {
        /**
         * @name 提现
         */
        Route::post("withdraw", "home/TiXian/WithDraw");
        /**
         * @name 发送短信
         */
        Route::post("senMsg", "home/WechatSms/index");
//        Route::group('shop', function () {
//            //门店注册
//            Route::post("add", 'home/Shop/AddOne');
//
//            Route::post("addorder", 'home/Order/AddOder');
////            门店添加一个本地库存
//            Route::post("addStock", 'home/Retrospect/AddOne');
////            删除门店添加的本地库存
//            Route::post("delStock", 'home/Retrospect/DeleteOne');
////            门店添加的本地库存列表
//            Route::post("StockList", 'home/Retrospect/GetList');
////            门店预估报价列表
//            Route::post("addRetrospect", 'home/Retrospect/GetRetrospectList');
//        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 前台用户管理
         */
        Route::group('user', function () {

            /**
             * @name 添加订单
             * @menu 前台用户管理
             */
            Route::post("addorder", 'home/Order/AddOder');
            /**
             * @name 添加本地库存
             * @menu 前台用户管理
             */
            Route::post("addStock", 'home/Retrospect/AddOne');
            /**
             * @name 删除门店添加的本地库存
             * @menu 前台用户管理
             */
            Route::post("delStock", 'home/Retrospect/DeleteOne');
            /**
             * @name 门店添加的本地库存列表
             * @menu 前台用户管理
             */
            Route::post("StockList", 'home/Retrospect/GetList');
            /**
             * @name 门店预估报价列表
             * @menu 前台用户管理
             *
             */
            Route::post("addRetrospect", 'home/Retrospect/GetRetrospectList');
            /**
             * @name 根据垃圾分类获取报价
             * @menu 前台用户管理
             * ids(分类id 逗号隔开)
             */
            Route::post("getprice", 'home/Retrospect/GetPrice');

             /**
              * @name 批量删除本地仓库
              * @menu 前台用户管理
              * ids(分类id 逗号隔开)
              */
            Route::post("bulkdelete", 'home/Retrospect/BulkDelete');
            /**
             * @name 根据垃圾分类获取报价
             * @menu 前台用户管理
             */
            /**
             * @name 获取单个用户
             * @menu 前台用户管理
             */
            Route::post("getone", 'home/User/GetDQOne');
            /**
             * @name 获取用户
             * @menu 前台用户管理
             */
            Route::post("getoneS", 'home/User/GetOne');
            /**
             * @name 获取用户列表
             * @menu 前台用户管理
             */
            Route::post("getlist", 'home/User/GetList');
            /**
             * @name 退出用户
             */
            Route::post("logout", 'home/User/logout');
            Route::post("change", 'home/User/UpdateOne');
            Route::post("changebyid", 'home/User/UpdateOneById');

            /**
             * @name 用户登录
             */
            /**
             * @name 积分审核
             * @menu 前台用户管理
             */
            Route::post("jifenshenhe", 'home/User/JiFenShenhe');
            Route::post("login", 'home/User/login');
            /**
             * @name 用户积分兑换
             */
            Route::post("jifenDH", 'home/User/DeleteJiFen');

            /**
             * @name 门店注册
             */
            Route::post("register", "home/User/AddOne");
            /**
             * @name 用户审核
             * @menu 前台用户管理
             */
            Route::post("confirm", "home/User/Confirm");
            /**
             * @name 登录用户信息
             * @menu 前台用户管理
             */
            Route::post('getbyuser','home/User/GetByUser');
             /**
              * @name 忘记密码
              * @menu 前台用户管理
              */
            Route::post('forgetpass','home/User/ForgetPass');
            /**
              * @name 暂存点添加业务
              * @menu 前台用户管理
              */
            Route::post('zaddyw','home/User/ZAddYWOne');
            /**
              * @name 主管添加暂存点
              * @menu 前台用户管理
              */
            Route::post('zaddzc','home/User/ZAddZCOne');
            /**
              * @name 后台添加任意
              * @menu 前台用户管理
              */
            Route::post('addother','home/User/AddOther');
            /**
              * @name 绑定其他微信(没有绑定其他账号则直接绑定)
              * @menu 前台用户管理
              */
            Route::post('changewx','home/User/ChangeWx');
            /**
              * @name 绑定其他微信(直接修改绑定)
              * @menu 前台用户管理
              */
            Route::post('qdchangewx','home/User/QdChangeWx');
            /**
              * @name 绑查询所有下属用户没有分页
              * @menu 前台用户管理
              */
            Route::post('getall','home/User/GetAllUser');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 消息管理
         * @menu 消息管理
         */
        Route::group('message', function () {
            /**
             * @name 获取通知
             * @menu 消息管理
             */
            Route::post("getnotice", 'home/Message/GetNotice');
            /**
             * @name 获取用户消息
             * @menu 消息管理
             */
            Route::post("getusernotice", 'home/Message/GetUserNotice');
            /**
             * @name 获取用户未读消息
             * @menu 消息管理
             */
            Route::post("getnoread", 'home/Message/GetNoRead');
            /**
             * @name 获取用户身份通知
             * @menu 消息管理
             */
            Route::post("typenotice",'home/Message/TypeNotice');
            /**
             * @name 查询消息内容
             * @menu 消息管理
             */
            Route::post("notice",'home/Message/Notice');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 垃圾分类管理
         * @menu 垃圾分类管理
         */
        Route::group('garbage', function () {
            /**
             * @name 添加垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("add", 'home/Garbage/AddOne');
            /**
             * @name 修改垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("change", 'home/Garbage/UpdateOne');
            /**
             * @name 删除垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("delete", 'home/Garbage/DeleteOne');
            /**
             * @name 获取单个垃圾分类
             * @menu 垃圾分类管理
             */
            Route::post("getone", 'home/Garbage/GetOne');
            /**
             * @name 垃圾分类列表
             * @menu 垃圾分类管理
             */
            Route::post("getlist", 'home/Garbage/GetList');
            /**
             * @name 所有垃圾分裂
             * @menu 垃圾分类管理
             */
            Route::post("getalllist", 'home/Garbage/GetAllList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 垃圾报价管理
         * @menu 垃圾报价管理
         */
        Route::group('garbageprice', function () {
            /**
             * @name 添加垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("add", 'home/GarbagePrice/AddOne');
            /**
             * @name 修改垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("change", 'home/GarbagePrice/UpdateOne');
            /**
             * @name 删除垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("delete", 'home/GarbagePrice/DeleteOne');
            /**
             * @name 获取单个垃圾报价
             * @menu 垃圾报价管理
             */
            Route::post("getone", 'home/GarbagePrice/GetOne');
            /**
             * @name 垃圾报价列表
             * @menu 垃圾报价管理
             */
            Route::post("getlist", 'home/GarbagePrice/GetList');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 订单管理
         * @menu 订单管理
         */
        Route::group('order', function () {
            /**
             * @name 添加订单
             * @menu 订单管理
             */
//             门店下单  --待修改
            Route::post("shopadd", "home/Order/ShopAddOne");
            /**
             * @name 修改订单(最高)
             * @menu 订单管理
             */
//             门店下单  --待修改
            Route::post("change", "home/Order/Update");
            /*
            * @name 修改订单
            * @menu 订单管理
            */
            Route::post("changeone", 'home/Order/ChangeOne');
            /**
             * @name 获取下级订单
             * @menu 订单管理
             */
//             门店下单  --待修改
            Route::post("norder", "home/Order/NOrder");
            // 门店修改价格  --待修改
            /**
             * @name 修改订单价格与重量
             * @menu 订单管理
             */
            Route::post("adjust", "home/Order/adjust");
            // Route::post("tempadd", "home/Order/TempAddOne");
            /**
             * @name 获取订单列表
             * @menu 订单管理
             */
            // 订单列表  -- 当前登录人发布的订单
            Route::post("getlist", "home/Order/GetList");
            /**
             * @name 确认订单
             * @menu 订单管理
             */
            // 确认订单  --待修改
            Route::post("confirm", 'home/Order/ConfirmOrder');
            /**
             * @name 获取单个订单
             * @menu 订单管理
             */
            // 根据订单id搜订单详情
            Route::post("getone", 'home/Order/getone');
            /**
             * @name 取消订单
             * @menu 订单管理
             */
            // 取消订单
            Route::post('cancel', 'home/Order/Cancel');
            /**
             * @name 业务员订单地图
             * @menu 订单管理
             */
            Route::post('ordermap','admin/Order/OrderMap');
            /**
             * @name 获取收支记录
             * @menu 订单管理
             */
            Route::post("getLog", 'home/Order/GetOrderLog');
            // 业务员、暂存点发布订单
            // Route::post('addOrder', 'home/Order/SaleAddOne');
        })->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        /**
         * @name 仓库管理
         * @menu 仓库管理
         */
        Route::group('tray', function () {
            /**
             * @name 添加仓库
             * @menu 仓库管理
             */
            Route::post("add", 'home/Tray/AddOne');
            /**
             * @name 修改仓库信息
             * @menu 仓库管理
             */
            Route::post("change", 'home/Tray/UpdateOne');
            /**
             * @name 删除仓库
             * @menu 仓库管理
             */
            Route::post("delete", 'home/Tray/DeleteOne');
            /**
             * @name 获取单个仓库信息
             * @menu 仓库管理
             */
            Route::post("getone", 'home/Tray/GetOne');
            /**
             * @name 获取仓库列表
             * @menu 仓库管理
             */
            Route::post("getlist", 'home/Tray/GetList');
            /**
             * @name 仓库订单列表
             * @menu 仓库管理
             */
            Route::post("orderlist", 'home/Tray/TrayOrderList');
            /**
             * @name 仓库出库订单
             * @menu 仓库管理
             */
            Route::post("orderadd", 'home/Tray/OrderAdd');
            /**
             * @name 仓库出库订单
             * @menu 仓库管理
             */
            Route::post("trayorderList", 'home/Tray/OrderList');
        });
        /**
         * @name 垃圾单位管理
         * @menu 垃圾单位管理
         */
        Route::group('garbageunit', function () {
            /**
             * @name 添加仓库
             * @menu 仓库管理
             */
            Route::post("add", 'home/GarbageUnit/AddOne');
            /**
             * @name 修改仓库信息
             * @menu 仓库管理
             */
            Route::post("change", 'home/GarbageUnit/UpdateOne');
            /**
             * @name 删除仓库
             * @menu 仓库管理
             */
            Route::post("delete", 'home/GarbageUnit/DeleteOne');
            /**
             * @name 获取单个仓库信息
             * @menu 仓库管理
             */
            Route::post("getone", 'home/GarbageUnit/GetOne');
            /**
             * @name 获取仓库列表
             * @menu 仓库管理
             */
            Route::post("getlist", 'home/GarbageUnit/GetList');
        });
        /**
         * @name 提现管理
         * @menu 提现管理
         */
        Route::group('tixian', function () {
            /**
             * @name 申请提现
             * @menu 提现管理
             */
            Route::post("add", "home/TiXian/AddOne");
            /**
             * @name 获取单个提现
             * @menu 提现管理
             */
            Route::post("getone", 'home/TiXian/GetOne');
            /**
             * @name 获取所有提现列表
             * @menu 提现管理
             */
            Route::post("getlist", "home/TiXian/GetList");
        });
//    })->middleware(['UserCheck','CheckAuth']);
    });

    /**
     * @name 用户登录
     */
    Route::post("login", 'home/User/login')->allowCrossDomain();
    Route::get("login", 'home/User/login')->allowCrossDomain();
    Route::get("test", 'admin/Index/test')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
    /**
     * @name 添加用户
     * @menu 前台用户管理
     */
    Route::post("add", 'home/User/AddOne')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
    /**
     * @name 获取省市县列表
     */
    Route::post("citylist",'admin/City/CityList')->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
    Route::group("wechat",function(){
        //设置微信参数
//        Route::post("config","wechat/Config/options");
//        //微信授权验证
        Route::get("oauth",'admin/index/oauth');
//        //微信授权验证
       Route::get("valid",'admin/index/valid');
//        //微信授权验证
        Route::post("jssdk",'admin/index/getWxShareSign');
//        //微信授权验证
        Route::get("push",'admin/push/sendMessage');
        //微信授权验证
        Route::post("index",'admin/index/index');
//        Route::get("test",'admin/index/test');
//        Route::get("testmessage",'admin/index/testmessage');
        Route::get("jssdk",'admin/index/jssdk');
        Route::get("test",'admin/index/test');
    })->allowCrossDomain();
})->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();

