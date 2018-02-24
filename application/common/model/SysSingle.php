<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/6/21
 * Time: 10:38
 */
namespace  app\common\model;
use \think\Db;
class SysSingle extends \think\Model {
    public static $MyType =array(
        1=>'介绍单页',
        2=>'分享文章'
    );
    protected $table='sys_single';

    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function selectSingle($pageSize,$search){
        $where = '';
        if($search[0]==-2){
            $where['type'] = ['>',$search[0]];
        }else if($search[0]>-2){
            $where['type'] = ['=',$search[0]];
        }
        $model =self::instance();
        $list = $model->where($where)->order('id desc')->paginate($pageSize);
        return $list;
    }

}