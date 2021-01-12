<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;

class User extends BaseController
{
    public function reg()
    {
        $data = $this->request->post();

        $valdate = new \app\index\validate\User();
        if (!$valdate->batch()->scene('reg')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['password'] = $password;

        try {
            $user = Db::name('tp_user')
                ->where('username', $data['username'])
                ->find();

            if (!is_null($user)) {
                if ($user['username'] == $data['username']) {
                    return $this->resFail('用户名已被注册');
                }
            }

            Db::name('tp_user')->insert($data);


        } catch (\Exception $e) {
            return $this->resFail('注册失败' . $e->getMessage());
        }

        return $this->resSuccess([
                'id' => Db::name('tp_user')->getLastInsID(),
                'username' => $data['username'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'role_id' => $data['role_id']], '注册成功');

    }

public function login()
{
    $data = $this->request->post();

    $valdate = new \app\index\validate\User();

    if(!$valdate->scene('login')->batch()->check($data)){
        return $this->resFail($valdate->getError());
    }
    try {
        $user = Db::name('tp_user')
            ->field('id,username,phone,email,password,is_del')
            ->where('username', $data['username'])
            ->find();
        if ($user['username'] != $data['username']) {
            return $this->resFail('请您输入正确的账号或密码');

        }
        if (password_verify($data['password'], $user['password'])) {
            $token=(md5($data['username'].time()));
            $data1 = [
                'user_id' => $user['id'],
                'token' => $token
            ];
            Db::name('tp_user_log')->insert($data1);

            $user['token'] = $token;
            unset($user['password']);

            return $this->resSuccess($user, '登录成功');
        } else {
            return $this->resFail('请您输入正确的账号或密码');
        }

    } catch (\Exception $e) {
        return $this->resFail('数据异常' . $e->getMessage());
    }
}


}
