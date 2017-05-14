<?php
namespace app\common\model;

use think\Model;

class BisAccount extends BaseModel
{
    /**
     * @param $data
     * @param $id
     * @return false|int
     * 更新最后登录时间
     */
    public function updateById($data,$id) {
        return $this->allowField(true)->save($data,['id'=>$id]);
    }

}