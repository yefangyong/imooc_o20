<?php

namespace app\Common\model;

use think\Model;

class Order extends Model
{
    /**
     * @param array $data
     * @return false|int
     * 订单数据入库
     */
    public function add($data = []) {
        $data['status'] = 1;
        $this->allowField(true)->save($data);
        return $this->id;
    }


    /**
     * @param $outTradeNo
     * @param $weixinData
     * @return false|int
     * 更新订单表的数据
     */
    public function updateOrderByoutTradeNo($outTradeNo,$weixinData) {
        if(!empty($weixinData['transaction_id'])) {
            $data['transaction_id'] = $weixinData['transaction_id'];
        }
        if(!empty($weixinData['total_fee'])) {
            $data['pay_amount'] = $weixinData['total_fee']/100;
            $data['pay_status'] = 1;
        }
        if(!empty($weixinData['time_end'])) {
            $data['pay_time'] = $weixinData['time_end'];
        }

        return $this->allowField(true)->save($data,['out_trade_no'=>$outTradeNo]);
    }

}
