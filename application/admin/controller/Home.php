<?php
/**
 * User: HDS
 * Date: 2016/7/7
 * Time: 17:04
 * Description:
 */
namespace app\admin\controller;

use \think\Request;
use \app\admin\model\Admin as AdminM;

class Home extends \app\admin\controller\Base
{
    protected $res = array(
        'index'      => array('css' => '5', 'js' => 'ajaxfileupload,uploadpic,pwStrength'),
    );

    public function index()
    {
        return $this->fetch();
    }

    public function updatecache()
    {
        $data['nickname'] = input('post.nickname', '', 'strip_tags');
        $data['username'] = input('post.username', '', 'strip_tags');
        $data['phone'] = input('post.phone', '', 'strip_tags');
        $data['email'] = input('post.email', '', 'strip_tags');
        $data['department'] = input('post.department', '', 'strip_tags');
        $data['company'] = input('post.company', '', 'strip_tags');
        $data['country'] = input('post.country', '', 'strip_tags');
        $data['remark'] = input('post.remark', '', 'strip_tags');
        foreach ($data as $key => $value) {
            if (cache('admin')[$key] === $value || empty($value)) {
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            return $this->api_error('无数据更新');
        }
        $data['id'] = ['id'];
        $result = AdminM::update($data);
        if ($result) {
            $model = new AdminM();
            $model->updateCache();
            return $this->api_success('操作成功');
        } else {
            return $this->api_dbError();
        }
    }

    public function updatepwd()
    {
        $admin = AdminM::get(['id']);
        $current_password = input('post.current_password', '', 'strip_tags');
        if (md5($current_password) != $admin['pass']) {
            return $this->api_error('原密码错误');
        }
        $data['pass'] = md5(input('post.new_password', '', 'strip_tags'));
        if (empty($data)) {
            return $this->api_error('无数据更新');
        }
        $data['id'] = ['id'];
        $result = AdminM::update($data);
        if ($result) {
            return $this->api_success('修改密码成功');
        } else {
            return $this->api_dbError();
        }
    }
}