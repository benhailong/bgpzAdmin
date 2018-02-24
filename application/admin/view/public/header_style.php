<link href="/static/metronic/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

<link href="/static/metronic/css/bootstrap-responsive.min.css" rel="stylesheet"
      type="text/css"/>

<link href="/static/metronic/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

<link href="/static/metronic/css/style-metro.css" rel="stylesheet" type="text/css"/>



<link href="/static/metronic/css/style.css" rel="stylesheet" type="text/css"/>

<link href="/static/metronic/css/style-responsive.css" rel="stylesheet" type="text/css"/>

<link href="/static/metronic/css/default.css" rel="stylesheet" type="text/css"
      id="style_color"/>
<link href="/static/metronic/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="/static/metronic/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"
      type="text/css"/>

<link href="/static/css/ui-dialog.css" rel="stylesheet" type="text/css"/>
<link href="/static/css/viewer.min.css" rel="stylesheet" type="text/css"/>

<link rel="shortcut icon" href="/static/metronic/image/favicon.ico"/>
<style>
    .updatethis td {
        border-bottom: 1px solid red;
        border-top: 1px solid red;
    }

    .updatethis td:first-child {
        border-left: 1px solid red;
    }

    .updatethis td:last-child {
        border-right: 1px solid red;
    }
</style>
<?php
if (isset($res['css']) && $res['css']) {
    $res['css'] = explodeAdvanced($res['css']);//爆破去重去空
    foreach ($res['css'] as $r) {
        if (\app\admin\model\Res::$css[$r]) {
            ?>
            <link rel="stylesheet" type="text/css"
                  href="/static/<?php echo ltrim(\app\admin\model\Res::$css[$r], '/'); ?>"/>
            <?php
        }
    }
}
?>
