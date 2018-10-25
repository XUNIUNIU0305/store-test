<?php
$this->params = ['js' => 'js/coupon-active.js', 'css' => 'css/coupon-active.css'];
?>

<div class="apx-acc-title bg-danger">
    激活优惠券
</div>
<!--表单-->
<div class="apx-coupon-active" id="J_coupon_info">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="control-label col-xs-3" for="">优惠券序列号：</label>
            <div class="col-xs-6">
                <ul class="list-inline">
                    <li><input type="text" maxlength="3" class="form-control J_coupon_code"></li>
                    <li><input type="text" maxlength="3" class="form-control J_coupon_code"></li>
                    <li><input type="text" maxlength="3" class="form-control J_coupon_code"></li>
                    <li><input type="text" maxlength="3" class="form-control J_coupon_code"></li>
                    <li><input type="text" maxlength="3" class="form-control J_coupon_code"></li>
                </ul>
            </div>
            <div class="col-xs-3 text-danger error-msg">请填写有效序列号</div>
            <div class="col-xs-6 col-xs-offset-3 text-muted form-control-static">请输入优惠券15位序列号</div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-3" for="">优惠券密码：</label>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="password" class="form-control" id="J_coupon_pwd">
                    <div class="input-group-btn">
                        <a href="javascript: void(0)" class="btn btn-link" onclick="$(this).parent().siblings().attr('type') === 'password' ? $(this).children('i').attr('class','glyphicon glyphicon-eye-close').parents('.input-group-btn').siblings().attr('type', 'text') : $(this).children('i').attr('class','glyphicon glyphicon-eye-open').parents('.input-group-btn').siblings().attr('type', 'password');">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xs-3 col-xs-offset-2 text-danger error-msg">请填写有效密码</div>
            <div class="col-xs-6 col-xs-offset-3 text-muted form-control-static">请输入优惠券8位密码</div>
        </div>
    </form>
    <div class="text-center">
        <button class="btn btn-danger btn-lg" id="J_info_btn">激活</button>
    </div>
</div>
<!--已被激活-->
<div class="apx-coupon-active hidden" id="J_coupon_used">
    <div class="coupon-box clearfix">
        <div class="pull-left">
            <div class="info-left-top J_coupon_name"></div>
            <div class="info-left-bottom">每个账户现持有<span class="J_receive_limit"></span>张</div>
            <div class="legend">￥<span class="J_coupon_price"></span> <small>满 <span class="J_consume_limit"></span> 可用</small></div>
            <div class="stamp">已被他人激活</div>
        </div>
        <!--有效期-->
        <div class="expire">
            <p>使用有效期</p>
            <span><span class="J_start_time"></span> <br> 至 <br> <span class="J_end_time"></span></span>
        </div>
    </div>
    <div class="text-danger h4 text-center">此优惠券已被他人激活！</div>
    <div class="text-center">
        <button class="btn btn-danger btn-lg J_cancel_btn">取消</button>
    </div>
</div>
<!--未激活-->
<div class="apx-coupon-active hidden" id="J_coupon_inactive">
    <div class="coupon-box clearfix inactived">
        <div class="pull-left">
            <div class="info-left-top J_coupon_name"></div>
            <div class="info-left-bottom">每个账户现持有<span class="J_receive_limit"></span>张</div>
            <div class="legend">￥<span class="J_coupon_price"></span> <small>满 <span class="J_consume_limit"></span> 可用</small></div>
            <div class="stamp">未激活</div>
        </div>
        <!--有效期-->
        <div class="expire">
            <p>使用有效期</p>
            <span><span class="J_start_time"></span> <br> 至 <br> <span class="J_end_time"></span></span>
        </div>
    </div>
    <div class="text-danger h4 text-center">请确认是否激活此优惠券！</div>
    <div class="text-center">
        <button class="btn btn-danger btn-lg" id="J_active_sure">确认激活</button>
        <button class="btn btn-default btn-lg J_cancel_btn">取消</button>
    </div>
</div>
<!--已激活-->
<div class="apx-coupon-active hidden" id="J_coupon_activated">
    <div class="coupon-box clearfix actived">
        <div class="pull-left">
            <div class="info-left-top J_coupon_name"></div>
            <div class="info-left-bottom">每个账户现持有<span class="J_receive_limit"></span>张</div>
            <div class="legend">￥<span class="J_coupon_price"></span> <small>满 <span class="J_consume_limit"></span> 可用</small></div>
            <div class="stamp">已激活</div>
        </div>
        <!--有效期-->
        <div class="expire">
            <p>使用有效期</p>
            <span><span class="J_start_time"></span> <br> 至 <br> <span class="J_end_time"></span></span>
        </div>
    </div>
    <div class="text-danger h4 text-center">恭喜您！激活成功！</div>
    <div class="text-center">
        <button class="btn btn-danger btn-lg">立即使用</button>
    </div>
</div>