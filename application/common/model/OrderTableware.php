<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 * 菜品
 */
namespace app\common\model;
use think\Db;

class OrderTableware extends \think\Model{
    protected $table='order_tableware';
    public static $company_status =array(
        '0'=>'尚未核实',
        '1'=>'已经核实',
    );
    public function selectTableware($orderId){
        $model = self::instance();
        $list = $model->where('order_id','=',$orderId)->select();
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