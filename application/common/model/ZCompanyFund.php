<?php

namespace app\common\model;
use think\Db;
class ZCompanyFund extends \think\Model {
    protected $table='z_company_fund';

    private static $instance;
    public function relData($pagesize,$search=''){
        $where = '';
        if($search[0]!=''){
            $where['b.name|b.linkstyle']=['like',"%$search[0]%"];
        }
        $field = array(
            'a.company_id as companyId',
            'a.cur_fund as cur',
            'a.withdraw_fund as withdraw',
            'a.total_fund as total',
            'a.frozen_fund as frozen',
            'b.name as name',
            'b.linkstyle as phone'
        );
        $list = $this->alias('a')
            ->join('z_company b','a.company_id = b.id','LEFT')
            ->where($where)->field($field)->paginate($pagesize);
        return $list;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * 查询一条数据
     */
    public function selectOne($id){
        $model = self::instance();
        $list = $model->find($id);
        $company_name = Db::name('z_company')->where('id','=',$list['company_id'])->value('name');
        $list['name'] = $company_name;
        return $list;
    }
    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}