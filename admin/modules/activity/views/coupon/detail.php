<?php
$this->params = ['js' => 'js/coupon-detail.js', 'css' => 'css/coupon-detail.css'];
?>

<div class="admin-main-wrap">
    <div class="admin-coupon-container">
        <div class="admin-coupon-box clearfix">
            <div class="col-xs-4">
                优惠券名称：<span id="J_coupon_name"></span>
            </div>
            <div class="col-xs-4">
                优惠券面额：<span id="J_coupon_price"></span>元
            </div>
            <div class="col-xs-4">
                有效期：<span id="J_coupon_time"></span>
            </div>
            <div class="col-xs-4">
                使用条件：满<span id="J_coupon_use_limit"></span>元使用
            </div>
            <div class="col-xs-4">
                单人持有上限：<span id="J_coupon_have_limit"></span>
            </div>
            <div class="col-xs-4">
                使用对象：<span id="J_coupon_supplier"></span>
            </div>
            <div class="col-xs-4">
                总发行量：<span id="J_coupon_total"></span>张
            </div>
            <div class="col-xs-4">
                剩余可发行量：<span id="J_coupon_unsent"></span>张 <button class="btn btn-danger J_handle_btn" data-toggle="modal" data-target="#apxModalAdminAddCount">增加</button>
            </div>
        </div>
        <!--nav tabs-->
        <ul class="nav nav-tabs" id="J_tab_box">
            <li class="active">
                <a href="#coupon_no_dist" data-toggle="tab" data-status="-1">未分发</a>
            </li>
            <li>
                <a href="#coupon_inactive" data-toggle="tab" data-status="0">未激活</a>
            </li>
            <li>
                <a href="#coupon_actived" data-toggle="tab" data-status="1">已激活</a>
            </li>
            <li>
                <a href="#coupon_used" data-toggle="tab" data-status="2">已使用</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade in active" id="coupon_no_dist">
                <div class="admin-coupon-detail-panel">
                    <div class="header"></div>
                    <div class="content with-header with-footer">
                        <ul class="list-unstyled pull-left">
                            <li class="active"><a href="#sub_dist" data-toggle="tab" class="btn btn-default btn-block">分发优惠券</a></li>
                            <li><a href="#sub_release" data-toggle="tab" class="btn btn-default btn-block">发行实体券</a></li>
                            <li><a href="#sub_karabiner" data-toggle="tab" class="btn btn-default btn-block">自动分发券</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="sub_dist">
                                <div class="title"><strong>分发优惠券</strong></div>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="" class="control-label col-xs-2">请输入用户ID：</label>
                                        <div class="col-xs-10">
                                            <textarea name="" id="J_hand_send_account" cols="30" rows="10" class="form-control" placeholder="例：2016358745,5598784512 请严格遵守填写规则"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-10 col-xs-offset-2">
                                            <a class="btn btn-danger btn-submit J_handle_btn" data-btn="hand" data-toggle="modal" data-target="#apxModalAdminSure">分发</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="sub_release">
                                <div class="title"><strong>发行实体券</strong></div>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="" class="control-label col-xs-2">发行量：</label>
                                        <div class="col-xs-7">
                                            <input type="text" id="J_entity_count" class="form-control">
                                        </div>
                                        <p class="col-xs-3 form-control-static">（剩余可发行数量：<span id="J_entity_unsent"></span>）</p>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-10 col-xs-offset-2">
                                            <a class="btn btn-danger btn-submit J_handle_btn" data-btn="entity"  data-toggle="modal" data-target="#apxModalAdminSure">确认发行</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="sub_karabiner">
                                <div class="title"><strong>自动分发券<span class="text-danger"></span></strong></div>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="" class="control-label col-xs-2">指定消费店：</label>
                                        <div class="col-xs-6">
                                            <select class="selectpicker" data-width="100%" data-live-search="true" id="J_supplier_list"></select>
                                        </div>
                                        <div class="col-xs-3"><a class="btn btn-danger" id="J_add_supplier">添加</a></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-8 col-xs-offset-2" id="J_supplier_box" style="overflow-x: hidden;overflow-y: auto;max-height: 88px;"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-2 control-label" for="">消费时间段：</label>
                                        <div class="col-xs-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control date-picker" value="" id="J_start_time">
                                                <span class="input-group-btn date-show">
                                                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                                </span>
                                            </div>
                                            <span class="suffix">到</span>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control date-picker" value="" id="J_end_time">
                                                <span class="input-group-btn date-show">
                                                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" required>
                                        <label class="col-xs-2 control-label" for="">指定消费额：</label>
                                        <div class="col-xs-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="consumption" id="consumption1" value="1" checked="checked">
                                                    满<input type="text" id="J_money_limit" class="form-control" maxlength="7">元发放
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="consumption" id="consumption2" value="0">
                                                    无限额
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" required>
                                        <label class="col-xs-2 control-label" for="">消费额条件：</label>
                                        <div class="col-xs-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="condition" value="1" checked="checked">
                                                    按指定店铺金额
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="condition" value="0">
                                                    按总金额
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" required>
                                        <label class="col-xs-2 control-label" for="">发放数量：</label>
                                        <div class="col-xs-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="circulation" id="circulation1" value="1" checked="checked">
                                                    按发行量
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="circulation" id="circulation2" value="0">
                                                    无限量
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-10 col-xs-offset-2">
                                            <a class="btn btn-submit btn-danger J_handle_btn" id="J_auto_handle" data-btn="auto"  data-toggle="modal" data-target="#apxModalAdminSure">确认发行</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
            <div class="tab-pane fade tab-flag" id="coupon_inactive">
                <div class="admin-coupon-detail-panel">
                    <div class="header">
                        <div class="col-xs-2">选择</div>
                        <div class="col-xs-2">序列号</div>
                        <div class="col-xs-2">生成时间</div>
                        <div class="col-xs-2">密码</div>
                        <div class="col-xs-2">关联用户</div>
                        <div class="col-xs-2 text-center">操作</div>
                    </div>
                    <div class="content with-header with-footer iscroll_container">
                        <ul class="list-unstyled dashed-split" id="J_coupon_inactive">
                            <script type="text/template" id="J_tpl_list">
                                {@each codes as it}
                                    <li class="J_coupon_box" data-id="${it.id}">
                                        <div class="col-xs-2">
                                            {@if it.status == 0}
                                                <input type="checkbox" name="inactive" value="" data-id="${it.id}">
                                            {@else if it.status == 1}
                                                <input type="checkbox" name="activated" value="">
                                            {@else if it.status == 2}
                                                <input type="checkbox" name="used" value="">
                                            {@/if}
                                        </div>
                                        <div class="col-xs-2">${it.code}</div>
                                        <div class="col-xs-2">${it.create_time}</div>
                                        <div class="col-xs-2">${it.password}</div>
                                        <div class="col-xs-2">${it.customer|customer_build}</div>
                                        {@if it.status == 0 || it.status == 1 }
                                        <div class="col-xs-2 text-center"><button class="btn btn-sm btn-link"><span data-toggle="modal" data-target="#apxModalAdminCancel" class="text-primary" data-id="${it.id}">注销</span></button></div>
                                        {@/if}
                                    </li>
                                {@/each}
                            </script>
                        </ul>
                    </div>
                    <div class="footer clearfix">
                        <div class="col-xs-6 operation">
                            <label>
                                <input type="checkbox" id="all_inactive" value=""> 全选
                            </label>
                            <label>
                                <input type="checkbox" id="invert_inactive" value=""> 反选
                            </label>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#apxModalAdminExport">导出</button>
                            <button data-toggle="modal" data-target="#apxModalAdminCancel" class="btn btn-sm btn-danger J_handle_btn" data-type="inactive">注销</button>
                        </div>
                        <div class="text-right col-xs-6 page-flag" id="J_inactive_page"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade tab-flag" id="coupon_actived">
                <div class="admin-coupon-detail-panel">
                    <div class="header">
                        <div class="col-xs-2">选择</div>
                        <div class="col-xs-2">序列号</div>
                        <div class="col-xs-2">生成时间</div>
                        <div class="col-xs-2">密码</div>
                        <div class="col-xs-2">关联用户</div>
                        <div class="col-xs-2 text-center">操作</div>
                    </div>
                    <div class="content with-header with-footer iscroll_container">
                        <ul class="list-unstyled dashed-split" id="J_coupon_activated"></ul>
                    </div>
                    <div class="footer clearfix">
                        <div class="col-xs-6 operation">
                            <label>
                                <input type="checkbox" id="all_activated" value=""> 全选
                            </label>
                            <label>
                                <input type="checkbox" id="invert_activated" value=""> 反选
                            </label>
                            <button data-toggle="modal" data-target="#apxModalAdminCancel" class="btn btn-sm btn-danger J_handle_btn"  data-type="activated">注销</button>
                        </div>
                        <div class="text-right col-xs-6 page-flag" id="J_activated_page"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade tab-flag" id="coupon_used">
                <div class="admin-coupon-detail-panel">
                    <div class="header">
                        <div class="col-xs-2">选择</div>
                        <div class="col-xs-2">序列号</div>
                        <div class="col-xs-2">生成时间</div>
                        <div class="col-xs-2">密码</div>
                        <div class="col-xs-2">关联用户</div>
                        <div class="col-xs-2 text-center">操作</div>
                    </div>
                    <div class="content with-header with-footer iscroll_container">
                        <ul class="list-unstyled dashed-split" id="J_coupon_used"></ul>
                    </div>
                    <div class="footer clearfix">
                        <div class="col-xs-6 operation">
                            <label>
                                <input type="checkbox" id="all_used" value=""> 全选
                            </label>
                            <label>
                                <input type="checkbox" id="invert_used" value=""> 反选
                            </label>
                        </div>
                        <div class="text-right col-xs-6 tab-flag" id="J_used_page"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 提示信息 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminSure" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">确定要分发吗？</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="J_sure_send" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminCancel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 36px 0;">确认要注销优惠券？</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="J_sure_cancel" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 增加发行量 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAddCount" tabindex="-1">
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
                        <label for="" class="">新增发行量：</label>
                        <input type="text" class="form-control" id="J_add_count">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="J_sure_add" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 提示信息 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminExport" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">确定要导出所有未激活优惠券吗？</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="J_sure_export" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>