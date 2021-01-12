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
        'username|用户名'  =>    'require',
        'email|邮件'      =>     'email',
        'phone|手机号'    =>    'mobile',
        'password|密码'    =>    'require|alphaNum',
        'role_id|角色id'  =>      'require|number|length:1'
    ];
    protected $scene = [
        'reg'=>['username','email','mobile','password','role_id'],
        'login'=>['username','password'],
        'updata'=>['mobile','email','id']
    ];
    protected $message = [];
}
