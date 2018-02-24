<?php
/**
 * User: HDS
 * Date: 2016/7/4
 * Time: 17:09
 * Description:
 */
namespace app\admin\model;

class Res extends \think\Model
{
    public static $css = array(
        1                  => 'metronic/css/select2_metro.css',
        2                  => 'metronic/css/DT_bootstrap.css',
        3                  => 'css/list.css',
        4                  => 'css/content-page.css',
        5                  => 'css/upload.css',
        6                  => 'zTree_v3/css/zTreeStyle/zTreeStyle.css',
        7                  => 'css/pdf_detail.css',
        8                  =>'css/multi-select.css',
        'datetimepicker'   => 'bootstrap-datetimepicker/css/datetimepicker.css',
        'bootstrap-select' => 'css/bootstrap-select.css',
        'searchableSelect' => 'css/jquery.searchableSelect.css',
        'ugx'              => '/ugx/AA000167181JP-rptUGXLabelA/AA000167181JP-rptUGXLabelAall_files/style.css',

    );

    public static $js = array(
        1                      => 'metronic/js/index.js',
        35                      =>'js/jquery.multi-select.js',
        'quickSearch'          =>'js/jquery.quicksearch.js',
        'list'                 => 'js/admin/list.js',
        'ajaxfileupload'       => 'js/ajaxfileupload.js',
        'select2'              => 'metronic/js/select2.min.js',
        'dataTables'           => 'metronic/js/jquery.dataTables.js',
        'DT_bootstrap'         => 'metronic/js/DT_bootstrap.js',
        'table_managed'        => 'metronic/js/table-managed.js',
        9                      => 'metronic/js/table-advanced.js',
        15                     => 'js/admin/goods/goods_add.js',
        'uploadpic'            => 'js/uploadpic.js',
        'jquery_ztree_core'    => 'zTree_v3/js/jquery.ztree.core-3.5.js',
        'jquery_ztree_excheck' => 'zTree_v3/js/jquery.ztree.excheck-3.5.js',
        'validate'             => 'jquery-validation/dist/jquery.validate.min.js',
        'pwStrength'           => 'js/admin/pwStrength.js',
        'echarts'              => 'js/echarts.js',
        'bootstrap-select'     => 'js/bootstrap-select.js',
        'searchableSelect'     => 'js/jquery.searchableSelect.js',
        'html2canvas'          => 'js/html2canvas.js',
        'ueditor_config'       => 'js/ueditor/ueditor.config_new.js',
        'ueditor_all'          => 'js/ueditor/ueditor.all.js',
        'ueditor_lang'         => 'js/ueditor/lang/zh-cn/zh-cn.js',
        36                     => 'js/admin/ugx/index.js',
        37                     => 'js/admin/ugx/ugx.js',
        38                     => 'js/admin/ugx/post.js',
        'datetimepicker'       => 'bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
        'jqprint'              => 'js/jquery.jqprint-0.3.js',
        'printarea'            => 'js/jquery.printarea.js',
        'lodop'                => 'js/lodop.js',
        'laydate'              => 'soko/laydate/laydate.js',
        'pulsate'              => 'js/jquery.pulsate.min.js',
        'template'             => 'js/template.js',
        'ueditor_config'                     =>  'js/ueditors/ueditor.config.js',
        'ueditor_all'                     =>  'js/ueditors/ueditor.all.js',
        'qrcode'                => 'js/jquery.qrcode.min.js',
        'jqNew'                 => 'js/jquery-1.8.3.min.js',



    );
    /*
     * 单例
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