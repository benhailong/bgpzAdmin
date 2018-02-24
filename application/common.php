<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use \think\Route;
use \think\Config;
use \think\Request;

Route::domain('admin', 'admin');
Route::domain('shop', 'shop');
Config::load(APP_PATH . '/admin/config.php');

if(!function_exists('doTest')) {
    function doTest($content, $label = 'test')
    {
        $info = "\t\t" . $label;
        $info .= "\t\t" . request()->ip();
        if (is_string($content) || is_numeric($content)) {
            $info .= "\t\t" . $content;
        } else {
            $info .= "\t\t" . serialize($content);
        }
        $info .= "\t\t" . date('Y-m-d H:i:s', time());
        $info .= "\n";
        file_put_contents('log/test', $info, FILE_APPEND);
    }
}
if (!function_exists('get_client_ip')) {
    function get_client_ip($type = 0, $adv = false)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}
/**
 * 改变数组的键名
 * @param array $arr 需要改变键名的数组
 * @param string $k 键名的元素的值
 * @return array
 */
if (!function_exists('chgKey')) {
    function chgKey($arr, $k)
    {
        if (empty($arr)) return $arr;
        $return_arr = array();
        foreach ($arr as &$v) {
            $return_arr[$v[$k]] = $v;
        }
        return $return_arr;
    }
}

/**
 * 爆破去重去空
 */
if (!function_exists('explodeAdvanced')) {
    function explodeAdvanced($var, $config = array())
    {
        static $defaultConfig = array(
            'separate'      => ',',         //分隔符
            'trim_charlist' => ',',         //需要去除的字符列表
            'trim_side'     => 'b',      //l 左边，r 右边， b(both) 左右两边
            'unique'        => TRUE,        //是否去重
            'sort_flags'    => SORT_REGULAR,//去重时的sort_flags
            'no_empty'      => TRUE,        //是否去空
        );
        $config = array_merge($defaultConfig, $config);
        if (is_string($var)) {
            static $trimFuns = array('l' => 'ltrim', 'r' => 'rtrim', 'b' => 'trim');
            if (!$config['no_empty']) {//不去空的话，从去除列表里面去掉分隔符
                $config['trim_charlist'] = str_replace($config['separate'], '', $config['trim_charlist']);
            }
            $var = explode($config['separate'], $trimFuns[$config['trim_side']]($var, $config['trim_charlist']));
        }
        if ($config['unique']) {
            $var = array_unique($var, $config['sort_flags']);
        }
        if ($config['no_empty']) {
            $var = array_filter($var);
        }
        return $var;
    }
}

if (!function_exists('strtoarray')) {
    function strtoarray($data)
    {
        $a = explode('&', $data);
        $res = array();
        foreach ($a as $value) {
            $b = explode('=', $value);
            $res[] = $b[1];
        }
        return $res;
    }
}
/**
 * 返回文件内容类型以及编码(对Fileinfo扩展的封装)
 * @param string $filePath
 * @param int $options
 * @return string
 */
if (!function_exists('fileInfo')) {
    function fileInfo($filePath, $options = 16)
    {
        error_reporting(0);
        if (!is_readable($filePath)) return '文件不可读';
        $finfo = new \finfo($options);
        if (!$finfo) return '实例化失败';
        return $finfo->file($filePath);
    }
}

/**
 * 创建文件夹
 */
if (!function_exists('mkdirAuto')) {
    function mkdirAuto($dir, $mode = 0755)
    {
        if (is_dir($dir)) {
            return TRUE;
        } else {
            return mkdir($dir, $mode, TRUE);
        }
    }
}

/**
 * 返回缩略图片
 */
if (!function_exists('imgSize')) {
    function imgSize($img, $width, $height)
    {
        if (isset($width) && isset($height)) {
            $imgs = explode('.', $img);
            return $imgs[0] . "_" . $width . "_" . $height . "." . $imgs[1];
        } else {
            return $img;
        }
    }
}







