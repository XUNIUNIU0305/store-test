<?php
$this->params = ['js' => 'js/coupon-create.js', 'css' => 'css/coupon-create.css'];
?>

<div class="admin-main-wrap">
    <div class="admin-coupon-container">
        <!--nav-->
        <ul class="nav nav-tabs datepicker-nav">
            <li><strong>生成优惠券</strong></li>
        </ul>
        <!--form start-->
        <form class="form-horizontal">
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">优惠券名称：</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" maxlength="30" id="coupon_name">
                </div>
                <div class="col-xs-3 error-msg">错误信息</div>
            </div>
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">优惠券面额：</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" maxlength="7" id="coupon_price">
                    <span class="suffix">元</span>
                </div>
                <div class="col-xs-3 error-msg">错误信息</div>
            </div>
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">发行量：</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" maxlength="7" id="coupon_total">
                    <span class="suffix">张</span>
                </div>
                <div class="col-xs-3 error-msg">错误信息</div>
            </div>
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">使用有效期：</label>
                <div class="col-xs-3">
                    <div class="input-group">
                        <input type="text" class="form-control date-picker" value="" id="coupon_start_time">
                        <span class="input-group-btn date-show">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                    <span class="suffix">到</span>
                </div>
                <div class="col-xs-3">
                    <div class="input-group">
                        <input type="text" class="form-control date-picker" value="" id="coupon_end_time">
                        <span class="input-group-btn date-show">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-xs-offset-2 col-xs-3 error-msg">错误信息</div>
            </div>
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">使用条件：</label>
                <div class="col-xs-3">
                    <div class="radio">
                        <label class="only">
                            满<input type="text" class="form-control" id="coupon_limit_price">元使用
                        </label>
                    </div>
                </div>
                <div class="col-xs-3 error-msg">错误信息</div>
            </div>
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">单人持有上限：</label>
                <div class="col-xs-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="upper_limit" id="upper_limit1" value="1" checked="checked">
                            <input type="text" class="form-control" id="coupon_limit_receive" value="1">张
                        </label>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="upper_limit" id="upper_limit2" value="0">
                            无上限
                        </label>
                    </div>
                </div>
                <div class="col-xs-3 error-msg">错误信息</div>
            </div>
            <div class="form-group" required>
                <label class="col-xs-3 control-label" for="">使用对象：</label>
                <div class="col-xs-4">
                    <div class="radio">
                        <label>
                            <input type="radio" name="object" id="object1" value="1" checked="checked">
                            <select class="selectpicker" data-width="320" data-live-search="true"  id="J_supplier_list">
                                <option value="-1" data-tokens="选择店铺">选择店铺</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-xs-2 hidden">
                    <div class="radio">
                        <label>
                            <input type="radio" name="object" id="object2" value="0">
                            无限制
                        </label>
                    </div>
                </div>
                <div class="col-xs-3 error-msg">错误信息</div>
            </div>
            <a class="btn btn-block btn-lg btn-danger" data-toggle="modal" data-target="#apxModalAdminCreate">生成优惠券</a>
        </form>
        <!--form end-->
    </div>
</div>
<!-- 提示信息 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminCreate" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 36px 0;">点击确认后，买家即可领取，确定现在生成吗？</div>
            </div>
            <div class="modal-footer">
                <button id="J_create_coupon"  type="button" class="btn btn-lg btn-danger">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>