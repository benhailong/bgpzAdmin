<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/24
 * Time: 13:40
 */
namespace app\common\model;
use \think\Db;
class ShopCategory extends \think\model{
    protected $table ='shop_category';

    public function selectShopGoods($filed){
        $model =self::instance();

        $list =$model->field($filed)->select();

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