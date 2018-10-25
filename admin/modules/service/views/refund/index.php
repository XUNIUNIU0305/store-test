<?php
$this->params = ['js' => 'js/refund.js', 'css' => 'css/refund.css'];
?>

<div class="admin-main-wrap">
    <!-- 退换货tabs -->
    <div class="admin-refund-container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="J_tab_box">
            <li class="active" data-status="0">
                <a href="#refund_todo" data-toggle="tab">待处理</a>
            </li>
            <li data-status="-2">
                <a href="#refund_done" data-toggle="tab">已处理</a>
            </li>
            <li data-status="8">
                <a href="#refund_money" data-toggle="tab">确认退款</a>
            </li>
            <li data-status="9">
                <a href="#refund_done" data-toggle="tab">已取消</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <!--待处理 start-->
            <div role="tabpanel" class="admin-refund-todo tab-pane fade in active" id="refund_todo">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="admin-management-panel">
                            <div class="header">
                                <small>时间排序</small>
                                <button class="btn btn-danger btn-xs J_date_sort active" data-sort="0">由远到近</button>
                                <button class="btn btn-danger btn-xs J_date_sort" data-sort="1">由近到远</button>
                                <button class="btn btn-danger btn-xs J_data_refresh" data-status="0">刷新</button>
                                <div class="input-group input-group-sm pull-right">
                                    <input type="text" class="form-control search" placeholder="请输入售后单号">
                                    <span class="input-group-btn" id="J_todo_search_btn">
                                        <button class="btn"><i class="glyphicon glyphicon-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="iscroll_container with-header with-footer">
                                <ul class="list-unstyled" id="J_todo_list">
                                	<script type="text/template" id="J_tpl_list">
                                		{@each codes as it}
                                			<li class="J_user" data-no="${it.code}">
		                                        <div class="media">
		                                            <a class="media-left media-middle" href="javascript:;">
		                                                <img src="${it.image}">
		                                            </a>
		                                            <div class="media-body">
		                                                <div class="col-xs-6 text-left">
		                                                    <strong class="h5">售后单号：${it.code}</strong>
		                                                </div>
		                                                <div class="col-xs-6 text-right">
		                                                    ${it.create_time}
		                                                </div>
		                                                <div class="col-xs-5 text-left">
		                                                    <strong class="h5">用户ID：${it.customer_account}</strong>
		                                                </div>
		                                                <div class="col-xs-3 text-left">
		                                                    数量：${it.quantity}个
		                                                </div>
		                                                <div class="col-xs-4 text-right">
		                                                    <strong>${it.status|statusname_build}</strong>
		                                                </div>
		                                            </div>
		                                        </div>
		                                    </li>
                                		{@/each}
                                	</script>
                                </ul>
                            </div>
                            <div class="footer" id="J_todo_page"></div>
                        </div>
                    </div>
                    <div class="col-xs-7 hidden" id="J_todo_detail">
                        <div class="admin-management-panel">
                            <div class="header header-lg">
                                <div class="pull-left">
                                    <div class="media">
                                        <a class="media-left media-middle" href="#">
                                            <img class="J_customer_header" src="/images/ensurance/icon1.png">
                                        </a>
                                        <ul class="media-body list-unstyled media-middle">
                                            <li>用户ID：<span class="J_customer_account"></span><span class="pull-right"></span></li>
                                            <li>手机号：<span class="J_customer_mobile"></span></li>
                                            <li>邮箱：<span class="J_customer_email"></span></li>
                                            <li>所属运营商：<span class="J_customer_operater"></span></li>
                                            <li>所属区域：<span class="J_customer_area"></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="pull-right" id="J_toggle_type">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="refund_type" id="reject" value="0">
                                            驳回
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="refund_type" id="exchange" value="1">
                                            换货
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="refund_type" id="refund" value="2">
                                            退货
                                        </label>
                                    </div>
                                    <button class="btn btn-lg btn-danger" data-toggle="modal" data-target="#apxModalAdminConfrimEdit" id="J_todo_edit">完成操作</button>
                                </div>
                            </div>
                            <div class="iscroll_container with-header">
                                <div class="col-xs-12">
                                    <div class="h5 high-lighted">订单信息</div>
                                    <div class="order-info">
                                        <script type="text/template" id="J_tpl_order">
                                            <table class="table table-bordered">
                                                {@each _ as it}
                                                <tr>
                                                    <td rowspan="3" width="60%">
                                                        <div class="media">
                                                            <div class="media-left media-middle">
                                                                <img src="${it.image}">
                                                            </div>
                                                            <div class="media-body">
                                                                <h5 class="media-heading">${it.title}</h5>
                                                                <p>
                                                                    {@each it.attributes as attr}
                                                                        ${attr.attribute}：${attr.option}
                                                                    {@/each}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="48">单价</td>
                                                    <td>${it.price|buildPrice}</td>
                                                </tr>
                                                <tr>
                                                    <td>数量</td>
                                                    <td>${it.count}</td>
                                                </tr>
                                                <tr>
                                                    <td>总价</td>
                                                    <td>${it.total_fee|buildPrice}</td>
                                                </tr>
                                                {@/each}
                                            </table>
                                        </script>
                                    </div>
                                    <table class="table table-bordered table-hover total">
                                        <tr>
                                            <td width="60%">优惠金额 :</td>
                                            <td class="td-right">￥
                                                <span class="discounts J_coupon_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%">商品总金额 :</td>
                                            <td class="td-right">￥
                                                <span class="amount J_items_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%">已退款 :</td>
                                            <td class="td-right">￥
                                                <span class="account-paid J_refund_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%">实付金额 :</td>
                                            <td class="td-right active">￥
                                                <span class="actually-paid J_total_price"></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="h5 high-lighted">商品信息</div>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td rowspan="3" width="60%">
                                                <div class="media">
                                                    <div class="media-left media-middle">
                                                        <img class="J_pro_img" src="/images/ensurance/icon1.png">
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="media-heading J_pro_title"></h5>
                                                        <p class="J_pro_attr"></p>
                                                    </div>
                                                </div>

                                            </td>
                                            <td width="48">单价</td>
                                            <td class="J_pro_price"></td>
                                        </tr>
                                        <tr>
                                            <td>数量</td>
                                            <td class="J_pro_count"></td>
                                        </tr>
                                        <tr>
                                            <td>总价</td>
                                            <td class="J_pro_total"></td>
                                        </tr>
                                    </table>
                                    <p>供应商：<span class="J_supply_company"></span></p>
                                    <p>联系人：<span class="J_supply_name"></span></p>
                                    <p>手机号：<span class="J_supply_mobile"></span></p>
                                    <p>固定电话：<span class="J_supply_telephone"></span></p>
                                    <p>退换地址：<span class="J_supply_address"></span></p>
                                    <div class="h5 high-lighted">售后原因</div>
                                    <p class="J_refund_reason"></p>
                                    <ul class="list-unstyled refund-img J_refund_img img-list"></ul>
                                </div>
                            </div>
                            <div class="iscroll_container with-header">
                                <div class="col-xs-12">
                                    <div class="refund-money hidden">
                                        <div class="h5">退款金额</div> 
                                        <div class="refund-amount"> 
                                            <input type="text" class="ipt-amount" id="J_money_input" placeholder="请输入退款金额"> 
                                            <p class="detail-amount"> 
                                                <img src="/images/excalmatory_point.png" class="mark-point"> 
                                                <span>最大可退金额： ￥</span>
                                                <span class="max-amount J_max_price"></span> 
                                            </p> 
                                        </div> 
                                    </div>
                                    <div class="h5 high-lighted">补全意见</div>
                                    <textarea id="advice" class="form-control" maxlength="200" rows="10"></textarea>
                                    <div class="h5 high-lighted">上传照片</div>
                                    <div class="img-upload-box-wrap">
                                		<label class="img-upload-box" for="upload_service_img">
                                            <input type="file" id="upload_service_img">
                                            <div class="lint">
                                                <i>+</i>
                                                添加图片
                                            </div>
                                        </label>
                                    	<div id="J_service_img"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--待处理 end-->

            <!--已处理 start-->
            <div role="tabpanel" class="admin-refund-done tab-pane fade" id="refund_done">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="admin-management-panel">
                            <div class="header">
                                <small>时间排序</small>
                                <button class="btn btn-danger btn-xs J_date_sort active" data-sort="0">由远到近</button>
                                <button class="btn btn-danger btn-xs J_date_sort" data-sort="1">由近到远</button>
                                <button class="btn btn-danger btn-xs J_data_refresh" data-status="-2">刷新</button>
                                <div class="input-group input-group-sm pull-right">
                                    <input type="text" class="form-control search" placeholder="请输入售后单号">
                                    <span class="input-group-btn" id="J_done_search_btn">
                                        <button class="btn"><i class="glyphicon glyphicon-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="iscroll_container with-header with-footer">
                                <ul class="list-unstyled" id="J_done_list"></ul>
                            </div>
                            <div class="footer" id="J_done_page"></div>
                        </div>
                    </div>
                    <div class="col-xs-7 hidden" id="J_done_detail">
                        <div class="admin-management-panel">
                            <div class="header header-lg">
                                <div class="media">
                                    <a class="media-left media-middle" href="#">
                                        <img class="J_customer_header" src="/images/ensurance/icon1.png">
                                    </a>
                                    <ul class="media-body list-unstyled media-middle">
                                        <li>用户ID：<span class="J_customer_account"></span><span class="pull-right"></span></li>
                                        <li>手机号：<span class="J_customer_mobile"></span></li>
                                        <li>邮箱：<span class="J_customer_email"></span></li>
                                    </ul>
                                    <ul class="media-body list-unstyled media-middle">
                                        <li>所属运营商：<span class="J_customer_operater"></span></li>
                                        <li>所属区域：<span class="J_customer_area"></span></li>
                                    </ul>
                                    <ul class="media-body list-unstyled media-middle hidden" id="J_cancel_btn">
                                        <li><button class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminConfrimCancelRefund">取消</button></li>
                                    </ul>
                                    <ul class="media-body list-unstyled media-middle">
                                        <li><p class="h4 text-danger text-center J_refund_statu" style="width: 66px;margin-left: 4px;"></p></li>
                                        <li class="text-center"><button class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminConfrimAddMsg" id="J_done_edit">追加备注</button></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="iscroll_container with-header iscroll_container-full">
                                <div class="col-xs-12">
                                    <div class="h5 high-lighted">订单信息</div>
                                    <div class="order-info"></div>
                                    <table class="table table-bordered table-hover total">
                                        <tr>
                                            <td width="80%">优惠金额 :</td>
                                            <td class="td-right">￥
                                                <span class="discounts J_coupon_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">商品总金额 :</td>
                                            <td class="td-right">￥
                                                <span class="amount J_items_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">已退款 :</td>
                                            <td class="td-right">￥
                                                <span class="account-paid J_refund_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">实付金额 :</td>
                                            <td class="td-right active">￥
                                                <span class="actually-paid J_total_price"></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="h5 high-lighted">商品信息</div>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td rowspan="3" width="60%">
                                                <div class="media">
                                                    <div class="media-left media-middle">
                                                        <img class="J_pro_img" src="/images/ensurance/icon1.png">
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="media-heading J_pro_title"></h5>
                                                        <p class="J_pro_attr"></p>
                                                    </div>
                                                </div>

                                            </td>
                                            <td width="48">单价</td>
                                            <td class="J_pro_price"></td>
                                        </tr>
                                        <tr>
                                            <td>数量</td>
                                            <td class="J_pro_count"></td>
                                        </tr>
                                        <tr>
                                            <td>总价</td>
                                            <td class="J_pro_total"></td>
                                        </tr>
                                    </table>
                                    <div class="refund-info hidden">
                                        <div class="h5 high-lighted">退款信息</div>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td rowspan="3" width="60%">
                                                    <div class="media">
                                                        <div class="media-left media-middle">
                                                            <img class="J_pro_img" src="/images/ensurance/icon1.png">
                                                        </div>
                                                        <div class="media-body">
                                                            <h5 class="media-heading J_pro_title"></h5>
                                                            <p class="J_pro_attr"></p>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td rowspan="3" style="vertical-align: middle;" width="48">申请退款金额</td>
                                                <td rowspan="3" style="vertical-align: middle;" class="J_order_refund_price"></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="help-supply hidden">
                                        <div class="h5 high-lighted">帮门店填写物流单号</div>
                                        <div class="form-group">
                                            <label for="logisticName">物流公司：</label>
                                            <div class="choose-express" style="display: inline-block; width: 200px;">
                                            <input type="text" />
                                            <select class="form-control">
                                                <option>选择物流</option>
                                            </select>
                                            <div></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">快递单号：</label>
                                            <div style="display: inline-block; width: 400px;">
                                                <input type="text" class="form-control" id="J_express_no">
                                            </div>
                                            <button class="btn btn-danger" style="margin-top: -6px;" id="J_submit_express">提交</button>
                                        </div>
                                    </div>
                                    <div class="h5 high-lighted">退换货信息</div>
                                    <ul class="list-unstyled admin-refund-done-info" id="J_edit_log"></ul>
                                    <hr>
                                    <div class="h5 high-lighted">售后原因</div>
                                    <p class="J_refund_reason"></p>
                                    <div class="refund-cancel hidden">
                                        <div class="h5 high-lighted">取消售后原因</div>
                                        <p class="J_cancel_reason"></p>
                                    </div>
                                    <ul class="list-unstyled admin-refund-done-gallary J_refund_img img-list refund-img"></ul>
                                    <hr>
                                    <div class="h5 high-lighted">客服回复</div>
                                    <div class="J_service_box">
                                        <script type="text/template" id="J_tpl_service">
                                            {@each plat_suggestion as it}
                                                <p>${it.admin_name}&nbsp;&nbsp;${it.post_time}</p>
                                                <p>${it.comments}</p>
                                                <ul class="list-unstyled admin-refund-done-gallary">
                                                    {@each it.images as img}
                                                        <li>
                                                            <a href="${img.path}" data-lightbox="unique-mark2" target="_blank"><img src="${img.path}" class="img-responsive"></a>
                                                        </li>
                                                    {@/each}
                                                </ul>
                                                <hr>
                                            {@/each}
                                        </script>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--已处理 end-->
            <!--确认退款 start-->
            <div role="tabpanel" class="admin-refund-todo tab-pane fade" id="refund_money">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="admin-management-panel">
                            <div class="header">
                                <small>时间排序</small>
                                <button class="btn btn-danger btn-xs J_date_sort active" data-sort="0">由远到近</button>
                                <button class="btn btn-danger btn-xs J_date_sort" data-sort="1">由近到远</button>
                                <button class="btn btn-danger btn-xs J_data_refresh" data-status="8">刷新</button>
                                <div class="input-group input-group-sm pull-right">
                                    <input type="text" class="form-control search" placeholder="请输入售后单号">
                                    <span class="input-group-btn" id="J_money_search_btn">
                                        <button class="btn"><i class="glyphicon glyphicon-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="iscroll_container with-header with-footer">
                                <ul class="list-unstyled" id="J_money_list">
                                	
                                </ul>
                            </div>
                            <div class="footer" id="J_money_page"></div>
                        </div>
                    </div>
                    <div class="col-xs-7 hidden" id="J_money_detail">
                        <div class="admin-management-panel">
                            <div class="header header-lg">
                                <div class="pull-left">
                                    <div class="media">
                                        <a class="media-left media-middle" href="#">
                                            <img class="J_customer_header" src="/images/ensurance/icon1.png">
                                        </a>
                                        <ul class="media-body list-unstyled media-middle">
                                            <li>用户ID：<span class="J_customer_account"></span><span class="pull-right"></span></li>
                                            <li>手机号：<span class="J_customer_mobile"></span></li>
                                            <li>邮箱：<span class="J_customer_email"></span></li>
                                            <li>所属运营商：<span class="J_customer_operater"></span></li>
                                            <li>所属区域：<span class="J_customer_area"></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="pull-right" id="J_money_toggle_type">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" checked name="" id="J_refund_money" value="2">
                                            退货
                                        </label>
                                    </div>
                                    <button class="btn btn-lg btn-danger" data-toggle="modal" data-target="#apxModalAdminConfrimEdit2" id="J_money_edit">完成操作</button>
                                    <button class="btn btn-lg btn-danger" data-toggle="modal" data-target="#apxModalAdminConfrimCancelRefund" id="J_todo_cancel">取消</button>
                                </div>
                            </div>
                            <div class="iscroll_container with-header">
                                <div class="col-xs-12">
                                    <div class="h5 high-lighted">订单信息</div>
                                    <div class="order-info"></div>
                                    <table class="table table-bordered table-hover total">
                                        <tr>
                                            <td width="60%">优惠金额 :</td>
                                            <td class="td-right">￥
                                                <span class="discounts J_coupon_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%">商品总金额 :</td>
                                            <td class="td-right">￥
                                                <span class="amount J_items_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%">已退款 :</td>
                                            <td class="td-right">￥
                                                <span class="account-paid J_refund_price"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%">实付金额 :</td>
                                            <td class="td-right active">￥
                                                <span class="actually-paid J_total_price"></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="h5 high-lighted">商品信息</div>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td rowspan="3" width="60%">
                                                <div class="media">
                                                    <div class="media-left media-middle">
                                                        <img class="J_pro_img" src="/images/ensurance/icon1.png">
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="media-heading J_pro_title"></h5>
                                                        <p class="J_pro_attr"></p>
                                                    </div>
                                                </div>

                                            </td>
                                            <td width="48">单价</td>
                                            <td class="J_pro_price"></td>
                                        </tr>
                                        <tr>
                                            <td>数量</td>
                                            <td class="J_pro_count"></td>
                                        </tr>
                                        <tr>
                                            <td>总价</td>
                                            <td class="J_pro_total"></td>
                                        </tr>
                                    </table>
                                    <div class="h5 high-lighted">供应商信息</div>
                                    <p>供应商：<span class="J_supply_company"></span></p>
                                    <p>联系人：<span class="J_supply_name"></span></p>
                                    <p>手机号：<span class="J_supply_mobile"></span></p>
                                    <p>固定电话：<span class="J_supply_telephone"></span></p>
                                    <p>退换地址：<span class="J_supply_address"></span></p>
                                    <div class="h5 high-lighted">售后原因</div>
                                    <p class="J_refund_reason"></p>
                                    <ul class="list-unstyled refund-img J_refund_img img-list"></ul>
                                </div>
                            </div>
                            <div class="iscroll_container with-header">
                                <div class="col-xs-12">
                                    <div class="refund-money">
                                        <div class="h5">退款金额</div> 
                                        <div class="refund-amount"> 
                                            <input type="text" class="ipt-amount" id="J_money_end_input" placeholder="请输入退款金额"> 
                                            <p class="detail-amount"> 
                                                <img src="/images/excalmatory_point.png" class="mark-point"> 
                                                <span>最大可退金额： ￥</span>
                                                <span class="max-amount J_max_price"></span>
                                                <span>申请退款金额： ￥</span>
                                                <span class="J_want_price"></span>
                                            </p> 
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--确认退款 end-->
        </div>
    </div>

