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
use \app\admin\model\AdminRole as AdminRoleM;
use  \app\common\model\Company as CompanyModel;

class Admin extends \app\admin\controller\Base
{
    protected $res = array(
        'index' => array('css' => '1,2', 'js' => 'select2,dataTables,DT_bootstrap,table_managed'),
        'save'  => array('css' => '', 'js' => 'pwStrength'),
    );

    public function index()
    {
        $AdminM = new AdminM;
        $list = $AdminM->readData();
        $this->assign('list', $list);
        $this->assign('status', AdminM::$status);
        return $this->fetch();
    }

    public function save()
    {
        $CompanyModel = new CompanyModel();
        $field='id,name';
        $lists = $CompanyModel->selectCompany('',$field);
        $AdminM = new AdminM();
        $AdminRoleM = new AdminRoleM;
        if (Request::instance()->isPost()) {
            $id = input('post.id');
            $params['username'] = input('post.name');
            $params['nickname'] = input('post.nickname');
            $params['pass'] = input('post.password');
            $params['phone'] = input('post.phone');
            $params['email'] = input('post.email');
            $params['role_id'] = input('post.role');
            $params['status'] = input('post.status');
            $params['company_id'] = input('post.company_id');
            $isrepeat = $this->isrepeat($id, 'Admin', 'id', 'username', $params['username']);
            if ($isrepeat) {
                return $this->api_error('用户名重复');
            }
            $isrepeat = $this->isrepeat($id, 'Admin', 'id', 'phone', $params['phone']);
            if ($isrepeat) {
                return $this->api_error('手机号重复');
            }
            $isrepeat = $this->isrepeat($id, 'Admin', 'id', 'email', $params['email']);
            if ($isrepeat) {
                return $this->api_error('邮箱重复');
            }

            if ($id!='') {
                $res = $AdminM->modify($id, $params);
            } else {
                $res = $AdminM->add($params);
            }
            if ($res) {
                return $this->api_success('保存成功');
            } else {
                return $this->api_dbError();
            }
        }else{
            $id = input('get.id');
            if ($id) {
                $list = $AdminM->readOne($id);
            }
        }
        $admin_status = AdminM::$status;
        $role_status = $AdminRoleM->readShow();
        $this->assign(array(
            'list'         => isset($list) ? $list : '',
            'admin_status' => isset($admin_status) ? $admin_status : '',
            'role_status'  => isset($role_status) ? $role_status : '',
            'id'           => $id,
            'lists'=>$lists
        ));
        return $this->fetch();
    }

    public function avatar()
    {
        $data['avatar'] = input('post.path');
        $data['id'] = cache('admin')['id'];
        if ($data['avatar'] == null) {
            $res['message'] = '头像未做修改';
            $res['code'] = 102;
            return json($res);
        }
        $result = AdminM::update($data);
        if ($result) {
            $model = new AdminM;
            $model->updateCache();
            $res['message'] = '保存成功';
            $res['code'] = 2000;
        } else {
            $res['message'] = '保存失败';
            $res['code'] = 102;
        }
        return json($res);
    }

    public function set()
    {
        $ids = strtoarray(input('post.ids'));
        $act = input('post.act');
        $aModel = new AdminM;
        foreach ($ids as $key => $value) {
            $data = array(
                'id'     => $value,
                'status' => $act,
            );
            $result = $aModel->update($data);
        }
        if ($result) {
            return $this->api_success('操作成功');
        } else {
            return $this->api_dbError();
        }
    }

    public function updatepwd()
    {
        $admin = \app\admin\model\Admin::get(session('admin')['id']);
        $current_password = input('post.current_password', '', 'strip_tags');
        if (md5($current_password . $admin['size']) != $admin['pass']) {
            return $this->api_error('原密码错误');
        }
        $data['pass'] = md5(input('post.new_password', '', 'strip_tags') . $admin['size']);
        if (empty($data)) {
            return $this->api_error('无数据更新');
        }
        $data['id'] = session('admin')['id'];
        $result = \app\admin\model\Admin::update($data);
        if ($result) {
            return $this->api_success('修改密码成功');
        } else {
            return $this->api_dbError();
        }
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
        $data['id'] = session('admin')['id'];

        $result = \app\admin\model\Admin::update($data);
        if ($result) {
            $model = new \app\admin\model\Admin();
            $model->updateCache(session('admin')['id']);
            return $this->api_success('操作成功');
        } else {
            return $this->api_dbError();
        }
    }
}