if (!function_exists('trim_array')) {
    function trim_array($array)
    {
        if (is_array($array)) {
            foreach ($array as &$val) {
                $val = trim_array($val);
            }
            return $array;
        } else {
            return trim($array);
        }
    }
}




//对象转数组
if (!function_exists('object_array')) {
    function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }
}
/**
 * https
 * by wuqh
 */
if (!function_exists('http')) {
    function http($url, $data = '', $method = 'GET', $header = '')
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        if ($header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        } else {
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != '') {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}


if (!function_exists('static_name')) {
    function static_name($dir)
    {
        $module = strtolower(Request::instance()->module());
        $controller = strtolower(Request::instance()->controller());
        $action = strtolower(Request::instance()->action());
        $dir = $dir . '/' . $module . '/' . $controller;
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $filename = explode('.', $file)[0];
                    if ($action == $filename) {
                        return $module . '/' . $controller . '/' . $action;
                    }
                }
                closedir($dh);
                return false;

            }
        }
        return false;
    }
}


if (!function_exists('geturl')) {
    //先检查当前文件夹是否存在，如不存在，创建文件夹
    function geturl()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $str = $year . $month . $day;
        $path = PUBLIC_PATH . "/file/excel/" . $str;
        if (!file_exists($path))//判断文件夹是否存在
        {
            mkdir($path);
        }
//return $path."/";
        return $str . "/";
    }
}
if (!function_exists('emptynull')) {
    function emptynull($data)
    {
        if (is_array($data)) {
            foreach ($data as &$val) {
                if (is_array($val)) {
                    $val = emptynull($val);
                } elseif ($val == '') {
                    $val = null;
                }
            }
            return $data;
        }else{
            return false;
        }
    }
}

if (!function_exists('diffStr')) {
    function diffStr($str1, $str2)
    {
        preg_match_all("/./u", $str1, $arr1);
        preg_match_all("/./u", $str2, $arr2);

        $sArr1 = $arr1[0];
        $sArr2 = $arr2[0];

        $num1 = count($sArr1);
        $num2 = count($sArr2);

        $aNew = array();

        if ($num1 > $num2) {
            foreach ($sArr1 as $k => $val) {
                if ($num2 > $k && $val != $sArr2[$k]) {
                    $aNew[] = array('s1' => $val, 's2' => $sArr2[$k]);
                } elseif ($num2 <= $k) {
                    $aNew[] = array("s1" => $val);
                }
            }
        } elseif ($num1 < $num2) {
            foreach ($sArr2 as $k => $val) {
                if ($num1 > $k && $val != $sArr1[$k]) {
                    $aNew[] = array('s1' => $sArr1[$k], 's2' => $val);
                } elseif ($num1 <= $k) {
                    $aNew[] = array("s2" => $val);
                }
            }
        } elseif ($num1 == $num2) {
            foreach ($sArr1 as $k => $val) {
                if ($val != $sArr2[$k]) {
                    $aNew[] = array('s1' => $val, 's2' => $sArr2[$k]);
                }
            }
        }

        return $aNew;
    }
}
if (!function_exists('get_areaid_byname')) {
    function get_areaid_byname($name, $type, $parent = 0)
    {
        if ($name == "") return "";
        $region = model("region");
        if (!$parent) {
            $data = $region->field("f_id")->where("f_name", $name)->where("f_type", $type)->find();
            if ($type == "city" && !$data)
                $data = $region->field("f_id")->where("f_name", $name)->where("f_type", 'region')->find();
        } else {
            $data = $region->field("f_id")->where("f_name", $name)
                ->where("f_parent_id", $parent)->where("f_type", $type)->find();
            if ($type == "city" && !$data['f_id']) {
                $data = $region->field("f_id")->where("f_name", $name)
                    ->where("f_parent_id", $parent)->where("f_type", 'region')->find();
            }
        }
        return $data['f_id'];
    }
}
/**
 * 获取套餐类型的名称
 */
