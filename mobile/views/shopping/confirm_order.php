<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>
<?php
$this->params = ['js' => 'js/confirmOrder.js', 'css' => 'css/confirmOrder.css'];
$this->title = '九大爷平台 - 订单确认';
?>


<div class="container">
    <div class="order-confirm-complete-wrap">
        <div class="order-confirm-complete">
            <div class="collage-detail-head">
                <div class="head-cont">
                    <div class="head-tit">收货地址</div>
                    <!-- 选择收货地址 -->
                    <div class="address-main hidden" id="choose-address">
                        <div class="choose-address">
                            <span class="text"></span>
                            <div class="address-more"></div>
                        </div>
                    </div>
                    <!-- 收货地址信息 -->
                    <div class="address-mess address-main" id="address-mess">
                        <div class="head-user">
                            <span class="user-name"></span>
                            <span class="user-number"></span>
                            <div class="head-user-more"></div>
                        </div>
                        <span class="head-addr"></span>
                    </div>

                    <img class="head-pic" src="/images/order_confirm_detail_page/caitiao.png" alt="">
                </div>

            </div>
            <!--prod-->
            <div class="panel collage-detail-section" id="J_order_box" >

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
            </div>
            
            <!-- 支付方式 -->
            <div class="mode-payment-wrap">
                <div class="mode-payment" id="mask_select_payment" data-mask="#mask_select_payment">
                    <div class="mode-payment-title">选择支付方式</div>
                    <ul>
                        <li class="mode-payment-way" data-pay="余额支付" data-id="1" data-name="余额支付">
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
                        <li class="mode-payment-way actived" data-pay="微信支付" data-id="3" data-name="微信支付">
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
                        <span class="cost-txt" id="goods-price"></span>
                    </li>
                    <li class="cost-item">
                        <span class="cost-tit">优惠券金额</span>
                        <span class="cost-txt" id="privilege">￥ 0.00</span>
                    </li>
                    <li class="cost-item">
                        <span class="cost-tit">立减</span>
                        <span class="cost-txt" id="vertical-reduction">￥ 0.00</span>
                    </li>
                    <li class="cost-item">
                        <span class="cost-tit">运费</span>
                        <span class="cost-txt" id="freight">￥ 0.00</span>
                    </li>
                </ul>
                <div class="cost-footer">
                    <span class="cost-number">数量：
                        <span id="J_product_count"></span>件</span>
                    <span class="cost-money">实付金额：
                        <span class="money-total J_pay_price"  id="money-total"></span>
                    </span>
                </div>
            </div>

            
        </div>
    </div>
    
</div>
<div class="confirm" id="confirm-payment">确认支付
    <span class="price J_pay_price"></span>
</div>
<!--地址选择-->
<div id="J-address-selected"  class="maskLayersh hidden">
    <div class="maskLayersh-shop J_address_list">
        <script type="text/template" id="J_tpl_address">
            <p href="#" class="btn-block-top J_add_address">
                选择收货地址
                <a href="/member/address/add" class="btn">新增地址</a>
            </p>
            <div class="mt44"></div>
            <div class="address-box">
                {@each _ as it}
                <!--address item-->
                <div class="address-item J_address_item" data-id="${it.id}">
                    <div class="title">
                        <span>收货人：<label class="J_contact">${it.consignee}</label></span>
                        <span class="J_mobile">${it.mobile}</span>
                    </div>
                    <div class="detail">收货地址：<span class="J_address">${it.province.name} ${it.city.name} ${it.district.name}${it.detail}</span></div>
                </div>
                {@/each}
                <!--address item-->
            </div>
        </script>

    </div>

</div>



 <div id="J_coupon_select"  class="maskLayersh hidden">
    <div class="maskLayersh-shop J_coupon_list">
        <script type="text/template" id="J_tpl_coupon">
            <a href="#" class="btn-block-top J_no_select_ticket" >暂不使用</a>
            <div class="coupon-body">
                <div class="coupon-height"></div>
                <div class="coupon-ul-body">
                    <ul class="acc-coupon-info">
                        {@each _ as it}
                        <li class="coupon-item">
                            <div class="pull-left">
                                <div class="price">
                                    <div class="big">
                                        <small>￥</small><label>${it.price}</label>
                                    </div>
                                    <p>满${it.limit_price}可用</p>
                                </div>
                                <div class="info">
                                    <p class="h2">${it.name}</p>
                                    <p>有效时间：${it.start_time}至${it.end_time}</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="chain">
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                    <s></s>
                                </div>
                                <a href="javascript:;" data-id=${it.id} class="J_use_coupon_btn"><span>立即使用</span></a>
                            </div>
                        </li>
                        {@/each}
                    </ul>
                </div>
            </div>
        </script>
    </div>
