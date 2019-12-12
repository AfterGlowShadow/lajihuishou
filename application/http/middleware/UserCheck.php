<?php

namespace app\http\middleware;

use think\facade\Request;

class UserCheck
{
    public function handle($request, \Closure $next)
    {
        $param = Request::param();
        if (!isset($param['token']) || empty($param['token'])) {
            BackData(400, "请登录账号");
            exit;
        } else {
            $user = session($param['token']);
            if(empty($user)){
                BackData(400, "请登录账号");
                exit;
            }else{
                if((time() - $user['time'])>2*3600){
                    BackData(400, "请重新登录");
                    exit;
                }
            }
        }
        return $next($request);
    }
}
