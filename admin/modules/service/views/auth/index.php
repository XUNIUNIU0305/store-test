<?php
$this->params = ['js' => 'js/auth-index.js', 'css' => 'css/auth-index.css'];
?>
<div class="admin-main-wrap">
    <div class="admin-audit-list-container">
        <!--nav-->
        <div class="admin-audit-list-title bg-danger">
            <ul class="nav nav-tabs">
                <li class="pull-left">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="按手机号或账号搜索" id="J_search_input" maxlength="11">
                        <span class="input-group-btn">
                            <button class="btn btn-default J_search_btn" type="button" id="J_search_text"><i class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>
                </li>
                <li class="pull-right" id="J_search_time">
                    时间排序 &nbsp;&nbsp;
                    <button class="btn btn-danger btn-xs active J_search_btn" data-id="desc">由近到远</button>
                    <button class="btn btn-danger btn-xs J_search_btn" data-id="asc">由远到近</button>
                </li>
                <li class="pull-right" id="J_search_status">
                    审核状态 &nbsp;&nbsp;
                    <button class="btn btn-danger btn-xs J_search_btn" data-id="1,3">未递交</button>
                    <button class="btn btn-danger btn-xs active J_search_btn" data-id="2">未审核</button>
                    <button class="btn btn-danger btn-xs J_search_btn" data-id="4,5">已审核</button>
                	&nbsp;&nbsp;&nbsp;&nbsp;
                </li>
            </ul>
        </div>
        <div class="auth-check-box hidden">
            <div class="check-all">
                <label for="">全选</label>
                <input type="checkbox" class="J_check_all" name="" value="">
            </div>
            <div class="auth-btn">
                <span class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminAuthAll">批量通过</span>
            </div>
        </div>
        <!--table-->
        <table class="table table-hover table-panel table-fix text-center dashed">
            <thead>
                <tr>
                    <th width="50">选择</th>
                    <th width="120">门店账号</th>
                    <th width="80">是否注册</th>
                    <th width="120">注册手机号</th>
                    <th width="175">申请人手机号</th>
                    <th width="120">申请人</th>
                    <th width="175">提交时间</th>
                    <th width="140">邀请二维码ID</th>
                    <th width="140">审核状态</th>
                    <th>邀请人</th>
                    <th width="140">快捷操作</th>
                </tr>
            </thead>
            <tbody id="J_auth_list">
            	<script type="text/template" id="J_tpl_list">
            		{@each auth as it}
                    <tr data-link="/service/auth/detail?id=${it.id}&p_id=${it.pid}">
                        {@if it.status != 4 && it.status != 5}
                        <td class="no-jump"><input type="checkbox" name="single" data-id="${it.id}" value=""></td>
                        {@else}
                        <td></td>
                        {@/if}
                        <td>${it.account}</td>
                        {@if it.id == ''}
                        <td class="text-danger">否</td>
                        {@else}
                        <td>是</td>
                        {@/if}
	        	    	<td>${it.register_mobile}</td>
	        	    	<td>${it.contact_mobile}</td>
	        	    	<td>${it.contact_name}</td>
	        	    	<td>${it.submit_time}</td>
	        	    	<td>${it.promoter_id.id}</td>
	        	    	<td>${it.status|status}</td>
                        <td>${it.promoter_id.invite_user}</td>
                        {@if it.status != 4 && it.status != 5 && it.id != ''}
                        <td class="text-success no-jump" data-id="${it.id}" data-toggle="modal" data-target="#apxModalAdminAuth">立即通过</td>
                        {@else}
                        <td></td>
                        {@/if}
	            	</tr>
            		{@/each}
            	</script>
            </tbody>
        </table>
        <div class="auth-check-box hidden">
            <div class="check-all">
                <label for="">全选</label>
                <input type="checkbox" class="J_check_all" name="" value="">
            </div>
            <div class="auth-btn">
                <span class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminAuthAll">批量通过</span>
            </div>
        </div>
        <!-- pagination -->
        <div class="text-right" id="J_auth_page"></div>
    </div>
</div>
<!-- confirm -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminAuth" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <i class="glyphicon glyphicon-warning-sign"></i>
                <span class="J_confrim_content">确定要通过吗？</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_auth_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- confirm -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminAuthAll" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <i class="glyphicon glyphicon-warning-sign"></i>
                <span class="J_confrim_content">确定要通过吗？</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_auth_sure_multi">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>