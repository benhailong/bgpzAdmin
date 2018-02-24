<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 */
namespace app\common\model;
use think\Db;
class UserFund extends \think\Model{
   public function relData($pagesize,$search=''){
        $where = '';
        if($search[0]!=''){
            $where['b.nickname|b.phone']=['like',"%$search[0]%"];
        }
       $field = array(
           'a.uid',
           'a.funds_cur as funds',
           'a.stamp_cur as stamp',
           'a.isAli as isAli',
           'a.realName as realName',
           'a.aliNo as aliNo',
           'b.nickname as name',
           'b.phone as phone'
       );
       $list = $this->alias('a')->join('user b','a.uid = b.id','LEFT')
           ->where($where)->field($field)->paginate($pagesize);
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