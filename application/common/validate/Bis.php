<?php
namespace app\common\validate;
use think\Validate;

class Bis extends Validate {
    protected $rule = [
       'name'=>'require|max:25',
        'email'=>'email',
        'logo'=>'require',
        'bank_name'=>'require',
        'bank_info'=>'require',
        'bank_user'=>'require',
        'faren'=>'require',
        'faren_tel'=>'require',
    ];

    /*
     * 场景设置和laravel中的好像
     */
    protected $scene = [
        'add'=>['name','email','logo','bank_name','bank_user','bank_info','faren','faren_tel'], //添加
        'listorder'=>['id','listorder'],
    ];
}