if(!function_exists('getPackageType')){
    function getPackageType($type){
        switch ($type){
            case 1:
                return '套餐';
                break;
            case 2:
                return '私宴';
                break;
            case 3:
                return '单点';
                break;
            case 4:
                return '聚会订单';
                break;
            default:
                return false;
        }
    }
}
if(!function_exists('orderStatus')){
    function orderStatus($status){
        switch ($status){
            case -1:
                return '已经被下单用户删除';
                break;
            case 0:
                return '删除';
                break;
            case 1:
                return '未支付';
                break;
            case 2:
                return '已支付(需要联系客户)';
                break;
            case 3:
                return '待接单(完成售前回访)';
                break;
            case 4:
                return '已被接单(进行中)';
                break;
            case 5:
                return '已经结束';
                break;
            default:
                return false;
        }
    }
}
if(!function_exists('getCookOrderStatus')){
    function getCookOrderStatus($status){
        switch ($status){
            case -1:
                return '删除订单';
                break;
            case 0:
                return '厨师已经接单';
                break;
            case 1:
                return '开始';
                break;
            case 2:
                return '拿菜和餐具结束';
                break;
            case 3:
                return '呼叫客户后';
                break;
            case 4:
                return '到达小区后';
                break;
            case 5:
                return '做菜就餐结束';
                break;
            case 6:
                return '收取加时费';
                break;
            case 7:
                return '清洁后';
                break;
            case 8:
                return '送还餐具';
                break;
            case 9:
                return '完成';
                break;
            default:
                return false;
        }
    }
}
if(!function_exists('toDate')){
    function toDate($time){
        if($time > 0){
            return date('Y-m-d H:i:s',$time);
        }else{
            return false;
        }
    }
}
if(!function_exists('toData')){
    function toData($time){
        if($time > 0){
            return date('Y-m-d H:i',$time);
        }else{
            return false;
        }
    }
}
if(!function_exists('getPartyStatus')){
    function getPartyStatus($status){
        switch ($status){
            case -2:
                return '已删除';
                break;
            case -1:
                return '已取消';
                break;
            case 0:
                return '未发起的聚会(草稿)';
                break;
            case 1:
                return '发起的聚会';
                break;
            case 2:
                return '进行中';
                break;
            case 3:
                return '已经结束';
                break;
            default:
                return '未知状态';
        }
    }
}
if(!function_exists('getDbConfig')){
    function getDbConfig($name){
        $map['name'] = $name;
        return db('SysConfig')->where($map)->value('value');
    }
}
if(!function_exists('Moa')){
    function Moa($moa){
        switch ($moa){
            case 1:
                return '上午';
                break;
            case 2:
                return '下午';
                break;
            default:
                return '未知';
        }
    }
}
if(!function_exists('getNo')){
    function getNo($orderId){
        $map['id'] = $orderId;
        return db('Order')->where($map)->value('no');
    }
}
if(!function_exists('getClient')){
    function getClient($client){
        switch ($client){
            case 1:
                return '用户端';
                break;
            case 2:
                return '厨师端';
                break;
            case 3:
                return '酒店端';
                break;
            default:
                return '未知调用者';
        }
    }
}
/**
 * 获取商城端的订单状态
 */
if(!function_exists('getShopOrderStatus')){
    function getShopOrderStatus($status){
        switch ($status){
            case -1:
                return '订单删除';
                break;
            case 1:
                return '已确认订单';
                break;
            case 2:
                return '支付成功';
                break;
            case 3:
                return '已经发货';
                break;
            case 4:
                return '已经收货';
                break;
            default:
                return '未知状态';
        }
    }
    if(!function_exists('getRecommand')){
        function getRecommand($val){
            switch ($val){
                case 1:
                    return '推荐';
                    break;
                case 0:
                    return '不推荐';
                    break;
                default:
                    return '未知推荐状态';
            }
        }
    }
    /**
     * 获取城市名称
     */
     if(!function_exists('getCityList')){
         function getCityList($id){
             if($id > 0){
                 $map['id'] = $id;
                 return db('ZCity')->where($map)->value('city_name');
             }else{
                return '全网可用';
             }
         }
     }
}