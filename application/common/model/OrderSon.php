<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/30
 * Time: 16:47
 */
namespace  app\common\model;
use \think\Db;
class OrderSon extends \think\Model{
    protected $table ='order_son';
    public function getFoodInfoByOrderId($orderId){
        $field = array(
            'a.id as id',
            'a.num as num',
            'a.single_price as price',
            'b.name as foodName',
            'b.image_url as img',
            'c.name as companyInfo',
            'c.addr as addr',
            'c.id as companyId',    
            'c.linkname as cmLink',
        );
        $map['a.order_id'] = $orderId;
        $map['a.type'] = 1;
        $result = $this->alias('a')->
            join('z_food b','a.son_id = b.id')->
            join('z_company c','b.company_id = c.id')
            ->where($map)->order('c.id desc')->field($field)->select();
        return $result;
    }

    public function selectOrderSonList($where){
        $model = self::instance();
        $list = $model->where($where)->column('son_id');
        return $list;
    }

    /**
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     * 查询这个订单的所有菜品数据
     */
    public function selectSonListInfo($where){
        $model = self::instance();
        $list = $model->where($where)->select();
        foreach ($list as $key=>&$vo){
            $data = Db::name('z_food')->where('id','=',$vo['son_id'])->field('name,price')->find();
            $vo['food_name'] = $data['name'];
            $vo['food_price'] = $data['price'];
        }
        return $list;
    }

    /**
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     * 查询这个订单的所有酒水
     */
    public function selectSonListWine($where){
        $model = self::instance();
        $list = $model->where($where)->select();
        foreach ($list as $key=>&$vo){
            $data = Db::name('z_wine')->where('id','=',$vo['son_id'])->field('name,price')->find();
            $vo['food_name'] = $data['name'];
            $vo['food_price'] = $data['price'];
        }
        return $list;

    }

    /**
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     * 获取所有这个私宴的总金额
     */
//    public function selectPriceFood($where){
//        $model = self::instance();
//        $list = $model->where('order_id','=',$where)->select();
//        foreach ($list as $key=>&$vo){
//           $vo['num']*
//        }
//        return $list;
//    }
    /**
     * 判断订单是否含有酒水
     */
    public function haveWine($orderId){
        $map['order_id'] = $orderId;
        $map['type'] = 2;
        $count = $this->where($map)->count();
        return $count;
    }
    /**
     * 酒水列表
     */
     public function wineList($orderId){
        $map['a.order_id'] = $orderId;
         $map['a.type'] = 2;
         $field = array('b.id','b.name','image_url as url','b.price as price','a.num as num');
         $list = Db::table('order_son')->alias('a')
             ->join('z_wine b','a.son_id = b.id')
             ->where($map)->field($field)->select();
         return $list;
     }
    /**
     * @var
     */
    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}