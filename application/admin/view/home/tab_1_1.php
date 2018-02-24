<div class="tab-pane row-fluid profile-account active" id="tab_1_1">
    <div class="row-fluid">
        <div class="span12">
            <div class="span3">
                <ul class="ver-inline-menu tabbable margin-bottom-10">
                    <li class="active">
                        <a data-toggle="tab" href="#tab_1-1">
                            <i class="icon-cog"></i>
                            账号信息
                        </a>
                        <span class="after"></span>
                    </li>
<!--                    <li class=""><a data-toggle="tab" href="#tab_2-2"><i-->
<!--                                class="icon-picture"></i> 修改头像</a></li>-->
                    <li class=""><a data-toggle="tab" href="#tab_3-3"><i
                                class="icon-lock"></i> 修改密码</a></li>
                </ul>
            </div>
            <div class="span9">
                <div class="tab-content">
                    <div id="tab_1-1" class="tab-pane active">
                        <div style="height: auto;" id="accordion1-1"
                             class="accordion collapse">
                            <form id="admininfo" action="#">
                                <label class="control-label">昵称</label>
                                <input type="text" value="{$curAdmin['nickname']}" name="nickname"
                                       class="m-wrap span8"/>
                                <label class="control-label">用户名</label>
                                <input type="text" value="{$curAdmin['username']}" disabled="disabled" name="username"
                                       class="m-wrap span8"/>
                                <label class="control-label">手机号</label>
                                <input type="text" value="{$curAdmin['phone']}" name="phone" class="m-wrap span8"/>
                                <label class="control-label">邮箱</label>
                                <input type="text" value="{$curAdmin['email']}" name="email" class="m-wrap span8"/>
                                <label class="control-label">部门</label>
                                <input type="text" value="{$curAdmin['department']}" name="department"
                                       class="m-wrap span8"/>
                                <label class="control-label">公司</label>
                                <input type="text" value="{$curAdmin['company']}" name="company"
                                       class="m-wrap span8"/>
                                <label class="control-label">国家</label>
                                <div class="controls">
                                    <input type="text" value="{$curAdmin['country']}" name="country"
                                           class="span8 m-wrap" style="margin: 0 auto;" data-provide="typeahead"/>
                                </div>
                                <label class="control-label">备注</label>
                                <textarea class="span8 m-wrap" name="remark"
                                          rows="3">{$curAdmin['remark']}</textarea>
                                <div class="submit-btn">
                                    <a id="admininfo_save" class="btn green">确定</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="tab_2-2" class="tab-pane">
                        <div style="height: auto;" id="accordion2-2"
                             class="accordion collapse">
                            <div class="row-fluid">
                                <div class="span6 ">
                                    <div class="control-group">
                                        <div class="controls picupload">
                                            <div class="pic_border">
                                                <!--{if(!empty($curAvatar))}
                                                <a href="javascript:undefined" title="删除"
                                                   onclick="pic.del(this,'avatar');"
                                                   class="btn btn-xs red del"><i class="fa fa-times"></i></a>
                                                {/if}-->
                                                <div class="thumbnail" style="margin-bottom:10px;">
                                                    {if(empty($curAvatar))}
                                                    <img alt=""
                                                         src="{$Think.config.web_admin_url}/file/general/avatar/avatar.jpg"/>
                                                    {else/}
                                                    <img alt=""
                                                         src="{$Think.config.web_admin_url}/file/general/avatar/{$curAvatar}"/>
                                                    {/if}
                                                    <span style="display:block;text-align:center;">&nbsp;</span>
                                                    <input type="hidden" name="pic[]" value="">
                                                    <input type="file" name="file[]" id="file_1"
                                                           class="file_input"
                                                           onchange="picUpload(this,'avatar');">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn">
                                <a id="avatar_save" class="btn green">保存</a>
                            </div>
                        </div>
                    </div>
                    <div id="tab_3-3" class="tab-pane">
                        <div style="height: auto;" id="accordion3-3"
                             class="accordion collapse">
                            <form id="adminpwd" action="#">
                                <label class="control-label">当前密码</label>
                                <input type="password" name="current_password" class="m-wrap span8"/>
                                <span></span>
                                <label class="control-label">新密码</label>
                                <input type="password" id="pass" name="new_password" class="m-wrap span8"
                                       onKeyUp="pwStrength(this.value);" onBlur="pwStrength(this.value);"/>
                                <input id="f_pass_level" type="hidden" class="form-control" value="0">
                                <span></span>
                                <div>
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
                                <label class="control-label">重复新密码</label>
                                <input type="password" name="rn_password" class="m-wrap span8"/>
                                <span></span>
                                <div class="submit-btn">
                                    <a id="adminpwd_save" class="btn green">保存</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>