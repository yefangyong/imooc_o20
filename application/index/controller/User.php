<?php
namespace app\index\controller;
use think\Controller;
use think\Exception;

class User extends Controller
{
    /**
     * @return mixed|void
     * 用户登录
     */
    public function login()
    {
        //获取session,如果登录直接跳转到首页
        $user = session('userAccount','','index');
        if($user && $user->id) {
             $this->redirect(url('index/index/index'));
        }
        if(request()->isPost()) {
            $data = input('post.');

            //tp5 validate验证机制
           if(empty($data['verifycode'])) {
               return show(0,'验证码不得为空!');
           }
            if(!captcha_check($data['verifycode'])) {
                return show(0,'验证码不正确!');
            }
            $rel = model('User')->get(['username'=>$data['username']]);
            if(!$rel) {
                return show(0,'用户名不存在');
            }
            if($rel->password != md5($data['password'].$rel->code)) {
                return show(0,'密码不正确');
            }
            //登录成功,异常处理
            try{
                model('User')->updateById(['last_login_time'=>time()],$rel->id);
            }catch(Exception $e) {
                return show(0,$e->getMessage());
            }
            session('userAccount',$rel,'index');
            return show(1,'登录成功');
        }else {
            return $this->fetch();
        }

    }

    /**
     * @return mixed|void
     * 用户注册
     */
    public function register() {
        if(request()->isPost()) {
            $data = input('post.');
            //进行数据验证，用tp5的validate验证机制
            if(!$data['verifycode']) {
                return show(0,'验证码不得为空!!');
            }
            if(!captcha_check($data['verifycode'])) {
                return show(0,'验证码不正确!!');
            }
            if($data['password'] != $data['repassword']) {
                return show(0,'密码不一致!');
            }
            $data['code'] = mt_rand(1000,10000);
            $data['password'] = md5($data['password'].$data['code']);
           // $data = 123;测试
            try {
                $rel = model('User')->add($data);
            }catch (Exception $e) {
                return show(0,$e->getMessage());
            }

            if($rel) {
                return show(1,'注册成功!',url('user/login'));
            }else {
                return show(0,'注册失败!');
            }
        }else {
            return $this->fetch();
        }
    }

    /**
     * 退出登录
     */
    public function logout() {
        session(null,'index');
        $this->redirect(url('index/user/login'));
    }
}
