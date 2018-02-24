<ul class="page-sidebar-menu">
    <li class="sidebar-toggler-wrapper">
        <div class="sidebar-toggler hidden-phone">
        </div>
    </li>
    <li class="sidebar-search-wrapper">
        <form class="sidebar-search" action="#" method="POST">
            <div class="form-container">
                <!--<div class="input-box">
                    <a href="javascript:;" class="remove"></a>
                    <input type="text" placeholder="搜索..."/>
                    <input type="button" class="submit" value=""/>
                </div>-->
            </div>
        </form>
    </li>
    {__NOLAYOUT__}
    {if(!empty($menu))}
    {foreach $menu as $k=>$v}
    <li class="{if $k==1} start {/if} {if isset($v['active'])} active {/if}">
        <a href="{if empty($v['child'])} {$v['action']} {else/} javascript:void(0);{/if}">
            {if isset($v['icon_class']) } <i class="fa {$v['icon_class']}"></i> {/if}
            <span class="title">{$v['name']}</span>
            {if isset($v['active'])} <span class="selected"></span> {/if}
            {if (!empty($v['child']))} <span class="arrow {if isset($v['active'])} open {/if}"></span> {/if}
        </a>
        {if !empty($v['child'])}
            <ul class="sub-menu" {if isset($v['active'])} style="display:block;" {/if}>
                {foreach $v['child'] as $kk=>$vv}
                    <li class="{if isset($vv['active'])} open {/if}">
                        <a href="{if empty($vv['child'])} {$vv['action']} {else/} javascript:void(0);{/if}">
                            {if isset($vv['icon_class']) } <i class="fa {$vv['icon_class']}"></i> {/if}
                            <span class="title">{$vv['name']}</span>
                            {if isset($vv['active'])} <span class="selected"></span> {/if}
                            {if (!empty($vv['child']))} <span class="arrow {if isset($vv['active'])} open {/if}"></span> {/if}
                        </a>
                        {if !empty($vv['child'])}
                            <ul class="sub-menu" {if isset($vv['active'])} style="display:block;" {/if}>
                                {foreach $vv['child'] as $kkk=>$vvv}
                                    <li class="{if isset($vvv['active'])} open {/if}">
                                        <a href="{if empty($vvv['child'])} {$vvv['action']} {else/} javascript:void(0);{/if}">
                                            {if isset($vvv['icon_class']) } <i class="fa {$vvv['icon_class']}"></i> {/if}
                                            <span class="title">{$vvv['name']}</span>
                                            {if isset($vvv['active'])} <span class="selected"></span> {/if}
                                            {if (!empty($vvv['child']))} <span class="arrow {if isset($vvv['active'])} open {/if}"></span> {/if}
                                        </a>
                                        {if !empty($vvv['child'])}
                                            <ul class="sub-menu" {if isset($vvv['active'])} style="display:block;" {/if}>
                                                {foreach $vvv['child'] as $kkkk=>$vvvv}
                                            <li class="{if isset($vvvv['active'])} open {/if}">
                                                <a href="{if empty($vvvv['child'])} {$vvvv['action']} {else/} javascript:void(0);{/if}">
                                                    {if isset($vvvv['icon_class']) } <i class="fa {$vvvv['icon_class']}"></i> {/if}
                                                    <span class="title">{$vvvv['name']}</span>
                                                    {if isset($vvvv['active'])} <span class="selected"></span> {/if}
                                                    {if (!empty($vvvv['child']))} <span class="arrow {if isset($vvvv['active'])} open {/if}"></span> {/if}
                                                </a>
                                            </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}
                    </li>
                {/foreach}
            </ul>
         {/if}
     </li>
    {/foreach}
    {/if}
</ul>