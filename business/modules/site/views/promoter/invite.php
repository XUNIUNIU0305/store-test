<?php
$this->params = ['css' => 'css/invite.css', 'js' => 'js/invite.js'];
?>
<div class="business-main-wrap">
    <div class="business-code-record">
        <div class="business-code-record-top row">
            <div class="back col-xs-2">
                <a href="javascript: void(0);" class="btn btn-business" id="J_back">&lt;返回上一级</a>
            </div>
            <div class="col-xs-5 info">
                <div class="pull-left text">
                    <p class="code">序列号：<span id="J_invite_id"></span></p>
                    <p class="remark">备注：<span id="J_remark"></span></p>
                </div>
            </div>
            <div class="col-xs-3 pull-right handle">
                <a href="" class="btn btn-business col-xs-5" id="J_download">下载二维码</a>
                <span class="btn btn-business col-xs-5" data-target="#apxModalBusinessEdit" data-toggle="modal">修改备注</span>
            </div>
        </div>
        <div class="business-account-list">
            <div class="form-group form-group-sm clearfix">
                <div class="col-xs-12">
                    <form class="form-inline pull-left">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="请输入门店账号或手机号" id="J_search_input">
                            <span class="input-group-btn">
                                <a class="btn btn-search" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></a>
                            </span>
                        </div>
                        <!-- /input-group -->
                    </form>
                    <div class="input-group clearfix">
                        <div class="status-tab pull-right" id="J_qr_status">
                            <span class="active" data-id="1,3">待提交</span>
                            <span data-id="2">待审核</span>
                            <span data-id="4">待开通</span>
                            <span data-id="5">已开通</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-content">
                <div class="head clearfix">
                    <div class="col-xs-2 text-center">门店账号</div>
                    <div class="col-xs-2 text-center">状态</div>
                    <div class="col-xs-2 text-center">预留手机号</div>
                    <div class="col-xs-3 text-center">付款时间</div>
                    <div class="col-xs-3 text-center">过审核时间</div>
                </div>
                <div class="content">
                    <ul class="list-unstyled row" id="J_qr_list">
                    	<script type="text/template" id="J_tpl_list">
                    		{@each data as it}
                    			<li>
		                            <div class="col-xs-2 text-center text-primary">${it.account}</div>
		                            <div class="col-xs-2 text-center text-ellipsis">${it.status|status}</div>
		                            <div class="col-xs-2 text-center">${it.mobile}</div>
		                            <div class="col-xs-3 text-center">${it.pay_datetime}</div>
		                            <div class="col-xs-3 text-center text-success">${it.authorized_datetime === '0000-01-01 00:00:00' ? '' : it.authorized_datetime}</div>
		                        </li>
                    		{@/each}
                    	</script>
                    </ul>
                </div>
                <div class="footer text-right" id="J_qr_page"></div>
            </div>
        </div>
    </div>
</div>

<!-- 修改备注 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessEdit" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">修改</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <label for="J_new_area_name">新备注：<span class="area_name"></span></label>
                        <input type="text" class="form-control" maxlength="20" id="J_new_remark">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_edit_btn">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
