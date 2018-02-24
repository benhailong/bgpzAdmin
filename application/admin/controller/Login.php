<?php
namespace app\admin\controller;

use \think\Request;

class Login extends \think\Controller
{
    protected $admin;

    protected function admin()
    {
        if (is_null($this->admin)) {
            $this->admin = new \app\admin\model\Admin;
        }
        return $this->admin;
    }

    /**
     * 登陆
     */
    public function index()
    {
        $bacHomePath = url('/');
        $alreadyLogin = $this->admin()->isLogin();
//        print_r($alreadyLogin);die;
        if (Request::instance()->isGet()) {
            if ($alreadyLogin) {
                $this->redirect($bacHomePath);
            }
            return $this->fetch("login");
        } elseif (Request::instance()->isPost()) {
            $userName = input('post.username');
            $passwd = input('post.password');
            return json($alreadyLogin ? TRUE : ($this->admin()->login($userName, $passwd)));
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        $this->admin()->logout();
        $this->redirect(url('/login'));
    }
}
