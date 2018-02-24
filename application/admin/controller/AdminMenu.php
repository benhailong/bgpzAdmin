<?php
/**
 * User: HDS
 * Date: 2016/7/7
 * Time: 17:04
 * Description:
 */
namespace app\admin\controller;

use \think\Request;
use \think\Db;
use \app\admin\model\AdminMenu as ModelAdminMenu;
use util\Tree as ExtendTree;

class AdminMenu extends \app\admin\controller\Base
{
    protected $res = array(
        'index'  => array('css' => '', 'js' => ''),
        'add'    => array('css' => '', 'js' => ''),
        'update' => array('css' => '', 'js' => '')
    );

    public function index()
    {
        $model = new ModelAdminMenu;
        $res = $model->where('status',1)->select();
        foreach ($res as $k => $r) {
            $result[$k]['id'] = $r['id'];
            $result[$k]['parentid'] = $r['parent_id'];
            $result[$k]['name'] = $r['name'];
            $result[$k]['action'] = $r['action'];
            $result[$k]['ismenu'] = $r['ismenu'] == 1 ? '是' : '<span style="color: red;">否</span>';
        }
        $tree = new ExtendTree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        foreach ($result as $r) {
            $r['str_manage'] = '<a class="btn mini blue" href="/admin_menu/add?parentid=' . $r['id'] . '">添加子菜单</a> | ';
            $r['str_manage'] .= '<a class="btn mini blue" href="/admin_menu/update?parentid=' . $r['parentid'] . '&id=' . $r['id'] . '">修改</a>';
            if ($r['id'] > 10) {
                $r['str_manage'] .= ' | <button class="btn mini red J_ajax_del" v-id="' . $r['id'] . '">删除</button> ';
            }
            $array[] = $r;
        }
        $tree->init($array);
        $str = "<tr>
        <td> \$id </td>
        <td > \$spacer\$name </td>
        <td> \$action </td>
        <td> \$ismenu </td>
        <td>\$str_manage</td>
        </tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign("categorys", $categorys);
        return $this->fetch();
    }

    public function add()
    {
        $model = new ModelAdminMenu;
        if (Request::instance()->isPost()) {
            if (input('post.parent_id')) {
                $pres = $model->where('id', input('post.parent_id'))->find();
            } else {
                $pres['ismenu'] = 1;
                $pres['path'] = '-0';
            }
            $data['name'] = input('post.name');
            $data['action'] = input('post.action');
            $data['icon_class'] = input('post.icon_class');
            $data['parent_id'] = input('post.parent_id');
            $data['ismenu'] = input('post.ismenu');
            $model->save($data);
            $insert_id =$model->id;
            if ($insert_id) {
                if (input('post.ismenu') && $pres['ismenu'] == 0) {
                    return $this->api_error('添加失败，父节点不是菜单哦！');
                }
                $data['path'] = $pres['path'] . '-' . $insert_id;
                $model->where('id', $insert_id)->update($data);
                return $this->api_success('添加成功');
            } else {
                return $this->api_dbError();
            }
        } else {
            $parentid = (int)input("get.parentid");
            $res = $model->column('id,parent_id,name,action');
            foreach ($res as $k => $r) {
                $result[$k]['id'] = $r['id'];
                $result[$k]['parentid'] = $r['parent_id'];
                $result[$k]['name'] = $r['name'];
                $result[$k]['action'] = $r['action'];
            }
            $tree = new ExtendTree;
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $tree->init($array);
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            return $this->fetch();
        }
    }

    public function update()
    {
        $model = new ModelAdminMenu;
        if (Request::instance()->isPost()) {
            $curMenu = input('post.', []);
            $pres = $model->where('id', $curMenu['parent_id'])->find();
            if (is_array($pres) && input('post.ismenu') && $pres['ismenu'] == 0) {
                return $this->api_error('添加失败，父节点不是菜单哦！');
            }
            $curMenu['path'] = $pres['path'] ? $pres['path'] . '-' . $curMenu['id'] : '-0-' . $curMenu['id'];
            $curMenu['id'] = (int)$curMenu['id'];
            $rs = $model->where('id', $curMenu['id'])->update($curMenu);
            if ($rs === false) {
                return $this->api_error('修改失败！');
            }
            $rs = $model->where('parent_id', $curMenu['id'])
                ->setField([
                    'path' => ['exp', 'CONCAT("' . $curMenu['path'] . '-", id)']
                ]);
            if ($rs === false) {
                return $this->api_error('修改失败！');
            }
            return $this->api_success('修改成功');
        } else {
            $id = (int)input("get.id");
            $parentid = (int)input("get.parentid");
            $res = $model->column('id,parent_id,name,action');
            foreach ($res as $k => $r) {
                $result[$k]['id'] = $r['id'];
                $result[$k]['parentid'] = $r['parent_id'];
                $result[$k]['name'] = $r['name'];
                $result[$k]['action'] = $r['action'];
            }
            $tree = new ExtendTree;
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $tree->init($array);
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $select_categorys = $tree->get_tree(0, $str);
            $info = $model->where('id', $id)->find();
            $this->assign("info", $info);
            $this->assign("select_categorys", $select_categorys);
            return $this->fetch();
        }
    }

    public function del()
    {
        $model = new ModelAdminMenu;
        $id = (int)input("post.id");
        if ($id < 11) {
            return $this->api_error('初始分类，不能删除！');
        }
        $res = $model->where('id', $id)->find();
        if ($res) {
            $rs = $model->where('parent_id', $id)->find();
            if (empty($rs)) {
                $data['id'] = $id;
                $data['status'] = -1;
                $rs = ModelAdminMenu::instance()->update($data);
                if ($rs) {
                    return $this->api_success('删除成功！');
                } else {
                    return $this->api_dbError();
                }
            } else {
                return $this->api_error('该分类下面还有子分类，不能删除！');
            }
        }
    }
}