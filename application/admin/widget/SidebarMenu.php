<?php
/**
 * User: HDS
 * Date: 2016/7/6
 * Time: 14:08
 * Description:
 */
namespace app\admin\widget;

class SidebarMenu extends \think\Controller
{

    public function Index()
    {
        $model = \app\admin\model\AdminMenu::instance();
        $data = array(
            'menu' => $model->menu(),
        );

        return $this->fetch('widget/sidebar', $data);
    }
}