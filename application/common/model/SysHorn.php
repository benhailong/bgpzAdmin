<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/6/21
 * Time: 10:38
 */
namespace  app\common\model;
use \think\Db;
class SysHorn extends \think\Model {
    public static $client= array(
        '1'=>'用户端',
//        '2'=>'厨师端',
        '3'=>'酒店端',
//        '4'=>'三端公用'
    );
    public static $status =array(
        '1'=>'可用',
        '0'=>'不可用',

    );
    protected $table='sys_horn';

    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function selectSysHorn($pageSize,$search){
        $where = '';
        if($search[0]==-2){
            $where['status'] = ['>',$search[0]];
        }else if($search[0]>-2){
            $where['status'] = ['=',$search[0]];
        }
        if($search[1]==-2){
            $where['client'] = ['>',$search[1]];
        }else if($search[1]>-2){
            $where['client'] = ['=',$search[1]];
        }
        $model =self::instance();
        $list = $model->where($where)->order('id desc')->paginate($pageSize);

        return $list;
    }

}