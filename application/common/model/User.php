<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 */
namespace app\common\model;
use think\Db;
class User extends \think\Model{
    public static $status = array(
        '0' => '禁用',
        '1' => '正常',
        '-1' => '删除',
    );
    public static $sex = array(
        '0' => '女',
        '1' => '男',
    );
    public static $rol = array(
        '1' => '消费用户',
        '2' => '厨师',
        '3' => '酒店管理员',
    );
   public function relData($pagesize,$search=''){
       $model = self::instance();
        $where = '';
        if($search[0]==-2){
            $where['u.status'] = ['>',-2];
        }elseif ($search[0]>=-1){
            $where['u.status'] = ['=',$search[0]];
        }
        if($search[1]==-2){
            $where['u.sex'] = ['>',-2];
        }elseif ($search[1]>-2){
            $where['u.sex'] = ['=',$search[1]];
        }
        if($search[2]==-2){
            $where['u.role'] =['>',-2];
        }elseif ($search[1]>-2){
            $where['u.role'] = ['=',$search[2]];
        }
        if($search[3]!=''){
            $where['u.nickname|u.phone']=['like',"%$search[3]%"];
        }
       $list = $model->alias('u')
//                   ->where('u.name|u.phone','like',"%$search[3]%")
                    ->where($where)
                   ->order('id desc')
                   ->paginate($pagesize);
       foreach ($list as $key=>$vo){
            $money = Db::name('user_fund')->where('uid','=',$vo['id'])->field('funds_cur')->find();
            $userInfo = Db::name('user_id_card')->where('uid','=',$vo['id'])->field('code,realname')->find();
            $userAddress = Db::name('user_addr')->where('uid','=',$vo['id'])->field('addr,name')->find();
            $list[$key]['funds_cur']=$money['funds_cur']?$money['funds_cur']:0;
            $list[$key]['code']=$userInfo['code']?$userInfo['code']:'';
            $list[$key]['realname']=$userInfo['realname']?$userInfo['realname']:'';
            $list[$key]['addr']=$userAddress['addr']?$userAddress['addr']:'';
            $list[$key]['truename']=$userAddress['name']?$userAddress['name']:'';
       }
       return $list;
   }

    /**
     * @param $uid
     * @param string $field
     * @return array|false|\PDOStatement|string|\think\Model
     * 获取一条用户信息
     */
    public function getUserOne($uid,$field='*'){
        $model = self::instance();
        $result = $model->where('id','=',$uid)->field($field)->find();
        return $result;
    }

    public function getUserInfoOne($mobile){
        $model = self::instance();
        $result = $model->where('phone','=',$mobile)->find();
        return $result;

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