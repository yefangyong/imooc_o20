<?php
namespace app\bis\controller;

use think\Controller;

class Location extends Base
{

    /**
     * @return mixed
     * 门店列表页
     */
    public function index()
    {
        $bisId = $this->getLoginUser()->bis_id;
        $data = model('BisLocation')->getBisByStatus($bisId);
        return $this->fetch('',['bisData'=>$data]);
    }

    /**
     * @return mixed|void
     * 门店申请入库
     */
    public function add() {
        if(request()->isPost()) {
            //门店信息检验

            $data = input('post.');

            //门店信息入库
            $data['cat'] = '';
            if(!empty($data['se_category_id'])) {
                $data['cat'] = implode('|',$data['se_category_id']);
            }

            //门店信息入库
            $lnglat = \Map::getLngLat($data['address']);
            $lnglat = json_decode($lnglat);
            if($lnglat->status!=0) {
                return $this->error('无法获取数据');
            }

            $bisId = $this->getLoginUser()->bis_id;
            if(!$bisId) {
                return show(0,'总店信息不存在');
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
                'api_address'=>$data['address'],
                'open_time'=>$data['open_time'],
                'content'=>empty($data['content'])?'':$data['content'],
                'is_main'=>0,
                'xpoint'=>empty($lnglat->result->location->lng)?'':$lnglat->result->location->lng,
                'ypoint'=>empty($lnglat->result->location->lat)?'':$lnglat->result->location->lat,
            ];

            $bisLoaction = model('BisLocation')->add($locationData);
            $bisData = model('Bis')->get($bisId);
            if($bisLoaction) {
                //邮件通知处理
                $title = "o2o门店申请通知!";
                $url = request()->domain().url('bis/location/waiting',['id'=>$bisId,'is_main'=>0]);
                $content = "您门店入驻的申请需要等待平台方审核，请点击链接<a href='".$url."' target='_blank'>查看审核状态</a>";
                \phpmailer\Email::send($bisData['email'],$title,$content);
                return show(1,'门店申请成功,待审核');
            }
        }else {
            //获取一级城市
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级栏目
            $categorys = Model('Category')->getCategorys();
            return $this->fetch('',[
                'citys'=>$citys,
                'categorys'=>$categorys
            ]);
        }

    }


    /**
     * @param $id
     * @param $main
     * @return mixed|void
     * 判断是否审核的页面
     */
    public function waiting($id,$main) {
        if(!$id) {
            return $this->error('参数错误');
        }
        $detail = model('BisLocation')->get(['bis_id'=>$id,'is_main'=>$main]);
        return $this->fetch('',['detail'=>$detail]);
    }


    /**
     * @param $id
     * @param $status
     * 下架门店申请
     */
    public function status() {
        $data = input('post.');
        $rel = model('BisLocation')->save(['status'=>$data['status']],['id'=>$data['id']]);
        $bisId = $this->getLoginUser()->bis_id;
        $bisData = model('Bis')->get($bisId);
        if($rel) {
            //邮件通知
            //status 1 审核成功  2不通过 0未审核 -1 下架
            //发送邮件给注册商户
            $title ='o2o入驻门店申请审核通知!';
            $content = "很抱歉,您的门店申请已经下架!";
            \phpmailer\Email::send($bisData['email'],$title,$content);
            return show(1,'操作成功!');
        }else {
            return show(0,'操作失败!');
        }
    }

    /**
     * @return mixed
     * 显示门店申请的详细信息
     */
    public function detail() {
        //获取一级城市
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级栏目
        $categorys = Model('Category')->getCategorys();
        $id = input('get.id');
        $data = model('BisLocation')->get($id);
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys,
            'bisData'=>$data
        ]);
    }


}
