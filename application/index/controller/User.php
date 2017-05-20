<?php
namespace app\index\controller;
use think\Controller;
use think\Exception;

class User extends Controller
{
    public function login()
    {
        return $this->fetch();
    }

    public function register() {
        if(request()->isPost()) {
            $data = input('post.');
            //进行数据验证，用tp5的validate验证机制
            if(!$data['verifycode']) {
                return $this->error('验证码不得为空!!');
            }
            if(!captcha_check($data['verifycode'])) {
                return $this->error('验证码不正确!!');
            }
            if($data['password'] != $data['repassword']) {
                return $this->error('密码不一致!');
            }
            $data['code'] = mt_rand(1000,10000);
            $data['password'] = md5($data['password'].$data['code']);
           // $data = 123;测试
            try {
                $rel = model('User')->add($data);
            }catch (Exception $e) {
                return $this->error($e->getMessage());
            }

            if($rel) {
                return $this->success('注册成功!',url('user/login'));
            }else {
                return $this->error('注册失败!');
            }
        }else {
            return $this->fetch();
        }
    }
}
