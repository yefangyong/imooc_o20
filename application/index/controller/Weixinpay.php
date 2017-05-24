<?php
namespace app\index\controller;
use think\Controller;
use think\Exception;
use wxpay\database\WxPayResults;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;

class Weixinpay extends Controller
{
    public function notify() {

        $weixinData = file_get_contents("php://input");
        file_put_contents('/tmp/2.txt',$weixinData,FILE_APPEND);
        try {
            $resultObj = new WxPayResults();
            $weixinData = $resultObj->Init($weixinData);
        }catch(Exception $e) {
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('return_msg',$e->getMessage());
            return $resultObj->toXml();
        }
        if($weixinData['return_code'] === 'FAIL' || $weixinData['result_code'] !== 'SUCCESS' ) {
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('result_code','error');
            return $resultObj->toXml();
        }
        //根据out_trade_no来查询订单数据
        $outTradeNo = $weixinData['out_trade_no'];
        $order = model('Order')->get(['out_trade_no'=>$outTradeNo]);

        if(!$order || $order->pay_status == 1) {
            $resultObj->setData('return_code','SUCCESS');
            $resultObj->setData('result_code','ok');
            return $resultObj->toXml();
        }

        //更新订单表 ，还有商品表中购买商品的数量
        try {
            $orderRes = model('Order')->updateOrderByoutTradeNo($outTradeNo,$weixinData);
            $dealRes = model('Deal')->UpdateBuyCountBy($order->deal_id,$order->deal_count);

            //消费券的生成
           $coupons = [
               'sn'=>$outTradeNo,
               'password'=>rand(10000,99999),
               'user_id'=>$order->user_id,
               'deal_id'=>$order->deal_id,
               'order_id'=>$order->id,
           ];
            $rel = model('Coupons')->add($coupons);
            if($rel) {
                //发送邮件,但是一般不发送邮件，一般加入队列处理，减轻服务器的压力，减少耦合
                $user = session('userAccount','','index');
                //发送邮件给注册商户
                $title ='优惠券发放通知!';
                $content = "您购买的商品的优惠券已经发放，请尽快使用!";
                \phpmailer\Email::send($user->email,$title,$content);
            }
        }catch(Exception $e) {
            //更新失败，告诉微信服务器我们需要回调
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('result_code','error');
            return $resultObj->toXml();
        }

        $resultObj->setData('return_code','SUCCESS');
        $resultObj->setData('result_code','ok');
        return $resultObj->toXml();





    }

    /**
     * 流程：
     * 1、调用统一下单，取得code_url，生成二维码
     * 2、用户扫描二维码，进行支付
     * 3、支付完成之后，微信服务器会通知支付成功
     * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
     */
    public function weixinQcode($id) {
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody("支付0.01元");
        $input->setAttach("支付0.01元");
        $input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->setTotalFee("1");
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag("QRcode");
        //支付成功的回调函数
        $input->setNotifyUrl("115.159.6.199/index.php/index/weixinpay/notify");
        $input->setTradeType("NATIVE");
        $input->setProductId($id);
        $result = $notify->getPayUrl($input);
        if(empty($result['code_url'])) {
            $url = '';
        }else {
            $url = $result["code_url"];
        }
        return '<img alt="扫码支付" src="/weixin/example/qrcode.php?data='.urlencode($url).'" style="width:150px;height:150px;"/>';

    }
}
