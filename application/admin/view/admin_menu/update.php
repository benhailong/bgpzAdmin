<div id="content" class="row-fluid">
    <div class="span12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption"><i class="icon-user"></i>修改菜单</div>
            </div>
            <div class="portlet-body">
                <form id="admin_menu_info" class="form-horizontal">
                    <input name="id" id="id" type="hidden" class="form-control"
                           value="<?php echo $info['id']; ?>">
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
                                        <input name="name" value="<?php echo $info['name']; ?>" id="name"
                                               type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">路径</label>
                                    <div class="controls">
                                        <input name="action" value="<?php echo $info['action']; ?>" id="action"
                                               type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">图标</label>
                                    <div class="controls">
                                        <input name="icon_class" value="<?php echo $info['icon_class']; ?>"
                                               id="icon_class" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label col-md-3">类型</label>
                                    <div class="controls">
                                        <select name="ismenu">
                                            <?php if ($info['ismenu'] == 1) { ?>
                                                <option value="0">只作为权限</option>
                                                <option value="1" selected>权限 + 菜单</option>
                                            <?php } else { ?>
                                                <option value="0" selected>只作为权限</option>
                                                <option value="1">权限 + 菜单</option>
                                            <?php } ?>
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
                                    <button id="admin_menu_update" type="button" class="btn blue">确定</button>
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