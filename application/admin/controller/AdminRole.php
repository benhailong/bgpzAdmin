<?php
/**
 * User: HDS
 * Date: 2016/7/7
 * Time: 17:04
 * Description:
 */
namespace app\admin\controller;

use app\admin\model\AdminAccess;
use \think\Request;
use \think\Db;
use \app\admin\model\AdminRole as ModelAdminRole;

class AdminRole extends \app\admin\controller\Base
{
    protected $res = array(
        'index'        => array('css' => '1,2', 'js' => 'select2,dataTables,DT_bootstrap,table_managed'),
        'addview'      => array('css' => '', 'js' => ''),
        'updateview'   => array('css' => '', 'js' => ''),
        'updateaccess' => array('css' => '6', 'js' => 'validate,jquery_ztree_core,jquery_ztree_excheck'),
    );

    public function index()
    {
        $model = new ModelAdminRole;
        $list = $model->readData();
        $this->assign('status', ModelAdminRole::$status);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addview()
    {
        return $this->fetch();
    }

    public function authview()
    {
        $id = input('get.id');
        if (!isset($id)) {
            $this->redirect('/admin_role');
        }
        $aModel = new ModelAdminRole;
        $role = $aModel->readOne($id);
        $this->assign('role', $role);
        return $this->fetch();
    }

    public function updateview()
    {
        $id = input('get.id');
        if (!isset($id)) {
            $this->redirect('/admin_role');
        }
        $aModel = new ModelAdminRole;
        $role = $aModel->readOne($id);
        $this->assign('role', $role);
        return $this->fetch();
    }

    public function add()
    {
        $data['name'] = input('post.name');
        $data['remark'] = input('post.remark');
        $aModel = new ModelAdminRole;
        $rp = $aModel->where('name', $data['name'])->select();
        if ($rp) {
            return $this->api_error('角色名已存在！');
        }
        $result = $aModel->save($data);
        if ($result) {
            return $this->api_success('操作成功');
        } else {
            return $this->api_dbError();
        }
    }

    public function update()
    {
        $data['id'] = input('post.id');
        $data['name'] = input('post.name');
        $data['remark'] = input('post.remark');
        $aModel = new ModelAdminRole;
        $rp = $aModel->where('name', $data['name'])->where('id', '<>', $data['id'])->select();
        if (count($rp) > 0) {
            return $this->api_error('角色名已存在！');
        }
        $result = $aModel->update($data);
        if ($result) {
            return $this->api_success('操作成功');
        } else {
            return $this->api_dbError();
        }
    }

    public function set()
    {
        $ids = strtoarray(input('post.ids'));
        $act = input('post.act');
        $aModel = new ModelAdminRole;
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

    public function updateaccess()
    {
        $rid = input('param.rid'); //角色ID
        $nodes = input('param.nodes'); //
        $errType = array(
            1 => '删除原来权限错误',
            2 => '增加新添加权限错误',
        );
        if ($rid) {
            $menu = db('admin_menu')->select();   //系统所有权限
            $access = db('admin_access')->where('role_id', $rid)->field('id, role_id, access_id')->select();  //我的权限
            foreach ($access as $v) {
                $o_nodes[] = $v['access_id'];
            }
            foreach ($menu as $k => $v) {
                $menu_array[$k] = array(
                    'id'   => $v['id'],
                    'pId'  => $v['parent_id'],
                    'name' => $v['name'],
                    'open' => true,
                );
                if (!empty($o_nodes)) {
                    if (in_array($v['id'], $o_nodes)) {
                        $menu_array[$k]['checked'] = true;
                    }
                }
            }
            if (Request::instance()->isPost()) {
                $node_array = explode(',', $nodes); //把已经选择的权限ID
                $node_array = array_filter($node_array);
                $node_array = array_unique($node_array);
                if (!empty($o_nodes)) {
                    $new_j_nodes = array_diff($o_nodes, $node_array);   //减掉的权限ID
                    if ($new_j_nodes) {
                        if (count($new_j_nodes) > 1) {
                            $j_nodes_str = implode(',', $new_j_nodes);
                            //删掉减掉的权限
                            $jres = db('admin_access')->where('role_id', $rid)->where('access_id', 'in', $j_nodes_str)->delete();
                        } else {
                            foreach ($new_j_nodes as $val_new_j_nodes) {
                                $jres = db('admin_access')->where('role_id', $rid)->where('access_id', $val_new_j_nodes)->delete();
                            }
                        }
                        if ($jres == false) {
                            $err = 1;
                        } else {
                            $err = 0;
                        }
                    }
                }
                if (!empty($o_nodes)) {
                    $new_z_nodes = array_diff($node_array, $o_nodes);   //新增加的权限ID
                } else {
                    $new_z_nodes = $node_array;
                }
                if ($new_z_nodes) {
                    if (count($new_z_nodes) > 1) {
                        $z_nodes_str = implode(',', $new_z_nodes);
                        $my_z_menu = db('admin_menu')->where('id', 'in', $z_nodes_str)->select();
                    } else {
                        foreach ($new_z_nodes as $v_new_z_nodes) {
                            $my_z_menu = db('admin_menu')->where('id', $v_new_z_nodes)->select();
                        }
                    }
                    foreach ($my_z_menu as $val) {
                        $data['role_id'] = $rid;
                        $data['access_id'] = $val['id'];
                        $dir = explode('/', $val['action']);
                        if (isset($dir[1])) {
                            $data['controller'] = $dir[1];
                            $data['action'] = isset($dir[2]) ? $dir[2] : 'index';
                            $data['create_time'] = time();
                            $data['update_time'] = time();
                            $insert_data[] = $data;
                        } else {
                            $data['controller'] = '';
                            $data['action'] = '';
                            $data['create_time'] = time();
                            $data['update_time'] = time();
                            $insert_data[] = $data;
                        }
                    }
                    //print_r($insert_data);exit;
                    //写入新增的权限
                    $zres = db('admin_access')->insertAll($insert_data);
                    if ($zres == false) {
                        $err = 2;
                    } else {
                        $err = 0;
                    }
                }
                //=== 写日志开始 ===
                $log_data = array(
                    '0' => date('Y-m-d H:i:s', time()),
                    '1' => session('admin')['username'],
                    '2' => Request::instance()->controller() . '/' . Request::instance()->action(),
                    '3' => isset($insert_data) ? json_encode($insert_data) : '',
                    '4' => Db::table('admin_access')->getLastSql(),
                    '5' => $err,
                );
                $this->write_admin_log($log_data);
                //=== 写日志结束 ===
                if ($err == 0) {
                    return $this->api_success('修改成功！');
                } else {
                    return $this->api_error($errType[$err]);
                }
                exit;
            }
//            $stop = [6, 7, 8, 9, 10, 11, 12];
//            foreach ($menu_array as $key => $value) {
//                if (!in_array($value['id'], $stop)) {
//                    $menu_array_re[] = $value;
//                } else {
//                }
//            }
            $this->assign('rid', $rid);
            $this->assign('access', $access);
            $this->assign('menu_array', $menu_array);
            return $this->fetch();
        } else {
            $this->error("缺少参数！");
        }
    }
}