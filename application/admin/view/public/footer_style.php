<!-- BEGIN CORE PLUGINS -->
<script src="/static/metronic/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/static/metronic/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/static/metronic/js/jquery-ui-1.10.1.custom.min.js"
        type="text/javascript"></script>
<script src="/static/metronic/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="/static/metronic/js/excanvas.min.js"></script>
<script src="/static/metronic/js/respond.min.js"></script>
<![endif]-->
<script src="/static/metronic/js/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/static/metronic/js/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/static/metronic/js/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/static/metronic/js/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="/static/metronic/js/app.js" type="text/javascript"></script>
<script src="/static/js/dialog-min.js" type="text/javascript"></script>
<script src="/static/js/jquery.validate.js" type="text/javascript"></script>
<script src="/static/js/validate-methods.js" type="text/javascript"></script>
<script src="/static/js/layer/layer.js" type="text/javascript"></script>
<script src="/help.js" type="text/javascript"></script>
<script src="/static/js/admin/common.js" type="text/javascript"></script>
<script type="text/javascript" src="http://api.map.baidu.com/getscript?v=1.2&ak=293YYtd7X13WphdOyloySdP5M11neeO9&services=&t=20130716024057"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script>
    $(function() {
        $('#pageContainerForAll').viewer({
            url: 'src'
        });
    });
</script>
<?php
if (isset($res['js']) && $res['js']) {
    $define = explodeAdvanced($res['js']);
    foreach ($define as $d) {
        if (\app\admin\model\Res::$js[$d]) {
            ?>
            <script type="text/javascript"
                    src="/static/<?php echo ltrim(\app\admin\model\Res::$js[$d], '/'); ?>"
            ></script>
            <?php
        }
    }
}
$has = static_name(PUBLIC_PATH . '/static/js/');
if ($has) {
    ?>
    <script type="text/javascript"
            src="/static/js/<?php echo $has . '.js'; ?>"
    ></script>
    <?php
}
?>
