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

class Cook extends \think\Model{
    protected $table='z_cook';
    public static $sex = array(
        '0'=>'女',
        '1'=>'男',
    );
    public static $types =array(
        '1'=>'厨师',
        '2'=>'茶艺师'
    );

    public static $cook_status_type = array(
        '1'=>'开始',
        '2'=>'拿菜和餐具结束',
        '3'=>'呼叫客户后',
        '4'=>'到达小区后',
        '5'=>'做菜就餐结束',
        '6'=>'收取加时费',
        '7'=>'清洁后',
        '8'=>'送还餐具',
        '9'=>'完成',
    );
    public static $examine =array(
        '1'=>'审核通过',
        '2'=>'审核不通过',
        '3'=>'尚未审核',
    );

    /**
     * 查询酒店下面的厨师信息
     */
    public function selectCook($company_id=''){
        $model=self::instance();
        $where = '';
        if($company_id!=''){
            $where['ck.company_id'] = ['=',$company_id];
        }

        $list = $model->alias('ck')
              ->join('user u','u.id=ck.uid')
              ->where($where)
              ->field('ck.id,u.name,u.phone,ck.address')
              ->select();
        return $list;
    }

    /**
     * @var查询厨师信息分页信息
     */
    public function selectCookInfo($pagesize,$search='',$company='',$field='*',$type=1){
        $model = self::instance();
        $where= '';
        if($company!=''){
            $where['ck.company_id'] = ['=',$company];
        }
        $where['ck.type'] = ['=',$type];
        if($search[0]!=''){
            $keyword = trim($search[0]);
            $where['ck.cook_name'] = ['like',"%$keyword%"];
        }
        if($search[1]==-2){
            $where['ck.status'] = ['>',-2];
        }elseif ($search[1]>-2){
            $where['ck.status'] = ['=',$search[1]];
        }
        $list = $model->alias('ck')
            ->join('user u','u.id=ck.uid')
            ->where($where)
            ->field($field)
            ->order('field(ck.status,3,1,2,0)')
            ->paginate($pagesize);
        if($company==''){
            foreach ($list as $key=>&$vo){
                if($vo['company_id']!=''){
                    $company_name = Db::name('z_company')->where('id','=',$vo['company_id'])->value('name');
                    $vo['company_name_list'] = $company_name;
                }else{
                    $vo['company_name_list'] = '';
                }
            }
        }

        return $list;
    }


    public function selectCookDetail($cookid){
        $model = self::instance();
        $list = $model->alias('ck')
                      ->join('user_id_card uic','uic.uid=ck.uid')
                      ->field('uic.card_pos,uic.card_nag,uic.realname,ck.id,ck.work_exp')
                      ->where('ck.id','=',$cookid)
                      ->find();

                return $list;
       }
//    }
    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * 查询一条数据
     */
    public function readOne($id)
    {
        $model = self::instance();
        $data = $model->alias('ck')
            ->join('user u','u.id=ck.uid')
            ->where('ck.id','=',$id)
            ->field('u.name,u.phone,u.id,u.sex,ck.id as cookId,ck.star,ck.good_at,ck.cook_avatar,ck.type,ck.cook_name')
            ->find();
        return $data;
    }

    public function selectFoodOne($where,$field){
        $model =self::instance();
        $list = $model->where($where)->field($field)->find();
        return $list;
    }
    /**
     * 查询厨师是否已经被酒店认领
     */
    public function selectCookOneInfo($uid){
        $where['u.id'] = $uid;
        $where['ck.type'] = 1;
        $model = self::instance();
        $data = $model->alias('ck')
            ->join('user u','u.id=ck.uid')
            ->where($where)
            ->field('u.name,u.phone,u.sex,ck.cookAge,ck.address,ck.star,ck.company_id,ck.id')
            ->find();
        return $data;

    }

    /**
     * @return $this
     * 查询所有厨师
     */
    public function selectCookData(){
        $where['type'] = 1;
        $where['status'] = 1;
        $model = self::instance();
        $list = $model->where($where)->field('cook_name,id')->select();
        return $list;

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