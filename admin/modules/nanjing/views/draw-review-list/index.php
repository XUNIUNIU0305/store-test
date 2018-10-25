<?php
$this->params = ['css' => 'css/draw-review-list.css', 'js' => 'js/draw-review-list.js'];
?>
<div class="admin-main-wrap">
    <div class="admin-bank-audit-list-container">
        <div class="pay-box">
            <div class="form-inline clearfix collapse" id="collapseExample">
                <div class="form-group pull-left">
                    <label for="">充值金额：</label>
                    <input type="text" class="form-control" id="J_add_input">&nbsp;元
                </div>
                <div class="form-group pull-left">
                    <label for="">验证码：</label>
                    <input type="text" class="form-control" id="J_captcha_input">
                    <span class="btn btn-code" id="J_captcha_btn">获取验证码</span>
                </div>
                <div class="form-group pull-left">
                    <span class="btn btn-danger btn-money" id="J_add_sure">确定充值</span>
                </div>
            </div>
            <div class="pay-handle">
                <span class="h4">账户余额：<span id="J_balance"></span></span>
                <span class="go-pay" role="button" data-toggle="collapse" href="#collapseExample"><span id="go_pay">去充值</span><span class="icon">&gt;</span></span>
                <div class="pull-right">
                    <span id="J_user_type">BUSINESS</span>&nbsp;&nbsp;
                    <span>余额: <span id="J_user_balance"></span></span>&nbsp;&nbsp;
                    <span>冻结金额：<span id="J_frost_money"></span></span>&nbsp;&nbsp;
                    <span>更新时间：<span id="J_update_time"></span></span>
                </div>
            </div>
        </div>
        <!--nav-->
        <div class="admin-audit-list-title bg-danger">
            <ul class="nav">
                <li class="pull-left" id="J_tabs_list">
                    审核状态 &nbsp;&nbsp;
                    <button class="btn btn-danger btn-xs" data-status="-1">全部</button>
                    <button class="btn btn-danger btn-xs active" data-status="0">未审核</button>
                    <button class="btn btn-danger btn-xs" data-status="1">通过</button>
                    <button class="btn btn-danger btn-xs" data-status="2">驳回</button>
                    <button class="btn btn-danger btn-xs" data-status="3">失败</button>
                    <button class="btn btn-danger btn-xs" data-status="4">成功</button>
                </li>
                <li class="pull-left hidden">
                    时间排序 &nbsp;&nbsp;
                    <button class="btn btn-danger btn-xs">由近到远</button>
                    <button class="btn btn-danger btn-xs">由远到近</button>
                </li>
            </ul>
        </div>
        <!--table-->
        <table class="table table-hover table-panel table-fix text-center dashed">
            <thead>
                <tr>
                    <th width="175">提现流水号</th>
                    <th width="175">申请时间</th>
                    <th>账号</th>
                    <th>账户类型</th>
                    <th width="150">账户手机</th>
                    <th width="120">银行</th>
                    <th width="175">银行账号</th>
                    <th>开户名称</th>
                    <th width="140">提现金额</th>
                    <th>状态</th>
                </tr>
            </thead>
            <tbody id="J_list_box">
                <script type="text/template" id="J_tpl_list">
                    {@each _ as it}
                       <tr data-id="${it.id}">
                            <td>${it.draw_number}</td>
                            <td>${it.apply_time}</td>
                            <td>${it.account.user_account}</td>
                            <td>
                                {@if it.account.user_type === 1}
                                    CUSTOM
                                {@else if it.account.user_type === 2}
                                    SUPPLY
                                {@else if it.account.user_type === 3}
                                    BUSINESS
                                {@else if it.account.user_type === 4}
                                    ADMIN
                                {@/if}
                            </td>
                            <td>${it.account.user_phone}</td>
                            <td>${it.bank.bank_name}</td>
                            <td>${it.bank.acct_no}</td>
                            <td>${it.bank.acct_name}</td>
                            <td>${it.rmb}</td>
                            <td>
                                {@if it.status === 0}
                                    未审核
                                {@else if it.status === 1}
                                    通过
                                {@else if it.status === 2}
                                    驳回
                                {@else if it.status === 3}
                                    失败
                                {@else if it.status === 4}
                                    成功
                                {@/if}
                            </td>
                        </tr> 
                    {@/each}
                </script>
            </tbody>
        </table>
        <!-- pagination -->
        <div class="text-right" id="J_page_list">
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