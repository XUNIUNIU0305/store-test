<?php
$this->params = ['js' => 'js/wechat.js', 'css' => 'css/wechat.css'];

$this->title = '九大爷平台 - 账户中心 - 微信绑定';
?>
<div class="top-title">账户绑定</div>
<div class="panel panel-default apx-acc-third-party">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <div class="media-object">
                    <img src="/images/account/wechat.png">
                </div>
            </div>
            <div class="media-body media-middle">
                <span class="third-describe">微信</span>
                <span class="binding-status unbound" id="J_binding_status"></span>
                <a data-toggle="collapse" href="#aside_panel_banding_1" class="collapsed pull-down" id="J_bind_show">展开</a>
            </div>
            <div class="collapse binding-detail clearfix" id="aside_panel_banding_1">
                <div class="detail-left">
                    <p class="prompt-msg" id="J_prompt_msg"></p>
                    <a href="#" class="btn btn-danger" id="J_bind_btn" data-toggle="modal" data-target="#apxModalAdminAlertEnterRole">解除绑定</a>
                </div>
                <div class="detail-right hidden">
                    <img src="" alt"" id="J_bind_img">
                    <span>用户名：</span>
                    <span class="wechat-name" id="J_bind_name"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default apx-acc-third-party hidden">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <div class="media-object">
                    <img src="/images/account/alipay.png">
                </div>
            </div>
            <div class="media-body media-middle">
                <span class="third-describe">绑定支付账号</span>
                <span class="binding-status">已绑定</span>
                <a data-toggle="collapse" href="#aside_panel_banding_2" class="collapsed pull-down">解绑</a>
            </div>
        </div>
        <div class="collapse binding-detail" id="aside_panel_banding_2" aria-expanded="false">
            <div class="detail-left">
                <p class="prompt-msg">绑定后可以使用支付宝账号一键登录九大爷购物平台</p>
                <a href="#" class="btn btn-danger">立即绑定</a>
            </div>
            <div class="detail-right"></div>
        </div>
    </div>
</div>
<div class="panel panel-default apx-acc-third-party hidden" id="J_remove_bind">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <div class="media-object">
                    <img class="J_bind_img" src="/images/account/wechat.png">
                </div>
            </div>
            <div class="media-body media-middle">
                <div class="col-xs-4">
                    <!-- <img src="/images/account/wechat.png" width="32px"> -->
                    <p>用户名：<span class="J_bind_name"></span></p>
                </div>
                <div class="col-xs-6">
                    <!-- <p>所在区域： 中国上海</p> -->
                    <p>绑定时间：<span class="J_bind_time"></span></p>
                </div>
                <div class="col-xs-2 hidden">
                    <a href="#" class="btn btn-danger">解除绑定</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 绑定提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertEnterRole" tabindex="-1">
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
                        <label for="name">请输入账号密码：</label>
                        <input type="password" class="form-control" id="J_password">
                    </div>
                </div>	
            </div>
						<div class="error-msg text-danger text-center hidden">
							请输入账号密码！
						</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_add_role">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>