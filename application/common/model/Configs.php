<?php
/**
 * User: HDS
 * Date: 2016/7/21
 * Time: 11:23
 * Description:
 */
namespace app\common\model;
use think\Db;
class Configs extends \think\Model
{
    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];

    public function getValue($code, $value = 1)
    {
        $model = self::instance();
        $where['code'] = $code;
        if ($value == 1) {
            $res = $model->where($where)->value('value');
        } else {
            $res = $model->where($where)->value('value2');
        }
        return $res;
    }

    public function getInfo($code)
    {
        $model = self::instance();
        $where['code'] = $code;
        $res = $model->where($where)->find();
        return $res;
    }

    public function upInfo($code, $data)
    {
        $model = self::instance();
        $where['code'] = $code;
        $res = $model->where($where)->update($data);
        return $res;
    }

    public function JPYtoCNY()
    {
        $JPY = $this->getValue('huilv');
        if (isset($JPY) && $JPY > 0) {
            $huilv = $JPY / 100;
        } else {
            $huilv = config('huilv') / 100;
        }
        return $huilv;
    }

    public function CNYtoJPY()
    {
        $JPY = $this->getValue('huilv');
        if (isset($JPY) && $JPY > 0) {
            $huilv = 100 / $JPY;
        } else {
            $huilv = 100 / config('huilv');
        }
        return $huilv;
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