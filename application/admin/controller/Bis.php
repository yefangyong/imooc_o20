<?php
namespace app\admin\controller;
use think\Controller;
use think\Model;

class Bis extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('Bis');
    }

    public function apply()
    {
        $status = input('post.status',0,'intval');
        $bis = $this->obj->getBisByStatus($status);
        return $this->fetch('',['bis'=>$bis]);
    }

    /**
     * @return mixed
     * 显示商家入驻申请的详细信息
     */
    public function detail() {
        $id = input('get.id');
        //获取一级城市
        $citys = Model('City')->getNormalCitysByParentId();
        //获取一级栏目
        $categorys = Model('Category')->getCategorys();
        //获取商家的信息
        $bisData = Model('Bis')->get($id);
        $bisLoaction = Model('BisLocation')->get(['is_main'=>1,'bis_id'=>$id]);
        $bisAccount = Model('BisAccount')->get(['is_main'=>1,'bis_id'=>$id]);
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys,
            'bisData'=>$bisData,
            'bisAccount'=>$bisAccount,
            'bisLoaction'=>$bisLoaction
        ]);

    }

    /**
     * 更改状态，审核入驻申请
     */
    public function status() {
        $data = input('post.');
        $rel = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
        $bisLocation = Model('BisLocation')->save(['status'=>$data['status'],'is_main'=>1,'bis_id'=>$data['id']]);
        $bisAccount = Model('BisAccount')->save(['status'=>$data['status'],'is_main'=>1,'bis_id'=>$data['id']]);
        $bisData = $this->obj->get($data['id']);
        if($rel && $bisAccount && $bisLocation && $bisData) {
            //status 1 审核成功  2不通过 0未审核 -1 删除
            //发送邮件给注册商户
            $title ='o2o入驻申请审核通知!';
            if($data['status'] == 2 || $data['status'] == -1) {
                $content = "您入驻的申请审核失败，请重新申请";
            }else {
                $content = "恭喜您，入驻的申请审核成功!";
            }
            \phpmailer\Email::send($bisData['email'],$title,$content);
            return show(1,'操作成功!');
        }else {
            return show(0,'操作失败!');
        }
    }

    /**
     * @return mixed
     * 商户列表
     */
    public function index() {
        $bis = $this->obj->getBisByStatus(1);
        return $this->fetch('',['bis'=>$bis]);
    }

    /**
     * @return mixed
     * 被删除的商户列表
     */
    public function dellist() {
        $bis = $this->obj->getBisByStatus(-1);
        return $this->fetch('',['bis'=>$bis]);
    }


}
