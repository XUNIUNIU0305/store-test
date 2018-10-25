<?php
$this->params = ['css' => 'css/deposit-and-draw-detail.css', 'js' => 'js/deposit-and-draw-detail.js'];
?>
<div class="deposit-detail">
    <div class="back-container text-right">
        <a class="btn btn-danger" id="J_back">返回上一页</a>
    </div>
    <table>
        <tbody>
            <tr>
                <td>操作类型</td>
                <td class="J_handle_type"></td>
            </tr>
            <tr>
                <td>用户类型</td>
                <td class="J_user_type"></td>
            </tr>
            <tr>
                <td>用户账号</td>
                <td class="J_user_account"></td>
            </tr>
            <tr>
                <td>手机号</td>
                <td class="J_user_mobile"></td>
            </tr>
            <tr>
                <td>用户名称</td>
                <td class="J_user_name"></td>
            </tr>
            <tr>
                <td>角色</td>
                <td class="J_user_role"></td>
            </tr>
            <tr>
                <td>所属区域</td>
                <td class="J_user_area"></td>
            </tr>
            <tr>
                <td>余额</td>
                <td class="J_user_balance"></td>
            </tr>
            <tr>
                <td>账户状态</td>
                <td class="J_user_status"></td>
            </tr>
            <tr>
                <td>操作金额</td>
                <td class="J_user_amount"></td>
            </tr>
            <tr>
                <td>状态</td>
                <td class="J_status"></td>
            </tr>
            <tr>
                <td>创建时间</td>
                <td class="J_create_time"></td>
            </tr>
            <tr>
                <td>审核时间</td>
                <td class="J_pass_time"></td>
            </tr>
            <tr>
                <td>执行时间</td>
                <td class="J_handle_time"></td>
            </tr>
            <tr>
                <td>取消时间</td>
                <td class="J_cancel_time"></td>
            </tr>
            <tr>
                <td>取消原因</td>
                <td class="J_cancel_reason"></td>
            </tr>
            <tr>
                <td>操作原因简要</td>
                <td class="J_operate_brief"></td>
            </tr>
            <tr>
                <td>操作原因详情</td>
                <td class="J_operate_detail"></td>
            </tr>
        </tbody>
    </table>
    <div class="back-container text-center">
        <span class="btn btn-danger hidden" data-toggle="modal" data-target="#apxModalAdminPass" id="J_pass_btn">确认订单</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="btn btn-default hidden" data-toggle="modal" data-target="#apxModalAdminRejectRemark" id="J_cancel_btn">取消订单</span>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminRejectRemark" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">请填写取消原因</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <label for="department">原因：</label>
                        <textarea class="form-control" id="J_reject_remark" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-lg btn-danger" id="J_reject_sure">确认</a>
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
                    <p class="text-center h3">确定通过？</p>
                    <div class="form-group form-group-sm hidden" id="J_has_pwd">
                        <label for="" class="h5">密码：</label>
                        <input type="password" name="" id="J_password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-lg btn-danger" id="J_pass_sure">确认</a>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>