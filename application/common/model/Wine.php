<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/14
 * Time: 13:47
 */
namespace app\common\model;
use think\Db;
class Wine extends \think\Model {
    protected $table='z_wine';
    public static $status =array(
        '1'=>'可用',
        '-1'=>'不可用'
    );
    public function selectWine($pagesize,$search){
        $model = self::instance();
        $where = '';
        if($search[0]!=''){
            $where['name'] = ['like',$search[0]];
        }
        if($search[1]==-2){
            $where['status'] =['>',-2];
        }elseif ($search[1]>-2){
            $where['status'] = ['=',$search[1]];
        }
        $list = $model->where($where)->order('sort desc')->paginate($pagesize);
        return $list;
    }

    public function selectOne($id){
        $model =self::instance();
        $list = $model->where('id','=',$id)->find();
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