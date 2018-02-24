<div class="header navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="#">
                <!--<img src="/static/metronic/image/title.jpg" style="width: 80px;height: 26px"/>-->
            </a>
            <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                <img src="/static/metronic/image/menu-toggler.png" alt=""/>
            </a>
            <ul class="nav pull-right">
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {if(empty($curAdmin['avatar']))}
                        <img alt="" src="{$Think.config.web_admin_url}/file/general/avatar/avatar.jpg"
                             style="width: 29px;height: 29px"/>
                        {else/}
                        <img alt="" src="{$Think.config.web_admin_url}/file/general/avatar/{:imgSize($curAvatar,50,50)}"
                             style="width: 29px;height: 29px"/>
                        {/if}
                        <span class="username">{$curAdmin['nickname']}</span>
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{$Think.config.web_admin}/admin"><i class="icon-user"></i>个人中心</a></li>
                        <li><a href="{$Think.config.web_admin}/login/logout"><i class="icon-key"></i>退出登录</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>