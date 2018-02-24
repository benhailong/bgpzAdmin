<?php
/**
 * User: HDS
 * Date: 2016/7/4
 * Time: 17:09
 * Description:
 */
namespace app\admin\model;

use app\admin\model\AdminAccess;

class Admin extends \think\Model
{
    /*
     * $_SESSION中存放已登录管理员信息的数组键名
     */
    const SESSION_KEY = 'admin';
    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];
//    protected $table='admin';
    public static $status = array(
        //'-1' => '删除',
        '0' => '禁用',
        '1' => '正常',
    );

    /**
     * 登陆
     */
    public function login($userName, $passwd)
    {
        if ($userName && $passwd) {
            $where['username'] = $userName;
            $size = $this->where($where)->value('size');
            $where['pass'] = md5($passwd . $size);
            $userinfo = $this->where($where)->find();
            if ($userinfo) {
                if ($userinfo['status'] != 1) {
                    $re['stat'] = false;
                    $re['msg'] = '用户未审核或者被冻结！';
                } else {
                    $role_id = $userinfo['role_id'];
                    $model = new AdminAccess;
                    $show = $model->where('role_id', $role_id)->select();
                    if (!empty($show) || $userinfo['id'] == 1) {
                        $data = array('last_login_time' => date('Y-m-d H:i:s', time()), 'last_login_ip' => get_client_ip());
                        $this->where('id =' . $userinfo['id'])->setField($data);
                        session(self::SESSION_KEY, $userinfo);
                        cache(self::SESSION_KEY, $userinfo);
                        $re['stat'] = TRUE;
                    } else {
                        $re['stat'] = false;
                        $re['msg'] = '用户缺少目录权限！';
                    }
                }
            } else {
                $re['stat'] = false;
                $re['msg'] = '用户名密码错误';
            }
        } else {
            $re['stat'] = false;
            $re['msg'] = '重要参数丢失';
        }
        return $re;
    }

    /**
     * 登出
     */
    public function logout()
    {
        if ($this->isLogin()) {
            session(self::SESSION_KEY, null);
            cache(self::SESSION_KEY, null);
            cache('size', null);
        }
        return $this->isLogin() ? FALSE : TRUE;
    }

    /**
     * 获取当前登陆的管理员的信息
     */
    public function curAdmin()
    {
//        return cache(self::SESSION_KEY);
        return empty(session(self::SESSION_KEY)['id']) ? NULL : cache(self::SESSION_KEY);
    }

    /**
     * 是否登录
     * @return boolean
     */
    public function isLogin()
    {
        return is_null($this->curAdmin()) ? FALSE : TRUE;
    }

    /**
     * 跟新session
     */
    public function updateCache($id)
    {
        $where['id'] = $id;
        $userinfo = $this->where($where)->find();
        $userinfo = $userinfo->toArray();
        cache(self::SESSION_KEY, $userinfo);
    }

    public function readData()
    {
        $model = self::instance();
        $data = $model->alias('a')
            ->field('a.*,b.id as b_id,b.name as b_name')
            ->join('admin_role b', 'a.role_id = b.id')
            ->where('a.status', '>', '-1')
            ->select();
        return $data;
    }

    public function readOne($id)
    {
        $model = self::instance();
        $data = $model
            ->where('id', $id)
            ->where('status', '>', '-1')
            ->find();
        return $data;
    }

    public function add($params)
    {
        $model = self::instance();
        $size = mt_rand(0, 9999);
        $model->data([
            'username' => $params['username'],
            'nickname' => $params['nickname'],
            'pass'     => md5($params['pass'] . $size),
            'size'     => $size,
            'phone'    => $params['phone'],
            'email'    => $params['email'],
            'role_id'  => $params['role_id'],
            'status'   => $params['status'],
            'company_id'   => $params['company_id'],
        ]);

        $res = $model->save();
        return $res;
    }

    public function modify($id, $params)
    {
        $model = self::instance();
        if (!empty($params['pass'])) {
            $size = mt_rand(0, 9999);
            $data = [
                'username' => $params['username'],
                'nickname' => $params['nickname'],
                'pass'     => md5($params['pass'] . $size),
                'size'     => $size,
                'phone'    => $params['phone'],
                'email'    => $params['email'],
                'role_id'  => $params['role_id'],
            ];
        } else {
            $data = [
                'username' => $params['username'],
                'nickname' => $params['nickname'],
                'phone'    => $params['phone'],
                'email'    => $params['email'],
                'role_id'  => $params['role_id'],
            ];
        }
        $res = $model->save($data, ['id' => $id]);

        return $res;

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