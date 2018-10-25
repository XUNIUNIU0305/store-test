<?php
$this->params = ['js' => 'js/department.js', 'css' => 'css/role.css'];
?>
<div class="admin-main-wrap">
    <!--management main content-->
    <div class="admin-management-container">
        <div class="clearfix">
            <button class="btn btn-danger btn-lg btn-round" data-toggle="modal" data-target="#apxModalAdminAlertEnterDepartment">添加新部门</button>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-2">序号</div>
                        <div class="col-xs-2">部门</div>
                        <div class="col-xs-6 text-left"></div>
                        <div class="col-xs-2">操作</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split" id="J_department_list">
                        	<script type="text/template" id="J_tpl_department">
                        		{@each _ as it}
                        		<li class="J_department" data-id="${it.id}">
	                                <div class="col-xs-2">${it.id}</div>
	                                <div class="col-xs-2 J_department_name">${it.name}</div>
	                                <div class="col-xs-6 text-left"></div>
	                                <div class="col-xs-2"><a href="" data-toggle="modal" data-target="#apxModalAdminDepartmentDel" class="btn btn-danger J_del_department">删除</a></div>
	                            </li>
                        		{@/each}
                        	</script>
                        </ul>
                    </div>
                    <div class="footer">
                        <div class="pull-right department_page">
                        	
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-12 text-left">关联用户</div>
                    </div>
                    <div class="iscroll_container with-header">
                        <ul class="list-unstyled has-col-8" id="J_department_users">
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDepartmentDel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <strong>删除&nbsp;<span class="high-lighted J_dele_name"></span>&nbsp;?</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger J_dele_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertDisable" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <strong>该部门关联用户，不可删除！</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger disabled">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertEnterDepartment" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <label for="department">请输入部门：</label>
                        <input type="text" class="form-control" id="department">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger J_add_department">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>