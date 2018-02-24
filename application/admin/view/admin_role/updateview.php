<div class="row-fluid">
    <div class="span12">
        <div class="tabbable tabbable-custom boxless">
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption"><i class="icon-reorder"></i>新增角色</div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form id="adminroleinfo" class="form-horizontal">
                                <h3 class="form-section">角色信息</h3>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">角色名称</label>
                                            <div class="controls">
                                                <input type="text" name="name" value="{$role['name']}"
                                                       class="m-wrap span12"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6 ">
                                        <div class="control-group">
                                            <label class="control-label">备注</label>
                                            <div class="controls">
                                                <input type="text" name="remark" value="{$role['remark']}"
                                                       class="m-wrap span12"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="hidden" name="id" value="{$role['id']}"/>
                                    <button id="adminroleupdate" type="button" class="btn blue"><i class="icon-ok"></i>
                                        确定
                                    </button>
                                    <button id="adminroleout" type="button" class="btn green">返回角色列表</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>