<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/29
 * Time: 9:33
 */
namespace app\common\model;
use think\Db;
class Ordering extends \think\Model{

    public static $package_type =array(
        '1'=>'套餐',
        '2'=>'私宴',
        '3'=>'单点',
        '4'=>'聚会订单',
    );
    public static $waiter_type = array(
        '1'=>'服务员',
        '2'=>'茶艺师'
    );
    /**
     * @var string 定义表名
     */
    protected $table='order';

    /**
     * @param $food_id
     * @return array
     * 查询我提供的订单
     */
    public function selectOrderPage($food_id){
        $model = self::instance();
        $where['ors.son_id']=['in',$food_id];
        $list = $model->distinct(true)->alias('o')
            ->join('order_son ors','ors.order_id=o.id')
            ->where($where)
            ->field('o.id,o.no,ors.num,ors.id as ors_id')
            ->group('o.id')
            ->column('ors.order_id');
        return $list;
    }
    /**
     * @param $pagesize
     * @param string $search
     * @param string $status
     * @return array
     * 待支付接单方法
     */
    public function relData($pagesize,$search='',$status,$company_id='',$timeMap){
        $model = self::instance();
        $where = '';
        if($company_id!=''){
            $where['o.company_id'] = $company_id;
        }
        if($search[0]!=''){
            $where['no'] =['like',"%$search[0]%"];
        }
        if($timeMap){
            $where['o.dinner_time'] = $timeMap;
        }
        $where['o.status'] = $status;
        $list = $model->alias('o')
                      ->join('z_package zp','zp.id=o.package_oid',"LEFT")
                      ->where($where)
                      ->field(
                          'o.price,
                           o.package_type as type,
                           o.id,o.no,
                           o.dinner_time,
                           o.dinner_name,
                           o.dinner_phone,
                           o.user_address,
                           o.table_num,
                           o.status,
                           zp.name as packageName,
                           o.is_company,
                           o.left_cook as leftCook'
                      )
                      ->order('o.id desc')
                      ->paginate($pagesize);
        foreach ($list as $key=>$vo){
            $map['type'] = 1;
            $map['order_id'] = $vo['id'];
            $info = Db::table('order_son')
                            ->alias('ors')
                            ->join('z_food zf','zf.id=ors.son_id')
                            ->where($map)
                            ->field('ors.num,zf.name')
                            ->select();
          $list[$key]['info']=$info;
            if($vo['status'] > 3){
                unset($orderCookInfo);
                $orderCookInfo = $this->getCookInfoByOrderId($vo['id']);
                $list[$key]['cook']=$orderCookInfo;
            }
            //记录回访信息
            if($vo['status'] == 5){
                $list[$key]['visitInfo'] = $this->visitInfo($vo['id']);
            }
        }
        foreach ($list as $kk=>&$item){
            if($item['type']==2){
                $order_son_id = Db::name('order_son')->where('order_id','=',$item['id'])->field('id')->find();
                if($order_son_id['id']){
                    $item['is_add'] = 1;
                }else{
                    $item['is_add'] = 0;
                }
            }
        }
        return $list;
    }

    /**
     * @param $pagesize
     * @param string $search
     * @param $status
     * @param string $company_id
     * @param $timeMap
     * 查询关于本酒店的相关订单数据
     */
    public function relArray($pagesize,$search='',$status,$company_id,$timeMap,$order_id){
        $model = self::instance();
        $where['o.company_id'] = ['<>',$company_id];
        if($search[0]!=''){
            $where['no'] =['like',"%$search[0]%"];
        }
        if($timeMap){
            $where['o.dinner_time'] = $timeMap;
        }
        $where['o.id'] = ['in',$order_id];
        $where['o.status'] = $status;
        $list = $model->alias('o')
            ->join('z_package zp','zp.id=o.package_oid',"LEFT")
            ->where($where)
            ->field(
                          'o.price,
                           o.package_type as type,
                           o.id,o.no,
                           o.dinner_time,
                           o.dinner_name,
                           o.dinner_phone,
                           o.user_address,
                           o.table_num,
                           o.status,
                           zp.name as packageName,
                           o.id'
             )
            ->order('o.id desc')
            ->paginate($pagesize);
        foreach ($list as $key=>$vo){

            $map['type'] = 1;
            $map['order_id'] = $vo['id'];
            $info = Db::table('order_son')
                ->alias('ors')
                ->join('z_food zf','zf.id=ors.son_id')
                ->where($map)
                ->field('ors.num,zf.name')
                ->select();
            $list[$key]['info']=$info;
            if($vo['status'] > 3){
                unset($orderCookInfo);
                $orderCookInfo = $this->getCookInfoByOrderId($vo['id']);
                $list[$key]['cook']=$orderCookInfo;
            }
            //记录回访信息
            if($vo['status'] == 5){
                $list[$key]['visitInfo'] = $this->visitInfo($vo['id']);
            }
        }

        return $list;

    }


