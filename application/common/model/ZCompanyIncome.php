<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 */
namespace app\common\model;
use think\Db;
class ZCompanyIncome extends \think\Model{
    public static $FundsType=array(
         1 =>'套餐分成',
         2 =>'平台结算',
    );
   public function relData($pagesize,$search=''){
        $where = '';
        if($search[0]!=''){
            $where['b.name|b.linkstyle']=['like',"%$search[0]%"];
        }
       if($search[1] != '' && $search[2] != ''){
           $where['a.create_time'] = array('between',array(strtotime($search[1]),strtotime($search[2])));
       }else if($search[1] != ''){
           $where['a.create_time'] = array('egt',strtotime($search[1]));
       }else if($search[2] != ''){
           $where['a.create_time'] = array('elt',strtotime($search[2]));
       }
       $field = array(
           'a.id',
           'a.type as type',
           'a.money as money',
           'a.create_time as createTime',
           'a.beforeAll as `before`',
           'a.afterAll as `after`',
           'b.name as name',
           'b.linkstyle as phone',
           'c.id as orderId',
           'c.no as orderNo',
           'c.dinner_time as dinnerTime',
       );
       $list = $this->alias('a')
           ->join('z_company b','a.company_id = b.id')
           ->join('order c','a.order_id = c.id','LEFT')
           ->where($where)->field($field)->order('id desc')->paginate($pagesize);
       foreach ($list as $key=>$value){
           $list[$key]['before'] = $value['before'] != ''?object_array(json_decode($value['before']) ):'';
           $list[$key]['after'] = $value['after'] != ''?object_array(json_decode($value['after'])):'';
       }
       return $list;
   }


    public function relDataList($pagesize,$search='',$company_id){
        $where['a.company_id'] = $company_id;
//        if($search[0]!=''){
//            $where['b.nickname|b.phone']=['like',"%$search[0]%"];
//        }
        if($search[0] != '' && $search[1] != ''){
            $where['a.create_time'] = array('between',array(strtotime($search[0]),strtotime($search[1])));
        }else if($search[0] != ''){
            $where['a.create_time'] = array('egt',strtotime($search[0]));
        }else if($search[1] != ''){
            $where['a.create_time'] = array('elt',strtotime($search[1]));
        }
        $field = array(
            'a.id',
            'a.type as type',
            'a.money as money',
            'a.create_time as createTime',
            'a.beforeAll as `before`',
            'a.afterAll as `after`',
            'b.name as name',
            'b.linkstyle as phone',
            'c.id as orderId',
            'c.no as orderNo',
            'c.dinner_time as dinnerTime',
        );
        $list = $this->alias('a')
            ->join('z_company b','a.company_id = b.id')
            ->join('order c','a.order_id = c.id','LEFT')
            ->where($where)->field($field)->order('id desc')->paginate($pagesize);
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