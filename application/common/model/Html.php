<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 10:13
 */
namespace  app\common\model;
use \think\Db;
class Html extends \think\Model{
    protected $table='z_html';
    
    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
