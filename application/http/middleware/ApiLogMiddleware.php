<?php

namespace app\http\middleware;

use think\Db;
use think\facade\Request;
class ApiLogMiddleware
{
    public function handle($request, \Closure $next)
    {
        $url    = Request::baseUrl(); // 获取请求的url
        $params = Request::param();
        $token = Request::header('token', '');

        if (empty($token)) {
            $user_id = -1;
            $user_name = '未知';
        } else{
            $user_log = Db::name('tp_user_log')
                ->field('user_id,token')
                ->where('token', $token)
                ->find();

            if ($user_log['token'] != $token) {
                $user_id = -1;
                $user_name = '未知';
            }
            else {
                $user_id = $user_log['user_id'];
                $user = Db::name('tp_user')
                    ->field('id,username')
                    ->where('id', $user_log['user_id'])
                    ->find();
                $user_name = $user['username'];
            }
        }

        $data = [
            'path' => $url,
            'user_id' => $user_id,
            'user_name' => $user_name,
            'token' => $token,
            'params' => json_encode($params)
        ];
        Db::name('tp_api_log')->insert($data);
        return $next($request);
    }
}