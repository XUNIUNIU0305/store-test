<?php
$this->params = ['js' => 'js/role.js', 'css' => 'css/role.css'];
?>
<!-- main area start -->
<div class="admin-main-wrap">
	 <!-- Nav tabs -->
	<div class="admin-role-edit">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#userEdit" role="tab" data-toggle="tab">账户编辑</a></li>
			<li role="presentation"><a href="#powerEdit" role="tab" data-toggle="tab">权限编辑</a></li>
		</ul>
		<div class="tab-content">
		    <div role="tabpanel" class="tab-pane fade in active" id="userEdit">
		    	<!--management main content-->
		        <div class="admin-management-container">
		            <div class="clearfix">
		                <form class="form-inline pull-left">
		                    <div class="form-group">
		                        <label>部门：</label>
		                        <select class="form-control" id="J_department_select">
		                            <option value="-1">全部</option>
		                        </select>
		                    </div>
		                    <div class="form-group">
		                        <label>身份／角色：</label>
		                        <select class="form-control" id="J_role_select">
		                            <option value="-1">全部</option>
		                        </select>
		                    </div>

		                    <div class="input-group">
		                        <input type="text" class="form-control" placeholder="请输入姓名" id="J_search_input">
		                        <span class="input-group-btn">
		                            <a class="btn" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></a>
		                        </span>
		                    </div>
		                    <!-- /input-group -->
		                </form>
		                <button class="btn btn-danger btn-lg btn-round pull-right" data-toggle="modal" data-target="#apxModalManageRoleAdd">添加新用户</button>
		            </div>
		            <div class="row">
		                <div class="col-xs-12">
		                    <div class="admin-management-panel">
		                        <div class="header">
		                            <div class="col-xs-3">
		                                <div class="row">
		                                    <div class="col-xs-4">账户</div>
		                                    <div class="col-xs-4">姓名</div>
		                                    <div class="col-xs-4">部门</div>
		                                </div>
		                            </div>
		                            <div class="col-xs-7 text-left">
		                                <div class="row">
		                                    <div class="col-xs-2">手机</div>
		                                    <div class="col-xs-3">邮箱</div>
		                                    <div class="col-xs-2">身份／角色</div>
		                                </div>
		                            </div>
		                            <div class="col-xs-2">操作</div>
		                        </div>
		                        <div class="iscroll_container with-header with-footer">
		                            <ul class="list-unstyled dashed-split" id="J_user_box">
		                            	<script type="text/template" id="J_tpl_user_list">
		                            		{@each _ as it, index}
		                            			<li class="J_user">
				                                    <div class="col-xs-3">
				                                        <div class="row J_user_id" data-id="${it.id}">
				                                            <div class="col-xs-4">${it.account}</div>
				                                            <div class="col-xs-4 J_user_name">${it.name}</div>
				                                            <div class="col-xs-4 J_user_department" data-id="${it.admin_department_id}">${it.department_name}</div>
				                                        </div>
				                                    </div>
				                                    <div class="col-xs-7 text-left">
				                                        <div class="row">
				                                            <div class="col-xs-2 J_user_mobile">${it.mobile}</div>
				                                            <div class="col-xs-3 J_user_email">${it.email}</div>
				                                            <div class="col-xs-6 J_user_role" data-id="${it.roles_name|nameID_build}">${it.roles_name|name_build}</div>
				                                        </div>
				                                    </div>
				                                    <div class="col-xs-2">
				                                        <a href="#" data-toggle="modal" data-target="#apxModalManageRoleAdd" class="btn btn-warning">修改</a>
				                                        <a href="#" data-toggle="modal" data-target="#apxModalAdminDelUser" class="btn btn-danger">删除</a>
				                                    </div>
				                                </li>
		                            		{@/each}
		                            	</script>
		                            </ul>
		                        </div>
		                        <div class="footer">
		                        	<div class="pull-right J_user_page"></div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div role="tabpanel" class="tab-pane fade" id="powerEdit">
		    	
		        <!--management main content-->
		        <div class="admin-management-container">
		            <div class="clearfix">
		                <button class="btn btn-danger btn-lg btn-round" data-toggle="modal" data-target="#apxModalAdminAlertEnterRole">添加新角色</button>
		            </div>
		            <div class="row">
		                <div class="col-xs-6">
		                    <div class="admin-management-panel">
		                        <div class="header">
		                            <div class="col-xs-2">ID</div>
		                            <div class="col-xs-2">身份／角色</div>
		                            <div class="col-xs-6 text-left"></div>
		                            <div class="col-xs-2">操作</div>
		                        </div>
		                        <div class="iscroll_container with-header with-footer">
		                            <ul class="list-unstyled dashed-split" id="J_role_list">
		                            	<script type="text/template" id="J_tpl_role">
		                            		{@each _ as it}
			                            		<li data-id="${it.id}" class="J_role">
				                                    <div class="col-xs-2">${it.id}</div>
				                                    <div class="col-xs-2 J_role_name">${it.role_name}</div>
				                                    <div class="col-xs-6 text-left"></div>
				                                    <div class="col-xs-2 J_role_dele_one"><a href="" data-toggle="modal" data-target="#apxModalAdminDelRole" class="btn btn-danger">删除</a></div>
				                                </li>
			                                {@/each}
		                            	</script>
		                            </ul>
		                        </div>
		                        <div class="footer">
		                        	<div class="pull-right powerPage"></div>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-xs-6">
		                    <!--修改身份-->
		                    <div class="admin-management-panel admin-manage-role">
		                        <div class="header">
		                            <div class="col-xs-10 text-left">身份／角色 名称</div>
		                        </div>
		                        <div class="iscroll_container with-header">
		                            <ul class="list-unstyled has-col-8" id="J_power_list">
		                            	<script type="text/template" id="J_tpl_allPower">
		                            		{@each _ as it}
		                            			<li class="title">
													<div class="checkbox">
				                                        <label><input type="checkbox" value="${it.id}">${it.name}：</label>
				                                    </div>
				                                </li>
				                                {@each it.sub as sub}
				                                	<li>
					                                    <div class="checkbox">
					                                        <label><input type="checkbox" value="${sub.id}">${sub.name}</label>
					                                    </div>
					                                </li>
				                                {@/each}
		                            		{@/each}
		                            	</script>
		                            </ul>
		                        </div>
		                    </div>
		                    <!--关联用户-->
		                    <div class="admin-management-panel">
		                        <div class="header">
		                            <div class="col-xs-12 text-left">关联用户</div>
		                        </div>
		                        <div class="iscroll_container with-header">
		                            <ul class="list-unstyled has-col-8" id="J_role_users">

		                            </ul>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>

