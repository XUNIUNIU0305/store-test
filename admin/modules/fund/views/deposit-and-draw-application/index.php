<?php
$this->params = ['css' => 'css/deposit-and-draw-application.css', 'js' => 'js/deposit-and-draw-application.js'];
?>
<div class="admin-main-wrap">
    <div class="create-order-container">
        <div class="section">
            <div class="form-box clearfix">
                <div class="navbar-form navbar-left">
                    <div class="form-group">
                        <label for="">账号：</label>
                        <input type="number" id="J_search_input" class="form-control" placeholder="请输入账号">
                    </div>
                    <span class="btn btn-danger" id="J_search_btn">查找</span>
                </div>
                <div class="input-group col-xs-5">
                    <label for="" class="pull-left">操作：</label>
                    <select class="form-control" id="J_select_type">
                        <option value="-1">请选择</option>
                        <option value="1">入账</option>
                        <option value="2">出账</option>
                    </select>
                    <input type="number" id="J_money_input" class="form-control" placeholder="请输入金额">
                    <label for="">元</label>
                </div>
            </div>
            <table class="J_user_info hidden">
                <tbody>
                    <tr>
                        <td>账号</td>
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
                        <td class="J_user_blance"></td>
                    </tr>
                    <tr>
                        <td>账户类型</td>
                        <td class="J_user_type"></td>
                    </tr>
                    <tr>
                        <td>账户状态</td>
                        <td class="J_user_status"></td>
                    </tr>
                </tbody>
            </table>
            <h3 class="J_handle_remark hidden">操作原因简要：</h3>
            <textarea class="J_handle_remark hidden" id="J_remark_input" maxlength="255" placeholder="此信息可被用户查看，请认真填写，最多255个字符"></textarea>
        </div>
        <div class="section">
            <div class="back-btn-box text-right">
                <a href="/fund/deposit-and-draw-list" class="btn btn-default">返回上一页</a>
            </div>
            <div class="J_handle_remark hidden">
                <h3>操作原因详情：</h3>
                <div class="form-group form-group-sm">
                    <div class="edit-box">
                        <label class="radio-inline">
                            <input type="file" name="file" id="J_editor_img_upload" data-target="#apx_editor" style='display: none'>
                        </label>
                        <!--tabs-->
                        <div class="tab-content">
                            <div id="tab_pc" class="tab-pane fade in active">
                                <textarea id="apx_editor" name="content" style="width: 100%; height: 575px">
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="confirm-btn-box text-center J_handle_remark hidden">
        <span class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminAlertEnterDepartment">确认订单</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
</div>
<!-- 确定提示 -->
<div class="apx-modal-admin-manage-role modal fade admin-management" id="apxModalAdminAlertEnterDepartment" tabindex="-1">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <div style="height: 300px; overflow-y: auto; border: none;">
                    <div class="user-info">
                        <p class="high-lighted">
                            操作类型：<span class="J_handle_type"></span>
                        </p>
                        <p class="high-lighted">
                            操作金额：<span class="J_handle_money"></span>
                        </p>
                        <p>
                            账号：<span class="J_user_account"></span>
                        </p>
                        <p>
                            手机号：<span class="J_user_mobile"></span>
                        </p>
                        <p>
                            用户名称：<span class="J_user_name"></span>
                        </p>
                        <p>
                            角色：<span class="J_user_role"></span>
                        </p>
                        <p>
                            所属区域：<span class="J_user_area"></span>
                        </p>
                        <p>
                            余额：<span class="J_user_blance"></span>
                        </p>
                        <p>
                            账户类型：<span class="J_user_type"></span>
                        </p>
                        <p>
                            账户状态：<span class="J_user_status"></span>
                        </p>
                    </div>
                    <p>
                        操作原因简要：<span class="J_brief"></span>
                    </p>
                    <p>操作原因详情：</p>
                    <div class="J_detail"></div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-lg btn-danger" id="J_create_btn">确认</a>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script src="/vender/kindeditor/kindeditor-all-min.js"></script>