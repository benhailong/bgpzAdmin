<?php
/**
 * User: HDS
 * Date: 2016/7/4
 * Time: 17:10
 * Description:
 */
namespace app\admin\model;

use \think\Request;

class AdminMenu extends \think\Model
{
    const PATH_SEPARATOR = '-';//t_sidebar_menu.path所用的分隔符
    protected $_scope = array(
        'default' => array(
            'field' => '*',
            'order' => 'path ASC'
        ),
    );
    protected $table='admin_menu';

    /*
     * 后台菜单组成的tree
     */
    protected $menu;

    public function menu()
    {
        $this->menu = $this->where('ismenu = 1 AND status = 1')->order('sort asc')->select();
        if (session('admin')['id'] != 1) {
            $role_id = db('admin')->where('id', session('admin')['id'])->value('role_id');
            $access = db('admin_access')->where('role_id', $role_id)->column('access_id');
            foreach ($this->menu as $key => $value) {
                if (!in_array($value['id'], $access)) {
                    unset($this->menu[$key]);
                }
            }
        }
        $this->menu = chgKey($this->menu, 'id');//id作为主键
        $array = ['平台酒店添加菜品','供货的订单'];
        foreach ($this->menu as $ky => $val) {
            if (in_array($val['name'], $array)) {
                unset($this->menu[$ky]);
            }
        }
        $this->active();
        $res = $this->tree($this->menu, 0);
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $data[$key] = $res[$key]->toArray();
            }
            $data = $this->orderpath($data);
        } else {
            $data = null;
        }
        return $data;
    }

    public function orderpath($data)
    {
        foreach ($data as &$val) {
            if (isset($val['child']) && is_array($val['child'])) {
                if (count($val['child']) > 1) {
                    $val['child'] = $this->arraySortByKey($val['child'], 'path');
                }
                foreach ($val['child'] as &$v) {
                    if (isset($v['child']) && is_array($v['child'])) {
                        $v = $this->orderpath($v);
                    }
                }
            }
        }
        return $data;
    }

    public function arraySortByKey(array $array, $key, $asc = true)
    {
        //dump($array);
        $result = array();
        // 整理出准备排序的数组
        foreach ($array as $k => $v) {
            $ky = isset($v[$key]) ? $this->epath($v[$key]) : '';
            if ($ky != '') {
                $values[$ky] = $v;
            }
        }
        // 对需要排序键值进行排序
        $asc ? ksort($values) : krsort($values);
        return array_values($values);
    }

    public function epath($path)
    {
        $a = explode(self::PATH_SEPARATOR, $path);
        $num = count($a) - 1;
        //dump($a);
        return $a[$num];
    }

    /**
     * 将当前菜单所有的祖先都置为打开状态
     * @param array $menu
     * @return array
     */
    protected function active()
    {
        $controller = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', Request::instance()->controller()));
        $action = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', Request::instance()->action()));
        $actionLowercase = abbrAction('/' . $controller . '/' . $action);
        foreach ($this->menu as &$m) {
            $a = explode(self::PATH_SEPARATOR, trim($m['path']));
            array_pop($a);
            $m['parents'] = $a;
            //确定当前菜单是否是当前页面
            if ($m['action'] && (abbrAction($m['action']) == $actionLowercase)) {
                $m['active'] = TRUE;
                foreach ($m['parents'] as $k) {
                    if (isset($this->menu[$k])) {
                        $this->menu[$k]['active'] = TRUE;
                        $this->curMenu[] = $this->menu[$k];
                    }
                }
                $this->curMenu[] = $m;
            }
        }
        if ($actionLowercase == '') {
            $this->menu[1]['active'] = TRUE;
            $this->curMenu[] = $this->menu[1];
        }
        return;
    }

    /**
     * 构建菜单的树
     */
    protected function tree($data, $pId)
    {
        $tree = array();
        foreach ($data as $k => $v) {
            if (isset($v['parent_id']) && ($v['parent_id'] == $pId)) {
                $v['child'] = $this->tree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /*
     * 当前菜单以及其所有的祖先菜单(用于后台面包屑)
    */
    protected $curMenu;

    public function curMenu()
    {
        if (is_null($this->curMenu)) {
            $this->menu();
        }
        return $this->curMenu;
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

function abbrAction($str)
{
    $search = array('/' . strtolower(config('DEFAULT_CONTROLLER')), '/' . strtolower(config('DEFAULT_ACTION')));
    return $str = trim(str_replace($search, '', strtolower($str)));
}