<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 修改状态
 * @param $status
 * @return string
 */
 function status($status) {
    $str = '';
    if($status == 1) {
        $str = '<span class="label label-success radius">正常</span>';
    }elseif($status == 0) {
        $str = '<span class="label label-danger radius">待审</span>';
    }elseif($status == -1){
        $str = '<span class="label label-danger radius">删除</span>';
    }

    return $str;
}

/**
 * 公共方法
 * @param $status
 * @param $message
 * @param array $data
 */
function show($status,$message,$data=array()) {
    $result = array(
        'status'=>$status,
        'message'=>$message,
        'data'=>$data
    );
    exit(json_encode($result));
}

/**
 * @param $url
 * @param $type 1|post方式 0|get方式
 * @param array $data
 */
function doCurl($url,$type = 0,$data=[]) {
    $cu = curl_init(); //初始化

    //设置选项
    curl_setopt($cu,CURLOPT_URL,$url); //设置url
    curl_setopt($cu,CURLOPT_RETURNTRANSFER,1); //信息以文件流的方式保存，而不是直接输出
    curl_setopt($cu,CURLOPT_HEADER,0); //不包括header头部信息

    if($type == 1) {
        //post
        curl_setopt($cu,CURLOPT_POST,1);
        curl_setopt($cu,CURLOPT_POSTFIELDS,$data);
    }

    //执行并获取内容
    $output = curl_exec($cu);
    //释放curl句柄
    curl_close($cu);

    return $output;
}

/**
 * @param $status
 * 获取商户入驻审核状态
 */
function bisRegister($status) {
    if($status == 1) {
        $str = '入驻申请成功!';
    }else if($status == 0) {
        $str = '待审核 审核后平台方会发送邮件通知，请关注邮件';
    }else if($status == 2) {
        $str = '非常抱歉,您提交的资料不符合条件，请重新编译';
    }else {
        $str = '该申请已被删除';
    }
    return $str;
}
