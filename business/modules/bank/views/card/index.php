<?php
$this->params = ['css' => 'css/card.css', 'js' => 'js/card.js'];
?>
<div class="business-bank-card">
    <div class="title">
        我的银行账户
        <p class="tip">
            如何更换银行账户？
            <span>每个账户仅限绑定一张默认银行卡；如需更换，请“解除绑定”后重新添加账户！</span>
        </p>
    </div>
    <div class="bank-card">
        <a href="/bank/open-account" class="hidden" id="J_no_bind">
            <div class="card">
                <p>添加新的银行账户</p>
            </div>
        </a>
        <div class="have-card hidden" id="J_is_bind">
            <div class="head clearfix">
                <div class="logo pull-left">
                    <img id="J_bank_img" src="/images/bank/jianshe_logo.png">
                </div>
                <div class="bank-name pull-left">
                    <p id="J_bank_name"></p>
                    <p id="J_bank_type"></p>
                </div>
                <span id="J_bank_status" class="status text-danger pull-right">未激活</span>
            </div>
            <p class="number" id="J_bank_code"></p>
            <p class="h3 text-center"><span id="J_user_name"></span></p>
            <div class="detail hidden clearfix">
                <div class="pull-left">
                    <p>归属地区</p>
                    <p class="h4"></p>
                </div>
                <div class="pull-left">
                    <p>开户支行</p>
                    <p class="h4"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="handle-box hidden" id="J_handle_box">
        <span class="btn btn-yellow hidden" id="J_active_btn">申请激活</span>
        <span class="btn btn-yellow" data-toggle="modal" data-target="#modalBandkTip">解除绑定</span>
    </div>
    <div class="handle-box hidden" id="J_input_box">
        <div class="form-group">
            <label>请输入验证金额</label>
            <input type="text" class="form-control" id="J_money_input" placeholder="请输入验证金额">
            <span class="btn btn-default" id="J_cancle_btn">取消</span>
            <span class="btn btn-primary" id="J_submit_money">确定</span>
            <p class="text-danger text-left h5" style="margin: 20px auto; width: 470px">验证金额有限期为两小时，有效期内无法重新发送；<br>
            验证金额连续输错三次该笔金额即为失效，需<span class="text-primary" data-toggle="modal" data-target="#modalBandkConfirm" style="cursor: pointer;line-height: 24px;">重新申请激活</span>。
            </p>
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
                <p>确定申请激活此账户？</p>
                <p class="h4" style="line-height: 24px;">申请激活成功后，您的企业账户上将收到一笔小额转账(0.01-1元)；您需确认该笔汇款后，提交汇款金额以完成激活！</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="J_active_sure">确定</button>
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
                <p class="h3">解除绑定？</p>
                <p class="h4" style="margin: 0 20px;">您确定解除绑定的银行账户吗？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="J_unbind_btn">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default hidden" data-dismiss="modal">确定</button>
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
                <p class="h3" id="businessCommonAlertMsg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>