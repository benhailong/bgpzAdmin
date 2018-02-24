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

class Company extends \think\Model{

    private static $instance;
    public static $is_with =array(
        '0'=>'不可以',
        '1'=>'可以'
    );
    protected  $table  = 'z_company';

    /**
     * @param $pagesize
     * @param string $search
     * @return \think\Paginator
     * 查询所有酒店列表
     */
    public function relData($pagesize,$search=''){
            $where ='';
            if($search[0]!=''){
                $where['name'] =['like',"%$search[0]%"];
            }
            $model = self::instance();
            $field = array('a.*','b.username');
            $list = $model->alias('a')
                ->join('admin b','a.id = b.company_id','left')
                ->where($where)->field($field)->order('sort desc')->paginate($pagesize);
            foreach ($list as $key=>$value){
                $list[$key]['userList'] = $this->getUMList($value['id']);
                //平台型酒店的供货商酒店的信息
                if($value['type'] == 1){
                    $list[$key]['companyLink'] = $this->getCompanyLink($value['id']);
                }
            }
            return $list;
    }
    /**
     * 获取酒店的供货酒店的列表
     */
    public function getCompanyLink($flatCompanyId){
        $map['a.flat_company_id'] = $flatCompanyId;
        $field = array('a.id as id','b.id as companyId','b.name as name'
        ,'b.linkname as linkMan','b.linkstyle as phone','b.addr as addr','a.status as status');
        $list = Db::table('z_company_company')->alias('a')
            ->join('z_company b','a.from_company_id = b.id')
            ->where($map)->field($field)->select();
        return $list;
    }
    /**
     * @param $cmId
     * @return false|\PDOStatement|string|\think\Collection
     */
    private function getUMList($cmId){
        $field = array(
          'b.nickname as name',
          'b.phone as phone',
          'a.role as role',
          'a.status as status',
          'a.id as id'
        );
        $map['a.company_id'] = $cmId;
        $list = Db::table('user_company')->alias('a')
            ->join('user b','a.uid = b.id','LEFT')
            ->where($map)->field($field)->select();
        foreach ($list as $key=>$value){
            $list[$key]['role'] = $value['role'] == 1?'厨师':'服务员';
        }
        return $list;
    }
    public function getOne($id){
        $model = self::instance();
        $where['id'] =['=',$id];
        $data = $model
            ->field('is_with')
            ->find($id);
        return $data;
    }

    /**
     * 查询酒店
     */
    public function selectCompany($where='',$field='*'){
        $model=self::instance();
        $map['status']  = ['=',1];
        $res = $model ->where($where)->where($map)->field($field)->select();
        return $res;
    }

    public function selectCompanys($where='',$field='*'){
        $model=self::instance();
        $res = $model ->where($where)->field($field)->find();
        return $res;
    }
    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}