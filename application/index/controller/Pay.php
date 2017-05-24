<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;
class Pay extends Base
{
    public function index() {
        $user = $this->getLoginUser();
        if(!$user) {
            return $this->error('请登录','user/login');
        }
        $orderId = input('get.orderId',0,'intval');
        if(!$orderId) {
            return $this->error('参数不合法!');
        }
        $order = model('Order')->get($orderId);
        if(!$order || $order->status!=1 || $order->pay_status != 0) {
            return $this->error('订单不存在!');
        }
        //严格判定
        if($order->username != $user->username) {
            return $this->error('不是你本人的订单!');
        }
        $deal = model('Deal')->get($order->deal_id);
        if(!$deal || $deal->status!=1) {
            return $this->error('商品不存在!');
        }

        //生成二维码
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody($deal->name);
        $input->setAttach($deal->name);
        $input->setOutTradeNo($order->out_trade_no);
        $input->setTotalFee($order->total_price*100);
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag("QRcode");
        //支付成功的回调函数
        $input->setNotifyUrl("115.159.6.199/index.php/index/weixinpay/notify");
        $input->setTradeType("NATIVE");
        $input->setProductId($deal->id);
        $result = $notify->getPayUrl($input);
        if(empty($result['code_url'])) {
            $url = '';
        }else {
            $url = $result["code_url"];
        }

        return $this->fetch('',[
            'deal'=>$deal,
            'order'=>$order,
            'url'=>$url
        ]);

    }

    /**
     * @return mixed
     * 成功页面
     */
    public function paysuccess() {
        return $this->fetch();
    }
}
