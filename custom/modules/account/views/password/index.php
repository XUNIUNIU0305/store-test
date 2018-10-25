<?php
$this->params = [
    'css' => 'css/password.css',
    'js' => 'js/password.js',
];
?>
<div class="acc-edit-pwd">
    <div class="title">修改密码</div>
    <div class="form-box">
        <p class="error-msg text-danger text-center hidden">错误信息</p>
        <div class="form">
            <div class="form-group">
                <label for="pwd_old" class=" text-right control-label">输入旧密码：</label>
                <div class="">
                    <input type="password" id="pwd_old" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="new_old1" class=" text-right control-label">输入新密码：</label>
                <div class="">
                    <input type="password" id="new_old1" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="new_old2" class=" text-right control-label">再次输入新密码：</label>
                <div class="">
                    <input type="password" id="new_old2" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="validate" class=" text-right control-label">验证码：</label>
                <div class="">
                    <input type="text" id="validate" class="form-control" value="">
                </div>
                <div class="">
                    <div class="validate-box J_verify_captcha"></div>
                </div>
                <div>
                    <a href="javascript:;" id="J_change_captcha" style="line-height: 40px; color: #1f66d1;">换一张</a>
                </div>
            </div>
        </div>
        <span class="btn btn-danger" id="J_change_pwd">确定</span>
    </div>
</div>
