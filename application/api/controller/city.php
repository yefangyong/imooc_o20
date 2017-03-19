<?php
namespace app\api\controller;

use think\Controller;

class City extends Controller
{
    public function getCitysByParentId() {
        $id = input('post.id');
        if(!$id) {
            return show(0,'ID不合法');
        }
        //获取二级城市
        $citys = model('City')->getNormalCitysByParentId($id);
        if(!$citys) {
            return show('0','没有数据!');
        }else {
            return show(1,'请求成功',$citys);
        }
    }
}
