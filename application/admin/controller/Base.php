<?php
namespace app\admin\controller;

use \think\Request;
use \think\Db;
use \app\admin\model\AdminAccess as ModelAdminAccess;
use Upyun\Config;
use Upyun\Upyun;

class Base extends \app\common\controller\Base
{
    protected $res;
    /*
     * AdminModel的对象
     */
    protected $admin;
    protected $curAdmin;

    protected function admin()
    {
        if (is_null($this->admin)) {
            $this->admin = new \app\admin\model\Admin;
        }
        return $this->admin;
    }

    public function _initialize()
    {

        $this->chkLogin();
        if (!$this->check_access(session('admin')['role_id'])) {
            $this->error("您没有访问权限！");
            exit();
        }
        /*if(isset($this->res[Request::instance()->action()])){
            $cur_res = $this->res[Request::instance()->action()];
        }*/
        $curAction = '/' . Request::instance()->controller() . '/' . Request::instance()->action();
        $fp = db('admin_menu')->where('action', $curAction)->value('path');
        $a = explode('-', trim($fp));
        array_pop($a);
        array_shift($a);
        array_shift($a);
        $bread = db('admin_menu')->where('action', $curAction)->value('name');
        if (!empty($a)) {
            foreach ($a as $b) {
                $bread_push[] = db('admin_menu')->where('id', $b)->value('name');
            }
            array_push($bread_push, $bread);
            $bread = $bread_push;
        }
        $this->assign(array(
            'curAdmin'  => cache('admin'),
            'curAvatar' => cache('admin')['avatar'],
            'bread'     => $bread,
            'res'       => isset($this->res[Request::instance()->action()]) ? $this->res[Request::instance()->action()] : null
        ));
    }

    /**
     * 检查登陆
     */
    protected function chkLogin()
    {
        $bacLoginPath = '/login'; // 后台登陆页

        if (!$this->admin()->isLogin()) {
            $this->redirect(url($bacLoginPath));
        }
    }

