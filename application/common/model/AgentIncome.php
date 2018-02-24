<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 14:01
 */
namespace app\common\model;
use \think\Db;
class AgentIncome extends \think\model{
    protected $table='z_agent_income';
    public static $types = array(
        '0'=>'未知状态',
        '1'=>'注册',
        '2'=>'订单',
        '3'=>'提现行为申请',
        '4'=>'提现行为确认',
        '5'=>'提现行为驳回'
    );
    public static $level = array(
        '0'=>'与下级无关',
        '1'=>'一级下线',
        '2'=>'二级下线'
    );
    public function selectAgentIncome($pagesize,$field,$time_from,$time_to,$name){
        $map = '';
        if($time_from!=''){
            $map['create_time'] =['>',strtotime($time_from)];
        }
        if($time_to!=''){
            $map['create_time'] =['<',strtotime($time_to)];
        }
        if($time_from!=''&&$time_to!=''){
            $map['create_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
        }
        if($name!=''){
            $where['name'] = ['like',"%$name%"];
            $name = Db::name('user')->where($where)->field('id')->select();
            $id = array_column($name,'id');
            $map['uid']=['in',$id];
        }
            $model = self::instance();
            $list = $model->where($map)->field($field)->paginate($pagesize);
        foreach ($list as $key=>$vo){
            $userName = Db::name('user')->where('id','=',$vo['uid'])->field('name')->find();
//            $userFname = Db::name('user')->where('id','=',$vo['fuid'])->field('name')->find();
            $list[$key]['name'] = $userName['name'];
//            $list[$key]['fname'] = $userFname['name'];
        }
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