<?php


namespace app\manage\controller;


use app\index\controller\BaseController;
use think\Db;
use app\index\validate;

class Product extends BaseController
{
    public function getProductList(){
        $page_num = $this->request->post('page_num', '1');
        $page_size = $this->request->post('start_time', '10');
        $data = $this->request->post();
        $valdate = new validate\Check();
        if (!$valdate->batch()->scene('getProductList')->check($data)) {
            return $this->resFail($valdate->getError());
        }
        $condition = [];
        if (!empty($data['product_name'])) {
            $condition[] = ['name', 'like', "%{$data['product_name']}%"];
        }
        if (!empty($data['product_desc'])) {
            $condition[] = ['desc', 'like', "%{$data['product_desc']}%"];
        }
        $res = Db::name('tp_product tp')
            ->leftJoin('tp_category tc','tp.category_id = tc.id')
            ->field('tp.id,tp.name,tp.desc,tp.is_sale,tc.p_id,tp.category_id,')
            ->where('is_del','=',0)
            ->where('tp.category_id','=','tc.id')
            ->where($condition)
            ->limit($page_num,$page_size)
            ->select();

        return $res;


           $re2= Db::name('tp_category')
            ->field('p_id')
            ->where($res['category_id'])
            ->limit($page_num,$page_size)
            ->select();

        return $re2;
    }
}