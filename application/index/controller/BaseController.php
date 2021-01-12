<?php


namespace app\index\controller;


use think\Controller;

class BaseController extends Controller
{
    protected function response($code, $data = [], $msg = '请求成功')
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        return $res;
    }

    protected function resSuccess($data = [], $msg = '请求成功', $code = 0)
    {
        return $this->response($code, $data, $msg);
    }


    protected function resFail($msg = '请求失败', $code = 1, $data = [])
    {
        return $this->response($code, $data, $msg);
    }

}