<script>
    window.zNodes = <?php echo json_encode($menu_array)?>;
</script>
<div class="portlet box">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i>修改角色资料
        </div>
    </div>
    <form id="u_form_edit" class="form-horizontal">
        <input name="rid" id="rid" type="hidden" value="<?php echo $rid ?>">
        <ul id="treeDemo" class="ztree"></ul>
        <div class="form-actions fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-offset-3 col-md-9">
                        <button id="accessupdate" type="button" class="btn blue">确定</button>
                        <button id="accessout" type="button" class="btn default">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>