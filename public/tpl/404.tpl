<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>404</title>
    <meta name="author" content="<?php echo \think\Config::get('web_admin_url'); ?>">
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="/static/404/css/style.css"/>
    <link rel="stylesheet" href="/static/404/css/base.css"/>
</head>

<body>
<div id="errorpage">
    <div class="tfans_error">
        <div class="logo"></div>
        <div class="errortans clearfix">
            <div class="e404"></div>
            <p><b>出错啦</b></p>
            <p>你访问的页面不存在</p>
            <div class="bt"><a href="<?php echo \think\Config::get('web_admin_url'); ?>">返回首页</a></div>
        </div>
    </div>
</div>
</body>
</html>