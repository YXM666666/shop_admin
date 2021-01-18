<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;
use app\index\validate;


class Category extends BaseController
{
    //    protected $middleware = ['ApiLogMiddleware', 'ApiAuthMiddleware'];
    //这是获取一级或二级分类列表的接口
    public function getCategoryList()
    {
        $data = $this->request->post();

        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('getCategoryList')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        try {
                $p_id = Db::name('tp_category')
                    ->field('id,p_id,name')
                    ->where('p_id', '=', $data['p_id'])
                    ->page($data['page_num'],$data['page_size'])
                    ->select();
            if (!empty($p_id)) {
                return $this->resSuccess($p_id, '查询成功');
            }else
                {
                    return $this->resSuccess([], '查询成功');
            }
        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    /**
     * 这是一个添加分类接口
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
                ->insert($data);
            return $this->resSuccess([],'插入成功');
        }catch (\Exception $e){
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    /**
     * 这是一个更新商品名称接口
     * @return array
     */
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

    /**
     * 这是一个根据分类ID获取分类接口
     * @return array
     */
    public function getCategoryInfo(){
        $data = $this->request->post();

        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('getCategoryInfo')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        try {
            $result = Db::name('tp_category')
                ->field('id,p_id,name')
                ->where('is_del','=',0)
                ->where('id',$data['category_id'])
                ->find();
            if (!empty($result)) {
                return $this->resSuccess($result, '查询成功');
            }else{
                return $this->resSuccess([], '查询成功');
            }
        }catch (\Exception $e){
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }
}