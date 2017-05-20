<?php
namespace app\common\model;

use think\Model;

class User extends BaseModel
{

    /**
     * @param $data
     * @return false|int|void
     * 重载add方法
     */
    public function add($data) {
        if(!is_array($data)) {
            return exception('数据不是一个数组!');
        }
        $data['status'] = 1;
         return $this->data($data)->allowField(true)->save();
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     * 修改最后登录时间
     */
    public function updateById($data,$id) {
       return $this->allowField(true)->save($data,['id'=>$id]);
    }
}