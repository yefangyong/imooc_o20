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
        return $this->fetch();
    }

   public function add() {
       $category = $this->obj->getNormalFirstCategory();
       return $this->fetch('',['categorys'=>$category]);
   }

    public function save() {
        $data = input('post.');
        $validate = validate('Category');
        if(!$validate->scene('add')->check($data)) {
            $this->error($validate->getError());
        }
        $res = $this->obj->add($data);
        if($res) {
            $this->success('新增成功!');
        }else {
            $this->error('新增失败!');
        }


    }


}
