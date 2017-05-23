<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;

class Weixinpay extends Controller
{
    public function notify() {
        //测试
        $weixinData = file_get_contents("php://input");
        file_put_contents('/tmp/2.txt',$weixinData,FILE_APPEND);
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
