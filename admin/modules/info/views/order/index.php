<?php
$this->params = ['js' => 'js/order.js', 'css' => 'css/order.css'];
?>


<div class="admin-main-wrap">
    <div class="apx-admin-order">
        <!--查询导航-->
        <div class="nav-bar">
            <div class="form-inline">
                <div class="form-group">
                    <div>
                        <label for="select_status">订单状态：</label>
                        <select id="select_status" class="form-control">
                            <option value="" selected>请选择</option>
                            <option value="1">未发货</option>
                            <option value="2">已发货</option>
                            <option value="3">已确认收货</option>
                            <option value="4">已取消</option>
                            <option value="5">已关闭</option>
                        </select>
                    </div>
                    <div>
                        <label for="select_query">查询方式：</label>
                        <select id="select_query" class="form-control">
                            <option value="query_order">按订单号查询</option>
                            <option value="query_user">按用户名查询</option>
                            <option value="query_mobile">按手机号查询</option>
                            <option value="query_time" selected>按时间查询</option>
                        </select>
                    </div>
                </div>

                <div class="input-group query_order">
                    <input type="text" class="form-control J_enter_btn" id="J_order_input" placeholder="请输入订单号">
                </div>

                <div class="input-group query_user">
                    <input type="text" class="form-control J_enter_btn" id="J_account_input" placeholder="请输入用户账号">
                </div>

                <div class="input-group query_mobile">
                    <input type="text" class="form-control J_enter_btn" id="J_mobile_input" placeholder="请输入手机号">
                </div>

                <div class="input-group query_time in">
                    <label>开始日期：</label>
                    <input type="text" class="form-control date-picker J_search_timeStart">
                    <span class="input-group-btn J_timeStart_show">
                        <button class="btn"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <div class="input-group query_time in">
                    <label>截止日期：</label>
                    <input type="text" class="form-control date-picker J_search_timeEnd">
                    <span class="input-group-btn J_timeEnd_show">
                        <button class="btn"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>

                <button class="btn btn-danger" id="J_search_btn" data-method="">查询</button>
            </div>
        </div>

        <div id="J_order_list">
	        <script type="text/template" id="J_tpl_order">
	            {@each _ as it, index}
	                <div class="panel-order panel col-xs-12 J_order_box">
	                    <div class="panel-heading collapsed" data-toggle="collapse" href="#order_panel_${index}">
	                        <p class="panel-title row">
	                            <span class="col-xs-3">订单编号:<span class="J_order_no">${it.order_no}</span></span>
	                            {@if it.status == 1}
	                                <span class="col-xs-2 text-center">订单状态: <span class="J_order_status">未发货</span></span>
	                            {@else if it.status == 2}
	                                <span class="col-xs-2 text-center">订单状态: <span class="J_order_status">已发货</span></span>
	                            {@else if it.status == 3}
	                                <span class="col-xs-2 text-center">订单状态: <span class="J_order_status">已收货</span></span>
	                            {@else if it.status == 4}
	                                <span class="col-xs-2 text-center">订单状态: <span class="J_order_status">已取消</span></span>
	                            {@else if it.status == 5}
	                                <span class="col-xs-2 text-center">订单状态: <span class="J_order_status">已关闭</span></span>
	                            {@/if}
	                            <span class="col-xs-3 text-center">订单金额: <span class="J_total_fee">${it.total_fee|tofixed_build}</span>元</span>
	                            <span class="col-xs-4 text-right">付款时间: <span class="J_pay_time">${it.pay_time}</span></span>
	                        </p>
	                    </div>
	                    <div id="order_panel_${index}" class="panel-collapse collapse">
	                        <div class="panel-body">
                                <div class="bg-danger clearfix">
                                    <div class="col-xs-3">买家名称: ${it.consignee}</div>
                                    <div class="col-xs-5 text-ellipsis" title="${it.address}">收货地址: ${it.address}</div>
                                    <div class="col-xs-4 text-left">订单生成时间: ${it.create_time}</div>
                                    <div class="col-xs-3">买家电话: ${it.mobile}</div>
                                    <div class="col-xs-5">买家邮箱: 暂无</div>
                                    <div class="col-xs-4">运单号: ${it.express_corporation} ${it.express_number}</div>
                                </div>
                                <ul class="list-unstyled">
                                    {@each it.items as x, index}
                                        <li class="clearfix">
                                            <div class="col-xs-5">
                                                <div class="media">
                                                    <div class="media-left media-middle">
                                                        <img src="" data-src="${x.image}">
                                                    </div>
                                                    <div class="media-body media-middle">
                                                        <strong>${x.title}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 remark">备注：${x.comments}</div>
                                            <div class="col-xs-3">
                                                <div>数量：${x.count}</div>
                                                <div>属性：${x.attributes|attr_build}</div>
                                            </div>
                                        </li>
                                    {@/each}
                                </ul>
	                        </div>
	                    </div>
	                </div>
	            {@/each}
	        </script>
	    </div>
        <!--pagination-->
        <div class="pull-right" id="J_page_list"></div>
    </div>
</div>