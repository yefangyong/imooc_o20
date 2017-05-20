<?php
namespace app\admin\controller;
use think\Controller;
use think\Model;

class Featured extends Base
{
    private $obj;

    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->obj = model('Featured');
    }

    /**
     * @return
     * 推荐位列表页
     */
    public function index() {
        $types = config('feature.type');
        $type = input('get.type',0,'intval');
        $rel = $this->obj->getFeaturedByType($type);
        return $this->fetch('',[
            'types'=>$types,
            'rel'=>$rel,
            'type'=>$type
        ]);
    }

    /**
     * 添加推荐位
     */
    public function add() {
        if(request()->isPost()) {
            $data = input('post.');
            //数据检验，自行完成，参考前面的代码validate
            if(empty($data) || !$data) {
                return show(0,'没有数据');
            }
            //数据入库
            $rel = $this->obj->add($data);
            if($rel) {
                return show(1,'添加成功');
            }else {
                return show(0,'添加失败!');
            }
        }else {
            $types = config('feature.type');
            return $this->fetch('',[
                'types'=>$types,
            ]);
        }
    }

}