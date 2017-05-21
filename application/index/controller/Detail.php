<?php
namespace app\index\controller;
use think\Controller;

class Detail extends Base
{
    public function index($id) {
        if(!$id || !is_numeric($id)) {
            return $this->error('ID不合法！');
        }
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status!=1) {
            return $this->error('商品不存在!');
        }
        //获取分类的信息
        $category = model('Category')->get($deal->category_id);
        //获取门店的信息
        $location = model('BisLocation')->getNormalLocationInId($deal->location_ids);
        //获取商家的信息
        $bisId = $deal->bis_id;
        $bis = model('Bis')->get($bisId);
        if(!$bis || $bis->status!=1) {
            return $this->error('商家不存在或者未审核!');
        }
        $flag = 0;
        $timedata = '';
        if($deal->start_time>time()) {
            $flag = 1;//未开始
            $dtime = $deal->start_time-time();
            $d = floor($dtime/(3600*24));
            if($d) {
                $timedata.=$d."天";
            }
            $h = floor($dtime%(3600*24)/3600);
            if($h) {
                $timedata.=$h."小时";
            }
            $m = floor($dtime%(3600*24)%3600/60);
            if($m) {
                $timedata.=$m."分钟";
            }
            $s =$dtime%(3600*24)%3600%60;
            if($s) {
                $timedata.=$s."秒";
            }
        }
        return $this->fetch('',[
            'deal'=>$deal,
            'controller'=>'detail',
            'title'=>$deal->name,
            'category'=>$category,
            'location'=>$location,
            'overplus'=>$deal->total_count-$deal->buy_count,
            'flag'=>$flag,
            'timedata'=>empty($timedata)?'':$timedata,
            'point'=>$location[0]->xpoint.','.$location[0]->ypoint,
            'bis'=>$bis

        ]);

    }

}
