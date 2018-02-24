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

class Food extends \think\Model{
    protected $table='z_food';
    public static $status =array(
            '0'=>'停售',
            '1'=>'在售'
        );
    public function relData($pagesize,$search='',$company=''){
        $model = self::instance();
        $where = '';
        if($company!=''){
            $where['f.company_id'] = ['=',$company];
        }

        if($search[0]!=''){
            $where['f.name'] = ['like',"%$search[0]%"];
        }
        if($search[1]==-2){
            $where['f.status'] = ['>',-2];
        }elseif ($search[1]>-2){
            $where['f.status'] = ['=',$search[1]];
        }
        $list = $model->alias('f')
            ->join('z_food_category zfc','zfc.id=f.category_id')
            ->where($where)
            ->order('f.id desc')
            ->field('f.id,f.image_url,f.price,f.order,f.status,f.name as foodName,zfc.name')
            ->paginate($pagesize);
        return $list;
    }


    /**
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * 查询套餐的菜品详情
     */
    public function relSee($id){
        $model = self::instance();
        $food_id = Db::name('z_package_foods')->where('package_id','=',$id)->column('food_id');
        $where['f.id'] = ['in',$food_id];
        $list = $model->alias('f')
            ->join('z_food_category zfc','zfc.id=f.category_id')
            ->where($where)
            ->order('f.id desc')
            ->field('f.id,f.image_url,f.price,f.order,f.status,f.name as foodName,zfc.name')
            ->select();
        return $list;
    }
    public function relDataInfo($pagesize,$where){
        $model = self::instance();
        $list = $model->alias('f')
            ->join('z_food_category zfc','zfc.id=f.category_id')
            ->where($where)
            ->order('f.id desc')
            ->field('f.id,f.image_url,f.price,f.order,f.status,f.name as foodName,zfc.name,f.company_id')
            ->paginate($pagesize,false, [
                'query' => request()->param(),
            ]);
        return $list;
    }


    public function selectDataFood($pagesize,$search='',$company=''){
        $model = self::instance();
        if($search[2]==-2){
            if($company!=''){
                $where['f.company_id'] = ['in',$company];
            }
        }elseif ($search[2]==1){
            if($company!=''){
                $map['company_id'] = ['in',$company];
                $list_food_id_data = Db::name('z_company_food_for_flat')->where($map)->column('food_id');
                $where['f.company_id'] = ['in',$company];
                $where['f.id'] = ['in',$list_food_id_data];
            }
        }elseif ($search[2]==2){
            if($company!=''){
                $map['company_id'] = ['in',$company];
                $list_food_id = Db::name('z_company_food_for_flat')->where($map)->column('food_id');
                $where['f.company_id'] = ['in',$company];
                $where['f.id'] = ['not in',$list_food_id];
            }
        }else{
            if($company!=''){
                $where['f.company_id'] = ['in',$company];
            }
        }
        if($search[0]!=''){
            $where['f.name'] = ['like',"%$search[0]%"];
        }
        if($search[1]==-2){
            $where['f.status'] = ['>',-2];
        }elseif ($search[1]>-2){
            $where['f.status'] = ['=',$search[1]];
        }

        $list = $model->alias('f')
            ->join('z_food_category zfc','zfc.id=f.category_id')
            ->where($where)
            ->order('f.id desc')
            ->field('f.id,f.image_url,f.price,f.order,f.status,f.name as foodName,f.company_id,zfc.name')
            ->paginate($pagesize);
        foreach ($list as $key=>&$vo){
            $res = Db::name('z_company_food_for_flat')->where('food_id','=',$vo['id'])->find();

            if($res['status']==1){
                $vo['is_add'] = 1;
            }elseif ($res['status']==0){
                $vo['is_add'] = 0;
            }else{
                $vo['is_add'] = 0;
            }

        }
        return $list;

    }

    /**
     * 查询平台酒店已经添加的菜品
     */
    public function alreadySelect($where,$pagesize){
        $model = self::instance();
        $list = $model->alias('f')
            ->join('z_food_category zfc','zfc.id=f.category_id')
            ->where($where)
            ->order('f.id desc')
            ->field('f.id,f.image_url,f.price,f.order,f.status,f.name as foodName,f.company_id,zfc.name')
            ->paginate($pagesize);
        foreach ($list as $key=>&$vo){
            $res = Db::name('z_company_food_for_flat')->where('food_id','=',$vo['id'])->find();
            if($res['status']==1){
                $vo['is_add'] = 1;
            }elseif ($res['status']==0){
                $vo['is_add'] = 0;
            }else{
                $vo['is_add'] = 0;
            }

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

    public function readOne_list ($id)
    {
        $Model = self::instance();
        $data = $Model->where('category_id','=',$id)->find();
        return $data;
    }

    /**
     * @param string $company_id
     * @return false|\PDOStatement|string|\think\Collection
     * 查询本酒店所有的菜品
     */
    public function selectFood($company_id='',$field='*'){
        $model = self::instance();
        $where = '';
       if($company_id!=''){
        $where['company_id'] = ['in',$company_id];
       }
        $list = $model->where($where)->field($field)->select();
        return $list;
    }




    public function selectFoodType($company_id=''){
        $model = self::instance();
        $where = '';
        $arr =array();
        if($company_id!=''){
            $where['company_id'] = ['in',$company_id];
        }
        $list = $model->distinct('category_id')->where($where)->field('category_id')->select();

        foreach ($list as $vo){
            $arr[] = $vo['category_id'];
        }
        if(count($arr)>0){
            $data = Db::name('z_food_category')->where('id','in',$arr)->field('id,name')->select();
            return $data;
        }

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