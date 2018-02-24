<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 */
namespace app\common\model;
use think\Db;
class OrderEvaluate extends \think\Model{

    public function relData($pagesize,$order_id)
    {
        $model = self::instance();
        $result = $model->where('order_id','=',$order_id)->paginate($pagesize);
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