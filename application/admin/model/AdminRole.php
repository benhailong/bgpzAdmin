<?php
/**
 * User: HDS
 * Date: 2016/7/4
 * Time: 17:09
 * Description:
 */
namespace app\admin\model;
class AdminRole extends \think\Model
{
    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];
    public static $status = array(
        //'-1' => '删除',
        '1' => '正常',
    );
    protected $table='admin_role';

    public function readShow()
    {
        $model = self::instance();
        $data = $model->where('status', '1')->column('name', 'id');
        return $data;
    }

    public function readData()
    {
        $model = self::instance();
        $data = $model->where('status', '>', '0')->select();
        return $data;
    }

    public function readOne($id)
    {
        $model = self::instance();
        $data = $model->where('id', $id)->where('status', '1')->select();
        return $data[0];
    }

    /**
     * 单例
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