</div>

<script type="text/template" id="J_tpl_order">
    {@each _ as it}
        {@if it.items[0].length > 0}
        <div class="J_coupon_box sec-box" data-type="0" data-supplier=${it.supplier_id}>
            <div class="panel-heading sec-tit">
                    <img class="sec-icon" src="/images/order_confirm_detail_page/store_32_icon.png" alt="">
                    <span id="business-information">${it.supplier}</span>
                <a href="javascript:;" class="J_use_coupon" style="float:right;" data-enable="false">暂无可用优惠券</a>
            </div>
            {@each it.items[0] as item}
            <div class="panel-body sec-main" data-skuid="${item.product_sku_id}">
                <!-- <hr> -->
                <div class="order-item sec-cont">
                    <div class="sec-pic">
                        <img data-id=${item.product_id} src="${item.image}">
                    </div>
                    <div  class="cont-txt">
                        <span class="description c-txt">${item.title}</span>
                        <span class="param txt-gray">
                            {@each item.attributes as attr}
                                <span>${attr.name}：${attr.selected_option.name};</span>
                            {@/each}

                        </span>
                        <div class="txt-box">
                            <span class="price txt-money">¥ <span  class="price">${item.price|pric_build}</span></span>
                            <span class="quantity ammount">x${item.count}</span>
                            <!-- <div class="number-button">
                                <div class="button">
                                    <span class="reduce deliver-goods-reduce"></span>
                                    <input class="text deliver-goods-text ammount input-box" type="number" value="${item.count}" disabled="true">
                                    <span class="add deliver-goods-add"></span>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="sec-footer panel-heading remark-box" data-skuid="${item.product_sku_id}">
                <h4 class="foot-txt-1">备注</h4>
                <textarea style="resize:none; outline-style:none;" class="foot-txt-2 J_mark" name="" placeholder="选填，给商家留言" id="J_remark_input"></textarea>
            </div>
            {@/each}
        </div>
        {@/if}
        {@each it.items[1] as item}
            {@each i in range(0, item.count)}
                <div class="J_coupon_box sec-box" data-type="1" data-supplier=${it.supplier_id} data-sku=${item.product_sku_id}>
                    <div class="panel-heading sec-tit">
                        <img class="sec-icon" src="/images/order_confirm_detail_page/store_32_icon.png" alt="">
                        <span id="business-information">${it.supplier}</span>
                        <a href="javascript:;" class="J_use_coupon" style="float:right;" data-enable="false">暂无可用优惠券</a>
                    </div>
                    <div class="panel-body sec-main" data-skuid="${item.product_sku_id}">
                        <!-- <hr> -->
                        <div class="order-item sec-cont">
                            <div class="sec-pic">
                                <img data-id=${item.product_id} src="${item.image}">
                            </div>
                            <div  class="cont-txt">
                                <span class="description c-txt">${item.title}</span>
                                <span class="param txt-gray">
                                    {@each item.attributes as attr}
                                        <span>${attr.name}：${attr.selected_option.name};</span>
                                    {@/each}

                                </span>
                                <div class="txt-box">
                                    <span class="price txt-money">¥ <span  class="price">${item.price|pric_build}</span></span>
                                    <span class="quantity ammount">x1</span>
                                    <!-- <div class="number-button">
                                        <div class="button">
                                            <span class="reduce deliver-goods-reduce"></span>
                                            <input class="text deliver-goods-text ammount input-box" type="number" value="1" disabled="true">
                                            <span class="add deliver-goods-add"></span>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="sec-footer panel-heading remark-box" data-skuid="${item.product_sku_id}">
                        <h4 class="foot-txt-1">备注</h4>
                        <textarea style="resize:none; outline-style:none;" class="foot-txt-2 J_mark" name="" placeholder="选填，给商家留言" id="J_remark_input"></textarea>
                    </div>
                </div>
            {@/each}
        {@/each}
    {@/each}
</script>
