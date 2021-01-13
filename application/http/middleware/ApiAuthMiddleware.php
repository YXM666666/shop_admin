<?php

namespace app\http\middleware;

use think\Db;

use think\facade\Request;

class ApiAuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        $url = Request::baseUrl();
        $token = Request::header('token', '');
        $params = Request::request();


        return $next($request);
    }
    private function response($code, $msg)
    {
        $data =  [
            'code' => $code,
            'msg' => $msg
        ];

        return json($data);
    }
}
