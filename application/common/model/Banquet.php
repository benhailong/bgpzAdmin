<?php
/**
 * Created by PhpStorm.
 * User: 戎大富
 * Date: 2017/6/14
 * Time: 15:24
 */
namespace  app\common\model;
use \think\Db;
class Banquet extends \think\model{
    protected $table = 'z_banquet';
    public static $status =array(
        '-2'=>'请选择服务类型',
//        '0'=>'刚申请',
        '1'=>'配餐完毕',
        '2'=>'需求驳回'
    );
    /**
     * @param $pageSize
     * @param string $search
     * @param $status
     * @param string $timeMap
     * @return \think\Paginator
     * 查询私宴订单的数据
     */
    public function selectBanquet($pageSize,$search='',$status='',$timeMap=''){
        $model=self::instance();
        $where = '';
        if($search!=''){
            $where['link_phone|link_man'] = ['like',"%$search%"];
        }
        if($timeMap){
            $where['create_time'] = $timeMap;
        }

        if($status!=-2||$status!=''){
            $where['status'] = ['=',$status];
        }else{
           $where['status'] = ['gt',$status];
        }
        $list = $model->where($where)->order('id desc')->paginate($pageSize);
        $foodTypeList = $this->getFoodTypeList();
        $dictionList = $this->getDictionList();
        foreach ($list as $key=>&$vo){
            $userName = Db::name('user')->where('id','=',$vo['uid'])->field('name')->find();
            $order_no = Db::name('order')->where('id','=',$vo['order_id'])->field('no')->find();
            $vo['no'] = $order_no['no'];//平台订单号
            $vo['banquet_name'] = $userName['name'];
            //私宴配置参数的获取
            $vo['optionList'] = $this->getBanquetOption($vo['id'],$foodTypeList,$dictionList);
        }
        return $list;
    }
    private function getDictionList(){
        $dictionMap['id'] = array('gt',0);
        $field = array('tag','name');
        return  db('SysDiction')->where($dictionMap)->field($field)->select();
    }

    /**
     * 查询私宴的信息
     */
    public function findBanquetList($order_id){
        $model = self::instance();
        $where['order_id'] = ['=',$order_id];
        $list = $model->where($where)->order('id desc')->find();
        $foodTypeList = $this->getFoodTypeList();
        $dictionList = $this->getDictionList();
        $userName = Db::name('user')->where('id','=',$list['uid'])->field('name')->find();
        $list['banquet_name'] = $userName['name'];
        //私宴配置参数的获取
        $list['optionList'] = $this->getBanquetOption($list['id'],$foodTypeList,$dictionList);
        return $list;
    }
    /**
     * 获取菜品分类的列表
     */
    private function getFoodTypeList(){
        $fatherMap['tag'] = 'banquetFoodType';
        $fId = db('SysDiction')->where($fatherMap)->value('id');
        $sonMap['status'] = 1;
        $sonMap['fid'] = $fId;
        $field = array('order','content');
        $list = db('SysDictionSon')->where($sonMap)->field($field)->order('`order` asc')->select();
        return $list;
    }
    /**
     * 获取私宴相关的配置参数
     */
    private function getBanquetOption($banquetId,$foodTypeList,$dictionFList){
        $map['banquet_id'] = $banquetId;
        $optionList = db('ZBanquetOption')->where($map)->select();
        $foodTypeTagList = array();
        $otherTag = array();

        foreach ($optionList as $key=>$value){
            unset($sonArray);
            unset($foodTypeSon);
            unset($otherSon);
                $sonArray = explode('_',$value['option_tag']);
                if($sonArray[0] == 'banquetFoodType'){
                    if(isset($sonArray[1])){
                        $foodTypeSon['key'] = $sonArray[1];
                        $foodTypeSon['value'] = $value['option_value'];
                        $foodTypeTagList[] = $foodTypeSon;
                    }
                }else{
                    $otherSon['key'] = $value['option_tag'];
                    $otherSon['value'] = $value['option_value'];
                    $otherTag[] = $otherSon;
                }
        }
        $returnArray = array();
        if(count($foodTypeTagList) > 0){
            foreach ($foodTypeTagList as $key=>$value){
                foreach ($foodTypeList as $kk=>$vv){
                    if($vv['order'] == $value['key']){
                        unset($sonReturn);
                        $sonReturn['name'] = $vv['content'];
                        $sonReturn['value'] = $value['value'];
                        $returnArray[] = $sonReturn;
                    }
                }
            }
        }

        /**
         * 对其他定义参数的处理
         */
        if(count($otherTag) > 0){
            foreach ($otherTag as $key=>$value){
                if($value['key']=='foodFlavor'&&count(json_decode($value['value']))>1){
//                    if(count(json_decode($value['value'])>1)){

                        foreach (json_decode($value['value']) as $kiy=>$itiy){
                            $name =  Db::name('sys_diction_son')->where('id','=',$itiy)->value('content');
                            $returnSon['name'] = "口味";
                            $returnSon['value'] = $name;
                            $returnArray[] = $returnSon;
                        }
//                    }

                }else{
                    foreach ($dictionFList as $kk=>$vv){
                        if($vv['tag'] == $value['key']){
                            unset($returnSon);
                            if($value['value']){
                                $returnSon['name'] = $vv['name'];
                                $returnSon['value'] = $value['value'];
                                $returnArray[] = $returnSon;
                            }
                        }
                    }
                }

            }
        }
        return $returnArray;
    }
    /**
     * @var
     */
    private static $instance;
    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
