<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 */
namespace app\common\model;
use think\Db;
class PackageFoods extends \think\Model{

    protected $table='z_package_foods';

    /**
     * @param $id
     * @param $company_id
     * @param $field
     * @return false|\PDOStatement|string|\think\Collection
     * 获取所属酒店下面套餐的菜品信息
     */
    public function selectPackage($id,$company_id,$field){
        $model = self::instance();
        $where['zpf.package_id'] =$id;
        $where['zf.company_id'] = $company_id;
        $list = $model->alias('zpf')
              ->join('z_food zf','zf.id=zpf.food_id')
              ->where($where)
              ->field($field)
              ->select();
        return $list;
    }

    /**
     * @param $package_id
     * 查询套餐下面是否已经添加菜品
     */
    public function selectPackageFoods($package_id){
        $model=self::instance();
        $where['package_id'] = ['=',$package_id];
        $list = $model->where($where)->field('id')->find();
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