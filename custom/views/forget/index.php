<?php
use custom\assets\EmptyAsset;

EmptyAsset::register($this)->addJs('js/forget.js')->addCss('css/forget.css');

$this->title = '九大爷平台 - 重置密码';
?>

<div class="forget-container">
    <a href="/"><img src="/images/new_login/logo.png" class="title-img"></a>
    <div class="login-wrap">
        <form class="login-form" role="form">
            <!--title-->
            <p class="title">找回密码</p>
            <!--row-->
            <div class="forget-group">
              <div class="form-group form-group-sm">
                  <label for="password1">设置新密码</label>
                  <div class="row">
                      <div class="col-xs-12">
                          <input type="password" class="form-control" id="password1" maxlength="20" minlength="8" placeholder="请填写新密码">
                      </div>
                      <div class="col-xs-12 text-danger error-msg error-control">密码只能包含数字和字母</div>
                  </div>
              </div>
              <!--row-->
              <div class="form-group form-group-sm">
                  <label for="password">确认新密码</label>
                  <div class="row">
                      <div class="col-xs-12">
                          <input type="password" class="form-control" id="password" maxlength="20" minlength="8" placeholder="请重复填写新密码">
                      </div>
                      <div class="col-xs-12 error-msg error-control text-danger">两次密码不一致</div>
                  </div>
              </div>
              <!--row-->
              <div class="form-group form-group-sm J_mobile">
                  <label for="mobile">已绑手机号</label>
                  <div class="row">
                      <div class="col-xs-12">
                          <input type="tel" class="form-control" id="mobile" placeholder="请填写您的手机号" maxlength="11">
                      </div>
                      <div class="col-xs-12 error-msg text-danger error-control">手机格式错误</div>
                  </div>
              </div>
              <!--row-->
              <div class="form-group form-group-sm">
                  <label for="validate">手机验证码</label>
                  <div class="row">
                      <div class="col-xs-8 mobile-input">
                          <input type="text" id="validate" class="mobile-control" value="" placeholder="请填写验证码">
                          <a class="mobile-btn J_get_verify_sms btn" >点击获取</a>
                      </div>
                      <div class="col-xs-12 error-msg error-control text-danger">验证码错误</div>
                  </div>
              </div>
            </div>  
            <div class="find-success hidden">
              <img src="/images/new_login/icon_120_success.png" alt="">
              <p class="find-describe">修改成功</p>
            </div>
            <div class="find-false hidden">
              <img src="/images/new_login/icon_120_false.png" alt="">
              <p class="find-describe">修改失败</p>
            </div>
            <!--row-->
            <a class="btn btn-submit J_btn_change_password">提交</a>
            <a href='/login' class="btn btn-submit hidden back-login">去登录</a>
            <a href="/forget" class="btn btn-submit hidden back-forget">重新找回</a>
            <a href="/login" class="back-home">返回登录</a>
        </form>
    </div>
    <div class="footer-box"></div>
    <footer class="apx-footer container-fluid text-center">
        <a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a>&nbsp;&nbsp;|&nbsp;
        <span>创智汇（苏州）电子商务有限公司版权所有</span>&nbsp;&nbsp;|&nbsp;
        <span>电话：400-0318-119   &nbsp;&nbsp;|&nbsp;  邮箱：<a href="mailto: kf@9daye.com.cn ">kf@9daye.com.cn </a></span>
    </footer>

</div>