<?php
namespace app\index\controller;
use think\Controller;

class Pay extends Base
{
    public function index() {
        //对接微信支付的api
        return '订单处理成功!';
    }
}
