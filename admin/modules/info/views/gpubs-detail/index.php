<?php
$this->params = ['js' => 'js/gpubs-detail.js', 'css' => 'css/gpubs-detail.css'];
?>
<div class="admin-frame-main">
    <div class="admin-main-wrap">
        <div class="admin-group-order-list-container">
            <div class="form-box">
                <div class="single-form">
                    <div class="form-group">
                        <label for="">订单类型：</label>
                        <select name="" id="gpubs_type">
                            <option value="3">全部</option>
                            <option value="1">自提拼购订单</option>
                            <option value="2">送货拼购订单</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">订单状态：</label>
                        <select name="" id="order_status">
                            <option value="5">全部</option>
                            <option value="1">拼购中</option>
                            <option value="6">未发货</option>
                            <option value="7">已发货</option>
                            <option value="8">已确认收货</option>
                            <option value="2">未提货</option>
                            <option value="3">部分提货</option>
                            <option value="4">全部提货</option>
                            <option value="9">已关闭</option>
                            <option value="0">拼购失败</option>
                        </select>
                    </div>
                </div>
                <div class="single-form">
                    <div class="form-group">
                        <label for="">团编号：</label>
                        <input type="text" name="" id="group_number">
                    </div>
                    <div class="form-group">
                        <label for="">团长账号：</label>
                        <input type="text" name="" id="custom_user_account">
                    </div>
                </div>
                <div class="single-form">
                    <div class="form-group">
                        <label for="">订单号：</label>
                        <input type="text" name="" id="detail_number">
                    </div>
                    <div class="form-group time-group">
                        <label for="">订单生成时间：</label>
                        <div class="data-picker-box">
                            <div class="input-group time-ipt-box query_time in">
                                <input type="text" class="form-control date-picker" value="" id="J_start_time">
                                <span class="input-group-btn date-show">
                                    <button class="btn btn-default time-btn" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </div>
                            <span class="time-sp">至</span>
                            <div class="input-group time-ipt-box query_time in">
                                <input type="text" class="form-control date-picker" value="" id="J_end_time">
                                <span class="input-group-btn date-show">
                                    <button class="btn btn-default time-btn" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-form">
                    <div class="form-group">
                        <label for="">手机号：</label>
                        <input type="text" name="" id="mobile" maxlength="13">
                    </div>
                    <div class="form-group">
                        <label for="">拼购商品：</label>
                        <input type="text" name="" id="product_title" maxlength="13">
                    </div>
                </div>
                <div class="time-form">
                    <div class="form-group">
                        <label for=""></label>
                    </div>
                    <div class="form-group">
                        <span class="btn btn-danger pull-right search-btn">查询</span>
                    </div>
                </div>
            </div>
            <div class="order-list-box">

                <div class="order-list" id="J_list_box">

                    <script type="text/template" id="J_list_tpl">
                        {@each _ as it,index}
                            <div class="panel-order panel col-xs-12">
                                <div class="panel-heading collapsed" data-toggle="collapse" href="#order_panel_2${index}">
                                    <p class="panel-title row">
                                        <span class="col-xs-3">订单编号: ${it.detailNumber}</span>
                                        <span class="col-xs-3">订单类型: ${it.gpubsGroup.gpubs_type == 1 ? '自提拼购' : '送货拼购'}</span>
                                        {@if it.gpubsGroup.gpubs_type == 1}
                                            {@if it.status == 0}
                                                <span class="col-xs-3">订单状态: 拼购失败</span>
                                            {@/if}
                                            {@if it.status == 1}
                                                <span class="col-xs-3">订单状态: 拼购中</span>
                                            {@/if}
                                            {@if it.status == 2}
                                                <span class="col-xs-3">订单状态: 未提货</span>
                                            {@/if}
                                            {@if it.status == 3}
                                                <span class="col-xs-3">订单状态: 部分提货</span>
                                            {@/if}
                                            {@if it.status == 4}
                                                <span class="col-xs-3">订单状态: 全部提货</span>
                                            {@/if}
                                        {@/if}
                                        {@if it.gpubsGroup.gpubs_type == 2}
                                            {@if it.status == 0}
                                                <span class="col-xs-3">订单状态: 拼购失败</span>
                                            {@/if}
                                            {@if it.status == 1}
                                                <span class="col-xs-3">订单状态: 拼购中</span>
                                            {@/if}
                                            {@if it.status == 6}
                                                <span class="col-xs-3">订单状态: 未发货</span>
                                            {@/if}
                                            {@if it.status == 7}
                                                <span class="col-xs-3">订单状态: 已发货</span>
                                            {@/if}
                                            {@if it.status == 8}
                                                <span class="col-xs-3">订单状态: 已确认收货</span>
                                            {@/if}
                                            {@if it.status == 9}
                                                <span class="col-xs-3">订单状态: 已关闭</span>
                                            {@/if}
                                        {@/if}
                                        
                                        <span class="col-xs-3">订单金额: ${it.total_fee}元</span>
                                        <span class="col-xs-3">团编号: ${it.gpubsGroup.group_number}</span>
                                        <span class="col-xs-3">团长账号: ${it.gpubsGroup.custom_user_account}</span>
                                        <span class="col-xs-3">付款时间: ${it.join_datetime}</span>
                                    </p>
                                </div>
                                <div id="order_panel_2${index}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="bg-danger clearfix">
                                            <div class="col-xs-2">买家名称: ${it.custom_user.shop_name}</div>
                                            <div class="col-xs-2">买家账号: ${it.custom_user.account}</div>
                                            {@if it.gpubsGroup.gpubs_type == 1}
                                                <div class="col-xs-3">自提点: ${it.gpubsGroup.spot_name}</div>
                                                <div class="col-xs-2">自提点联系人: ${it.gpubsGroup.consignee}</div>
                                            {@/if}
                                            {@if it.gpubsGroup.gpubs_type == 2}
                                                <div class="col-xs-3">收货地址: ${it.full_address}</div>
                                                <div class="col-xs-2"></div>
                                            {@/if}
                                            <div class="col-xs-3">订单生成时间: ${it.join_datetime}</div>
                                            <div class="col-xs-2">买家电话: ${it.custom_user.mobile}</div>
                                            <div class="col-xs-2"></div>
                                            {@if it.gpubsGroup.gpubs_type == 1}
                                                <div class="col-xs-3">自提点地址: ${it.gpubsGroup.full_address}</div>
                                                <div class="col-xs-2">联系人手机: ${it.gpubsGroup.mobile}</div>
                                                <div class="col-xs-3"></div>
                                            {@/if}
                                            {@if it.gpubsGroup.gpubs_type == 2}
                                                <div class="col-xs-3">买家邮箱: ${it.custom_user.email}</div>
                                                <div class="col-xs-2"></div>
                                                <div class="col-xs-3">运单号：${it.express_number}</div>
                                            {@/if}
                                        </div>
                                        <ul class="list-unstyled">
                                            <li class="clearfix">
                                                <div class="col-xs-5">
                                                    <div class="media">
                                                        <div class="media-left media-middle">
                                                            <img src="${it.product_image_filename}">
                                                        </div>
                                                        <div class="media-body media-middle">
                                                            <strong>${it.product_title}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4">
                                                    备注：${it.comment}
                                                </div>
                                                <div class="col-xs-3">
                                                    <div>数量：${it.quantity}</div>
                                                    <div>属性：
                                                    {@each it.skuAttributes as item}
                                                        ${item.name} : ${item.selectedOption.name};
                                                    {@/each}
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <!-- <a data-toggle="modal" data-target="#modalDealOrder" href="#" class="btn btn-danger pull-right">
                                            <strong>点我操作</strong>
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        {@/each}
                    </script>
                </div>
                <div class="text-right" id="J_coupon_page"></div>
            </div>

        </div>
    </div>
</div>
