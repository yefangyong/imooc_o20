<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    public $city;
    public $account;

    public function _initialize() {
        //城市数据
        $citys = model('City')->getNormalCitys();
        //传递给模板
        $this->assign('citys',$citys);
        $this->getCity($citys);
        $this->assign('city',$this->city);
        //用户数据
        $user = $this->getLoginUser();
        $this->assign('user',$user);
        //获取商品分类数据
        $cats = $this->getNormalRecommendByCategory();
        $this->assign('cat',$cats);
        $this->assign('controller',strtolower(request()->controller()));
        $this->assign('title','o2o团购网');

    }

    /**
     * @param $citys
     * 获取城市
     */
    public function getCity($citys) {
        foreach($citys as $city) {
            $city = $city->toArray();
            if($city['is_default'] == 1) {
                $defaultname = $city['uname'];
                break;
            }
        }
        $defaultname = $defaultname?$defaultname:'nanchang';
        if(session('cityuname','','index') && !input('get.city')) {
            $cityuname = session('cityuname','','index');
        }else {
            $cityuname = input('get.city',$defaultname,'trim');
            session('cityuname',$cityuname,'index');
        }

        $this->city = model('City')->where(['uname'=>$cityuname])->find();
    }


    /**
     * @return mixed
     * 获取登录用户信息
     */
    public function getLoginUser() {

        if(!$this->account) {
            $this->account = session('userAccount','','index');
        }
        return $this->account;
    }

    /**
     * @return mixed
     * 获取城市分类的数据，二级分类，
     */
    public function getNormalRecommendByCategory() {
        //获取一级分类的数据
        $cats = model('Category')->getNormalRecommendCategoryByParentId(0,5);
        foreach($cats as $cat) {
            $parentIds[] = $cat->id;
        }
        //获取二级分类数据
        $sedCats = model('Category')->getNormalCategoryByParentId($parentIds);

        foreach($sedCats as $sedCat) {
            $sedCatsArr[$sedCat->parent_id][] =[
                'id'=>$sedCat->id,
                'name'=>$sedCat->name
            ];
        }
        //组合数据
        foreach($cats as $cat) {
            $recomCats[$cat->id] = [$cat->name,empty($sedCatsArr[$cat->id])?'':$sedCatsArr[$cat->id]];
        }

        return $recomCats;
    }

}
