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

}
