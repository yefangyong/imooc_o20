<?php
namespace app\bis\controller;
use think\Controller;
use think\Model;

class Register extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('City');
    }

    public function index() {
        //获取一级城市
        $citys = $this->obj->getNormalCitysByParentId();
        //获取一级栏目
        $categorys = Model('Category')->getCategorys();
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys
        ]);
    }

}