<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/10/10
 * Time: 9:46
 */
namespace app\common\model;
use think\Db;
class packageCook extends \think\Model {
    protected $table='z_package_cook';

    public function getOne($where){
            $model = self::instance();
            $data = $model->where($where)->find();
            return $data;
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