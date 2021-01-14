<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;
use app\index\validate;

/**
 * Class Category
 * @package app\manage\controller
 */
class Category extends BaseController
{
    //    protected $middleware = ['ApiLogMiddleware', 'ApiAuthMiddleware'];
    public function getCategoryList()
    {
        $data = $this->request->post();
        $page_num = $this->request->post('page_num', '1');
        $page_size = $this->request->post('start_time', '10');

        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('getCategoryList')->check($data)) {
            return $this->resFail($valdate->getError());
        }
        try {
            if ($data['p_id'] == 0) {
                $p_id = Db::name('tp_category')
                    ->field('id,p_id,name')
                    ->where('p_id', '=', '0')
                    ->limit($page_num, $page_size)
                    ->select();
                return $this->resSuccess($p_id, '查询成功');
            }
                $p_id = Db::name('tp_category')
                    ->field('id,p_id,name')
                    ->where('p_id', '=', $data['p_id'])
                    ->limit($page_num, $page_size)
                    ->select();
            if (!empty($p_id)) {
                return $this->resSuccess($p_id, '查询成功');
            }else
                {
                    return $this->resFail('请输入正确的父类ID' ,1);
            }


        } catch
        (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function addCategory(){
        $data = $this->request->post();
        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('addCategory')->check($data)) {
            return $this->resFail($valdate->getError());
        }
        $data['name'] = $data['category_name'];
        unset( $data['category_name']);
        try {
            Db::name('tp_category')
                ->where('p_id', '=', $data['p_id'])
                ->insert($data);
            return $this->resSuccess([],'插入成功');
        }catch (\Exception $e){
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    public function updateCategory(){
        $data = $this->request->post();
        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('updateCategory')->check($data)) {
            return $this->resFail($valdate->getError());
        }
        $data['name'] = $data['category_name'];
        unset( $data['category_name']);
        try {
            Db::name('tp_category')
                ->where('id', '=', $data['id'])
                ->update($data);
            return $this->resSuccess([],'插入成功');
        }catch (\Exception $e){
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }
}