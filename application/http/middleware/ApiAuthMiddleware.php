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
            '/reg',
            '/login',
        ]; // 白名单列表，

        if (in_array($url, $notNeedAuth)) {// 白名单的接口不用认证
            return $next($request);
        }
        $api = Db::name('tp_api')
            ->field('path')
            ->where('path', $url)
            ->find();//tp_role_api表中的api_id
        if (empty($api)){
            return $this->response(9999, '接口不存在');
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

        if (date('Y-m-d H:i:s', time() - 24 * 60 * 60) > $user['create_time']) {
            return $this->response(10002, '您的登录时间已经过期，请重新登录');
        }

        $res = Db::name('tp_user_api ua')
            ->leftJoin('tp_user_log ul', 'ua.user_id=ul.user_id')
            ->leftJoin('tp_api a', 'ua.api_id=a.id')
            ->field('ua.id')
            ->where('ul.token', '=', $token)
            ->where('a.path', '=', $url)
            ->find();//tp_user_api表的api_id

        if (!$res){
           $res= Db::name('tp_role_api ra')
           ->leftJoin('tp_api a ','ra.api_id=a.id')
           ->leftJoin('tp_user u','ra.role_id=u.role_id')
           ->where('a.path','=',$url)
           ->where('u.id','=',$user['user_id'])
           ->find();
        }
        if(is_null($res) ){
            return $this->response(10003, '您没有权限');
        }
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
