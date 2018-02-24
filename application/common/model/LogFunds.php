<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 */
namespace app\common\model;
use think\Db;
class LogFunds extends \think\Model{
    public static $FundsType=array(
        -2 =>'三方支付消费',
        -1 =>'减少',
         1 =>'增加',
         2 =>'转账到银行卡',
         3 =>'币种互换'
    );
    public static $currency=array(
        0=>'未知',
        1=>'元',
        2=>'张粮票'
    );
    public static $channel=array(
        0=>'不涉及三方支付',
        1=>'支付宝',
        2=>'微信'
    );
   public function relData($pagesize,$search=''){
        $where = '';
        if($search[0]!=''){
            $where['b.nickname|b.phone']=['like',"%$search[0]%"];
        }
       if($search[1] != '' && $search[2] != ''){
           $where['a.create_time'] = array('between',array(strtotime($search[1]),strtotime($search[2])));
       }else if($search[1] != ''){
           $where['a.create_time'] = array('egt',strtotime($search[1]));
       }else if($search[2] != ''){
           $where['a.create_time'] = array('elt',strtotime($search[2]));
       }
       $field = array(
           'a.uid',
           'a.type as type',
           'a.money as money',
           'a.note as note',
           'a.ip as ip',
           'a.create_time as createTime',
           'a.sence as sence',
           'a.oid as oid',
           'a.currency as currency',
           'a.before_funds as `before`',
           'a.after_funds as `after`',
           'a.channel as channel',
           'b.phone as phone',
           'b.nickname as name'
       );
       $list = $this->alias('a')->join('user b','a.uid = b.id')
           ->where($where)->field($field)->paginate($pagesize);
       foreach ($list as $key=>$value){
           $list[$key]['before'] = $value['before'] != ''?object_array(json_decode($value['before']) ):'';
           $list[$key]['after'] = $value['after'] != ''?object_array(json_decode($value['after'])):'';
       }
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