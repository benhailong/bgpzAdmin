<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/24
 * Time: 13:40
 */
namespace app\common\model;
use \think\Db;
class ShopOrder extends \think\model{
    protected $table ='shop_order';
    public function getList($clear = 0,$userInfo='',$timeStart='',$timeOver='',$status=''){
        $map = '';
        $field = array(
            'a.id as orderId','a.create_time','a.price','b.nickname',
            'b.avatar','b.phone','a.address_id','a.status','a.pay_time','a.order_id as orderNo',
            'a.coupon_id','a.client,a.uid');
        if($clear == 0){
            $userInfo = $userInfo?$userInfo:session('userInfoShopOrder');
            $timeStart = $timeStart?strtotime($timeStart):session('timeStartShopOrder');
            $timeOver = $timeOver?strtotime($timeOver):session('timeOverShopOrder');
            $status = $status?$status:session('statusShopOrder');
            if($userInfo){
                $map['b.nickname|b.name|b.phone'] = $userInfo;
                session('userInfoShopOrder',$userInfo);
            }
            if($timeStart && $timeOver){
                $map['a.create_time'] = array('between',array($timeStart,$timeOver));
                session('timeStartShopOrder',$timeStart);
                session('timeOverShopOrder',$timeOver);
            }else if($timeStart){
                $map['a.create_time'] = array('egt',$timeStart);
                session('timeStartShopOrder',$timeStart);
            }else if($timeOver){
                $map['a.create_time'] = array('elt',$timeOver);
                session('timeOverShopOrder',$timeOver);
            }
            if($status){
                $map['a.status'] = $status;
                session('statusShopOrder',$status);
            }
        }else{
            session('userInfoShopOrder',null);
            session('timeStartShopOrder',null);
            session('timeOverShopOrder',null);
            session('statusShopOrder',null);
            $map['a.id'] = array('gt',0);
        }
        $list = $this->alias('a')
            ->join('user b','a.uid = b.id','LEFT')->field($field)->where($map)
            ->order('create_time desc')->paginate();
        foreach ($list as $key=>$value){
            //商品的子订单及对地址信息的处理
            $list[$key]['sonList'] = $this->getOrderSon($value['orderId']);
            if($value['address_id']==0){
                $company_id = Db::name('user_company')->where('uid','=',$value['uid'])->value('company_id');
                $address_info = Db::name('z_company')->where('id','=',$company_id)
                     ->field('region_id as regionId,addr,linkname as name,linkstyle as link')->find();
                $list[$key]['addressInfo'] = $address_info;
            }else{
                $list[$key]['addressInfo'] = $this->getAddressInfo($value['address_id']);
            }
            if($value['coupon_id'] > 0){
                $list[$key]['couponInfo'] = $this->getCouponInfo($value['couponInfo']);
            }
        }
        return $list;
    }
    /**
     * 获取订单的子订单信息
     */
    private function getOrderSon($orderId){
        $map['a.order_id'] = $orderId;
        $field = array('a.num','b.name as goodName','c.tag_name as tagName','c.price as price');
        $info = db('ShopOrderSon')->alias('a')
            ->join('shop_goods b','a.good_id = b.id','LEFT')
            ->join('shop_goods_option c','a.tag_id = c.id','LEFT')
            ->where($map)->field($field)->select();
        return $info;
    }
    /**
     * 获取用户地址的相关信息
     */
    private function  getAddressInfo($addrId){
        $adrMap['id'] = $addrId;
        $addrField = array('region_id as regionId','addr','name','link');
        $addressInfo = db('UserAddr')->where($adrMap)->field($addrField)->find();
        if($addressInfo){
            $data['addr'] = $this->getRegionInfo($addressInfo['regionId']).$addressInfo['addr'];
        }else{
            $data['addr'] = '';
        }
        $data['name'] = $addressInfo['name'];
        $data['link'] = $addressInfo['link'];
        return $data;
    }

    /**
     * 获取地区相关的信息
     * @param $regionId
     */
    private function getRegionInfo($regionId,$name=''){
        $field = array('name','fid','type');
        $map['id'] = $regionId;
        $info = db('SysRegion')->where($map)->field($field)->find();
        if($info['type'] == 'state' || $info['fid'] == 1){
            return $info['name'].$name;
        }else{
            return $this->getRegionInfo($info['fid'],$info['name'].$name);
        }
    }
    /**
     * 对优惠券信息进行获取
     */
    private function getCouponInfo($couponId){
        $map['id'] = $couponId;
        $field = array('a.id','a.create_time','a.active_time','b.name','b.type','b.money');
        $info = db('CouponUser')->alias('a')->join('coupon b','a.fid = b.id','LEFT')
            ->where($map)->field($field)->find();
        return $info;
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