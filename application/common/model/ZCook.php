<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/14
 * Time: 13:47
 */
namespace app\common\model;
use think\Db;
class ZCook extends \think\Model {
    protected $table='z_cook';

    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}