<?php
namespace app\admin\validate;
use think\Validate;

class Category extends Validate {
    protected $rule = [
        ['name','require|max:10','分类名称必须传递|分类名不得超过十个字符'],
        ['parent_id','number'],
        ['id','number'],
        ['status','number|in:0,-1,1','状态必须为数字|状态范围不合法'],
        ['listorder','number']
    ];

    /*
     * 场景设置和laravel中的好像
     */
    protected $scene = [
        'add'=>['name','parent_id'], //添加
        'listorder'=>['id','listorder'],
    ];
}