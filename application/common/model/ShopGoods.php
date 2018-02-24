<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/4/24
 * Time: 13:40
 */
namespace app\common\model;
use \think\Db;
class ShopGoods extends \think\model{
    protected $table ='shop_goods';
    public static $post_type =array(
       '1'=>'包邮',
        '2'=>'全球购'
    );
    public static $is_show =array(
        '0'=>'不展示',
        '1'=>'展示'
    );
    public static $location =array(
        '0'=>'不推荐',
        '1'=>'置顶推荐',
        '2'=>'列表推荐'
    );
    public function selectShopGoods($pagesize,$field='*',$search){
        $model =self::instance();
        $where = '';
//        $where['sc.flat']=1;
        if($search[0]!=''){
            $where['sg.name|sg.origin'] = ['like',"%$search[0]%"];
        }
        if($search[1]==-2){
            $where['sg.status'] =['>',-2];
        }elseif ($search[1]>-2){
            $where['sg.status'] = ['=',$search[1]];
        }

        if($search[2]==-2){
            $where['sg.location'] =['>',-2];
        }elseif ($search[2]>-2){
            $where['sg.location'] = ['=',$search[2]];
        }

        if($search[3]==-2){
            $where['sc.client'] =['>',-2];
        }elseif ($search[3]>-2){
            $where['sc.client'] =['=',$search[3]];
        }

        $list =$model->alias('sg')
            ->join('shop_category sc','sc.id=sg.category_id')
            ->where($where)
            ->order('sg.id desc')
            ->field($field)
            ->paginate($pagesize);

        return $list;
    }

    public function readOne($id){
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