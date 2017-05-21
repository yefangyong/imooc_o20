<?php
namespace app\admin\controller;
use think\Controller;
use think\Model;

class Deal extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('Bis');
    }

    /**
     * @return mixed
     * 获取审核后的所有团购商品
     */
    public function index() {
        $data = input('get.');
        $sdata = [];
        if(!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time'])>strtotime($data['start_time'])) {
            $sdata['create_time'] = [
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])]
            ];
        }
        if(!empty($data['category_id'])) {
            $sdata['category_id'] = $data['category_id'];
        }
        if(!empty($data['city_id'])) {
            $sdata['city_id'] = $data['city_id'];
        }
        if(!empty($data['name'])) {
            $sdata['name'] = ['like','%'.$data['name'].'%'];
        }
        $deal = model('Deal')->getNormalDeal($sdata);
        $categoryArrs = $cityArrs = [];
        $categorys = model('Category')->getNormalFirstCategory();
        foreach($categorys as $category) {
            $categoryArrs[$category->id] = $category->name;
        }
        $citys = model('City')->getNormalCitys();
        foreach($citys as $city) {
            $cityArrs[$city->id] = $city->name;
        }
        return $this->fetch('',['categorys'=>$categorys,
                              'citys'=>$citys,
                                'deal'=>$deal,
                                'cityArrs'=>$cityArrs,
                                'categoryArrs'=>$categoryArrs,
                                'name'=>empty($data['name'])?'':$data['name'],
                                'category_id'=>empty($data['category_id'])?'':$data['category_id'],
                                'city_id'=>empty($data['city_id'])?'':$data['city_id'],
                                'start_time'=>empty($data['start_time'])?'':$data['start_time'],
                                'end_time'=>empty($data['end_time'])?'':$data['end_time']
                            ]);
    }






}
