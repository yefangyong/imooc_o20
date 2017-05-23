<?php
namespace app\index\controller;
use think\Controller;

class Weixinpay extends Controller
{
    public function notify() {
        //测试
        $weixinData = file_get_contents("php://input");
        file_put_contents('/tmp/2.txt',$weixinData,FILE_APPEND);
    }
}
