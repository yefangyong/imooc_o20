<?php
namespace app\bis\controller;
use think\Controller;
use think\Model;
class Register extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('City');
    }

    public function index() {
        //获取一级城市
        $citys = $this->obj->getNormalCitysByParentId();
        //获取一级栏目
        $categorys = Model('Category')->getCategorys();
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys
        ]);
    }


    public function add() {
        if(!request()->isPost()) {
            return show(0,'请求错误!');
        }

        //获取表单的值
        $data = input('post.');
//        //商户信息校验
//        $validate = validate('Bis');
//        if(!$validate->scene('add')->check($data)) {
//            $this->error($validate->getError());
//        }

        //商户信息入库
        $lnglat = \Map::getLngLat($data['address']);
        $lnglat = json_decode($lnglat);
        if($lnglat->status!=0) {
            return $this->error('无法获取数据');
        }
        $bisData = [
            'name'=>$data['name'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].$data['se_city_id'],
            'logo'=>$data['logo'],
            'licence_logo'=>$data['licence_logo'],
            'description'=>empty($data['description'])?'':$data['description'],
            'bank_info'=>$data['bank_info'],
            'bank_user'=>$data['bank_user'],
            'bank_name'=>$data['bank_name'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
            'email'=>$data['email'],
        ];
        $bisId = model('Bis')->add($bisData);
        if(!$bisId) {
           $this->error('申请失败，请重新申请!');
        }


        //总店信息检验


        //总店信息入库
        $data['cat'] = '';
        if(!empty($data['se_category_id'])) {
            $data['cat'] = implode('|',$data['se_category_id']);
        }
        $locationData = [
            'bis_id'=>$bisId,
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'tel'=>$data['tel'],
            'contact'=>$data['contact'],
            'category_id'=>$data['category_id'],
            'category_path'=>$data['category_id'].','.$data['cat'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].$data['se_city_id'],
            'address'=>$data['address'],
            'open_time'=>$data['open_time'],
            'content'=>empty($data['content'])?'':$data['content'],
            'is_main'=>1,
            'xpoint'=>empty($lnglat->result->location->lng)?'':$lnglat->result->location->lng,
            'ypoint'=>empty($lnglat->result->location->lat)?'':$lnglat->result->location->lat,
        ];

        $bisLoaction = model('BisLocation')->add($locationData);
        if(!$bisLoaction) {
            $this->error('申请失败，请重新输入!');
        }

        //账户相关的信息检验


        //账户相关的信息入库
        $data['code'] = mt_rand(1000,10000);
        $accountData = [
            'bis_id'=>$bisId,
            'username'=>$data['username'],
            'password'=>md5($data['password']).$data['code'],
            'is_main'=>1,
            'code'=>$data['code'],
        ];

        $accountId = model('BisAccount')->add($accountData);
        if(!$accountId) {
            return $this->error('申请失败!');
        }

        //发送邮件给注册商户
        $title ='o2o入驻申请通知!';
        $url = request()->domain().url('bis/register/waiting');
        $content = "您入驻的申请需要等待平台方审核,请点击链接<a href='".$url."' target='_blank' >查看审核状态</a>";
        \phpmailer\Email::send($data['email'],$title,$content);
        return $this->success('请求成功!');

    }

    public function waiting() {
        return 'success';
    }

}