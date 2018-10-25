<div class="apx-acc-aside pull-left acc_expand">
    <!-- avatar part start -->
    <div class="acc-aside-avatar-wrap">
        <div class="acc-aside-avatar">
            <img class="img-responsive img-circle" src="/images/slider05.jpg" alt="avatar">
            <div>昵称：张zyhyww</div>
            <div>手机：12345678901</div>
        </div>
        <div class="acc-aside-expand">
            <div class="media">
                <div class="media-body">
                    <ul class="list-unstyled col-xs-6">
                        <li><strong>账户名称</strong>：未知用户名（id: 1234567890）</li>
                        <li><strong>门店地址</strong>：浙江省 杭州市 XX区 中山区12号</li>
                        <li><strong>默认地址</strong>：浙江省 杭州市 XX区 中山区12号</li>
                        <li><strong>账号等级</strong>：VIP 4</li>
                    </ul>
                    <div class="col-xs-2 text-center">
                        <a href="#" data-target="#modalAccPwd" data-toggle="modal">
                            <div class="img-circle"><img src="/images/account/info.png" class="img-responsive"></div>
                            修改密码
                        </a>
                    </div>
                    <div class="col-xs-2 text-center">
                        <a href="#">
                            <div class="img-circle"><img src="/images/account/address.png" class="img-responsive"></div>
                            地址管理
                        </a>
                    </div>
                    <div class="col-xs-2 text-center">
                        <a href="#" data-target="#modalAccRecharge" data-toggle="modal">
                            <div class="img-circle"><img src="/images/account/recharge.png" class="img-responsive"></div>
                            账户充值
                        </a>
                    </div>
                </div>
                <div class="media-right media-middle text-center" href="#">
                    <a href="#">
                        <img src="/images/account/balance.png" class="img-responsive">
                        <strong>¥12345.00</strong> 账户余额
                    </a>
                </div>
            </div>
            <div class="acc-aside-expand-btn J_acc_expand">
                <i class="glyphicon glyphicon-chevron-right"></i>
            </div>
        </div>
    </div>
    <!-- avatar part end -->
    <div class="acc-aside-list">
        <a data-toggle="collapse" href="#aside_panel_1" class="collapsed">
            订单中心 <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <div class="collapse" id="aside_panel_1">
            <ul class="list-unstyled clearfix">
                <li class="active">
                    <a href="#">菜单#1</a>
                </li>
                <li>
                    <a href="#">菜单#2</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="acc-aside-list">
        <a data-toggle="collapse" href="#aside_panel_2" class="collapsed">
            交易中心 <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <div class="collapse" id="aside_panel_2">
            <ul class="list-unstyled clearfix">
                <li>
                    <a href="#">菜单#3</a>
                </li>
                <li>
                    <a href="#">菜单#4</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="acc-aside-list">
        <a data-toggle="collapse" href="#aside_panel_3" class="collapsed">
            服务中心 <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <div class="collapse" id="aside_panel_3">
            <ul class="list-unstyled clearfix">
                <li>
                    <a href="#">菜单#5</a>
                </li>
                <li>
                    <a href="#">菜单#6</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="acc-aside-list">
        <a data-toggle="collapse" href="#aside_panel_4" class="collapsed">
            优惠券 <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <div class="collapse" id="aside_panel_4">
            <ul class="list-unstyled clearfix">
                <li>
                    <a href="#">菜单#7</a>
                </li>
                <li>
                    <a href="#">菜单#8</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="acc-aside-list">
        <a data-toggle="collapse" href="#aside_panel_5" class="collapsed">
            账户充值 <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <div class="collapse" id="aside_panel_5">
            <ul class="list-unstyled clearfix">
                <li>
                    <a href="#">菜单#9</a>
                </li>
                <li>
                    <a href="#">菜单#0</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="acc-aside-recmmend">
        <a href="#"><img src="/images/item_big.jpg" alt="" class="img-responsive"></a>
    </div>
</div>
<!--修改密码弹窗-->
<div class="apx-modal-acc-pwd modal fade" id="modalAccPwd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title" id="myModalLabel">
                    <strong><span class="h3">修改密码</strong>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="pwd_old" class="col-xs-4 text-right control-label">输入旧密码：</label>
                        <div class="col-xs-8">
                            <input type="password" id="pwd_old" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_old1" class="col-xs-4 text-right control-label">输入新密码：</label>
                        <div class="col-xs-8">
                            <input type="password" id="new_old1" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_old2" class="col-xs-4 text-right control-label">再次输入新密码：</label>
                        <div class="col-xs-8">
                            <input type="password" id="new_old2" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="validate" class="col-xs-4 text-right control-label">验证码：</label>
                        <div class="col-xs-6">
                            <input type="text" id="validate" class="form-control" value="">
                        </div>
                        <div class="col-xs-2">
                            <div class="validate-box">JQQP</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">修改密码</button>
                <div class="error-msg text-danger">
                    <strong><i class="glyphicon glyphicon-warning-sign"></i>旧密码输入有误</strong>
                </div>
            </div>
        </div>
    </div>
</div>
<!--充值弹窗-->
<div class="apx-modal-acc-recharge modal fade" id="modalAccRecharge" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title" id="myModalLabel">
                    <strong><span class="h3">账户充值</strong>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="recharge_amount" class="col-xs-4 text-right control-label">请选择充值金额：</label>
                        <div class="col-xs-6">
                            <input type="text" id="recharge_amount" class="form-control" value="">
                        </div>
                        <div class="col-xs-1">
                            <p class="form-control-static">元</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 text-right control-label">请选择充值方式：</label>
                        <div class="radio col-xs-3">
                            <label for="recharge_alipay">
                                <input type="radio" name="recharge_way" id="recharge_alipay" value="alipay" checked="checked">
                                <span class="btn btn-info btn-xs">支付宝</span>
                            </label>
                        </div>
                        <!--<div class="radio col-xs-3">
                            <label for="recharge_wepay">
                                <input type="radio" name="recharge_way" id="recharge_wepay" value="wepay">
                                <span class="btn btn-success btn-xs">微信</span>
                            </label>
                        </div>-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">确认充值</button>
                <div class="info-msg">
                    温馨提醒：充值成功后，余额可能存在延迟现象，如有问题，请咨询客服。
                </div>
            </div>
        </div>
    </div>
</div>
