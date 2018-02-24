<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/30
 * Time: 16:47
 */
namespace  app\common\model;
use \think\Db;
class OrderCook extends \think\Model{
    protected $table ='order_cook';

    public function selectWithCook($order,$cook){
        $model = self::instance();
        $where['order_id'] = ['=',$order];
        $where['cook_id'] = ['=',$cook];
        $list = $model->where($where)->value('id');
        return $list;
    }

    /**
     * @param $data
     * @param $type
     * @return mixed
     * 查询待拿菜的信息
     */
    public function selectOrderCook($data){
        $model= self::instance();

        foreach ($data as $key=>&$vo){
            $where['oc.order_id'] = ['=',$vo['id']];
            $list = $model->alias('oc')
                          ->join('z_cook zc','zc.id=oc.cook_id')
                          ->join('user u','u.id=zc.uid')
                          ->where($where)
                          ->field('u.name,u.phone')
                          ->select();
            $list = collection($list)->toArray();

            $username = implode(',',array_column($list,'name'));
            $phone = implode(',',array_column($list,'phone'));
            $data[$key]['names']=$username;
            $data[$key]['phones']=$phone;
        }
        return $data;
    }

    /**
     * 给尚未确定的厨师分菜
     */
    public function handleCookSon($orderId,$cookInfo){
        $orderMap['id'] = $orderId;
        $leftCook = db('Order')->where($orderMap)->value('left_cook');
        $countMap['order_id'] = $orderId;
        $nowCount = $this->where($countMap)->count();
        if($nowCount == 0){
            $cookArray = array();
            if(count($cookInfo) > 0){
                foreach ($cookInfo as $key=>$value){
                    $cookArray[$value][] = $key;
                }
                for($i=1;$i<=$leftCook;$i++){
                    unset($data);
                    unset($orderCookId);
                    unset($foodMap);
                    $data['order_id'] = $orderId;
                    $data['cook_id'] = 0;
                    $data['type'] = 1;
                    $data['status'] = 0;
                    $orderCookId = $this->insertGetId($data);
                    if(isset($cookArray[$i])){
                        if(count($cookArray[$i]) > 0){
                            $foodMap['id'] = array('in',$cookArray[$i]);
                            $foodMap['type'] = 1;
                            $sonData['order_cook_id'] = $orderCookId;
                            model('OrderSon')->where($foodMap)->update($sonData);
                        }
                    }
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
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