<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/11
 * Time: 14:56
 */
namespace  app\common\model;
use think\Db;
class CookSign extends \think\Model{
    public static $is_own =array(
        '0'=>'注册的厨师',
        '1'=>'酒店自有厨师'
    );
    public static $type_own =array(
        '1'=>'上午',
        '2'=>'下午'
    );
    protected $table='z_cook_sign';
    private static $instance;
    public function selectCookSign($pagesize,$time_from='',$time_to='',$field='*',$name='',$type=1,$status=-1,$status_type=-1,$company_id=''){
        $model = self::instance();
        $map['zc.type'] = ['=',$type];

        if($status_type==-1){
            $map['cs.type'] = ['>',$status_type];
        }else{
            $map['cs.type'] = ['=',$status_type];
        }
        if($status==-1){
            $map['cs.status'] = ['>',$status];
        }else{
            $map['cs.status'] = ['=',$status];
        }
        if($company_id!=''){
            $map['zc.company_id'] = ['=',$company_id];
        }
        if($name!=''){
            $map['zc.cook_name'] = ['like',"%$name%"];
        }
        if($time_from!=''){
            $map['cs.sign_time'] =['>',strtotime($time_from)];
        }
        if($time_to!=''){
            $map['cs.sign_time'] =['<',strtotime($time_to)];
        }
        if($time_from!=''&&$time_to!=''){
            $map['cs.sign_time'] =['between time',[strtotime($time_from),strtotime($time_to)]];
        }
        $map['cs.sign_time'] = ['>',time()];
        $list = $model->alias('cs')
                      ->join('z_cook zc','zc.id = cs.cook_id')
                      ->where($map)
                      ->order('id desc')
                      ->field($field)
                      ->paginate($pagesize);
        return $list;
    }





    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}