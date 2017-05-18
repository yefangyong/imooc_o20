<?php
namespace app\bis\controller;
use think\Controller;
class Login extends Controller
{
    public function index() {
        if(request()->isPost()) {
            $data = input('post.');
            //通过用户名来判断
            //严格的判定
            if(!$data['username'] || empty($data['username'])) {
                return show(0,'用户名不得为空!');
            }
            if(!$data['password'] || empty($data)) {
                return show(0,'密码不得为空!');
            }
            $ret = Model('bisAccount')->get(['username'=>$data['username']]);
            if($ret->status != 1 || !$ret) {
                return show(0,'该用户不存在，或者该用户未审核!');
            }
            if($ret->password != md5($data['password'])) {
                return show(0,'密码不正确!');
            }
            model('bisAccount')->updateById(['last_login_time'=>time()],$ret->id);
            //保存用户信息，bis是作用域
            session('bisAccount',$ret,'bis');
            return show(1,'登录成功!');
        }else {
            $account = session('bisAccount','','bis');
            if($account) {
                $this->redirect(url('bis/index/index'));
            }
            return $this->fetch();
        }

    }

    public function logout() {
        //清除session
        session(null,'bis');
        $this->redirect(url('bis/login/index'));
    }
}