<div id="content" class="row-fluid listcontent">
    <div class="span12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption"><i class="icon-user"></i>角色列表</div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn green" href="#" data-toggle="dropdown">
                            <i class="icon-cogs"></i>&nbsp;&nbsp;操作
                            <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a id="info_delete"><i class="icon-trash"></i>删除</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_2">
                    <thead>
                    <tr>
                        <th style="width:8px;"><input type="checkbox" class="group-checkable"
                                                      data-set="#sample_2 .checkboxes"/></th>
                        <th>ID</th>
                        <th>角色名称</th>
                        <th>备注</th>
                        <th>更新时间</th>
                        <th>状态</th>
                        <th style="width:150px;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {if(!empty($list) && count($list)>0)}
                    {volist name='list' id='val'}
                    <tr class="odd gradeX">
                        <td>{if($val.id>2)}<input type="checkbox" class="checkboxes" name="id" value="{$val.id}"/>{/if}
                        </td>
                        <td>{$val.id}</td>
                        <td>{$val.name}</td>
                        <td>{$val.remark}</td>
                        <td>{$val.update_time}</td>
                        <td>{if ($val.status==1)} <span class="label label-success"> {/if}
                                {if ($val.status==2)} <span class="label label-important"> {/if}
                                {$status[$val.status]}</span></td>
                        <td>
                            {if($val.id!=1)}
                            <a class="btn mini purple" href="/admin_role/updateview?id={$val.id}">修改</a>
                            <a class="btn mini purple" href="/admin_role/updateaccess?rid={$val.id}">权限修改</a>{/if}
                        </td>
                    </tr>
                    {/volist}
                    {else/}
                    <tr>
                        <td colspan="7"><span style="margin-left: 50px">暂无数据</span></td>
                    </tr>
                    {/if}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>