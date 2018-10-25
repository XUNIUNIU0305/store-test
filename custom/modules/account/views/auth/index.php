<?php
$this->params = ['js' => 'js/auth-index.js', 'css' => 'css/auth-index.css'];
?>
<div class="top-title">递交审核信息</div>
<div class="panel panel-default apx-acc-audit-info">
    <div class="panel-body">
        <p class="error-msg in">*<span id="J_head_title">请上传您的门店资质信息，我们的工作人员会在1个工作日内进行审批，通过后即可进行消费</span></p>
        <form class="form-horizontal" role="form">
            <div class="form-group ">
                <label class="col-xs-2" for="shop_name">门店名称：</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" maxlength="10" id="shop_name" placeholder="">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2 no" for="company_name">公司名称:</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" maxlength="20" id="company_name" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-2" for="">门店所属区域：</label>
                <div class="col-xs-8">
                    <div class="row address">
                        <div class="col-xs-4">
                            <select class="selectpicker J_province" id="J_province" data-dropup-auto="false" data-width="100%">
                            <option value="">请选择</option>
                        </select>
                        </div>
                        <div class="col-xs-4">
                            <select class="selectpicker J_city" id="J_city" data-dropup-auto="false" data-width="100%">
                            <option value="">请选择</option>
                        </select>
                        </div>
                        <div class="col-xs-4">
                            <select class="selectpicker J_district" id="J_district" data-dropup-auto="false" data-width="100%">
                            <option value="">请选择</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2" for="address">门店具体地址:</label>
                <div class="col-xs-10">
                    <textarea rows="2" id="address" maxlength="50" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2" for="responsor">负责人:</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" maxlength="10" id="responsor" placeholder="">
                </div>
            </div>

            <div class="form-group ">
                <label class="col-xs-2 no" for="shop_name">负责人身份证:</label>
                <div class="col-xs-10">
                    <label class="img-upload-box" for="upload_img_1" style="width: 300px; height: 188px;">
                        <div class="lint">
                            修改身份证正面
                        </div>
                        <input type="file" id="upload_img_1">
                        <img id="J_card_front" src="/images/add_card_icon.png">
                    </label>
                    <label class="img-upload-box" for="upload_img_2" style="width: 300px; height: 188px;">
                        <div class="lint">
                            修改身份证背面
                        </div>
                        <input type="file" id="upload_img_2">
                        <img id="J_card_back" src="/images/add_card_icon.png">
                    </label>
                </div>
            </div>
            
            <div class="form-group ">
                <label class="col-xs-2 no" for="contact">联系人:</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" maxlength="10" id="contact" placeholder="">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2 no" for="phone">联系人手机号:</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" maxlength="11" id="phone" placeholder="">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2" for="email">电子邮箱:</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" id="email" placeholder="">
                </div>
            </div>
            <div class="form-group hidden">
                <label class="col-xs-2 optional" for="tel1">门店固话:</label>
                <div class="col-xs-2">
                    <input type="text" class="form-control" id="tel1" placeholder="">
                </div>
                <div class="col-xs-3">
                    <input type="text" class="form-control" id="tel2" placeholder="">
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2 no" for="shop_name">营业执照照片:</label>
                <div class="col-xs-10">
                    <label class="img-upload-box" for="upload_img_3" style="width: 200px; height: 220px;">
                        <div class="lint">
                            修改
                        </div>
                        <input type="file" id="upload_img_3">
                        <img class="img" id="J_business_licence" src="/images/add_card_icon1.png">
                    </label>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-xs-2 no" for="shop_name">门店照片:</label>
                <div class="col-xs-10">
                    <label class="img-upload-box" for="upload_img_4" style="width: 200px; height: 220px;">
                        <div class="lint">
                            修改门店门面照片
                        </div>
                        <input type="file" id="upload_img_4">
                        <img class="img" id="J_store_front" src="/images/add_card_icon1.png">
                    </label>
                    <label class="img-upload-box" for="upload_img_5" style="width: 200px; height: 220px;">
                        <div class="lint">
                            修改门店店内照片
                        </div>
                        <input type="file" id="upload_img_5">
                        <img class="img" id="J_store_inside" src="/images/add_card_icon1.png">
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-10 col-xs-offset-2">
                     <div class="checkbox">
                        <label class="optional">
                            <input type="checkbox" id="J_agreement"> 我已经阅读并同意<a href="#" data-target="#apxModalAgreement" data-toggle="modal">《九大爷协议》</a>
                        </label>
                    </div>
                </div>
            </div>
        </form>
        <a class="btn btn-danger btn-lg btn-block" id="J_submit_info">提交</a>
    </div>
</div>

