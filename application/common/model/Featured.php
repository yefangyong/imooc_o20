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

}