<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 22:18
 */

namespace app\lib\exception;


class ParamException extends BaseException
{
    //http 状态码
    public $code = 400;

    //错误信息
    public $msg = '参数错误';

    //错误码
    public $errorCode = '10000';
}