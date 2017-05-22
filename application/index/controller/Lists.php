<?php
namespace app\index\controller;
use think\Controller;
class Lists extends Base {

    public function index() {
        //思路，先获取一级分类,然后判断是一级分类？二级分类，还是全部
        $categorys = model('Category')->getNormalFirstCategory();
        foreach($categorys as $category) {
            $firstCatId[] = $category->id;
        }

        $id = input('id',0,'intval');

        //判断是一级分类？二级分类，还是全部
        $data = [];
        if(in_array($id,$firstCatId)) {//一级分类
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif($id){//二级分类
            $category = model('Category')->get($id);
            if(!$category || $category->status!=1) {
                return $this->error('不存在此分类!');
            }
            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $category->id;
            $data['category_id'] = $categoryParentId;
        }else {//全部
            $categoryParentId = 0;
        }

        //哪个城市下面的数据
        $data['city_id'] = $this->city->id;

        $sedCategorys = '';
        //获取父类下面的全部子分类
        if($categoryParentId) {
            $sedCategorys = model('Category')->getNormalFirstCategory($categoryParentId);
        }else {
            $sedCategorys = model('Category')->getAllNormalSecondCategory();
        }

        //排序数据
        $order = [];
        $order_time = input('order_time','');
        $order_price = input('order_price','');
        $order_sale = input('order_sale','');
        if(!empty($order_price)) {
            $orderflag = 'order_price';
            $order['order_price'] = $order_price;
        }elseif(!empty($order_sale)) {
            $orderflag = 'order_sale';
            $order['order_sale'] = $order_sale;
        }elseif($order_time) {
            $orderflag = 'order_time';
            $order['order_time'] = $order_time;
        }else {
            $orderflag = '';
        }

        //根据条件获取商品的数据
        $deals = model('Deal')->getDealsByConditions($data,$order);
        return $this->fetch('',[
            'id'=>$id,
            'categorys'=>$categorys,
            'sedCategorys'=>$sedCategorys,
            'categoryParentId'=>$categoryParentId,
            'orderflag' =>$orderflag,
            'deals'=>$deals
        ]);
    }
}