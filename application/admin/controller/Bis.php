<?php
namespace app\admin\controller;
use think\Controller;
class Bis extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('Bis');
    }

    public function index()
    {
        $status = input('post.status',0,'intval');
        $bis = $this->obj->getBisByStatus($status);
        return $this->fetch('',['bis'=>$bis]);
    }


}
