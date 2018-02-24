<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/14
 * Time: 9:50
 */
namespace app\common\model;
use think\Db;
class Waiter extends \think\Model {
    protected $table='z_waiter';//服务员查询
    public function selectWaiter($pagesize,$time_from='',$time_to='',$type = '',$name = ''){
        $map = '';
        if($time_from!=''){
//            $map['create_time'] =['>',strtotime($time_from)];
            $whereMap['create_time'] =['>',strtotime($time_from)];
        }
        if($time_to!=''){
//            $map['create_time'] =['<',strtotime($time_to)];
            $whereMap['create_time'] =['<',strtotime($time_to)];
        }
        if($time_from!=''&&$time_to!=''){
//            $map['create_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
            $whereMap['create_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
        }
        if($type != ''){
            $map['type'] = $type;
        }
        if($name != ''){
            $map['name'] = array('like',"%".$name."%");
        }
        $model = self::instance();
        $list = $model->where($map)->order('create_time desc')->paginate($pagesize);
        foreach ($list as $key=>$vo){
            $whereMap['waiter_id'] = ['=',$vo['id']];
            $count = Db::name('z_waiter_time')->where($whereMap)->count('id');
            $list[$key]['num'] = $count;
        }
        return $list;
    }


    public function readOne($id){
        $model = self::instance();
        $data = $model->where('id','=',$id)->find();
        return $data;
    }


    public function selectWaiterName(){
        $model = self::instance();
        $list = $model->select();
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