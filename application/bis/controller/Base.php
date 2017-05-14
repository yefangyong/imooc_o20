<?php
namespace app\bis\controller;
use think\Controller;
class Base extends Controller {

    public $account;
    public function _initialize() {
        $isLogin = $this->isLogin();
        if(!$isLogin) {
            $this->redirect(url('bis/login/index'));
        }
    }

    /**
     * @return bool
     * 判断是否登录了
     */
    public function isLogin() {
        $account = $this->getLoginUser();
        if($account) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * @return mixed
     * 获取登录用户信息
     */
    public function getLoginUser() {

        if(!$this->account) {
            $this->account = session('bisAccount','','bis');
        }
        return $this->account;
    }
}