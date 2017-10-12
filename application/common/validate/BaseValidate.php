<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 21:36
 */

namespace app\common\validate;
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
            //自定义错误
            $e = new ParamException([
                'msg'=>$this->error
            ]);
            //内部错误
            //$e = new Exception(implode(',',$this->error));
            throw $e;
        }else {
            return true;
        }
    }
}