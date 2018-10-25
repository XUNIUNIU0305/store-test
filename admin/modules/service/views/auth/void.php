<?php
$this->params = ['js' => 'js/auth-void.js', 'css' => 'css/auth-void.css'];
?>
<div class="admin-main-wrap">
    <div class="admin-audit-cancel-container">
        <!--nav-->
        <div class="admin-audit-cancel-title bg-danger">
            <ul class="nav nav-tabs">
                <li class="pull-left">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="按手机号或账号搜索" id="J_search_input">
                        <span class="input-group-btn" id="J_search_btn">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>
                </li>
                <li class="pull-right hidden" id="J_sort_time">
                    时间排序 &nbsp;&nbsp;
                    <button class="btn btn-danger btn-xs active" data-id="desc">由近到远</button>
                    <button class="btn btn-danger btn-xs" data-id="asc">由远到近</button>
                </li>
                <li class="pull-right" id="J_sort_status">
                    操作状态 &nbsp;&nbsp;
                    <button class="btn btn-danger btn-xs active" data-id="0">未操作</button>
                    <button class="btn btn-danger btn-xs" data-id="1">已操作</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                </li>
            </ul>
        </div>
        <div class="cancel-list" id="J_cancel_list">
        	<script type="text/template" id="J_tpl_list">
        		{@each auth as it}
					<div class="item">
		                <div class="item-title clearfix">
		                    <div class="col-xs-2">门店账号：<span>${it.account}</span></div>
		                    <div class="col-xs-3">注册手机号：<span>${it.register_mobile}</span></div>
		                </div>
		                <div class="item-info clearfix">
		                    <div class="col-xs-4">申请人：<span>${it.contact_name}</span></div>
		                    <div class="col-xs-4">邀请人：<span>${it.promoter_id.invite_user}</span></div>
		                    <div class="col-xs-4">商户单号：<span class="J_copy_no">${it.pay.out_trade_no}</span></div>
		                    <div class="col-xs-4">申请人手机号：<span>${it.contact_mobile}</span></div>
		                    <div class="col-xs-4">邀请人电话：<span>${it.promoter_id.invite_mobile}</span></div>
		                    <div class="col-xs-4">退款单号：<span>${it.refund_number}</span></div>
		                    <div class="col-xs-4">提交时间：<span>${it.submit_time}</span></div>
		                    <div class="col-xs-4">邀请二维码ID：<span>${it.promoter_id.id}</span></div>
		                    <div class="col-xs-4">微信支付单号：<span>${it.pay.transaction_id}</span></div>
		                </div>
		                <div class="btn-box clearfix">
		                	{@if it.refund_number == ''}
		                    	<span class="btn pull-right" data-toggle="modal" data-target="#modalRefund" data-code="${it.pid}">操作</span>
		                	{@/if}
		                    <span class="btn pull-right J_copy_btn">复制商户单号</span>
		                </div>
		            </div>
        		{@/each}
        	</script>
        </div>
        <!-- pagination -->
        <div class="text-right" id="J_cancel_page"></div>
    </div>
</div>
<!-- 退款单号弹窗 -->
<div class="apx-modal-admin-manage-staff modal fade" id="modalRefund" tabindex="-1">
	<div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
	            <a type="button" class="close" data-dismiss="modal">
	                <span aria-hidden="true">&times;</span>
	                <span class="sr-only">Close</span></a>
	            <h4 class="modal-title">输入退款单号</h4>
	        </div>
	        <div class="modal-body">
                <form class="form-horizontal">
                	<h2 style="text-align: center;margin-bottom: 20px; font-size: 20px;">如果已进行退款操作，请输入退款单号</h2>
                    <div class="form-group">
                        <label for="logisticSeries" class="col-xs-3 text-right control-label">退款单号：</label>
                        <div class="col-xs-8 cash-after">
                            <input type="text" id="J_refund_number" placeholder="" maxlength="50" class="form-control" value="">
                        </div>
                    </div>
                </form>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-lg btn-danger" id="J_sure_btn">确认</button>
	            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
	        </div>
	    </div>
	</div>
</div>