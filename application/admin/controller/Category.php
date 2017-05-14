<?php
namespace app\admin\controller;
use think\Controller;
class Category extends Controller
{
    private $obj;

    public function _initialize()
    {
       $this->obj = Model('Category');
    }

    public function index()
    {
        $parent_id = input('get.parent_id',0,'intval');
        $category = $this->obj->getCategorys($parent_id);
        return $this->fetch('',['categorys'=>$category]);
    }

   public function add() {
       $category = $this->obj->getNormalFirstCategory();
       return $this->fetch('',['categorys'=>$category]);
   }

    /**
     * 更新和添加功能
     */
    public function save() {
        if(!request()->isPost()) {
            return $this->error('请求失败!');
        }
        $data = input('post.');
        $validate = validate('Category');
        if(!$validate->scene('add')->check($data)) {
            $this->error($validate->getError());
        }
        if(!empty($data['id'])) {
            return $this->update($data);
        }
        $res = $this->obj->add($data);
        if($res) {
            $this->success('新增成功!');
        }else {
            $this->error('新增失败!');
        }
    }

    /**
     * 编辑页面开发
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0) {
        $categorys = $this->obj->getNormalFirstCategory();
        $category = $this->obj->get($id);
        return $this->fetch('',['categorys'=>$categorys,'category'=>$category]);
    }

    /**
     * @param $data 数组
     * 修改功能
     */
    public function update($data) {
        $rel = $this->obj->save($data,['id'=>$data['id']]);
        if($rel) {
            return $this->success('修改成功!');
        }else {
            return $this->error('修改失败!');
        }
    }

    /**
     * 排序逻辑
     */
    public function listorder() {
        $data = input('post.');
        $rel = $this->obj->save(['listorder'=>$data['listorder']],['id'=>$data['id']]);
        if($rel) {
            return show(1,'操作成功!');
        }else {
            return show(0,'操作失败!');
        }
    }

    /**
     * 更改状态
     */
    public function status() {
        $data = input('post.');
        $rel = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($rel) {
            return show(1,'操作成功!');
        }else {
            return show(0,'操作失败!');
        }
    }


}
