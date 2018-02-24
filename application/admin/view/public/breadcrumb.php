<ul class="breadcrumb">
    <li>
        <i class="icon-home"></i>
        管理后台
        {if(!empty($bread))}
        <i class="icon-angle-right"></i>
        {/if}
    </li>
    {volist name='bread' key='key' id='val'}
    <li>{$val}
        {if($key<count($bread))}
        <i class="icon-angle-right"></i>
        {/if}
    </li>
    {/volist}
</ul>