</div>
<!-- 确定弹窗 -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminConfrimEdit" tabindex="-1">
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
	            <span class="J_confrim_content">确定要执行吗？</span>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-lg btn-danger" id="J_edit_sure">确认</button>
	            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
	        </div>
	    </div>
	</div>
</div>
<!-- 确定弹窗2 -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminConfrimEdit2" tabindex="-1">
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
	            <span class="J_confrim_content">确定要执行吗？</span>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-lg btn-danger" id="J_money_sure">确认</button>
	            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
	        </div>
	    </div>
	</div>
</div>
<!-- 追加备注弹窗 -->
<div class="apx-modal-admin-manage-staff modal fade" id="apxModalAdminConfrimAddMsg" tabindex="-1">
	<div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
	            <a type="button" class="close" data-dismiss="modal">
	                <span aria-hidden="true">&times;</span>
	                <span class="sr-only">Close</span></a>
	            <h4 class="modal-title">追加备注</h4>
	        </div>
	        <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-2" for="J_service_remark">补全意见:</label>
                        <div class="col-xs-10">
                            <textarea class="form-control" id="J_service_remark" rows="3" maxlength="200" placeholder="意见区"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="img-upload-box-wrap">
                            <label class="col-xs-2">上传照片:</label>
                            <div class="col-xs-10">
                                <label class="img-upload-box" for="upload_service_add_img">
                                    <input type="file" id="upload_service_add_img">
                                    <div class="lint">
                                        <i>+</i>
                                        添加图片
                                    </div>
                                </label>
                                <div id="J_service_add_img"></div>
                            </div>
                        </div>
                    </div>
                </form>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-lg btn-danger" id="J_edit_add_sure">确认</button>
	            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
	        </div>
	    </div>
	</div>
</div>
<!-- 取消弹窗 -->
<div class="apx-modal-admin-manage-staff modal fade" id="apxModalAdminConfrimCancelRefund" tabindex="-1">
	<div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
	            <a type="button" class="close" data-dismiss="modal">
	                <span aria-hidden="true">&times;</span>
	                <span class="sr-only">Close</span></a>
	            <h4 class="modal-title">取消售后</h4>
	        </div>
	        <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-2" for="J_cancel_refund">取消原因:</label>
                        <div class="col-xs-10">
                            <textarea class="form-control" id="J_cancel_reason" rows="3" maxlength="200" placeholder="必填"></textarea>
                        </div>
                    </div>
                    <p class="h4 high-lighted text-center hidden" id="J_cancel_confirm">请再次确认！</p>
                </form>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-lg btn-danger" id="J_cancel_sure">确认</button>
	            <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
	        </div>
	    </div>
	</div>
</div>