<?php
namespace app\api\controller;

use think\Controller;

class Category extends Controller
{
    public function getCategorysByParentId() {
        $id = input('post.id');
        if(!intval($id)) {
            return show(0,'ID不合法');
        }
        //获取二级城市
        $category = model('Category')->getNormalFirstCategory($id);
        if(!$category) {
            return show('0','没有数据!');
        }else {
            return show(1,'请求成功',$category);
        }
    }
}
