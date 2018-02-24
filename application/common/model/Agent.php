<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 10:13
 */
namespace  app\common\model;
use \think\Db;
class Agent extends \think\Model{
    protected $table='z_agent';
    public static $status=array(
        '0'=>'申请中',
        '1'=>'申请成功',
        '2'=>'申请驳回'
    );

    /**
     * 查询推广人员列表
     */
    public function selectAgent($pagesize,$field='*',$search){
            $model = self::instance();
            $map = '';
            if($search[0]!=''){
                $where['name'] = ['like',"%$search[0]%"];
                $name = Db::name('user')->where($where)->field('id')->select();
                $id = array_column($name,'id');
                $map['uid']=['in',$id];
            }
            $list = $model->where($map)->field($field)->order('id desc')->paginate($pagesize);
            foreach ($list as $key=>$vo){
                $userName = Db::name('user')->where('id','=',$vo['uid'])->field('name,phone')->find();
                $userFname = Db::name('user')->where('id','=',$vo['fuid'])->field('name')->find();
                $list[$key]['name'] = $userName['name'];
                $list[$key]['fname'] = $userFname['name'];
                $list[$key]['phone'] = $userName['phone'];
                $list[$key]['underLineNumber'] = $this->countMyUnderLine($vo['uid']);
             }
            return $list;
    }

    public function selectDetail($uid,$pagesize,$time_from,$time_to){
        $model = self::instance();
        $where['fuid'] = ['=',$uid];
        if($time_from!=''){
            $where['create_time'] =['>',strtotime($time_from)];
        }
        if($time_to!=''){
            $where['create_time'] =['<',strtotime($time_to)];
        }
        if($time_from!=''&&$time_to!=''){
            $where['create_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
        }
        $list = $model->where($where)->paginate($pagesize,false, [
            'query' => request()->param(),
        ]);
        foreach ($list as $key=>$vo){
            $userName = Db::name('user')->where('id','=',$vo['uid'])->field('name,phone')->find();
            $userFname = Db::name('user')->where('id','=',$vo['fuid'])->field('name')->find();
            $list[$key]['name'] = $userName['name'];
            $list[$key]['phone'] = $userName['phone'];
            $list[$key]['fname'] = $userFname['name'];
        }
        return $list;
    }
    /**
     * 获取推广下级的人数
     */
    public function countMyUnderLine($fUid){
        $map['fuid'] = $fUid;
        $map['status'] = 1;
        $count = Db::table('z_agent')->where($map)->count();
        return $count;
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
