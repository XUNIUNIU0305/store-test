<?php
$this->params = ['js' => 'js/employee.js', 'css' => 'css/employee.css'];
?>
<div class="admin-main-wrap">
    <!--management main content-->
    <div class="admin-management-container">
        <div class="clearfix">
            <form class="form-inline pull-left">
                <div class="input-group">
                    <input type="text" class="form-control" id="J_search_input" placeholder="输入员工姓名">
                    <span class="input-group-btn" id="J_search_btn">
                        <a class="btn"><i class="glyphicon glyphicon-search"></i></a>
                    </span>
                </div>
                <!-- /input-group -->
            </form>
            <button class="btn btn-danger btn-lg btn-round pull-right" data-toggle="modal" data-target="#apxModalManageEditRole">添加新员工</button>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-1">员工ID</div>
                        <div class="col-xs-2">姓名</div>
                        <div class="col-xs-2">手机号</div>
                        <div class="col-xs-5 text-left">备注</div>
                        <div class="col-xs-2">操作</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split" id="J_employee_list">
                        	<script type="text/template" id="J_tpl_employees">
	                        	{@each _ as it}
	                        		<li class="J_employee">
		                                <div class="col-xs-1 J_employee_id">${it.id}</div>
                                        <div class="col-xs-2 J_employee_name">${it.name}</div>
		                                <div class="col-xs-2 J_employee_mobile">${it.mobile|mobile_build}</div>
		                                <div class="col-xs-5 text-left J_employee_remarks">${it.remark}</div>
		                                <div class="col-xs-2">
		                                    <a href="" class="btn btn-warning" data-toggle="modal" data-target="#apxModalManageEditRole">修改</a>
		                                    <a href="" class="btn btn-danger"  data-toggle="modal" data-target="#apxModalAdminAlertDel">删除</a>
		                                </div>
		                            </li>
	                        	{@/each}
                        	</script>
                        </ul>
                    </div>
                    <div class="footer J_employee_page">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertDel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4 text-danger"><i class="glyphicon glyphicon-alert"></i>确定要删除该用户吗？</div>
                <div class="text-left row">
                    <div class="col-xs-8 col-xs-offset-4">姓名：<span class="J_alert_name"></span></div>
                    <div class="col-xs-6 col-xs-offset-4">手机号：<span class="J_alert_mobile"></span></div>
                    <div class="col-xs-8 col-xs-offset-4">备注：<span class="J_alert_remarks"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_dele_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-admin-manage-staff modal fade" id="apxModalManageEditRole" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">修改员工信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-1" for="employee_name">姓名：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="employee_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-1" for="employee_mobile">手机：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" maxlength="11" id="employee_mobile">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-1" for="employee_remarks">备注：</label>
                        <div class="col-xs-11">
                            <textarea class="form-control" id="employee_remarks" rows="8" placeholder="备注内容"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-danger" id="J_edit_sure">确认</button>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>