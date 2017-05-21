<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
    /**
     * @param $data
     * @return false|int
     * 添加分类
     */
    public function add($data) {
        $data['status'] = 1;
        return $this->save($data);
    }

    /**
     * @param int $id
     * @return false|\PDOStatement|string|\think\Collection
     * 获取所有的一级的正常分类
     */
    public function getNormalFirstCategory($id=0) {
        $data = [
            'status'=>1,
            'parent_id'=>$id
        ];

        $order = [
            'listorder'=>'desc',
            'id'=>'desc'
        ];

        return $this->where($data)->order($order)->select();
    }

    /**
     * @param int $id
     * @return \think\Paginator
     * 获取除了删除的分类，带分页
     */
    public function getCategorys($id = 0) {
        $data = [
            'status'=>['neq',-1],
            'parent_id'=>$id
        ];

        $order = [
            'listorder'=>'desc',
            'id'=>'desc'
        ];

        $rel = $this->where($data)->order($order)->paginate();
        //echo $this->getLastSql();
        return $rel;
    }

    /**
     * @param int $id
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     * 获取前五条的分类数据
     */
    public function getNormalRecommendCategoryByParentId($id = 0,$limit = 5) {
        $data = [
            'status'=>1,
            'parent_id'=>$id
        ];
        $order = [
            'listorder'=>'desc',
            'id'=>'desc'
        ];
        $rel = $this->where($data)->order($order);
        if($limit) {
            $rel = $rel->limit($limit);
        }
        return $rel->select();
    }

    /**
     * @param $parentId
     * @return false|\PDOStatement|string|\think\Collection
     * 根据父类id查找所有的二级分类数据
     */
    public function getNormalCategoryByParentId($parentId) {
        $data = [
            'parent_id' => ['in',implode(',',$parentId)],
            'status'=>1
        ];
        $order = [
            'listorder'=>'desc',
            'id'=>'desc'
        ];
        return $this->where($data)->order($order)->select();
    }



}