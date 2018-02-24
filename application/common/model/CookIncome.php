<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/12
 * Time: 10:56
 */
namespace  app\common\model;
use think\Db;
class CookIncome extends \think\Model {
    protected $table = 'z_cook_income';

    public static $types = array(
        '1'=>'套餐',
        '2'=>'私宴',
        '3'=>'单点',
        '4'=>'聚会订单'
    );

    public static $status = array(
        '1'=>'做饭收入',
        '2'=>'提现完成',
        '3'=>'加时费收入'
);
    public function relData($pagesize,$time_from='',$time_to='',$company='',$field='*',$type=1,$cook_name,$order){
        $map= '';
        $where['zc.type']=['=',$type];
        if($cook_name!=''){
            $where['zc.cook_name|zc.work_phone'] =['like',"%$cook_name%"];
        }
        if($order!=''){
            $where['or.no'] = ['=',$order];
        }
        if($company!=''){
            $where['zci.company_id'] = ['=',$company];
        }
        if($time_from!=''){
            $map['zci.create_time'] =['>',strtotime($time_from)];
        }
        if($time_to!=''){
            $map['zci.create_time'] =['<',strtotime($time_to)];
        }
        if($time_from!=''&&$time_to!=''){
            $map['zci.create_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
        }
        $model = self::instance();
        $list = $model->alias('zci')
            ->join('order or','or.id=zci.oid')
            ->join('z_cook zc','zci.cook_id=zc.id')
            ->where($where)
            ->where($map)
            ->field($field)
            ->paginate($pagesize );
        return $list;
    }

    public function selectCookIncome($search,$pagesize,$company_id){
        $model =self::instance();
        $where = '';
        if($search[0]==-2){
            $where['zci.sence'] = ['>',-2];
        }elseif ($search[0]>-2){
            $where['zci.sence'] = ['=',$search[0]];
        }
        if($search[1]!=''){
            $where['zc.cook_name'] =['like',"%$search[1]%"];
        }
        if($company_id!=''){
           $cook_id =  Db::name('z_cook')->where('company_id','=',$company_id)->column('id');
            if(count($cook_id)>0){
                $where['zci.cook_id'] = ['in',$cook_id];
            }
        }
        $list = $model->alias('zci')
                      ->join('z_cook zc','zci.cook_id=zc.id')
                      ->join('order or','zci.oid=or.id')
                      ->field('or.no,zci.*,zc.cook_name,zc.cook_avatar,zc.work_phone,zci.create_time')
                      ->where($where)
                      ->order('zci.id desc')
                      ->paginate($pagesize);
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