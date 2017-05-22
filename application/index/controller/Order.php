<?php
namespace app\index\controller;
use think\Controller;

class Order extends Base
{
    public function Confirm() {
        $user = $this->getLoginUser();
        if(!$user) {
             $this->error('请登录','user/login');
        }
        $id = input('get.id',0,'intval');
        if(!$id) {
            $this->error('参数不合法!');
        }
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status!=1) {
            return $this->error('商品不存在或者已下架');
        }
        $count = input('get.count',1,'intval');
        //$deal = $deal->toArray();
        $this->assign('controller','pay');
        return $this->fetch('',[
            'deal'=>$deal,
            'title'=>'支付页',
            'user'=>$user,
            'count'=>$count

        ]);
    }

}
