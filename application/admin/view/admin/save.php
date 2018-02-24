<div class="row-fluid">
    <div class="span12">
        <div class="tabbable tabbable-custom boxless">
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption"><i class="icon-reorder"></i>修改管理员信息</div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form id="subform" class="form-horizontal">
                                <h3 class="form-section">管理员信息</h3>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">用户名</label>
                                            <div class="controls">
                                                <input type="text" name="name"
                                                       value="{$list['username']?$list['username']:''}"
                                                       class="m-wrap span12"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">昵称</label>
                                            <div class="controls">
                                                <input type="text" name="nickname"
                                                       value="{$list['nickname']?$list['nickname']:''}"
                                                       class="m-wrap span12"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">密码</label>
                                            <div class="controls">
                                                <input id="f_pass_level" type="hidden" class="form-control" value="0">
                                                <input type="password" name="password" onKeyUp="pwStrength(this.value);"
                                                       onBlur="pwStrength(this.value);
                                                       value=" {$list['pass']?$list['pass']:''}" class="m-wrap span12"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <table width="217" border="1" cellspacing="0" cellpadding="0"
                                                   bordercolor="#cccccc" height="30" style='display:inline'>
                                                <tr align="center" bgcolor="#eeeeee">
                                                    <td width="30%" id="strength_L">弱</td>
                                                    <td width="5%">&nbsp;</td>
                                                    <td width="30%" id="strength_M">中</td>
                                                    <td width="5%">&nbsp;</td>
                                                    <td width="30%" id="strength_H">强</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">手机号</label>
                                            <div class="controls">
                                                <input type="text" name="phone"
                                                       value="{$list['phone']?$list['phone']:''}"
                                                       class="m-wrap span12 phone"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">邮箱</label>
                                            <div class="controls">
                                                <input type="text" name="email"
                                                       value="{$list['email']?$list['email']:''}"
                                                       class="m-wrap span12"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">角色</label>
                                            <div class="controls">
                                                <select name="role" class="form-control">
                                                    {foreach name='role_status' item='va_role' key='ke_role'}
                                                    <option {if(isset($list[
                                                    'role_id']) &&
                                                    $list['role_id']==$ke_role)}selected="selected"{/if}value="{$ke_role}">{$va_role}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">所属酒店</label>
                                            <div class="controls">
                                                <select name="company_id" class="form-control">
                                                    {foreach name='lists' id='va' key='ke'}
                                                    <option value="{$va.id}">
                                                        {$va.name}
                                                    </option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">用户状态</label>
                                            <div class="controls">
                                                <select name="status" class="form-control">
                                                    {foreach name='admin_status' id='va' key='ke'}
                                                    <option {if(isset($list[
                                                    'status']) && $list[
                                                    'status']==$ke)}selected="selected"{/if}value="{$ke}">{$va}</option>
                                                    {/foreach}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="hidden" name="id" value="{$id?$id:''}">
                                    <input type="submit" class="btn blue" value="确定">
                                    <button id="out" type="button" class="btn green">返回管理员列表</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>