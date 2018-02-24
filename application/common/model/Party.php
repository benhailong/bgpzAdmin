<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/17
 * Time: 15:41
 */
namespace app\common\model;
use \think\Db;
class Party  extends \think\Model {
    protected $table = 'z_party';
    public function selectParty($pagesize,$field='*',$search='',$status=false){
        $model = self::instance();
        $where='';
        if($search[0]!=''){
            $where['theme|linkname|party_address'] = array('like',"%".$search[0]."%");
        }
        if($status){
            $map['status'] = ['=',$status];
        }else{
            $map['id'] = array('gt',0);
        }
        $list = $model->where($where)->where($map)->field($field)->order('create_time desc')->paginate($pagesize);
        return $list;
    }

    /**
     * @param $order_id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function selectPartyOne($order_id){
        $model = self::instance();
        $where['order_id'] = ['=',$order_id];
        $list = $model->where($where)->find();
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