<?php
$this->params = ['js' => 'js/coupon-list.js', 'css' => 'css/coupon-list.css'];
?>
<div class="admin-main-wrap">
    <div class="admin-coupon-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#coupon_inuse" data-toggle="tab" data-status="0">使用中</a>
            </li>
            <li>
                <a href="#coupon_used" data-toggle="tab" data-status="2">已过期</a>
            </li>
        </ul>
        <!--search bar-->
        <div class="search-nav clearfix">
            <form class="form-inline pull-left">
                <div class="input-group">
                    <input type="text" class="form-control" id="J_search_input" placeholder="请输入优惠券名称">
                    <span class="input-group-btn">
                        <a class="btn" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></a>
                    </span>
                </div>
                <!-- /input-group -->
            </form>
            <a href="/activity/coupon/create" id="J_create_coupon" class="btn btn-danger btn-lg btn-round pull-right">
                <i class="glyphicon glyphicon-plus"></i> 生成优惠券
            </a>
        </div>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="coupon_inuse">
                <ul class="list-unstyled admin-coupon-list" id="J_coupon_inuse">
                    <script type="text/template" id="J_tpl_list">
                        {@each codes as it}
                            <li class="clearfix">
                                <div class="pull-left">优惠券</div>
                                <div class="content clearfix">
                                    <div class="col-xs-4 text-danger">
                                        <strong class="h2">${it.price}</strong>元
                                        <span>满${it.consume_limit}元可用</span>
                                    </div>
                                    <div class="col-xs-3">
                                        <ul class="list-unstyled">
                                            <li class="text-ellipsis" title="${it.name}">优惠券名称：${it.name}</li>
                                            <li class="text-ellipsis" title="${it.supplier|supplier_build}">使用对象：${it.supplier|supplier_build}</li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-3">
                                        <ul class="list-unstyled">
                                            <li class="text-ellipsis" title="${it.start_time}-${it.end_time}">有效期：${it.start_time}-${it.end_time}</li>
                                            <li>发行数量：${it.total_quantity}</li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-2 text-right">
                                        <ul class="list-unstyled">
                                            <li><a href="/activity/coupon/detail?id=${it.id}" class="btn btn-danger">详情</a></li>
                                            {@if it.status == 0}
                                                <li><a href="#" class="btn btn-default" data-target="#apxModalAdminDel" data-toggle="modal" data-id="${it.id}" data-name="${it.name}">删除</a></li>
                                            {@/if}
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        {@/each}
                    </script>
                </ul>
                <div class="text-right" id="J_coupon_page"></div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="coupon_used">
                <ul class="list-unstyled admin-coupon-list" id="J_coupon_used">
                    
                </ul>
                <div class="text-right" id="J_used_page"></div>
            </div>
        </div>
    </div>
</div>
<!-- 提示信息 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">确定要删除吗？</div>
                <div>优惠券名称：<span id="J_del_name"></span></div>
            </div>
            <div class="modal-footer">
                <button type="button" data-toggle="modal" data-target="#apxModalAdminDelSure" data-dismiss="modal" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 提示信息 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDelSure" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">真的确定要删除吗？</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="J_sure_del" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>