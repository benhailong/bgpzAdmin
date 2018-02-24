<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/3/28
 * Time: 11:05
 * 菜品
 */
namespace app\common\model;
use think\Db;

class Package extends \think\Model{
    protected $table='z_package';
    public static $changes =array(
        '0'=>array('name'=>'不允许','id'=>0),
        '1'=>array('name'=>'允许','id'=>1)
    );
    public static $change_Package =array(
        '-1'=>array('name'=>'删除','id'=>-1),
        '1'=>array('name'=>'正常','id'=>1),
    );

    public static $statusData = array(
        '-1'=>'被删除',
        '0'=>'申请中',
        '1'=>'申请通过',
        '2'=>'申请不通过'
    );
    public function relData($pagesize,$search='',$company=''){
        $model = self::instance();
        $where = '';
        if($company!=''){
            $where['p.company_id'] = ['=',$company];
        }
        if($search[0]!=''){
            $where['p.name'] = ['like',"%$search[0]%"];
        }
        if($search[1]==-2){
            $where['p.change'] = ['>',-2];
        }elseif ($search[1]>-2){
            $where['p.change'] = ['=',$search[1]];
        }
        if($search[2]==-2){
            $where['p.status'] = ['>',-2];
        }elseif ($search[2]>-2){
            $where['p.status'] = ['=',$search[2]];
        }
        if($search[3]==-2){
            $where['zpt.id'] = ['>',0];
        }elseif ($search[3]>-2){
            $where['zpt.id'] = ['=',$search[3]];
        }
        $list = $model->alias('p')
                      ->join('z_package_type zpt','zpt.id=p.type')
                      ->field('p.follow_num as follow_number,p.id,p.name,p.image_url,p.price,p.change,p.changePackage,p.status,p.index,p.company_index,p.company_id,zpt.name as package_name')
                      ->where($where)
                      ->order('p.id desc')
                      ->paginate($pagesize);
        foreach ($list as $key=>$vo){
            $count = Db::name('z_package_foods')->where('package_id','=',$vo['id'])->count('id');
            if($vo['company_id']==0){
                $list[$key]['company_name'] = '平台套餐';
            }else{
                $company_name = Db::name('z_company')->where('id','=',$vo['company_id'])->value('name');
                $list[$key]['company_name'] = $company_name;
            }
            $cook_img = Db::name('z_package_cook')->where('package_id','=',$vo['id'])->value('cook_img');
            if($cook_img!=''){
                $list[$key]['cook_img'] = $cook_img;
            }
            $list[$key]['num'] = $count;
        }
        return $list;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * 查询一条数据
     */
    public function readOne($id)
    {
        $Model = self::instance();
        $data = $Model
            ->find($id);
        return $data;
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