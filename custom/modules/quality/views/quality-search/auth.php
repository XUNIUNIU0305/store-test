<?php
$this->params = ['js' => ['js/quality_global.js', 'js/auth.js'], 'css' => ['css/quality_global.css', 'css/auth.css']];

$this->title = '九大爷平台 - 质保单查询';
?>

<div class="custom-qulity-query">
    <div class="query-title">
        <span>九大爷质保查询系统</span>
    </div>
	<div class="content">
		<div class="tab-container">
            <ul class="nav" role="tablist">
                <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">车主查询</a></li>
                <li role="presentation" ><a href="#service" role="tab" data-toggle="tab">服务商登录</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="carCode" class="control-label">
                                请输入车牌号或车架号查询
                            </label>
                            <div>
                                <input type="text" class="form-control" id="carCode" maxlength="17" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mobile" class="control-label">
                                请输入车主手机号
                            </label>
                            <div>
                                <input type="text" class="form-control" maxlength="11" id="mobile" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="captcha" class="control-label">
                                请输入验证码
                            </label>
                            <div class="captcha">
                                <input type="text" class="form-control" id="captcha" autocomplete="off">
                                <span class="text-center text-white" id="J_get_captcha">获取验证码</span>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <span class="btn btn-search" id="J_owner_search">立即查询</span>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="service">
                    <div class="form-horizontal service">
                        <div class="form-group">
                            <label for="quality_account" class="control-label">
                                请输入在九大爷注册的用户名或手机号码
                            </label>
                            <div>
                                <input type="text" class="form-control" maxlength="11" id="quality_account" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mobile2" class="control-label">
                                请输入账号密码
                            </label>
                            <div>
                                <input type="password" class="form-control" maxlength="20" id="J_login_pwd" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="captcha2" class="control-label">
                                请输入验证码
                            </label>
                            <div class="captcha">
                                <input type="text" class="form-control" maxlength="4" id="captcha2" autocomplete="off">
                                <span class="text-center text-white J_verify_captcha"></span>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <span class="btn btn-search" id="J_login_now">立即登录</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="apx-footer container-fluid text-center">
            <a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a>&nbsp;&nbsp;|&nbsp;
            <span>创智汇（苏州）电子商务有限公司版权所有</span>&nbsp;&nbsp;|&nbsp;
            <span>电话：400-0318-119   &nbsp;&nbsp;|&nbsp;  邮箱：<a href="mailto: kf@9daye.com.cn ">kf@9daye.com.cn </a></span>
        </footer>
	</div>
</div>
