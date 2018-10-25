<?php
use custom\assets\EmptyAsset;

EmptyAsset::register($this)->addJs('js/login.js')->addCss('css/login.css');

$this->title = '九大爷平台 - 登录';
?>

<div class="login-container">
  <a href="/"><img src="/images/new_login/logo.png" class="title-img"></a>
  <div class="apex-table-container">
    <div class="apex-login-box">
      <p class="title">登录</p>
      <form class="form-horizontal">
        <!-- addClass 'hidden' to hide the error msg -->
        <p class="form-control-static text-center text-danger hidden J_error_msg error-msg-control"><i class="glyphicon glyphicon-remove"></i></p>
        <div class="form-group">
          <label for="username" class="col-xs-2 control-label">
            <i class="label-img username-img"></i>
          </label>
          <div class="col-xs-10">
            <input type="text" class="form-control" id="username" placeholder="请输入您的用户名或者手机号码">
          </div>
          <div class="error-container hidden">
            <img src="/images/new_login/icon_10_wrong.png">
            <span class="error-msg">用户名不能为空！</span>
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-xs-2 control-label">
            <i class="label-img password-img"></i>
          </label>
          <div class="col-xs-10">
            <input type="password" class="form-control" id="password" placeholder="请输入您的密码">
          </div>
          <div class="error-container hidden">
            <img src="/images/new_login/icon_10_wrong.png">
            <span class="error-msg">密码不能为空</span>
          </div>
        </div>
        <div class="form-group">
          <label for="captcha" class="col-xs-2 control-label">
            <i class="label-img captcha-img"></i>
          </label>
          <div class="col-xs-7">
            <input type="text" class="form-control" id="captcha" placeholder="请输入验证码" maxlength="4">
          </div>
          <div class="col-xs-3">
            <p class="text-center text-white J_verify_captcha captcha-control"></p>
          </div>
          <div class="error-container hidden">
            <img src="/images/new_login/icon_10_wrong.png">
            <span class="error-msg">验证码不能为空！</span>
          </div>
        </div>
        <div class="form-group form-border">
          <div class="btn btn-danger btn-block btn-lg J_login_btn">登录</div>
        </div>
        <div class="form-group form-border">
        <a href="/register" class="pull-left btn-link btn btn-xs J_login_panel_toggle">立即注册</a>
          <a href="/forget" class="pull-right btn-link btn btn-xs J_login_panel_toggle">忘记密码？</a>
        </div>
      </form>
      <div class="clearfix"></div>
      <div class="wechat-login col-xs-10 col-xs-offset-1">
        <p>或者，微信一键登录</p>
        <a href="#" class="btn btn-link btn-sm" id="J_wechat_login">
          <img class="img-responsive" src="/images/new_login/icon_42_wechat.png" alt="wechat_login">
        </a>
      </div>
    </div>
  </div>
  <div class="footer-box"></div>
  <footer class="apx-footer container-fluid text-center">
      <a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a>&nbsp;&nbsp;|&nbsp;
      <span>创智汇（苏州）电子商务有限公司版权所有</span>&nbsp;&nbsp;|&nbsp;
      <span>电话：400-0318-119   &nbsp;&nbsp;|&nbsp;  邮箱：<a href="mailto: kf@9daye.com.cn ">kf@9daye.com.cn </a></span>
  </footer>
</div>