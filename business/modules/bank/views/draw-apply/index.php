<?php
$this->params = ['css' => 'css/draw-apply.css', 'js' => 'js/draw-apply.js'];
?>
<div class="business-bank-extract">
    <div class="header">
        <span class="h3">提取余额到银行账户</span>
        <span class="balance">余额：<span id="J_user_balance"></span>元</span>
        <span class="tip hidden"><i class="glyphicon glyphicon-info-sign"></i>提现申请间隔不少于7天</span>
        <a href="/account/index" class="btn pull-right hidden">返回上一级</a>
        <a href="/bank/draw-list" class="btn pull-right hidden">提现记录</a>
    </div>
    <div class="info">
        <div class="form-inline">
            <div class="form-group">
                <label>*银行信息：</label>
                <div class="add-new hidden" id="J_no_bind"><i class="glyphicon glyphicon-remove-sign"></i><span>未绑定银行账户</span></div>
                <div class="bank-info hidden" id="J_is_bind">
                    <p class="name">开户户名&nbsp;<span id="J_user_name"></span></p>
                    <div class="card">
                        <img src="/images/bank/jianshe_logo.png" alt="" id="J_bank_logo">
                        <span id="J_bank_name"></span>
                        <span id="J_bank_code"></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="text-danger hidden" id="J_go_active">未激活</span>
                    </div>
                </div>
            </div>
            <div id="J_input_box">
                <div class="form-group">
                    <label>*提现金额：</label>
                    <input type="text" class="form-control" id="J_extract_num" placeholder="请输入提现金额">元
                    <p class="error text-danger">*提现金额必须为100的整数倍！</p>
                    <p class="balance-tip hidden">您还可以提现0.00元，单笔最低提现金额100.00元</p>
                </div>
                <div class="form-group">
                    <a href="javascript:;" class="btn btn-business disabled" id="J_next_btn">下一步</a>
                    <span class="text-danger" style="margin-left: 34px">*提现时间为每天9:00-16:30</span>
                </div>
            </div>
            <div id="J_sure_box" class="hidden">
                <div class="form-group">
                    <label>*提现金额：</label>
                    <p class="price" id="J_price"></p>
                </div>
                <div class="br"></div>
                <div class="form-group">
                    <label>手机验证码：</label>
                    <input type="text" class="form-control" id="J_captcha_input">
                    <span class="btn btn-business" id="J_captcha_btn">获取验证码</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="J_tip_mobile"></span>
                </div>
                <div class="form-group">
                    <label>登录密码：</label>
                    <input type="password" class="form-control" id="J_pass_word">
                    <span class="btn btn-business invisible">获取验证码</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>本站登录密码</span>
                </div>
                <div class="form-group">
                    <a href="javascript:;" class="btn btn-business" id="J_extract_sure">确认提现</a>
                    <a href="javascript:;" class="back" id="J_back">返回修改</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-business-alert modal fade" tabindex="-1" role="dialog" id="modalBandkConfirm">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">提示窗口</h4>
            </div>
            <div class="modal-body">
                <p>我是提示文字</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">确定</button>
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