    public function relArrayList($pagesize,$search='',$status,$timeMap,$order_id){
        $model = self::instance();
        if($search[0]!=''){
            $where['no'] =['like',"%$search[0]%"];
        }
        if($timeMap){
            $where['o.dinner_time'] = $timeMap;
        }
        $where['o.id'] = ['in',$order_id];
        $where['o.status'] = $status;
        $list = $model->alias('o')
            ->join('z_package zp','zp.id=o.package_oid',"LEFT")
            ->where($where)
            ->field(
                'o.price,
                           o.package_type as type,
                           o.id,o.no,
                           o.dinner_time,
                           o.dinner_name,
                           o.dinner_phone,
                           o.user_address,
                           o.table_num,
                           o.status,
                           zp.name as packageName,
                           o.id'
            )
            ->order('o.id desc')
            ->paginate($pagesize);
        foreach ($list as $key=>$vo){
            $map['type'] = 1;
            $map['order_id'] = $vo['id'];
            $info = Db::table('order_son')
                ->alias('ors')
                ->join('z_food zf','zf.id=ors.son_id')
                ->where($map)
                ->field('ors.num,zf.name')
                ->select();
            $list[$key]['info']=$info;
            if($vo['status'] > 3){
                unset($orderCookInfo);
                $orderCookInfo = $this->getCookInfoByOrderId($vo['id']);
                $list[$key]['cook']=$orderCookInfo;
            }
            //记录回访信息
            if($vo['status'] == 5){
                $list[$key]['visitInfo'] = $this->visitInfo($vo['id']);
            }
        }
        return $list;

    }
    /**
     * 订单回访页面获取订单相关的信息
     * 获取订单相关的信息
     */
    public function vistGetInfo($orderId){
        $map['id'] = $orderId;
        $field = array('tea_artist_number as tea','waiter','status','is_company','user_address');
        $info = $this->where($map)->field($field)->find();
        return $info;
    }
    /**
     * @param $orderId
     * @return false|\PDOStatement|string|\think\Collection
     */
    private function visitInfo($orderId){
        $map['order_id'] = $orderId;
        $field = array('first_time','programme','remark');
        $info = Db::table('z_visit')->where($map)->field($field)->select();
        return $info;
    }
    /**
     *  根据订单编号获取订单相关的信息
     */
    private function getCookInfoByOrderId($orderId){
        $map['order_id'] = $orderId;
        $field = array(
            'a.id as coId',
            'b.id',
            'b.cook_name as name',
            'c.phone as phone',
            'a.status as status',
            'a.create_time as robTime'
            );
        $info = Db::table('order_cook')->alias('a')
            ->join('z_cook b','a.cook_id = b.id')
            ->join('user c','c.id = b.uid')
            ->where($map)->field($field)->select();
        return $info;
    }
    /**
     * 查询酒店待接单
     */
    public function selectInfo($pagesize,$search='',$status,$company_id='',$field){
        $model = self::instance();
        $where = '';
        if($company_id!=''){
            $where['o.company_id'] = $company_id;
        }
        if($search[0]!=''){
            $where['o.no'] =['like',"%$search[0]%"];
        }
        $where['o.status'] = $status;
        $list = $model->alias('o')
            ->join('z_package zp','zp.id=o.package_oid','LEFT')
            ->where($where)
            ->field($field)
            ->paginate($pagesize);

        return $list;
    }

    /**
     * 查询待拿菜
     */
//    public function selectFoodInfo($pagesize,$search='',$status,$company_id='',$field,$ostatus){
//        $model = self::instance();
//        $where = '';
//       }
//        $where['orc.status']=['=',$ostatus];
//        $where['o.status'] = $status;
//        $list = $model->alias('o')
//            ->join('z_package zp','zp.id=o.package_oid')
//            ->join('order_cook orc','orc.order_id=o.id')
//            ->where($where)
//            ->field($field)
//            ->paginate($pagesize);
//
//        return $list;
//    }        if($company_id!=''){
//            $where['o.company_id'] = $company_id;
//        }
//        if($search[0]!=''){
//            $where['o.no'] =['like',"%$search[0]%"];
//

    public function getOne($id){
        $model = self::instance();
        $list = $model->where('id','=',$id)->find();
        return $list;
    }
    /**
     * @param $pagesize
     * @param string $search
     * @param $status
     * @param $grab_status
     * @param string $company_id
     * @param $field
     * @return \think\Paginator
     * 查询厨师待接单
     */
    public function selectOrderInfo($pagesize,$search='',$status,$grab_status,$company_id='',$field){
        $model = self::instance();
        $where = '';
        if($company_id!=''){
            $where['o.company_id'] = $company_id;
        }
        if($search[0]!=''){
            $where['o.no'] =['like',"%$search[0]%"];
        }
        $where['o.grab_status'] =$grab_status;
        $where['o.status'] = $status;
        $list = $model->alias('o')
            ->join('z_package zp','zp.id=o.package_oid')
            ->where($where)
            ->field($field)
            ->paginate($pagesize);

        return $list;
    }


    /**
     * @var
     * 厨师刚接单(厨师刚拿菜)(厨师核对菜品)(厨师开始做饭)........
     */
    public function selectStart($pagesize,$search='',$field,$status,$company_id=''){
        $model =self::instance();
        $where='';
        if($search[0]!=''){
           $where['or.no'] = ['=',$search[0]];
        }
        if($company_id!=''){
            $where['or.company_id'] = $company_id;
        }
        $where['orc.status'] = ['=',$status];
        $list = $model->alias('or')
                      ->join('order_cook orc','orc.order_id = or.id')
                      ->join('z_package zp','zp.id=or.package_oid')
                      ->where('or.status','=',4)
                      ->where($where)
                      ->field($field)
                      ->paginate($pagesize);
        return $list;
    }

    public function selectEnd($pagesize,$search='',$field,$status,$company_id=''){
        $model =self::instance();
        $where='';
        if($search[0]!=''){
            $where['or.no'] = ['=',$search[0]];
        }
        if($company_id!=''){
            $where['or.company_id'] = $company_id;
        }
        $where['orc.status'] = ['=',$status];
        $list = $model->alias('or')
            ->join('order_cook orc','orc.order_id = or.id')
            ->join('z_package zp','zp.id=or.package_oid')
            ->where('or.status','=',5)
            ->where($where)
            ->field($field)
            ->paginate($pagesize);
        return $list;
    }
    /**
     * 获取剩余厨师的数量
     * @param $orderId
     */
    public function getLeftCook($orderId){
        $map['id'] = $orderId;
        return $this->where($map)->value('left_cook');
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