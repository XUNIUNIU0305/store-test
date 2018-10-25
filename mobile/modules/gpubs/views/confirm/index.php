<?php
$this->params = ['js' => 'js/confirm.js', 'css' => 'css/confirm.css'];
$this->title = '确认订单';
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<style>
    .container {
        background-color: #f4f5f7;
    }
</style>
<div class="address-confirm-wrap hidden">
    <div class="address-confirm">
        <div class="wrap">
            <div class="reminder">
                <span class="tishi">提示</span>
                <span class="close" id="close-tishi"></span>
            </div>
            <div class="con">
                <div class="kaituantitle hidden">请再次确认自提点信息，一旦开团将不可修改</div>
                <div class="cantuantitle hidden">请确认一下自提点信息，您需至该地点提货：</div>
                <div class="address-con">
                    <p class="self-reference">自提点名称：
                        <span class="X_spot_name"></span>
                    </p>
                    <p class="linkman">联系人：
                        <span class="X_contact"></span>
                        <span class="X_mobile"></span>
                    </p>
                    <p class="self-address">自提点地址：<span class="X_address"></span></p>
                </div>
                <div class="confirm-button">
                    <div class="affirm" id="affirm">确认</div>
                    <div class="cancel" id="cancel">取消</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="order-confirm-complete-wrap">
        <div class="order-confirm-complete">
            <div class="collage-detail-head">
                <div class="head-cont">
                    <div class="head-tit"></div>
                    <!-- 选择收货地址 -->
                    <div class="address-main" id="choose-address">
                        <div class="choose-address">
                            <span class="text"></span>
                            <div class="address-more"></div>
                        </div>
                    </div>
                    <!-- 收货地址信息 -->
                    <div class="address-mess address-main hidden" id="address-mess">
                        <div class="head-user">
                            <span class="pick-up-site"></span>
                            <span class="user-name"></span>
                            <span class="user-number"></span>
                            <div class="head-user-more"></div>
                        </div>
                        <span class="head-addr" id="head-addr"></span>
                    </div>

                    <img class="head-pic" src="/images/order_confirm_detail_page/caitiao.png" alt="">
                </div>

            </div>
            <div class="collage-detail-section">
                <div class="sec-box">
                    <div class="sec-tit">
                        <img class="sec-icon" src="/images/order_confirm_detail_page/store_32_icon.png" alt="">
                        <span class="merchant-title"></span>
                    </div>
                    <div class="sec-main">
                        <div class="sec-cont">
                            <div class="sec-pic" data-id="">
                                <img  src="" alt="">
                            </div>
                            <div class="cont-txt">
                                <span class="c-txt"></span>
                                <span class="txt-gray"></span>
                                <div class="txt-box">
                                    <span class="txt-money">
                                        <span class="fuhao"></span>
                                        <span class="price"></span>
                                    </span>
                                    <div class="number-button">
                                        <div class="button">
                                            <span class="reduce" id="deliver-goods-reduce"></span>
                                            <input class="text" type="number" id="deliver-goods-text" value="">
                                            <span class="add" id="deliver-goods-add"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="service-policy">
                         <div class="policy">
                            <div class="item">
                                <span class="icon">
                                    <img src="/images/order_confirm_detail_page/confirm_28_icon.png" alt="">
                                </span>
                                <span class="policy-con"></span>
                            </div>
                        </div>
                        <!-- <div class="service-policy-more" id="service-policy-more"></div> -->
                    </div>
                </div>
            </div>
            <!-- 配送服务 -->
            <div class="delvivery-service-wrap">
                <div class="delvivery-service">
                    <div class="title">配送服务</div>
                    <div class="mess">
                        <div class="left">
                            <p>快递运输</p>
                            <p>工作日、双休日与节假日均可送货</p>
                        </div>
                        <!-- <div class="right">

                        </div> -->
                    </div>
                </div>
                <div class="sec-footer">
                    <h4 class="foot-txt-1">备注</h4>
                    <textarea style="resize:none; outline-style:none;" class="foot-txt-2" name="" placeholder="选填，给商家留言" id="J_remark_input"></textarea>
                </div>
            </div>
            <!-- 优惠券 -->
            <div class="discount-coupon-wrap">
                <div class="discount-coupon">
                    <div class="title">优惠券</div>
                    <!-- 暂无可使用优惠券 -->
                    <div class="discount-coupon-no common">暂无可使用优惠券
                        <span class="discount-coupon-more-logo"></span>
                    </div>
                </div>
            </div>
            <!-- 支付方式 -->
            <div class="mode-payment-wrap">
                <div class="mode-payment" id="mask_select_payment" data-mask="#mask_select_payment">
                    <div class="mode-payment-title">选择支付方式</div>
                    <ul>
                        <li class="mode-payment-way" data-pay="1" data-name="余额支付">
                            <div class="mode-payment-left">
                                <div class="logo">
                                    <img src="/images/order_confirm_detail_page/yue_zhifu.png" alt="">

                                </div>
                                <div class="mode-payment-center">
                                    <p class="mode-title">余额支付</p>
                                    <p class="mode-payment-else mode-payment-else-yue">账户余额
                                        <span></span>
                                    </p>
                                </div>
                            </div>

                            <div class="mode-payment-right">
                                <span></span>
                            </div>
                        </li>
                        <li class="mode-payment-way actived" data-pay="3" data-name="微信支付">
                            <div class="mode-payment-left">
                                <div class="logo">
                                    <img src="/images/order_confirm_detail_page/weixinzhifu_logo.png" alt="">
                                </div>

                                <div class="mode-payment-center">
                                    <p class="mode-title">微信支付</p>
                                    <p class="mode-payment-else safety">微信安全支付</p>
                                </div>
                            </div>

                            <div class="mode-payment-right">
                                <span class="mode-payment-right-btn"></span>
                            </div>
                        </li>
                    </ul>
                    
                </div>
            </div>
            <!-- 金额信息 -->
            <div class="collage-detail-cost">
                <ul class="cost-list">
                    <li class="cost-item">
                        <span class="cost-tit">商品金额</span>
                        <span class="cost-txt" id="good-price"></span>
                    </li>
                    <li class="cost-item">
                        <span class="cost-tit">优惠券金额</span>
                        <span class="cost-txt">￥ 0.00</span>
                    </li>
                    <li class="cost-item">
                        <span class="cost-tit">立减</span>
                        <span class="cost-txt">￥ 0.00</span>
                    </li>
                    <li class="cost-item">
                        <span class="cost-tit">运费</span>
                        <span class="cost-txt">￥ 0.00</span>
                    </li>
                </ul>
                <div class="cost-footer">
                    <span class="cost-number">数量：
                        <span>1</span>件</span>
                    <span class="cost-money">实付金额：
                        <span class="money-total" id="money-total"></span>
                    </span>
                </div>
            </div>

            
        </div>
    </div>
