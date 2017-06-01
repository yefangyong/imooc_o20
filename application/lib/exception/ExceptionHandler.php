<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 21:51
 */

namespace app\lib\exception;


use think\Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    //http 状态码
    public $code = 400;

    //错误信息
    public $msg = '参数错误';

    //错误码
    public $errorCode = '10000';

    public function render(Exception $e)
    {

        if($e instanceof BaseException) {
            //用户行为导致的错误,返回给用户具体的错误信息，无需记录日志
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else {
            //服务器内部错误，需要记录日志，无需告诉给用户
            $this->code = '500';
            $this->msg = '服务器内部错误，不想告诉你!';
            $this->errorCode = '999';
            //记录错误日志
            $this->recordErrorLog($e);
        }
        $request = Request::instance();
        $result = [
            'code'=>$this->code,
            'msg'=>$this->msg,
            'errorCode'=>$this->errorCode,
            'url'=>$request->url()
        ];
        return json($result,$this->code);
    }

    /**
     * @param Exception $e
     * 记录错误异常，用户导致的异常无需记录日志，意义不大，
     * 服务器内部产生的异常需要记录到日志文件，排错
     * 生产环境下，只能通过日志来排查错误，测试环境下可以直接打断点排查错误
     */
    private function recordErrorLog(Exception $e) {
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        Log::record($e->getMessage(),'error');
    }

}