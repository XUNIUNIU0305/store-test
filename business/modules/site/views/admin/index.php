<?php
$this->params = ['css' => 'css/admin.css', 'js' => 'js/admin.js'];
?>
<div class="business-main-wrap">
    <div class="business-admin">
        <div class="">
            <div class="business-admin-manage">
                <div class="header">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#admin" role="tab" data-toggle="tab" data-is="1">管理员</a></li>
                        <li role="presentation"><a href="#unadmin" role="tab" data-toggle="tab" data-is="0">非管理员</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="admin">
                            <div class="head">
                                <div class="form-inline">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="请输入用户ID" id="J_search_input_admin">
                                        <span class="input-group-btn">
                                        <a class="btn" id="J_search_btn_admin"><i class="glyphicon glyphicon-search"></i></a>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="business-account-list">
                                <div class="header">
                                    <span class="h3">管理员列表</span>
                                </div>
                                <div class="list-content">
                                    <div class="head clearfix row">
                                        <div class="col-xs-2 text-center">账户ID</div>
                                        <div class="col-xs-2 text-center">姓名</div>
                                        <div class="col-xs-2 text-center">手机号</div>
                                        <div class="col-xs-2 text-center">省</div>
                                        <div class="col-xs-4 text-center">管理员设置</div>
                                    </div>
                                    <div class="content">
                                        <ul class="list-unstyled row" id="J_admin_list">
                                        	<script type="text/template" id="J_tpl_admin">
                                        		{@each list as it}
                                        			<li>
		                                                <div class="col-xs-2 text-center admin_id">${it.account}</div>
		                                                <div class="col-xs-2 text-center">${it.name}</div>
		                                                <div class="col-xs-2 text-center">${it.mobile}</div>
		                                                <div class="col-xs-2 text-center text-ellipsis">${it.province}</div>
		                                                <div class="col-xs-4 text-center text-success">
		                                                    <a href="" class="btn btn-reset" data-is="${is}" data-toggle="modal" data-target="#${target}" data-id="${it.id}">${text}</a>
		                                                </div>
		                                            </li>
                                        		{@/each}
                                        	</script>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="unadmin">
                            <div class="head">
                                <div class="form-inline">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="请输入用户ID" id="J_search_input_unadmin">
                                        <span class="input-group-btn">
                                        <a class="btn" id="J_search_btn_unadmin"><i class="glyphicon glyphicon-search"></i></a>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="business-account-list">
                                <div class="header">
                                    <span class="h3">非管理员列表</span>
                                </div>
                                <div class="list-content">
                                    <div class="head clearfix row">
                                        <div class="col-xs-2 text-center">账户ID</div>
                                        <div class="col-xs-2 text-center">姓名</div>
                                        <div class="col-xs-2 text-center">手机号</div>
                                        <div class="col-xs-2 text-center">省</div>
                                        <div class="col-xs-4 text-center">管理员设置</div>
                                    </div>
                                    <div class="content">
                                        <ul class="list-unstyled row" id="J_unadmin_list">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer text-right" id="J_admin_page"></div>
            </div>
        </div>
    </div>
</div>
<!-- 设置管理员弹窗 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessAdd" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">设置管理员：<span id="unadmin_id"></span></h4>
            </div>
            <div class="modal-body">
                <div class="h4 row clearfix">
                    <div class="col-xs-3 col-xs-offset-2">选择省：</div>
                    <select class="selectpicker col-xs-4 btn-group-xs pull-left" data-width="30%" data-level="1" tabindex="-98" id="J_area_list" ></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_sure_add">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 解除管理员弹窗 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessReset" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">解除管理员:<span id="admin_id"></span></h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">确定要解除其管理员身份吗？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_sure_reset">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>