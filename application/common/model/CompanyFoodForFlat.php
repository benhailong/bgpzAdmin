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

class CompanyFoodForFlat extends \think\Model{
    protected $table='z_company_food_for_flat';

    private static $instance;

    /**
     * @param $where
     * @return array|false|\PDOStatement|string|\think\Model查询虚拟酒店是否已经添加这个酒店的菜品
     */
    public function selectOneInfo($where){
        $model = self::instance();
        $list = $model->where($where)->find();
        return $list;

    }

    public function alreadySelectFoodId($company_id){
        $model = self::instance();
        $where['company_id'] = $company_id;
        $list = $model->where($where)->order('sort asc')->select();
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