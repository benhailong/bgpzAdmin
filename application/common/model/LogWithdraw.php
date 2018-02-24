<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/6/27
 * Time: 11:30
 */
namespace  app\common\model;
use \think\Request;
use \think\Db;
class LogWithdraw extends \think\model{
    public static $type_array = array(
        '1'=>'用户',
        '2'=>'厨师',
        '3'=>'酒店管理员'
    );

    public static $status_array = array(
        '1'=>'已通过驳回',
        '2'=>'驳回原因'
    );
    protected $table='log_withdraw';

    public function selectLogWithdrawList($search,$field,$pagesize){
        $model =self::instance();
        if($search[0]!=''){
            $where['u.name|u.phone|u.nickname'] = ['like',"%$search[0]%"];
        }

        if(strtotime($search[1]) > 0 && strtotime($search[2]) > 0){
            $where['lw.create_time'] = array('between',array(strtotime($search[1]),strtotime($search[2])));
        }else if(strtotime($search[1]) > 0){
            $where['lw.create_time'] =['gt',strtotime($search[1])];
        }else if($search[2] > 0){
            $where['lw.create_time'] =['lt',strtotime($search[2])];
           // $return = array('between',array(strtotime($search[1]),strtotime($search[2])));
            // $return = array('gt',strtotime($search[1]));
            // $return = array('lt',strtotime($search[2]));
            //$return = array('gt',0);

        }else{
            $where['lw.create_time'] = ['gt',0];
        }
        $where['lw.status'] = ['=',0];
        $list = $model->alias('lw')
                      ->join('user u','lw.uid=u.id')
                      ->join('user_fund uf','lw.uid=uf.uid')
                      ->field($field)
                      ->where($where)
                      ->order('lw.id desc')
                      ->paginate($pagesize);
//        foreach ($list as $key=>&$vo){
//            if($vo['role']==1){
//                if($vo['sence'] == 1){
//                    $vo['sence_name'] = '用户推广提现';
//                }
//            }elseif ($vo['role'] ==2){
//                    if($vo['sence']==1){
//                        $vo['sence_name'] = '厨师推广提现';
//                    }else{
//                        $vo['sence_name'] = '厨师收益提现';
//                    }
//            }
//        }
        foreach ($list as $key=>&$vo){
            if($vo['sence']==1){
                        $vo['sence_name'] = '推广提现';
                    }else{
                        $vo['sence_name'] = '收益提现';
                    }
        }
        return $list;
    }

    /**
     * @param $search
     * @param $field
     * @param $pagesize
     * @return \think\Paginator
     * 提现完成
     */
    public function selectLogWithdrawInfo($search,$field,$pagesize){
        $model =self::instance();
        if($search[0]!=''){
            $where['u.name|u.phone|u.nickname'] = ['like',"%$search[0]%"];
        }

        if(strtotime($search[1]) > 0 && strtotime($search[2]) > 0){
            $where['lw.create_time'] = array('between',array(strtotime($search[1]),strtotime($search[2])));
        }else if(strtotime($search[1]) > 0){
            $where['lw.create_time'] =['gt',strtotime($search[1])];
        }else if($search[2] > 0){
            $where['lw.create_time'] =['lt',strtotime($search[2])];
            // $return = array('between',array(strtotime($search[1]),strtotime($search[2])));
            // $return = array('gt',strtotime($search[1]));
            // $return = array('lt',strtotime($search[2]));
            //$return = array('gt',0);

        }else{
            $where['lw.create_time'] = ['gt',0];
        }

        if($search[3]==0){
            $where['lw.status'] = ['>',0];
        }elseif ($search[3]>0){
            $where['lw.status'] = ['=',$search[3]];
        }else{
            $where['lw.status'] = ['neq',0];
        }

        $list = $model->alias('lw')
            ->join('user u','lw.uid=u.id')
            ->join('user_fund uf','lw.uid=uf.uid')
            ->field($field)
            ->where($where)
            ->order('lw.id desc')
            ->paginate($pagesize);
        foreach ($list as $key=>&$vo){
            if($vo['role']==1){
                if($vo['sence'] == 1){
                    $vo['sence_name'] = '用户推广提现';
                }
            }elseif ($vo['role'] ==2){
                if($vo['sence']==1){
                    $vo['sence_name'] = '厨师推广提现';
                }else{
                    $vo['sence_name'] = '厨师收益提现';
                }
            }
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