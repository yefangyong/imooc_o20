<?php
namespace app\common\model;

use think\Model;

class BisLocation extends BaseModel
{
    /**
     * @param $id
     * @return bool
     * 获取根据总店Id门店列表
     */
    public function getBisByStatus($id) {
        $data = ['bis_id'=>$id];
        $order = ['id'=>'desc'];
        $rel = $this->where($data)->order($order)->paginate(1);
        if($rel) {
            return $rel;
        }else {
            return false;
        }
    }


    /**
     * @param $bisId
     * @return false|\PDOStatement|string|\think\Collection
     * 获取门店信息列表无分页
     */
    public function getNormalBisById($bisId) {
        $data = [
            'status'=>1,
            'bis_id'=>$bisId
        ];
        $rel = $this->where($data)->select();
        if($rel) {
            return $rel;
        }else {
            return '';
        }
    }

}