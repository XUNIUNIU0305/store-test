<?php
use custom\assets\EmptyAsset;

EmptyAsset::register($this)->addJs('js/register.js')->addCss('css/register.css');

$this->title = '九大爷平台 - 注册';
?>
<div class="regist-container">
    <a href="/"><img src="/images/new_login/logo.png" class="title-img"></a>
    <div class="login-wrap">
        <form class="login-form" role="form">
            <!--title-->
            <p class="title">注册</p>
            <!--row-->
            <div class="form-group form-group-sm">
                <label for="shop_name">注册码</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control" id="shop_name" placeholder="请输入注册码" data-tmp="" maxlength="9">
                    </div>
                    <div class="col-xs-12 error-msg text-danger error-control"><i class="glyphicon glyphicon-remove"></i>注册码错误</div>
                </div>
            </div>
            <!--row-->
            <!--添加error类名显示错误信息-->
            <div class="form-group form-group-sm">
                <label for="shop_pwd1">设置新密码</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="password" class="form-control" id="shop_pwd1" maxlength="20" placeholder="请填写新密码">
                    </div>
                    <div class="col-xs-12 error-msg text-danger error-control"><i class="glyphicon glyphicon-remove"></i>密码只能包含数字和字母</div>
                </div>
            </div>
            <!--row-->
            <div class="form-group form-group-sm">
                <label for="shop_pwd2">确认新密码</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="password" class="form-control" id="shop_pwd2" maxlength="20" placeholder="请重复填写新密码">
                    </div>
                    <div class="col-xs-12 error-msg text-danger error-control"><i class="glyphicon glyphicon-remove"></i>两次密码不一致</div>
                </div>
            </div>
            <!--row-->
            <div class="form-group form-group-sm address">
                <label for="">门店所属区域</label>
                <div class="row">
                    <div class="col-xs-4">
                        <select class="selectpicker btn-group-xs J_province" data-width="100%" data-dropup-auto="false">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <select class="selectpicker btn-group-xs J_city" data-width="100%" data-dropup-auto="false">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <select class="selectpicker btn-group-xs J_district" data-width="100%" data-dropup-auto="false">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="col-xs-12 error-msg text-danger error-control"><i class="glyphicon glyphicon-remove"></i>不能为空</div>
                </div>
            </div>
    
            <div class="form-group form-group-sm J_email">
                <label for="email">联系邮箱</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control" id="email" placeholder="请填写您的电子邮箱">
                    </div>
                    <div class="col-xs-12 error-msg text-danger error-control"><i class="glyphicon glyphicon-remove"></i>格式错误</div>
                </div>
            </div>
            <!--row-->
            <div class="form-group form-group-sm J_mobile">
                <label for="mobile">联系手机号</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="tel" class="form-control" id="mobile" placeholder="请填写您的手机号" maxlength="11">
                    </div>
                    <div class="col-xs-12 error-msg text-danger error-control"><i class="glyphicon glyphicon-remove"></i>格式错误</div>
                </div>
            </div>
            <!--row-->
            <div class="form-group form-group-sm">
                <label for="validate">验证码</label>
                <div class="row">
                    <div class="col-xs-8 mobile-input">
                        <input type="text" id="validate" class="form-control" value="">
                    </div>
                    <div class="col-xs-2">
                        <a class="btn btn-default mobile-btn J_get_verify_sms">点击获取</a>
                    </div>
                </div>
            </div>
            <!--row-->
            <a class="btn btn-submit J_register_submit">提交</a>
            <div class="form-group form-border">
                <a href="/login" class="pull-right btn-link btn btn-xs J_login_panel_toggle">返回登录</a>
            </div>
        </form>
    </div>
    <div class="footer-box"></div>
    <footer class="apx-footer container-fluid text-center">
        <a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a>&nbsp;&nbsp;|&nbsp;
        <span>创智汇（苏州）电子商务有限公司版权所有</span>&nbsp;&nbsp;|&nbsp;
        <span>电话：400-0318-119   &nbsp;&nbsp;|&nbsp;  邮箱：<a href="mailto: kf@9daye.com.cn ">kf@9daye.com.cn </a></span>
    </footer>
</div>