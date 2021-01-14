<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;
use think\facade\Request;

class User extends BaseController
{
//    protected $middleware = ['ApiLogMiddleware', 'ApiAuthMiddleware'];

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
            'role_id' => $data['role_id'],
            'create_time'=>date('Y-m-d H:i:s')],
            '注册成功');

    }

    public function login()
    {
        $data = $this->request->post();

        $valdate = new \app\index\validate\User();

        if (!$valdate->scene('login')->batch()->check($data)) {
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
            if($user['is_del']==1){
                return $this->resFail('您已被删除');
            }
            if (password_verify($data['password'], $user['password'])) {
                $token = (md5($data['username'] . time()));
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


    public function update($q=10)
    {
        $token = Request::header('token', '');
        $data = $this->request->post();

        $valdate = new \app\index\validate\Check();

        if (!$valdate->scene('updata')->batch()->check($data)) {
            return ($valdate->getError());
        }

        $update = [];

        if (!empty($data['email'])){
            $update['email'] = $data['email'];
        }
        if (!empty($data['phone'])){
            $update['phone'] = $data['phone'];
        }
        if (!empty($data['role_id'])){
            $update['role_id'] = $data['role_id'];
        }

        try {
            $user = Db::name('tp_user_log')
                ->field('user_id,create_time')
                ->where('token', $token)
                ->find();

            if ($user['user_id'] != $data['id']) {
                return $this->resFail('请输入正确的id', '1');
            }

            Db::name('tp_user')
                ->where('id','=',$data['id'])
                ->update($update);

            $data['create_time']=$user['create_time'];

            return $this->resSuccess($data, '修改成功');


        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    public function getUserlist(){
        $page_num = $this->request->post('page_num', '1');
        $page_size = $this->request->post('start_time', '10');
        $data = $this->request->post();

        $valdate = new \app\index\validate\Check();

        if (!$valdate->scene('getUserlist')->batch()->check($data)) {
            return ($valdate->getError());
        }
        $condition = [];
        if (!empty($data['username'])) {
            $condition[] = ['username', 'like', "%{$data['username']}%"];
        }
        if (!empty($data['email'])) {
            $condition[] = ['email', 'like', "%{$data['email']}%"];
        }
        if (!empty($data['phone'])) {
            $condition['phone'] = $data['phone'];
        }
        try {
            $user = Db::name('tp_user')
                ->where($condition)
                ->field('password',true)
                ->where('is_del','=',0)
                ->page($page_num,$page_size)
                ->select();

            return $this->resSuccess($user, '查询成功');


        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    public function deleteUser(){
        $data = $this->request->post();
        $token = Request::header('token', '');
        $valdate = new \app\index\validate\Check();

        if (!$valdate->scene('deleteUser')->batch()->check($data)) {
            return ($valdate->getError());
        }
        try {
           $user = Db::name('tp_user_log')
            ->field('user_id')
            ->where('token',$token)
            ->find();

            if ($user['user_id']==$data['id']){
                return $this->resFail('您不能删除自己', '1');
            }

            Db::name('tp_user')
                ->where('id',$data['id'])
                ->setField('is_del','1');

            return $this->resSuccess([], '删除成功');

        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }

    }
}
