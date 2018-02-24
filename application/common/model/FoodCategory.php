<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/30
 * Time: 16:47
 */
namespace  app\common\model;
use \think\Db;
class foodCategory extends \think\Model{
    protected $table ='z_food_category';
    public function selectAll(){
        $model = self::instance();
        $list = $model->field('name,id')->select();
        return $list;
    }

    public function selectCategory($pagesie){
        $model = self::instance();
        $list = $model->order('id desc')->paginate($pagesie);
        return $list;
    }


    public function readOne($id)
    {
        $Model = self::instance();
        $data = $Model
            ->find($id);
        return $data;
    }

    public function delOne($id){
        $Model = self::instance();
        $where['id'] = $id;
        $data = $Model->where($where)->delete();

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