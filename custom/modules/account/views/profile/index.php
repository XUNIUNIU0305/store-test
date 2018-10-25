<?php
$this->params = ['js' => 'js/profile.js', 'css' => 'css/profile.css'];

$this->title = '九大爷平台 - 账户中心 - 手机绑定';
?>
<div class="top-title">
    修改基本信息
</div>
<div class="br"></div>
<div class="apx-acc-edit-profile J_edit_profile">
    <div class="panel-body">
        <form class="form-horizontal" role="form">
            <div class="form-group ">
                <label class="col-xs-3" for="shop_name">账户头像：</label>
                <div class="col-xs-9">
                    <label class="img-upload-box J_header_img" for="upload_img">
                        <div class="lint">
                            <i>+</i>
                            上传头像
                        </div>
                        <input type="file" id="upload_img">
                        <img src="" data-filename="" class="hide">
                     </label>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-3" for="shop_name">店铺名称：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="shop_name" maxlength="60" placeholder="请输入店铺名称">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-3" for="nickname">账户昵称：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="nick_name" maxlength="60" placeholder="请输入账户昵称">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-3" for="">门店所属区域：</label>
                <div class="col-xs-9">
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
            <div class="form-group ">
                <label class="col-xs-3" for="email">联系邮箱：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="email" maxlength="100" placeholder="请填写您的电子邮箱">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-3" for="email">开票抬头：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="invoice_name" maxlength="100" placeholder="请填写您的开票抬头">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-3" for="email">开票税号：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="invoice_no" maxlength="100" placeholder="请填写您的开票税号">
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-3" for="verify">手机验证码：</label>
                <div class="col-xs-7">
                    <input type="text" class="form-control" id="verify" maxlength="6">
                </div>
                <div class="col-xs-2">
                    <a class="btn btn-block btn-default J_get_verify_sms">获取验证码</a>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-9 col-xs-offset-3 J_error_msg hidden">
                    <p class="text-danger">警告：店铺名称不能为空！</p>
                </div>
            </div>
        </form>
        <a class="btn btn-danger btn-lg btn-block J_btn_save_profile">确认修改</a>
    </div>
</div>
</div>
