<?php
$this->params = ['css' => 'css/draw-review-detail.css', 'js' => 'js/draw-review-detail.js'];
?>
<div class="admin-main-wrap">
    <div class="bank-audit-detail">
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
        <div class="title"><span class="btn" id="J_go_back">返回列表</span></div>
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2" class="text-center">账户信息</td>
                </tr>
                <tr>
                    <td>账户类型</td>
                    <td id="J_user_info_type"></td>
                </tr>
                <tr>
                    <td>账户号</td>
                    <td id="J_user_account"></td>
                </tr>
                <tr>
                    <td>账户手机</td>
                    <td id="J_user_mobile"></td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2" class="text-center">银行信息</td>
                </tr>
                <tr>
                    <td>银行名称</td>
                    <td id="J_bank_name"></td>
                </tr>
                <tr>
                    <td>开户名称</td>
                    <td id="J_user_name"></td>
                </tr>
                <tr>
                    <td>银行账户</td>
                    <td id="J_bank_code"></td>
                </tr>
                <tr>
                    <td>账户类型</td>
                    <td id="J_bank_type"></td>
                </tr>
                <tr>
                    <td>银行预留手机号</td>
                    <td id="J_bank_mobile"></td>
                </tr>
                <tr>
                    <td>银行账户绑定时间</td>
                    <td id="J_create_time"></td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <tr>
                <td colspan="2" class="text-center">审核信息</td>
            </tr>
            <tr>
                <td>提现金额</td>
                <td id="J_apply_count" class="text-danger"></td>
            </tr>
            <tr>
                <td>审核状态</td>
                <td id="J_apply_status" class="text-danger"></td>
            </tr>
            <tr>
                <td>申请时间</td>
                <td id="J_apply_time"></td>
            </tr>
            <tr>
                <td>通过时间</td>
                <td id="J_pass_time"></td>
            </tr>
            <tr>
                <td>驳回时间</td>
                <td id="J_reject_time"></td>
            </tr>
            <tr>
                <td>失败时间</td>
                <td id="J_failure_time"></td>
            </tr>
            <tr>
                <td>成功时间</td>
                <td id="J_success_time"></td>
            </tr>
            <tr>
                <td>审核留言</td>
                <td id="J_verify_msg"></td>
            </tr>
        </table>
        <p class="handle-box hidden" id="J_handle_box">
            <span class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminPass">通过</span>
            <span class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminRejectRemark">驳回</span>
        </p>
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

<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminRejectRemark" tabindex="-1">
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
                        <label for="department">留言：</label>
                        <textarea class="form-control" id="J_reject_remark" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_reject_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminPass" tabindex="-1">
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
                        <p class="text-center h3">确定通过？</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_pass_sure">确认</button>
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
</div>nv