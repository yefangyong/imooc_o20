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

    /**
     * @param $id
     * @param $cityId
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     * 根据城市和分类获取商品信息
     */
    public function getNormalDealByCategoryIdCityId($id,$cityId,$limit=10) {
        $data = [
            'end_time'=>['gt',time()],
            'category_id'=>$id,
            'city_id'=>$cityId,
            'status'=>1,
        ];
        $order = [
            'id'=>'desc'
        ];
        $rel = $this->where($data)->order($order);
        if($limit) {
            $rel = $rel->limit($limit);
        }
        return $rel->select();
    }

    /**
     * @param array $data
     * @param string $order
     * @return \think\Paginator
     * 根据条件获取商品数据
     */
    public function getDealsByConditions($data=[],$orders) {
        $order = [];
        $data['status'] = 1;
        if(!empty($orders['order_sale'])) {
            $order['buy_count'] = 'desc';
        }
        if(!empty($orders['order_time'])) {
            $order['create_time'] = 'desc';
        }
        if(!empty($orders['order_price'])) {
            $order['current_price'] = 'desc';
        }
        $datas = [];
        $datas[] = "end_time>".time();
        if(!empty($data['se_category_id'])) {
            $datas[] = "find_in_set(".$data['se_category_id'].",se_category_id)";
        }
        if(!empty($data['city_id'])) {
            $datas[] = 'city_id='.$data['city_id'];
        }
        if(!empty($data['category_id'])) {
            $datas[] = 'category_id='.$data['category_id'];
        }

        $datas[] = "status=1";

        $rel =  $this->allowField(true)->where(implode(' AND ',$datas))->order($order)->paginate(1);

         return $rel;
    }

}