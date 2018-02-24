<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/28
 * Time: 16:40
 */
namespace app\common\model;
class PackageType extends \think\model{
    public static $package_type = array(
        '0'=>'禁用',
        '1'=>'显示'
    );
  protected $table= 'z_package_type';
    public function selectPackageType($pagesize,$search){
        $model = self::instance();
        $where ='';
        if($search[0]!=''){
            $where['name'] = ['like',"%$search[0]%"];
        }
        $list = $model->where($where)->order('id desc')->paginate($pagesize);
        return $list;

    }

    /**
     * @return false|\PDOStatement|string|\think\Collection
     * 查询套餐分类
     */
    public function selectPackTypeList(){
        $model = self::instance();
        $where['status'] = 1;
        $list = $model->where($where)->field('id,name')->select();
        return $list;
    }
    public function readOne($id)
    {
        $Model = self::instance();
        $data = $Model
            ->find($id);
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