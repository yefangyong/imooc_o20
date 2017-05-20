<?php
namespace app\admin\controller;
use think\Controller;
class Base extends Controller {

    /**
     * 公共的修改状态方法
     */
    public function status() {
        $data = input('post.');
        if(empty($data)) {
            return show(0,'无数据');
        }
        if(!is_numeric($data['id'])) {
            return show(0,'id不合法');
        }
        //获取控制器名
        $model = request()->controller();
        $rel = model($model)->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($rel) {
            return show(1,'操作成功!');
        }else {
            return show(0,'操作失败!');
        }
    }

    /**
     * 排序逻辑
     */
    public function listorder() {
        $data = input('post.');
        //获取控制器名
        $model = request()->controller();
        $rel =model($model)->save(['listorder'=>$data['listorder']],['id'=>$data['id']]);
        if($rel) {
            return show(1,'操作成功!');
        }else {
            return show(0,'操作失败!');
        }
    }
}