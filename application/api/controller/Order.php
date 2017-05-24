<?php
namespace app\api\controller;

use think\Controller;

class Order extends Controller
{
    public function paystatus() {
        $id = input('post.id',0,'intval');
        if(!$id) {
            return show(0,'error');
        }
        //判断是否登录了
        $user  = session('userAccount','','index');
        if(!$user) {
            return show(0,'未登录');
        }

        $order = model('Order')->get($id);
        if($order && $order->pay_status == 1) {
            return show(1,'success');
        }
    }
}
