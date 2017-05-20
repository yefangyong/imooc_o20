<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    public $city;
    public $account;

    public function _initialize() {
        //城市数据
        $citys = model('City')->getNormalCitys();
        //传递给模板
        $this->assign('citys',$citys);
        $this->getCity($citys);
        $this->assign('city',$this->city);
        //用户数据
        $user = $this->getLoginUser();
        $this->assign('user',$user);

    }

    /**
     * @param $citys
     * 获取城市
     */
    public function getCity($citys) {
        foreach($citys as $city) {
            $city = $city->toArray();
            if($city['is_default'] == 1) {
                $defaultname = $city['uname'];
                break;
            }
        }
        $defaultname = $defaultname?$defaultname:'nanchang';
        if(session('cityuname','','index') && !input('get.city')) {
            $cityuname = session('cityuname','','index');
        }else {
            $cityuname = input('get.city',$defaultname,'trim');
            session('cityuname',$cityuname,'index');
        }

        $this->city = model('City')->where(['uname'=>$cityuname])->find();
    }


    /**
     * @return mixed
     * 获取登录用户信息
     */
    public function getLoginUser() {

        if(!$this->account) {
            $this->account = session('userAccount','','index');
        }
        return $this->account;
    }

}
