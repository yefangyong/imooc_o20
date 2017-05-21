<?php
namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{
    /**
     * @param $type
     * @return string|\think\Paginator
     * 通过类型获取推荐位列表
     */
    public function getFeaturedByType($type) {
        $data = [
            'status'=>['neq',-1],
            'type'=>$type
        ];
        $order = ['id'=>'desc'];
        $rel = $this->where($data)->order($order)->paginate(2);
        if($rel) {
            return $rel;
        }else {
            return '';
        }
}

    /**
     * @param $type
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     * 根据类型和数量获取正常的推荐位
     */
    public function getNormalFeaturedByType($type,$limit=1) {
        $data = [
            'status'=>1,
            'type'=>$type
        ];
        $order = [
            'id'=>'desc'
        ];
        $rel = $this->where($data)->order($order);
        if($limit) {
            $rel->limit($limit);
        }
        return $rel->select();
    }

}