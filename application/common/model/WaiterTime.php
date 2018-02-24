<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/14
 * Time: 13:47
 */
namespace app\common\model;
use think\Db;
class WaiterTime extends \think\Model {
    protected $table='z_waiter_time';
    public function selectOne($id,$paginate){
        $model = self::instance();
        $list = $model->where('waiter_id','=',$id)->order('date_time desc')->paginate($paginate);
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