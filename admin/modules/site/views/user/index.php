<?php
$this->params = ['js' => 'js/user.js', 'css' => 'css/user.css'];
?>
<div class="admin-main-wrap">
	<!--management main content-->
	<div class="admin-management-container">
		<div class="clearfix">
			<label>用户管理</label>
			<input type="text" id="J_search" placeholder="请输入用户账号或手机号" />
			<button class="" id="btn-search"><i class="glyphicon glyphicon-search"></i></button>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<div class="admin-management-panel">
					<div class="header">
						<div class="col-xs-12 text-left">用户信息</div>
					</div>
					<div class="iscroll_container with-header with-footer" style="touch-action: none;">
						<ul class="list-unstyled has-col-8 list-margin" id="J_department_users" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
							<li><label class="label-text-rt">账户头像：</label><img /></li>
							<li>
							    <label class="label-text-rt">账号：</label>
							    <label id="account-code"></label>
							</li>
							<li>
							    <label class="label-text-rt">账号余额：</label>
							    <label id="user_balance"></label>
							</li>
							<li>
							    <label class="label-text-rt">账号状态：</label>
							    <label id="user_status"></label>
							</li>
							<li>
							    <label class="label-text-rt">店铺名称：</label>
							    <label id="shop-name"></label>
							</li>
							<li>
							    <label class="label-text-rt">账户昵称：</label>
							    <label id="user-name"></label>
							</li>
							<li>
							    <label class="label-text-rt">用户ID：</label>
							    <label id="user-id"></label>
							</li>
							<li>
							    <label class="label-text-rt">业务区域：</label>
							    <label id="l-operator"></label>
							</li>
							<li>
							    <label class="label-text-rt">行政区域：</label>
							    <label id="l-store"></label>
							</li>
							<li>
							    <label class="label-text-rt">手机号：</label>
							    <label id="l-mobile"></label>
							</li>
							<li>
							    <label class="label-text-rt">绑定微信：</label>
							    <label id="l-wechat"></label>
							</li>
							<li>
							    <label class="label-text-rt">用户等级：</label>
							    <label id="l-level"></label>
							</li>
						</ul>
						<div class="iScrollVerticalScrollbar iScrollLoneScrollbar" style="overflow: hidden; pointer-events: none;">
							<div class="iScrollIndicator" style="transition-duration: 0ms; display: none; height: 658px; transform: translate(0px, 0px) translateZ(0px); transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);"></div>
						</div>
					</div>
					<div class="footer">
                        <button class="btn btn-sm btn-danger J_update_btn" disabled data-toggle="modal" data-target="#apxModalAdminAlertUser" data-type="1">重置密码</button>
                        <button class="btn btn-sm btn-danger J_remove_account" disabled data-toggle="modal" data-target="#apxModalAdminAlertUser" data-type="2">封停账号</button>
                        <button class="btn btn-sm btn-danger J_remove_mobile" disabled data-toggle="modal" data-target="#apxModalAdminAlertUser" data-type="3">解绑手机号</button>
                        <button class="btn btn-sm btn-danger J_up_level" disabled data-toggle="modal" data-target="#apxModalAdminAlertUser" data-type="4">提高账户等级</button>
                        <button class="btn btn-sm btn-danger J_down_level" disabled data-toggle="modal" data-target="#apxModalAdminAlertUser" data-type="5">降低账户等级</button>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="admin-management-panel">
					<!--<div class="header">
						<div class="col-xs-12 text-left">消费记录</div>
					</div>-->
					<div class="header header-rt">
					    <div class="col-xs-12 J_header_rt">消费记录</div>
					    <div class="col-xs-12">
                            <div class="col-xs-4">商品</div>
                            <div class="col-xs-2">数量</div>
                            <div class="col-xs-2">金额</div>
                            <div class="col-xs-2">订单状态</div>
                            <div class="col-xs-2">付款时间</div>
						</div>
					</div>
					<div class="iscroll_container with-header with-footer with-header-rt" style="touch-action: none;">
						<ul class="list-unstyled dashed-split" id="J_department_list" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
							<script type="text/template" id="J_tpl_order">
                                {@each _.orders as it}
                                    <li class="J_department" data-id="">
                                           <div class="col-xs-12 j-accounts"><i></i>订单帐号：${it.order_no}</div>
                                           {@each it.items as x}
                                               <div class="col-xs-12 j-info-list">
                                                   <div class="col-xs-4">
                                                       <img src="${x.image}"/>
                                                       <div>
                                                           <p>${x.title}</p>
                                                           <p>${x.attributes|attr_build}</p>
                                                       </div>
                                                   </div>
                                                   <div class="col-xs-2">${x.count}</div>
                                                   <div class="col-xs-2">￥${x.price}</div>
                                                   <div class="col-xs-2">${it.status|first_build}</div>
                                                   <div class="col-xs-2 col-div-child">${it.pay_time}</div>
                                               </div>
                                           {@/each}
                                           <div class="col-xs-12 down-div">
                                               <img src="/images/admin/xiala.png" class="" onclick="$(this).parents('.J_department').toggleClass('new_active');$(this).toggleClass('img-chevron-down')" id="J_department_xl" data-toggle="collapse" data-target="#collapseExample_${it.order_no}" aria-expanded="false" aria-controls="collapseExample"/>
                                           </div>
                                           <div class="col-xs-12 j-details collapse" id="collapseExample_${it.order_no}">
                                               <div class="col-xs-5"><p>收货地址：${it.address}</p></div>
                                               <div class="col-xs-5"><p>运单号:${it.express_number}</p><p>(${it.express_corporation})</p></div>
                                               <div class="col-xs-2"><a href="/info/order/index">查看详情</a></div>
                                           </div>
                                   </li>
                                {@/each}
                            </script>
						</ul>
						<div class="iScrollVerticalScrollbar iScrollLoneScrollbar" style="overflow: hidden; pointer-events: none;">
							<div class="iScrollIndicator" style="transition-duration: 0ms; display: none; height: 608px; transform: translate(0px, 0px) translateZ(0px); transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);"></div>
						</div>
					</div>
					<div class="footer">
						<div class="pull-right department_page" id="J_user_page">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 修改提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4 text-center">
                    <div class="modal-pass"><label>确认<span id="J_handle_type">重置</span>：</label><input type="text" id="J_reset_sure" placeholder="请输入“YES”" style="padding: 0 10px;" /></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger hidden" id="J_pass_user">确认</button>
                <button type="button" class="btn btn-lg btn-danger hidden" id="J_del_user">确认</button>
                <button type="button" class="btn btn-lg btn-danger hidden" id="J_del_mobile">确认</button>
                <button type="button" class="btn btn-lg btn-danger hidden" id="J_up_level">确认</button>
                <button type="button" class="btn btn-lg btn-danger hidden" id="J_down_level">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDelUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4 text-danger">
					确定封停此账号？
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_del_user">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>