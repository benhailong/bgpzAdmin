<?php
namespace app\admin\controller;

use \think\Request;
use \app\common\model\Services;
use \app\common\model\User as ModelUser;
use \app\common\model\Order as ModeOrder;
use app\admin\model\AdminAccess;

class Index extends \app\admin\controller\Base
{
    protected $res = array(
        'index' => array('css' => '', 'js' => 'echarts')
    );

    public function index()
    {

        return $this->fetch();
    }


}
