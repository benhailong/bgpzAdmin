<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/6/12
 * Time: 11:35
 */
namespace  app\common\model;
use think\Db;
class CompanyCompany extends \think\Model {
    protected $table='z_company_company';

    private static $instance;
    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getCompanyCompany($where,$field){
        $model = self::instance();
        $list = $model->where('flat_company_id','=',$where)->field($field)->select();
        return $list;

    }


    /**
     * @param $where
     * @return array
     *查询虚拟酒店和实体酒店的关系
     */
    public function getCompanyCompanyId($where){
        $model = self::instance();
        $list = $model->where('flat_company_id','=',$where)->column('from_company_id');
        return $list;
    }


}