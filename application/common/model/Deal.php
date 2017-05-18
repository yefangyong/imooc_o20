<?php
namespace app\common\model;

use think\Model;

class Deal extends BaseModel
{
    /**
     * @param $id
     * @return string|\think\Paginator
     * 团购商品列表开发，某个商户下的团购商品
     */
    public function getDealById($id) {
        $data = [
            'bis_id'=>$id,
        ];

        $order = [
            'id'=>'desc'
        ];

        $rel = $this->where($data)->order($order)->paginate(1);
        if($rel) {
            return $rel;
        }else {
            return '';
        }
    }

    /**
     * @param array $data
     * @return bool|\think\Paginator
     * 获取审核后的所有团购商品
     */
    public function getNormalDeal($data = []) {
        $data['status'] = 1;
        $order = ['id'=>'desc'];

        $rel = $this->where($data)->order($order)->paginate(1);

//        echo $this->getLastSql();
//        exit();
        if($rel) {
            return $rel;
        }else {
            return false;
        }
    }

}