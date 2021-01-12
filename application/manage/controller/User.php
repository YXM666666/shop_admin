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
            return $valdate->getError();
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
}