</div>
<div class="confirm" id="confirm-payment">确认支付
    <span class="price"></span>
</div>
<!--地址选择-->
<div id="J-address-selected"  class="maskLayersh hidden">
    <div class="maskLayersh-shop J_address_list">
        <script type="text/template" id="J_tpl_address">
            <p href="#" class="btn-block-top J_add_address">
                <span class="choose_address"></span>
                <a href="javascript:void(0)" class="btn"></a>
            </p>
            <div class="mt44"></div>
            <div class="address-box">
                {@each _ as it, index}
                <!--address item-->
                <div class="address-item J_address_item" data-id="${it.id}" data-index="${index}">
                    <div class="title">
                        <div class="detail ziti-detail">自提点信息：<span class="J_spot_name">${it.spot_name}</span></div>
                        <span class="hidden shouhuoman">收货人：<label class="J_contact">${it.consignee}</label></span>
                        <span class="hidden lianximan">联系人：<label class="J_contact">${it.consignee}</label></span>
                        <span class="J_mobile">${it.mobile}</span>
                    </div>
                    <div class="detail songhuoaddress hidden">收货地址：<span class="J_address">${it.province.name} ${it.city.name} ${it.district.name}${it.detail}</span></div>
                    <div class="detail zitiaddress hidden">自提点地址地址：<span class="J_address">${it.province.name} ${it.city.name} ${it.district.name}${it.detailed_address}</span></div>
                </div>
                {@/each}
                <!--address item-->
            </div>
        </script>
    </div>
</div>
