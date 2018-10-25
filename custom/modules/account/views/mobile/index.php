<?php
$this->params = ['js' => 'js/mobile.js', 'css' => 'css/mobile.css'];

$this->title = '九大爷平台 - 账户中心 - 手机绑定';
?>
<div class="top-title">绑定手机</div>
<div class="br"></div>
<div class="acc-bind-mobile">
    <!-- 未绑定状态 -->
    <div class="form-box hidden">
        <div class="bind-status clearfix">
            <span class="pull-left">绑定手机</span>
            <span class="pull-right">完成</span>
        </div>
        <div class="form-container">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail3" class="col-xs-2 control-label">验证手机：</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control new-mobile-num mobile-ipt" id="inputEmail3" onKeyUp="value=value.replace(/[^\d]/g,'')" placeholder="请输入手机号码！" maxlength="11">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-xs-2 control-label">手机验证码：</label>
                    <div class="col-xs-7 ipt-control">
                        <input type="text" class="form-control verify-new-code" id="inputPassword3" placeholder="请输入短信验证码！">
                    </div>
                    <div class="col-xs-3">
                        <a href="#" class="btn btn-default J_get_verify_sms">获取验证码</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-4 col-xs-8">
                        <button type="submit" class="btn btn-default btn-danger new-mobile-btn">下一步</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bind-success ">
            <div class="text-center">
                <img src="/images/new-account/bind/succeed.png" alt="">
                <p class="h2">手机绑定成功！</p>
                <p class="jump">
                    点击跳转至
                    <a href="">手机绑定</a>
                    主页
                </p>
            </div>
        </div>
        <div class="footer-tip">
            <p class="h3">为什么绑定手机？</p>
            <p class="h4">为保障您的账户信息安全，绑定手机后将提高您的账户安全系数！</p>
        </div>
    </div>
<!-- 已绑定、更改绑定成功、 -->
    <div class="bind-over text-center hidden">
        <img src="/images/new-account/bind/iPhone.png" alt="">
        <p>
            已绑定手机号：
            <span id="J_mobile"></span>
            <a href="#" class="edit">修改</a>
        </p>
    </div>
<!-- 更改绑定 -->
    <div class="change-bind hidden">
        <div class="change-status">
            <span>验证身份</span>
            <span>设置新手机号</span>
            <span>完成</span>
        </div>
        <div class="form-container">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail3" class="col-xs-2 control-label">手机号码：</label>
                    <div class="col-xs-10">
                        <p class="num"></p>
                        <input type="text" class="form-control change-mobile  mobile-ipt hidden" id="inputPassword3" placeholder="请输入新手机号码！" maxlength="11">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-xs-2 control-label">手机验证码：</label>
                    <div class="col-xs-7 ipt-control">
                        <input type="text" class="form-control change_verify_ipt" id="inputPassword3" placeholder="请输入短信验证码！">
                        <input type="text" class="form-control change_new_ipt hidden" id="inputPassword3" placeholder="请输入短信验证码！">
                    </div>
                    <div class="col-xs-3">
                        <a href="#" class="btn btn-default J_get_verify_sms">获取验证码</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-4 col-xs-8">
                        <button type="submit" class="btn btn-default btn-danger" id="change_mobile_btn">下一步</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 初次绑定成功 -->
    <div class="bind-success hidden">
        <div class="text-center">
            <img src="/images/new-account/bind/succeed.png" alt="">
            <p class="h2">手机绑定成功！</p>
            <p class="jump">
                点击跳转至
                <a href="">手机绑定</a>
                主页
            </p>
        </div>
    </div>
    <div class="footer-tip prompt-msg2 hidden">
        <p class="h3">为什么验证手机？</p>
        <p class="h4">为保障您的账户信息安全，在变更账户信息时需要进行身份验证，感谢您的理解与支持！</p>
    </div>
</div>

 
<!-- <div class="top-title">手机绑定</div>
<div class="panel panel-default apx-acc-bind-mobile">
    <div class="panel-body">

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active J_step_1" id="tab_mobile_binded">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-xs-3 J_new_mobile_caption" for="mobile">手机号码：</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" id="mobile" maxlength="11" placeholder="请填写新手机号">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3" for="verify_old">短信验证码：</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" id="verify_old" maxlength="6">
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-block btn-default J_get_verify_sms">获取验证码</button>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-xs-9 col-xs-offset-3 hidden old_err">
                            <p class="text-danger">警告：您输入的手机不符合规定！</p>
                        </div>
                    </div>
                </form>
                <button class="btn btn-danger btn-lg btn-block J_btn_first">确认绑定</button>
            </div>

            <div role="tabpanel" class="tab-pane fade in J_step_2" id="tab_bind_mobile">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-xs-3" for="new_mobile">新手机号：</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" maxlength="11" id="new_mobile"
                                   placeholder="请填写新手机号">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3" for="verify"> 手机验证码：</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" id="new_verify_code" maxlength="6">
                        </div>
                        <div class="col-xs-2">
                            <a class="btn btn-block btn-default J_get_verify_sms">获取验证码</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-9 col-xs-offset-3 hidden new-err">
                            <p class="text-danger ">警告：您输入的手机不符合规定！</p>
                        </div>
                    </div>
                </form>
                <a class="btn btn-danger btn-lg btn-block J_btn_bind_new">确认绑定</a>
            </div>
        </div>
    </div>
</div>
</div> -->
