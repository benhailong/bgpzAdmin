<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/14
 * Time: 13:47
 */
namespace app\common\model;
use think\Db;
class ZCookFund extends \think\Model {
    protected $table='z_cook_fund';

    private static $instance;
    public function relData($pagesize,$search=''){
        $where = '';
        if($search[0]!=''){
            $where['b.cook_name|c.phone']=['like',"%$search[0]%"];
        }
        $field = array(
            'a.cook_id as cookId',
            'a.cur_fund as cur',
            'a.withdraw_fund as withdraw',
            'a.total_fund as total',
            'a.frozen_fund as frozen',
            'a.all_fund as `all`    ',
            'd.isAli as isAli',
            'd.realName as realName',
            'd.aliNo as aliNo',
            'b.cook_name as name',
            'c.phone as phone'
        );
        $list = $this->alias('a')
            ->join('z_cook b','a.cook_id = b.id','LEFT')
            ->join('user c','b.uid = c.id','LEFT')
            ->join('user_fund d','b.uid = d.uid','LEFT')
            ->where($where)->field($field)->paginate($pagesize);
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