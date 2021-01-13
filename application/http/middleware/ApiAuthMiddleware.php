<?php

namespace app\http\middleware;

use think\Db;

use think\facade\Request;

class ApiAuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        $url = Request::baseUrl(); // 获取请求的url
        $notNeedAuth = [
            '/think',
            '/hello',
            '/test',
            '/reg',
            '/login',
            '/getCodeByEmail', // 重置密码时发送验证码接口
            '/resetPassword', // 重置密码接口
        ]; // 白名单列表，

        if (in_array($url, $notNeedAuth)) {// 白名单的接口不用认证
            return $next($request);
        }

        $token = Request::header('token', '');

        if (empty($token)) {
            return $this->response(10000, '请传入一个token');
        }
        $user = Db::name('tp_user_log')
            ->where('token', $token)
            ->find();

        if ($user['token'] != $token) {
            return $this->response(10001, '您没有登录');
        }

        if (date('Y-m-d H:i:s', time() - 24 * 60 * 60) > $user['CREATE_time']) {
            return $this->response(10002, '您的登录时间已经过期，请重新登录');
        }

        $user_api = Db::name('tp_user_api')
            ->where('user_id', $user['user_id'])
            ->find();//tp_user_api表的api_id

        $user_role =  Db::name('tp_user')
            ->where('id', $user['user_id'])
            ->find();//tp_user表的role_id

        $role_api = Db::name('tp_role_api')
            ->where('role_id', $user_role['role_id'])
            ->find();//tp_role_api表中的api_id

        if($user_api['api_id'] && $role_api['api_id'] != 7){
            return $this->response(10003, '您无权访问');}
        return $next($request);//返回rensponse 对象

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
