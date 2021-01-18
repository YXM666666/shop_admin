<?php

namespace app\index\validate;

use think\Validate;

class Check extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|用户ID'              =>    'require|number',
        'username|用户名'  =>    'string',
        'email|邮件'      =>     'email',
        'phone|手机号'    =>    'mobile',
        'password|密码'    =>    'require|alphaNum',
        'role_id|角色id'  =>      'number|length:1',
        'page_size|每页多少行' =>  'number',
        'page_num|分几页' =>  'number',
        'p_id|父类id' => 'require|number',
        'category_name|商品名' => 'require',
        'category_id|在分类表的id' => 'require|number',
        'name|商品名' =>'require',
        'price|商品价格' => 'number|require',
        'is_sale|商品上架' => 'bool|require',
        'role_name|角色名称' => 'require'

    ];
    protected $scene = [
        'reg'=>['username','email','phone','password','role_id'],
        'login'=>['username','password'],
        'updata'=>['phone','email','id','role_id'],
        'getUserlist'=>['phone','page_num','page_size'],
        'deleteUser'=>['id'],
        'getCategoryList'=>['p_id','page_num','page_size'],
        'addCategory'=>['p_id','category_name'],
        'updateCategory'=>['id','category_name'],
        'getCategoryInfo'=>['category_id'],
        'getProductList'=>['page_num','page_size'],
        'addProduct'=>['category_id','name','price'],
        'updateProductStatus'=>['id','is_sale'],
        'deleteProduct'=>['id'],
        'addRole'=>['role_name'],
        'deleteRole'=>['id']
    ];
    protected $message = [];
}
