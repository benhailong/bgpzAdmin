<div id="content" class="row-fluid">
    <div class="span12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption"><i class="icon-user"></i>创建菜单</div>
            </div>
            <div class="portlet-body">
                <form id="admin_menu_info" class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">上级</label>
                                    <div class="controls">
                                        <select name="parent_id">
                                            <option value="0">作为一级菜单</option>
                                            <?php echo $select_categorys; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">名称</label>
                                    <div class="controls">
                                        <input name="name" id="name" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">路径</label>
                                    <div class="controls">
                                        <input name="action" id="action" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">图标</label>
                                    <div class="controls">
                                        <input name="icon_class" id="icon_class" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">类型</label>
                                    <div class="controls">
                                        <select name="ismenu">
                                            <option value="0">只作为权限</option>
                                            <option value="1" selected>权限 + 菜单</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="admin_menu_add" type="button" class="btn blue">确定</button>
                                    <button id="admin_menu_out" type="button" class="btn default">取消</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>