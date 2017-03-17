<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome() {
        \phpmailer\Email::send('1281074511@qq.com','测试','成功啦');
        return '发送成功!';
        return '欢迎来到o2o商城平台!';
    }


}
