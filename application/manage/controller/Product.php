<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;
use app\index\validate\Verification;
use app\index\validate\Check;
class Product extends BaseController
{
//        protected $middleware = ['ApiLogMiddleware', 'ApiAuthMiddleware'];

    /**
     * 这是一个获取商品分页列表接口
     * @return array
     */
    public function getProductList()
    {
        $data = $this->request->post();

        $valdate = new Verification();
        if (!$valdate->batch()->scene('getProductList')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        $condition = [];

        if (!empty($data['product_name'])) {
            $condition[] = ['p.name','like', "%{$data['product_name']}%"];
        }

        if (!empty($data['product_desc'])) {
            $condition[] = ['p.desc','like', "%{$data['product_desc']}%"];
        }


        try {
            $res = Db::name('tp_product p')
                ->leftJoin('tp_category c', 'p.category_id = c.id')
                ->field('p.id,p.name name,p.desc,p.price,p.is_sale,c.p_id p_category_id,c.id category_id,c.name category_name')
                ->where('p.is_del', '=', 0)
                ->where($condition)
                ->page($data['page_num'],$data['page_size'])
                ->select();
            return $this->resSuccess($res);
        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }

    }

    /**
     * 这是一个添加商品接口
     * @return array
     */
    public function addProduct()
    {
        $data = $this->request->post();

        $valdate = new Check();
        if (!$valdate->batch()->scene('getProductList')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        try {
            Db::name('tp_product')
                ->insert($data);
            return $this->resSuccess([], '插入成功');
        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    /**
     * 这是一个更新商品接口
     * @return array
     */
    public function updateProduct()
    {
        $data = $this->request->post();

        $valdate = new Verification();
        if (!$valdate->batch()->scene('updateProduct')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        $update = [];

        if (!empty($data['category_id'])) {
            $update['category_id'] = $data['category_id'];
        }

        if (!empty($data['name'])) {
            $update['name'] = $data['name'];
        }

        if (!empty($data['price'])) {
            $update['price'] = $data['price'];
        }

        if (!empty($data['desc'])) {
            $update['desc'] = $data['desc'];
        }

        try {
            Db::name('tp_product')
                ->where('id', '=', $data['id'])
                ->update($update);
            return $this->resSuccess([], '更新成功');
        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    /**
     * 这是一个对商品进行上架/下架处理的接口
     * @return array
     */
    public function updateProductStatus()
    {
        $data = $this->request->post();

        $valdate = new Check();
        if (!$valdate->batch()->scene('updateProductStatus')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        try {

            Db::name('tp_product')
                ->update($data);
            return $this->resSuccess([], '更新成功');
        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }

    /**
     * 这是一个删除商品的接口
     * @return array
     */
    public function deleteProduct()
    {
        $data = $this->request->post();

        $valdate = new Check();
        if (!$valdate->batch()->scene('deleteProduct')->check($data)) {
            return $this->resFail($valdate->getError());
        }

        try {
            Db::name('tp_product')
                ->where('id', $data['id'])
                ->update(['is_del'=>'1']);
            return $this->resSuccess([], '删除成功');
        } catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
    }


}