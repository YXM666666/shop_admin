<?php

namespace app\index\validate;

use think\Validate;

class User extends Validate
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
        'category_id|在分类表的id' => 'number',
        'name|商品名' =>'',
        'price|商品价格' => 'number'
    ];
    protected $scene = [
        'reg'=>['username','email','phone','password','role_id'],
        'login'=>['username','password'],
        'updata'=>['phone','email','id','role_id'],
        'updateProduct'=>['id','category_id','name','price']
    ];
    protected $message = [];
}