    public function check_access($role_id)
    {
        /* 如果用户角色是1，则无需判断 */
        if ($role_id == 1) {
            return true;
        }
        $controller = strtolower(Request::instance()->controller());
        $action = strtolower(Request::instance()->action());
        //忽略的地址
        $ignore_array = array('login-index', 'index-index');
        if (in_array($controller . '-' . $action, $ignore_array)) {
            return true;
        }
        //如果是AJAX
        if (Request::instance()->isAjax()) {
            return true;
        }
        //暂时把所有的权限都放出来
        /*if (1 == 1) {
            if (session('admin')['id'] != 2) {
                return true;
            }
        }*/
        $aModel = new ModelAdminAccess();
        $res = $aModel->where("role_id = $role_id and controller = '" . $controller . "' and action = '" . $action . "'")->count();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function write_admin_log($data)
    {
        $file_name = 'file/log/' . date('Ymd', time()) . '.txt';
        if (is_array($data)) {
            $data_str = implode(' | ', $data);
        } else {
            $data_str = $data;
        }
        file_put_contents($file_name, $data_str . "\r\n\r\n", FILE_APPEND);
    }

    public function search($search, $page, $fl = 'search')
    {
        if ($search === null && $page === null) {
            session($fl, null);
        } elseif ($search === '') {
            session($fl, null);
        } elseif ($search !== null) {
            session($fl, serialize($search));
        }
        return unserialize(session($fl));
    }
    /**
     * 对于状态的筛选条件建立
     */
    public function statusSelect($status,$fl = 'statusSelect'){
        if($status === null){
            session($fl,null);
        }elseif(trim($status) == ''){
            session($fl,null);
        }else{
            session($fl,serialize($status));
        }
        return unserialize(session($fl));
    }
    /**
     * 对时间信息进行通用的搜索
     */
    protected function getTimeMap(){
        $start = input('time_from');
        $end = input('time_to');
        $this->assign('time_from',$start);
        $this->assign('time_to',$end);
        $start = strtotime($start);
        $end = strtotime($end);
        if($start > 0 && $end > 0){
            $return = array('between',array($start,$end));
        }else if($start > 0){
            $return = array('gt',$start);
        }else if($end > 0){
            $return = array('lt',$end);
        }else{
            $return = array('gt',0);
        }
        return $return;
    }
    /**
     * 获取任意长度的随机字符串
     * @param $length
     * @return string
     */
    function getRandChar($length)
    {
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $char_txt = '';
        for ($i = 0; $i < $length; $i++) {
            $char_txt .= $chars[array_rand($chars)];
        }
        return $char_txt;
    }

    /**
     * @param $sence
     * @param $uid
     * @param $status
     * @param $type
     * @param $withdrawId
     *  @param $is_user 1用户推送  2厨师端推送
     * 提现推荐
     */
    public function withdrawCheckOut($sence,$uid,$status,$type,$withdrawId,$is_user){

            if($status==2){//审核失败
                $msg = '提现失败!!!';
                $array = array('sence'=>$sence,'type'=>$type,'withdrawId'=>$withdrawId,'status'=>$status);
            }elseif ($status==1){//审核成功
                $msg = '提现成功,请注意查收';
                $array = array('sence'=>$sence,'type'=>$type,'withdrawId'=>$withdrawId,'status'=>$status);
            }
            $tokenMap['uid'] =$uid;
            $tokenMap['is_html5'] =0;
            $tokenMap['client'] =['neq',3];
            $token = db('UserToken')->where($tokenMap)->column('token');
            $this->Jpush(2,$token,$msg,$array);
    }

    /**
     * 供货酒店新供货订单的提醒刷新
     * @param $cmId 主酒店的编号
     * @param $orderId 订单的自增长编号
     */
    public function provideFoodAlert($cmId,$orderId){
        $map['a.order_id'] = $orderId;
        $map['a.type'] = 1;
        $map['b.company_id'] = array('neq',$cmId);
        $field = 'b.company_id';
        $cmList = db('OrderSon')->alias('a')
            ->join('z_food b','a.son_id = b.id','LEFT')
            ->where($map)->column($field);
        if(count($cmList) > 0){
            $umMap['company_id'] = array('in',$cmList);
            $umMap['status'] =  1;
            $uidList = db('UserCompany')->where($umMap)->column('uid');
            if(count($uidList) > 0){
                $tMap['uid'] = array('in',$uidList);
                $tMap['client'] = 3;
                $tMap['is_html5'] = 0;
                $tokenArray = db('UserToken')->where($tMap)->column('token');
                $msg = '您有新的供货订单，请保证按时给厨师提供相应的菜品、酒水或餐具';
                $array = array('sence'=>'provideFoodAlert');
                $this->Jpush(3,$tokenArray,$msg,$array);
            }
        }
    }
    /**
     * 一级分销、二级分销、厨师、酒店端、收益推送
     */

    public function MoneyIn($sence,$uid,$type){
        $tokenMap['uid'] =$uid;
        $tokenMap['is_html5'] =0;
        $token_array = db('UserToken')->where($tokenMap)->find();
        if($token_array['token']!=''){
            $msg = '聚会的打赏的金额已经到你的账户,请注意查收';
            $array = array('sence'=>$sence,'type'=>$type);
            $this->Jpush(1,$token_array['token'],$msg,$array);
        }
    }
    /**

     * 后台完成订单信息回访 向酒店端发送推送
     * $client 所属的端的编号 1用户端 2厨师端 3酒店端
     * $sence 场景
     * $orderId 订单编号
     */
    public function companyOrder($client,$sence,$orderId)
    {
        $company_id = Db::name('order')->where('id','=',$orderId)->value('company_id');//查询承接酒店
        if($company_id > 0){
            $ucMap['company_id'] = $company_id;
            $ucMap['status'] = 1;
            $uid_data = Db::name('user_company')->where($ucMap)->column('uid');//查询这个酒店的管理员
            if(count($uid_data) > 0){
                $tokenMap['client'] = $client;//所属的端的编号 1用户端 2厨师端 3酒店端'
                $tokenMap['uid'] =['in',$uid_data];//所有的所属酒店端用户
                $tokenMap['is_html5'] =0;
                $token_array = db('UserToken')->where($tokenMap)->select();
                if(count($token_array) > 0){
                    $token = array_column($token_array,'token');
                    $msg = '酒店已经有新的订单，请查看订单信息!!';
                    $array = array('sence'=>$sence,'orderId'=>$orderId);
                    $this->Jpush($client,$token,$msg,$array);
                }
            }
        }
    }

    /**
     * 酒店端选择自带厨师或者平台配置厨师
     * @param $orderId  订单编号
     * @param $sence   场景
     */
    public function cookHaveOrder($orderId,$sence,$client){
        $where['order_id'] = $orderId;
        $cook_list_data = db('OrderCook')->where($where)->column('cook_id');
        $uid_data = Db::name('z_cook')->where('id','in',$cook_list_data)->column('uid');
        $tokenMap['uid'] = ['in',$uid_data];
        $tokenMap['client'] = ['=',2];
        $tokenMap['is_html5'] =0;
        $token =  db('UserToken')->where($tokenMap)->column('token');
        $msg = '酒店已经像你分配订单,请查看下订单详情!!';
        $array = array('sence'=>$sence,'orderId'=>$orderId);
        $this->Jpush($client,$token,$msg,$array);
    }

    /**
     * @param $orderId  厨师的id
     * @param $status   是否审核通过
     * @param $sence    场景
     */
    public function beCheckOut($orderId,$status,$sence){
        $uid_data = Db::name('z_cook')->where('id','=',$orderId)->field('uid')->find();
        $tokenMap['uid'] = $uid_data['uid'];
        $tokenMap['is_html5'] =0;
        $token = db('UserToken')->where($tokenMap)->value('token');
        if($status==1){
            $msg = '恭喜你,您已经审核通过';
        }elseif($status==2){
            $msg = '很遗憾,您没有通过审核';
        }
        $array = array('sence'=>$sence,'status'=>$status);
        $this->Jpush(2,$token,$msg,$array);
    }

    /**
     * @param $orderId
     * @param $sence
     * @param $client
     *
     */
    public function putGoods($orderId,$sence,$client,$logisticsCompany,$logSheetOrder){
        $data = Db::name('shop_order')->where('id','=',$orderId)->find();
        if(count($data)>0){
            if($client==1){//查询商品订单的订单编号
                //如果是1的话就查询订单表中的用户ID
//                $list = Db::name('order')->where('id','=',$data['order_id'])->field('uid')->find();
//                if($list['uid']!=''){
                    //查询token
                    $where['uid'] = ['=',$data['uid']];
                    $where['client'] = 1;
                    $where['is_html5'] =0;

                $token = Db::name('user_token')->where($where)->value('token');
                    if(count($token)>0){
                        $array = array('sence'=>$sence,'orderId'=>$orderId,'logSheetOrder'=>$logSheetOrder,'logistics'=>$logisticsCompany);
                        $msg ='你的订单已经发货';
                        $this->Jpush($client,$token,$msg,$array);
                    }
//                }

            }elseif($client==3){//如果是3的话就是查询订单表中承接酒店的ID
                $list = Db::name('user_company')->where('uid','=',$data['uid'])->field('company_id')->find();//查询酒店编号
                if($list['company_id']!=''){
                    //查询这个酒店下面所有的管理员
                    $result = Db::name('user_company')->where('company_id','=',$list['company_id'])->value('uid');
                    if(count($result)>0){
                        $where['uid'] = ['in',$result['uid']];
                        $where['client'] =3;
                        $where['is_html5'] =0;
                        $token =Db::name('user_token')->where($where)->value('token');
                        if(count($token)>0){
                            $array = array('sence'=>$sence,'orderId'=>$orderId);
                            $msg ='你的订单已经发货';
                            $this->Jpush($client,$token,$msg,$array);
                        }
                    }

                }

            }

        }

    }
    /**
     * @param $client
     * @param $sence
     * @param $orderId
     * @return array|bool
     * 极光推送
     */
    public function Jpush($client,$token,$msg,$array){//1用户端 2厨师端 3 酒店端
        if($client == 1){
            $ji=array(
                'appKey'=>'d5c2c1bf92e0402210feec8a',
                'masterSecret'=>'45ee98b2736ca58628947725',
            );
        }else if($client == 2){
            $ji=array(
                'appKey'=>'ef608df0da24d14d1fa9e392',
                'masterSecret'=>'9eae16aa74d778a13bb29460',
            );
        }else if($client == 3){
            $ji=array(
                'appKey'=>'237105db069167beeace2097',
                'masterSecret'=>'67046b0d73a13f68e7954ee4',
            );
        }else{
            return false;
        }

        $client = new \JPush\Client($ji['appKey'],$ji['masterSecret']);
        $push = $client->push();
        $push->setPlatform('all');
        if(isset($token)){
            $push->addAlias($token);
            $push->iosNotification($msg, array(
                    'alert'=>$msg,
                    'badge' => '1',
                    'extras' => $array,
                    'sound'=>'default'
                )
            );
            $push->androidNotification($msg, array(
                    'badge' => '1',
                    'extras' => $array
                )
            );
            $result = $push->send();
            return $result;
        }else{
            return false;
        }
    }

    /**
     * @param $client
     * @param $msg
     * @param $array
     * @return array|bool
     * 小广播
     */
    public function JpushHorn($client,$msg,$array){
        if($client == 1){
            $ji=array(
                'appKey'=>'d5c2c1bf92e0402210feec8a',
                'masterSecret'=>'45ee98b2736ca58628947725',
            );
        }else if($client == 2){
            $ji=array(
                'appKey'=>'ef608df0da24d14d1fa9e392',
                'masterSecret'=>'9eae16aa74d778a13bb29460',
            );
        }else if($client == 3){
            $ji=array(
                'appKey'=>'237105db069167beeace2097',
                'masterSecret'=>'67046b0d73a13f68e7954ee4',
            );
        }else{
            return false;
        }
        $client = new \JPush\Client($ji['appKey'],$ji['masterSecret']);
        $push = $client->push();
        $push->setPlatform('all');
            $push->addAllAudience();
            $push->iosNotification($msg, array(
                    'alert'=>$msg,
                    'badge' => '1',
                    'extras' => $array,
                    'sound'=>'default'
                )
            );
            $push->androidNotification($msg, array(
                    'badge' => '1',
                    'extras' => $array
                )
            );
            $result = $push->send();
            return $result;

    }

    /**
     * 获取城市信息的列表
     */
   protected function getCityList(){
       $map['status'] = 1;
       $field = array('city_name as name','id');
       $list = db('ZCity')->where($map)->field($field)->select();
       return $list;
   }
}
