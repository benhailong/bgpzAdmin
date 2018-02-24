<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 16:10
 */
namespace app\common\model;
use \think\Db;
class CouponUser extends \think\Model {
    public static $status = array(
        '0'=>'失效',
        '1'=>'有效',
        '2'=>'已使用',
        '3'=>'尚未领取',
        '-1'=>'删除'
    );
    public function selectCouponUser($pagesize,$status,$time_from,$time_to,$fid){
        $model= self::instance();
        $where = '' ;
        if($time_from!=''){
            $where['create_time'] =['>',strtotime($time_from)];
        }
        if($time_to!=''){
            $where['create_time'] =['<',strtotime($time_to)];
        }
        if($time_from!=''&&$time_to!=''){
            $where['create_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
        }
        if($status!=-2&&$status!=''){
            $where['status'] = ['=',$status];
        }else{
            $where['status'] = ['>',-2];
        }
        if($fid > 0){
            $where['fid'] = $fid;
        }
        $list = $model->where($where)->order('id desc')->paginate($pagesize,false, [
            'query' => request()->param(),
        ]);
        foreach ($list as $key=>&$vo){
            $vo['name'] = Db::name('user')->where('id','=',$vo['uid'])->value('name');
            $vo['name'] = $vo['name']?$vo['name']:'未被领取';
            $vo['qrcode'] = $this->getQrcode($vo['id']);
        }
        return $list;
    }

    /**
     * 获取优惠券的二维码
     */
    private function getQrcode($id){
        $map['link_id'] = $id;
        $map['type'] = 2;
        return db('SysQrcode')->where($map)->value('source');
    }

    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}