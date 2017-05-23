<?php
namespace app\index\controller;
use think\Controller;

class Order extends Base
{

    /**
     * 订单入库处理
     */
    public function add()
    {
        $user = $this->getLoginUser();
        if (!$user) {
            $this->error('请登录,', 'user/login');
        }
        $count = input('get.count', 0, 'intval');
        $total_price = input('get.total_price');
        if (!$count || !$total_price) {
            return $this->error('参数不合法!');
        }
        $id = input('get.id', 0, 'intval');
        if (!$id) {
            return $this->error('参数不合法!');
        }
        $deal = model('Deal')->get($id);
        if (!$deal || $deal->status != 1) {
            return $this->error('商品不存在!');
        }
        $orderSn = setOrderSn();
        $data = [
            'out_trade_no' => $orderSn,
            'user_id' => $user->id,
            'username' => $user->username,
            'deal_id' => $id,
            'deal_count' => $count,
            'total_price' => $total_price,
            'referer' => $_SERVER['HTTP_REFERER']
        ];
        $orderId = model('Order')->add($data);
        if($orderId) {
            $this->redirect('pay/index',['orderId'=>$orderId]);
        }else{
            return $this->error('订单处理失败!');
        }
    }


    /**
     * @return mixed|void
     * 确认订单开发页面
     */
    public function Confirm() {
        $user = $this->getLoginUser();
        if(!$user) {
             $this->error('请登录','user/login');
        }
        $id = input('get.id',0,'intval');
        if(!$id) {
            $this->error('参数不合法!');
        }
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status!=1) {
            return $this->error('商品不存在或者已下架');
        }
        $count = input('get.count',1,'intval');
        //$deal = $deal->toArray();
        $this->assign('controller','pay');
        return $this->fetch('',[
            'deal'=>$deal,
            'title'=>'支付页',
            'user'=>$user,
            'count'=>$count

        ]);
    }

}
