<?php
namespace app\index\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        //广告位推荐内容
        $left = model('Featured')->getNormalFeaturedByType(0,2);
        $right =  model('Featured')->getNormalFeaturedByType(1,1);

        //商品根据城市和分类获取  美食->数据推荐的数据
        $food = model('Deal')->getNormalDealByCategoryIdCityId(1,$this->city->id);

        //获得4个子分类
        $cats = model('Category')->getNormalRecommendCategoryByParentId(1,4);

        //酒店推荐内容
        $hotel = model('Deal')->getNormalDealByCategoryIdCityId(4,$this->city->id);

        $cat2 = model('Category')->getNormalRecommendCategoryByParentId(4,4);
        return $this->fetch('',[
            'left'=>$left,
            'right'=>$right,
            'food'=>$food,
            'cats'=>$cats,
            'hotel'=>$hotel,
            'cat2'=>$cat2
        ]);
    }
}