<!-- main area start -->

<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDelUser" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4">确定要删除该用户吗？</div>
                <div class="text-left row">
                    <div class="col-xs-8 col-xs-offset-4 J_name">姓名：<span>九大爷</span></div>
                    <div class="col-xs-8 col-xs-offset-4 J_department">部门：<span>客服部</span></div>
                    <div class="col-xs-8 col-xs-offset-4 J_mobile">电话：<span>13012345678</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger J_dele_user">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 添加 -->
<div class="apx-modal-admin-manage-role modal fade" id="apxModalManageRoleAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">添加用户</h4>
            </div>
            <div class="modal-body">
                <div class="form-inline">
                    <div class="form-group form-group-sm">
                        <label for="username">姓名：</label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="usermobile">手机：</label>
                        <input type="text" class="form-control" maxlength="11" id="usermobile">
                    </div>
                    <div class="form-group form-group-sm has-feedback">
                        <label class="control-label" for="pwd_1">设置密码：</label>
                        <input type="password" maxlength="20" class="form-control" id="pwd_1">
                        <span class="glyphicon glyphicon-eye-open form-control-feedback" onclick="$(this).toggleClass('glyphicon-eye-close');$(this).siblings('input').attr('type')==='password'?$(this).siblings('input').attr('type','text'):$(this).siblings('input').attr('type', 'password')"></span>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="department">部门：</label>
                        <select id="department" class="form-control">
                            <option value="-1">全部</option>
                        </select>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="useremail">邮箱：</label>
                        <input type="text" class="form-control" id="useremail">
                    </div>
                    <div class="form-group form-group-sm has-feedback">
                        <label class="control-label" for="pwd_2">确认密码：</label>
                        <input type="password" maxlength="20" class="form-control" id="pwd_2">
                        <span class="glyphicon glyphicon-eye-open form-control-feedback" onclick="$(this).toggleClass('glyphicon-eye-close');$(this).siblings('input').attr('type')==='password'?$(this).siblings('input').attr('type','text'):$(this).siblings('input').attr('type', 'password')"></span>
                    </div>
                </div>
                <div class="form">
                    <div class="form-group">
                        <label>身份／角色</label>
                    </div>
                    <div class="form-inline" id="J_alert_role_list">
                    </div>
                </div>
                <div class="form">
                    <div class="form-group">
                        <label>权限</label>
                    </div>
                    <div id="J_alert_power_list">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a  class="btn btn btn-danger" id="J_user_edit_sure">确认</a>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDelRole" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <strong>确认删除 &nbsp;<span class="high-lighted J_dele_name"></span> ?</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger J_dele_role">确认</button>
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
                <strong>该身份／权限关联用户，不可删除！</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger disabled">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 添加角色提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertEnterRole" tabindex="-1">
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
                        <label for="name">请输入身份／角色名：</label>
                        <input type="text" class="form-control" id="newName">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_add_role">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
