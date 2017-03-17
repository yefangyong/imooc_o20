<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
    public function add($data) {
        $data['status'] = 1;
        return $this->save($data);
    }

    public function getNormalFirstCategory() {
        $data = [
            'status'=>1,
            'parent_id'=>'0'
        ];

        $order = [
            'id'=>'desc',
            'listorder'=>'desc'
        ];

        return $this->where($data)->order($order)->select();
    }

    public function getCatorys($id = 0) {
        $data = [
            'status'=>['neq',-1],
            'parent_id'=>$id
        ];

        $order = ['id'=>'desc'];

        $rel = $this->where($data)->order($order)->paginate();
        //echo $this->getLastSql();
        return $rel;
    }



}