<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;
use app\index\validate;

class Product extends BaseController
{
    //    protected $middleware = ['ApiLogMiddleware', 'ApiAuthMiddleware'];
//    public function getProductList()
//    {
//        $page_num = $this->request->post('page_num', '1');
//        $page_size = $this->request->post('start_time', '10');
//        $data = $this->request->post();
//        $valdate = new validate\Check();
//        if (!$valdate->batch()->scene('getProductList')->check($data)) {
//            return $this->resFail($valdate->getError());
//        }
//        $condition = [];
//        if (!empty($data['product_name'])) {
//            $condition[] = ['name', 'like', "%{$data['product_name']}%"];
//        }
//        if (!empty($data['product_desc'])) {
//            $condition[] = ['desc', 'like', "%{$data['product_desc']}%"];
//        }
//        try {
//            $res = Db::name('tp_product tp')
//                ->join('tp_product tp1')
//                ->leftJoin('tp_category tc', 'tp1.category_id = tc.id')
//                ->field('tp.id,tp.name,tp.desc,tp.is_sale,tc.p_id,tp.category_id,')
//                ->where('tp1.category_id','=','tc.id')
//                ->where('is_del', '=', 0)
//                ->where($condition)
//                ->page($page_num, $page_size)
//                ->find();
//            var_dump(Db::getLastInsID());
//        } catch
//        (\Exception $e) {
//            return $this->resFail('数据异常' . $e->getMessage());
//        }
//        die();
//
//    }


public function addProduct(){
        $data = $this->request->post();
        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('getProductList')->check($data)) {
            return $this->resFail($valdate->getError());
        }
    try {
        Db::name('tp_product')
            ->insert($data);
        return $this->resSuccess([], '插入成功');
    }catch (\Exception $e) {
            return $this->resFail('数据异常' . $e->getMessage());
        }
}


}