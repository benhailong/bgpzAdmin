<?php
/**
 * User: HDS
 * Date: 2016/7/4
 * Time: 17:09
 * Description:
 */
namespace app\admin\model;
class AdminAccess extends \think\Model
{
    protected $table='admin_access';
    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];
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