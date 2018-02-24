<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/18
 * Time: 10:13
 */
namespace  app\common\model;
use \think\Db;
class Advertisement extends \think\Model{
    protected $table='sys_advertisement';
    public static $client= array(
        '1'=>'用户端',
//        '2'=>'厨师端',
        '3'=>'酒店端',
//        '4'=>'三端公用'
    );
    public static $status =array(
        '0'=>'删除',
        '1'=>'有效'
    );

    /**
     * @var array
     * 用户端
     */
    public static  $tag =array(
        'banner'=>'首页banner',
        'userReserve'=>'立即预定',
        'payBanner'=>'用户端全部支付页面',
        'foodStamps'=>'购买粮票',
        'shopBanner'=>' 商城首页banner',
        'shopReserve'=>'商城立即购买',
        'foodPayment'=>'粮票支付',
    );
    /**
     *酒店端
     */
    public static $company_tag = array(
        'shoppingBanner'=>'商城banner',
        'shoppingReserve'=>'立即购买',
        'shoppingPayment'=>'支付'
    );

    /**
     * 查询推广人员列表
     */
    public function selectAdvertisement($pagesize,$search){
        $model = self::instance();
        $where= '';
        if($search[0]==-2){
            $where['status'] = ['>',-2];
        }elseif ($search[0]>=-1){
            $where['status'] = ['=',$search[0]];
        }

        if($search[1]==-2){
            $where['client'] = ['>',-2];
        }elseif ($search[1]>=-1){
            $where['client'] = ['=',$search[1]];
        }
        $list = $model->where($where)->order('status desc,id desc')->paginate($pagesize);
        return $list;
    }
    public function selectOne($id){
        $model =self::instance();
        $list = $model->find($id);
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
