<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 10:13
 */
namespace  app\common\model;
use \think\Db;
class City extends \think\Model{
    protected $table='z_city';

    public function selectCity($city_id){
        $model = self::instance();
        $where['id'] =['=',$city_id];
        $list = $model->where($where)->find();
        return $list;
    }
    /**
     * 判断选择城市的存在性
     */
    public function checkExist($id){
        $map['id'] = $id;
        $map['status'] = 1;
        $count = $this->where($map)->count();
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取城市列表的信息
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCity(){
        $fields = array('id','city_name as name');
        $map['status'] = 1;
        $list = $this->where($map)->field($fields)->select();
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
