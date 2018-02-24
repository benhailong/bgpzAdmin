<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 16:07
 */
namespace app\common\model;
use dump_r\Node\_String\_JSON\_Array;
use \think\Db;
class Coupon extends \think\Model{
    protected $table  = 'coupon';
    public static $types = array(
        '1'=>'现金抵用券',
        '2'=>'折扣券'
    );
    public static $flatList=array(
        '1'=>'私宴',
        '2'=>'商城',
        '3'=>'报名',
        '4'=>'酒店商城'
    );

    public static $behavior=array(
        '0'=>'不进行自动领取',
        '1'=>'注册自动领取',
        '2'=>'推广上线奖励',
        '3'=>'推广下线奖励'
    );
    public function selectCoupon($pagesize,$field=''){
        $model = self::instance();
//        $list = $model->field($field)->order('id desc')->paginate($pagesize);
        $list = $model->field($field)->order('create_time desc')->paginate($pagesize);

//        foreach ($list as $key=>$vo){
//            $vo['flatList'] =json_decode($vo['flatList']);
//
//        }

        return $list;
    }

    public function selectOneCoupon($id){
        $model = self::instance();
        $list = $model->where('id','=',$id)->find();
//        echo $model->getLastSql();die;
        return $list;
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