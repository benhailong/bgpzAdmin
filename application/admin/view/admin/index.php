<div id="content" class="row-fluid">
    <div class="span12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption"><i class="icon-user"></i>管理员列表</div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn green" href="#" data-toggle="dropdown">
                            <i class="icon-cogs"></i>&nbsp;&nbsp;操作
                            <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a id="info_delete"><i class="icon-trash"></i>删除</a></li>
                            <li><a id="info_disabled"><i class="icon-ban-circle"></i>禁用</a></li>
                            <li><a id="info_res"><i class="icon-undo"></i>恢复</a></li>
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
                        <th>角色</th>
                        <th>用户名</th>
                        <th>昵称</th>
                        <th>手机号</th>
                        <th>邮箱</th>
                        <th>最后登录IP</th>
                        <th>最后登录时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <form id="info_ser" action="">
                        {volist name='list' id='val'}
                        <tr class="odd gradeX">
                            <td>
                                {if($val.id!=1)}
                                <input type="checkbox" class="checkboxes" name="id" value="{$val.id}"/>
                                {/if}
                            </td>
                            <td>{$val.id}</td>
                            <td>{$val.b_name}</td>
                            <td>{$val.username}</td>
                            <td>{$val.nickname}</td>
                            <td>{$val.phone}</td>
                            <td>{$val.email}</td>
                            <td>{$val.last_login_ip}</td>
                            <td>{$val.last_login_time}</td>
                            <td>{if ($val.status==1)} <span class="label label-success"> {/if}
                                {if ($val.status==2)} <span class="label label-important"> {/if}
                                {$status[$val.status]}</span></td>
                            <td><a class="btn mini purple" href="/admin/save?id={$val.id}">修改</a></td>
                        </tr>
                        {/volist}
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>