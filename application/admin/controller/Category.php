<?php
namespace app\admin\controller;
use think\Controller;
class Category extends Controller
{
    private $obj;

    public function _initialize()
    {
       $this->obj = model('Category');
    }

    public function index()
    {
        $parent_id = input('get.parent_id',0,'intval');
        $category = $this->obj->getCatorys($parent_id);
        return $this->fetch('',['categorys'=>$category]);
    }

   public function add() {
       $category = $this->obj->getNormalFirstCategory();
       return $this->fetch('',['categorys'=>$category]);
   }

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

    public function edit($id = 0) {
        $categorys = $this->obj->getNormalFirstCategory();
        $category = $this->obj->get($id);
        return $this->fetch('',['categorys'=>$categorys,'category'=>$category]);
    }

    public function update($data) {
        $rel = $this->obj->save($data,['id'=>$data['id']]);
        if($rel) {
            return $this->success('修改成功!');
        }else {
            return $this->error('修改失败!');
        }
    }


}
