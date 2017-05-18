<?php

namespace app\Common\model;

use think\Model;

class City extends Model
{
    //
    public function getNormalCitysByParentId($parent_id = 0) {
        $data = [
            'status'=>1,
            'parent_id'=>$parent_id
        ];

        $order = [
            'id'=>'desc',
            'listorder'=>'desc'
        ];

        return $this->where($data)->order($order)->select();
    }

    public function getNormalCitys() {
        $data = [
            'status'=>1,
            'parent_id'=>['gt',0],
        ];

        $order = [
            'id'=>'desc',
        ];

        $rel = $this->where($data)->order($order)->select();

        return $rel;
    }
}
