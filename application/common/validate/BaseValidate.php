<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 21:36
 */

namespace app\common\validate;


use app\lib\exception\BaseException;
use app\lib\exception\ParamException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * @return bool
     * 验证方法
     */
    public function goCheck() {
        $request = Request::instance();
        $param = $request->param();
        $result = $this->batch()->check($param);

        if(!$result) {
            $e = new ParamException([
                'msg'=>'测试啊啊啊'
            ]);
            throw $e;
        }else {
            return true;
        }
    }
}