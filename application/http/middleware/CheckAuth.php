<?php

namespace app\http\middleware;

use think\facade\Request;

class CheckAuth
{
    public function handle($request, \Closure $next)
    {
        $param = Request::param();
        $AuthList = session($param['token']);
        if (empty($AuthList)) {
            BackData(400, "你没有权限访问，请联系管理员");
            exit;
        }
        $api_url = $_SERVER['REQUEST_URI'];
        $api_url = str_replace('\\', '/', $api_url);
        if (strpos($api_url, 'index.php')) {
            $truely_url = ltrim(explode('index.php', $api_url)[1], '/');
        } else {
            $truely_url = ltrim($api_url, '/');
        }
        if(!array_key_exists($truely_url,$AuthList)){
            BackData(400, "你没有权限访问，请联系管理员");
            exit;
        }
        return $next($request);
    }
}
