<?php
namespace app\common\controller;

use \think\Controller;
use Upyun\Config;
use Upyun\Upyun;
class Base extends Controller
{
    const SUCCESS = 2000;
    const ACHIEVE = 1000;
    const DBERROR = 1001;
    /**
     * 获取文件的内容
     */
    protected function getFileContent($url){
        if(strpos($url,'html')){
            $url_u = $url;
//            $url = 'http://'.$_SERVER['HTTP_HOST'].$url;
            if(is_file(HTML_PATH_P.'/'.$url_u)){
                $content = file_get_contents(HTML_PATH_P.'/'.$url_u);
                $content = html_entity_decode($content);
                return $content;
            }

        }else{
            return '';
        }
    }
    public function api_error($message, $code = null, $data = null)
    {
        global $_ERROR;
        $res['data'] = $data;
        $res['code'] = $code;
        if (empty($message)) {
            $res['message'] = $_ERROR[$code];
        } else {
            $res['message'] = $message;
        }
        return json($res);
    }

    /**
     * 获取指定长度的任意字符串或数组
     * @param $length
     * @author luanpeng
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
    public function api_dbError($message = '数据库操作失败！', $data = null)
    {
        $res['data'] = $data;
        $res['code'] = self::DBERROR;
        $res['message'] = $message;
        return json($res);
    }

    public function api_success($message = null, $data = null)
    {
        $res['code'] = self::SUCCESS;
        $res['message'] = $message;
        if (!empty($data)) {
            $res['data'] = $data;
        }
        return json($res);
    }

    public function isrepeat($id, $Model, $key, $field, $postfield)
    {
        if ($id == null) {
            $nums = model($Model)->where($field, $postfield)->count();
            if ($nums) {
                return true;
            } else {
                return false;
            }
        } else {
            $nums = model($Model)->where($field, $postfield)->where($key, '<>', $id)->count();
            if ($nums) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function api_paras($params, $limit, $info = null)
    {
        foreach ($limit as $value) {
            if ($params[$value] == null) {
                $res['data'] = $info;
                $res['code'] = self::ACHIEVE;
                $res['message'] = '缺少参数' . $value . '！';
                return $res;
            }
        }
    }

    public function api_refuse()
    {
        return false;
    }
    /**
     * 对图片信息进行处理
     */
    protected function handleImage($modelName = 'company'){
        import('upayun.vendor.autoload',EXTEND_PATH);
        $bucketConfig = new Config('eightplate','bagepanzis','BGPZadmin2017');
        $client=new Upyun($bucketConfig);
        $list = input('post.pic/a');
        $url = array();
        foreach ($list as $key => $value){
            if($value!=''){
                $file['tmp_name'] = PUBLIC_PATH.'/file/general/'.$modelName.'/'.$value;
                $file = fopen($file['tmp_name'],'r');
                $name=time().'.jpg';
                $client->write($name,$file);
                $url[] = 'http://eightplate.b0.upaiyun.com/'.$name;

            }
            @unlink(PUBLIC_PATH.'/file/general/'.$modelName.'/'.$value);
        }
        return $url;
    }
}