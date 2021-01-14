<?php


namespace app\manage\controller;
use app\index\controller\BaseController;
use app\index\validate;
use think\db;

class Role extends BaseController
{
public function addRole(){
    $data = $this->request->post();
    $valdate = new validate\Check();
    if (!$valdate->batch()->scene('addRole')->check($data)) {
        return $this->resFail($valdate->getError());
    }
    $data['name']=$data['role_name'];
    unset( $data['role_name']);
    try {
        Db::name('tp_role')
            ->insert($data);
        return $this->resSuccess([], '添加成功');
    } catch (\Exception $e) {
        return $this->resFail('数据异常' . $e->getMessage());
    }
}

public function getRoleList(){
    try {
        $res= Db::name('tp_role')
            ->field('id,name,create_time')
            ->where('is_del','=',0)
            ->select();
        return $this->resSuccess($res, '获取成功');
    } catch (\Exception $e) {
        return $this->resFail('数据异常' . $e->getMessage());
    }
}

public function deleteRole(){
    $data = $this->request->post();
    $valdate = new validate\Check();
    if (!$valdate->batch()->scene('deleteRole')->check($data)) {
        return $this->resFail($valdate->getError());
    }
    try {
        Db::name('tp_role')
            ->where('id', $data['id'])
            ->setField('is_del', '1');
        return $this->resSuccess([], '删除成功');
    } catch (\Exception $e) {
        return $this->resFail('数据异常' . $e->getMessage());
    }
}
}