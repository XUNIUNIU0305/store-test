<?php
$this->params = ['css' => 'css/open-account.css', 'js' => 'js/open-account.js'];
?>
<div class="business-bank-info">
    <div class="title">填写银行信息</div>
    <div class="form-inline col-xs-offset-4">
        <div class="form-group">
            <label>账户类型</label>
            <div class="bank-btn-group" id="J_user_type">
                <span class="btn" data-type="1">个人</span>
                <span class="btn" data-type="0">企业</span>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="text-danger hidden" id="J_type_tip">企业账户需在绑定成功后进行激活操作</span>
        </div>
        <div class="form-group hidden">
            <label>账户类型</label>
            <div class="bank-btn-group">
                <span class="btn active">企业账号</span>
                <span class="btn">卡</span>
            </div>
        </div>
        <div class="form-group">
            <label>开户户名</label>
            <input type="text" class="form-control" id="J_user_name" placeholder="请填写开户户名">
        </div>
        <div class="form-group">
            <label>银行账号</label>
            <input type="text" class="form-control" id="J_bank_code" placeholder="请填写银行账号">
        </div>
        <div class="form-group">
            <label>选择银行</label>
            <p class="bank-name" id="J_select_bank_name">
                <span>请选择银行</span>
            </p>
            <div class="select-bank-list hidden" id="J_select_bank_box">
                <ul id="J_bank_list">
                    <li>
                        <span>请选择银行</span>
                    </li>
                    <li>
                        <img src="/images/bank/jianshe_logo.png">
                        <span>建设银行</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="form-group">
            <label>开户支行</label>
            <div class="search-bank-box">
                <p class="select-bank text-ellipsis" id="J_select_bank">请选择开户支行</p>
                <div class="search-bank hidden" id="J_search_bank">
                    <input type="text" class="search-input" id="J_search_input" placeholder="请输入关键字或联行号">
                    <span class="search-icon btn" id="J_search_icon"><i class="glyphicon glyphicon-search"></i> 搜索 </span>
                    <ul id="J_sub_bank"></ul>
                </div>
            </div>
            <p class="area-select hidden"><span id="J_select_info"></span><span class="open" data-toggle="modal" data-target="#modalArea" id="J_open_select">我要缩小选择范围&gt;</span></p>
        </div>
        <div class="form-group">
            <label>证件类型</label>
            <select class="selectpicker" id="J_id_type" data-width="292">
                <option value="-1">请选择证件类型</option>
            </select>
        </div>
        <div class="form-group">
            <label>证件号码</label>
            <input type="text" class="form-control" id="J_id_code" placeholder="请填写证件号码">
        </div>
        <div class="form-group">
            <label>银行手机</label>
            <input type="text" class="form-control" id="J_mobile" placeholder="请填写银行预留手机号码" maxlength="11">
        </div>
    </div>
    <p class="handle-box">
        <span class="btn" data-toggle="modal" data-target="#modalBandkTip">绑定</span>
        <span class="error-tip" data-toggle="modal" data-target="#errorTip">常见错误</span>
    </p>
</div>
<div class="modal fade apx-modal-business-alert bank-modal" tabindex="-1" role="dialog" id="modalArea">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">开户行筛选</h4>
            </div>
            <div class="modal-body">
                <div class="bank-list">
                    <p class="title">请选择开户银行（非必选）：</p>
                    <div class="list-box" data-id=""></div>
                </div>
                <div class="area-list">
                    <p class="title">请选择开户地区（非必选）：</p>
                    <div class="row address">
                        <div class="col-xs-4">
                            <select class="selectpicker J_province" data-width="100%">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <select class="selectpicker J_city" data-width="100%">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <select class="selectpicker J_district" data-width="100%">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="J_select_sure">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-business-alert modal fade" tabindex="-1" role="dialog" id="modalBandkTip">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">提示窗口</h4>
            </div>
            <div class="modal-body">
                <p class="h3">确定提交信息？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="J_submit_info" style="background-color: #459ae9;border-color: #459ae9;">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-business-alert modal fade" tabindex="-1" role="dialog" id="businessCommonAlert">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">提示窗口</h4>
            </div>
            <div class="modal-body">
                <p class="h3" id="businessCommonAlertMsg">确定提交信息？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-business-alert modal fade" tabindex="-1" role="dialog" id="modalLoading">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <button type="button" class="close hidden"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">提示窗口</h4>
            </div>
            <div class="modal-body">
                <p class="h3" id="businessCommonAlertMsg">正在处理中...</p>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default hidden" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-business-alert modal fade" tabindex="-1" role="dialog" id="errorTip">
    <div class="modal-dialog " role="document" style="height: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">常见错误提示</h4>
            </div>
            <div class="modal-body" style="margin: 0 20px;">
                <p class="h3 text-danger text-left" id="businessCommonAlertMsg">身份认证失败/信息认证未通过</p>
                <p class=" text-left">
                    　　银行账户信息（开户名、账户号码、预留手机号码、证件号码）错误，请确保上述信息的正确性、一致性（与开户时提交的信息相同）。
                    <br>　　如果您的银行账户是企业账户，且开户名中包含括号“（）”，请确保括号的全/半角（中/英括号）一致，以开户时银行柜员录入的信息为准。
                </p>
                <p class="h3 text-danger text-left">尝试次数过多</p>
                <p class="text-left">　　连续多次提交错误信息后，银行拒绝校验，请过段时间再重新提交。
尝试次数与等待时间根据不同银行会有所不同；<br>　　尝试次数一般为三至五次，等待时间一般为半小时，个别银行当天提交次数过多后只能等到次日再提交。</p>
                <p class="h3 text-danger text-left">暂不支持该银行</p>
                <p class="text-left">
                    　　您选择的支行暂不支持校验，请重新选择其他支行。
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>