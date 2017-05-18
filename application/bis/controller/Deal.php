<?php
namespace app\bis\controller;

use think\Controller;

class Deal extends Base
{
    public function index() {
        $bisId = $this->getLoginUser()->bis_id;
        $data = model('Deal')->getDealById($bisId);
        if(!$data || empty($data)) {
            return $this->error('没有数据!');
        }
        return $this->fetch('',['data'=>$data]);
    }

    public function add() {
        if(request()->isPost()) {
            //验证数据的合法性，用tp5的validate验证机制

            //数据入库
            $data = input('post.');

            //有问题，多选的数据js获取不到，待解决
            $location = model('BisLocation')->get($data['location_ids'][0]);
            $deals = [
                'name'=>$data['name'],
                'bis_id'=>$this->getLoginUser()->bis_id,
                'city_id'=>$data['city_id'],
                'image'=>$data['image'],
                'category_id'=>$data['category_id'],
                'se_category_id'=>empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
                'location_ids'=>empty($data['location_ids'])?'':implode(',',$data['location_ids']),
                'start_time'=>strtotime($data['start_time']),
                'end_time'=>strtotime($data['end_time']),
                'total_count'=>$data['total_count'],
                'origin_price' =>$data['origin_price'],
                'current_price'=>$data['current_price'],
                'coupons_begin_time'=>strtotime($data['coupons_begin_time']),
                'coupons_end_time'=>strtotime($data['coupons_end_time']),
                'notes'=>$data['notes'],
                'description'=>$data['description'],
                'bis_account_id'=>$this->getLoginUser()->id,
                'xpoint'=>$location->xpoint,
                'ypoint'=>$location->ypoint
            ];

            $rel = model('Deal')->add($deals);
            if($rel) {
                return show(1,'团购商品添加成功!');
            }else {
                return show(0,'团购商品添加失败!');
            }

        }else {
            //获取一级城市
            $citys = model('city')->getNormalCitysByParentId();
            //获取一级栏目
            $categorys = Model('Category')->getCategorys();
            //获取门店信息
            $bisId = $this->getLoginUser()->bis_id;
            $bisLocation = model('BisLocation')->getNormalBisById($bisId);
            return $this->fetch('',[
                'citys'=>$citys,
                'categorys'=>$categorys,
                'bisLocation'=>$bisLocation
            ]);
        }
    }

}
