<?php
$this->params = ['css' => 'css/fund-management.css', 'js' => 'js/fund-management.js'];
?>
<div class="admin-main-wrap">
    <div class="bank-funds">
        <div class="title h4">
            账户余额：<span id="J_balance"></span>
            <div class="pull-right">
                <span id="J_user_type">BUSINESS</span>&nbsp;&nbsp;
                <span>余额: <span id="J_user_balance"></span></span>&nbsp;&nbsp;
                <span>冻结金额：<span id="J_frost_money"></span></span>&nbsp;&nbsp;
                <span>更新时间：<span id="J_update_time"></span></span>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#pay_out" data-toggle="tab">出金</a>
            </li>
            <li>
                <a href="#deposit" data-toggle="tab">入金</a>
            </li>
            <li>
                <a href="#list" data-toggle="tab">明细</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="pay_out">
                <div class="form-inline pay_out_box">
                    <div class="form-group">
                        <label>出金金额：</label><br>
                        <input type="text" class="form-control" id="J_out_input"> 元
                    </div>
                    <div class="out-btn-group">
                        <span class="btn" id="J_reset_out">清除</span>
                        <span class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminOutMoney">确认</span>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="deposit">
                <div class="form-inline pay_out_box">
                    <div class="form-group">
                        <label>入金金额：</label><br>
                        <input type="text" class="form-control" id="J_add_input"> 元
                    </div>
                    <div class="form-group code">
                        <input type="text" class="form-control" id="J_captcha_input">
                        <span class="btn" id="J_captcha_btn">获取验证码</span>
                    </div>
                    <div class="out-btn-group">
                        <span class="btn" id="J_reset_add">清除</span>
                        <span class="btn btn-danger" data-target="#apxModalAdminAddMoney" data-toggle="modal">确认</span>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade detail" id="list">
                <div class="header">
                    <div class="date-picker-box clearfix">
                        <div class="form-group col-xs-3">
                            <span>开始时间：</span>
                            <div class="input-group">
                                <input type="text" class="form-control date-picker J_search_timeStart">
                                <span class="input-group-btn J_timeStart_show">
                                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-xs-3">
                            <span>结束时间：</span>
                            <div class="input-group">
                                <input type="text" class="form-control date-picker J_search_timeEnd">
                                <span class="input-group-btn J_timeEnd_show">
                                    <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-1 form-group pull-right">
                            <span class="btn btn-danger" id="J_search_btn">查询</span>
                        </div>
                    </div>
                </div>
                <!--table-->
                <table class="table table-hover table-panel table-fix text-center dashed">
                    <thead>
                        <tr>
                            <th>交易流水号</th>
                            <th>银行交易流水</th>
                            <th>币种</th>
                            <th>交易金额</th>
                            <th>交易状态</th>
                            <th>交易类型</th>
                            <th>交易时间</th>
                        </tr>
                    </thead>
                    <tbody id="J_list_box">
                        <script type="text/template" id="J_tpl_list">
                            {@each _ as it}
                                <tr>
                                    <td>${it.MerchantSeqNo}</td>
                                    <td>${it.TransSeqNo}</td>
                                    <td>
                                        {@if it.Currency == '156'}
                                            人民币
                                        {@else} 
                                            ${it.Currency}
                                        {@/if}
                                    </td>
                                    <td>${it.TransAmount|trim}</td>
                                    <td>
                                        {@if it.TransStatus == '00'}
                                            成功
                                        {@else}
                                            失败
                                        {@/if}
                                    </td>
                                    <td>
                                        {@if it.TransType == '04'}
                                            入金
                                        {@else if it.TransType == '05'}
                                            出金
                                        {@/if}
                                    </td>
                                    <td>${it.TransDate} ${it.TransTime}</td>
                                </tr>
                            {@/each}
                        </script>
                    </tbody>
                </table>
                <div class="modal-loading-box hidden" id="J_list_loading">
                    <div class="loading-inner">
                        <div class="line-spin-fade-loader">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
                <p class="text-center add-more hidden" id="J_show_more" style="position: relative;">点击加载更多</p>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertReject" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-textarea">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <p class="h4 text-center">操作成功！<span id="J_second">4</span>s后返回列表页！</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger">立即返回</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminOutMoney" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-textarea">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <p class="h4 text-center">确定执行？</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_out_sure">确定</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAddMoney" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-textarea">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <p class="h4 text-center">确定执行？</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_add_sure">确定</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminLoading" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close hidden" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-textarea" style="height: 220px;">
                <p class="h3">正在处理中...</p>
                <div class="modal-loading-box">
                    <div class="loading-inner">
                        <div class="line-spin-fade-loader">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
