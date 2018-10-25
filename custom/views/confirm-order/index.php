<?php
$this->params = ['js' => 'js/confirm-order.js', 'css' => 'css/confirm-order.css'];

$this->title = '九大爷平台 - 确认订单';
?>
<!-- main container -->
<div class="container-fluid apx-settle-container">
    <div class="container">
        <div class="row">
            <h3 class="apx-settle-title">确认订单信息</h3>
            <div class="apx-settle-box">
                <h3 class="apx-settle-title">
                    <strong>收货人信息</strong>
                </h3>
                <div class="apx-settle-address-box clearfix">
                    <a href="javascript:void(0)" class="btn btn-link btn-add" id="J_add_btn">新增收货地址</a>
                    <div id="J_address_list" class="clearfix limit-height">

                    </div>
                    <div class="col-xs-12">
                        <a class="load-all J_load_all" href="javascript:;">显示全部地址
                            <i></i>
                        </a>
                    </div>
                </div>
                <h3 class="apx-settle-title">
                    <strong>选择支付方式</strong>
                </h3>
                <div class="apx-settle-pay-box clearfix" data-toggle="buttons">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="J_paymemt btn btn-alipay" data-payment="2" data-name="支付宝">
                            <input type="radio" name="options" id="option1" autocomplete="off">
                            <i></i>支付宝
                        </label>
                        <label class="J_paymemt btn btn-wepay hidden" data-payment="">
                            <input type="radio" name="options" id="option2" autocomplete="off">
                            <i></i>微信
                        </label>
                        <label class="J_paymemt btn btn-balance active" data-payment="1" data-name="余额">
                            <input type="radio" name="options" id="option3" autocomplete="off" checked>
                            <i></i>余额
                            <div class="balance-info">
                                <div class="balance-info-cnt">
                                    账户当前余额：<span id="J_balance"></span>元
                                </div>
                            </div>
                        </label>
                        <label class="J_paymemt btn pay-company" data-payment="5">
                            <input type="radio" name="options" autocomplete="off">
                            <i></i>南行-企业网关
                        </label>
                        <label class="J_paymemt btn pay-person hidden" id="J_person_pay" data-payment="4">
                            <input type="radio" name="options" autocomplete="off">
                            <i></i>南行-个人网关
                        </label>
                        <label class="J_paymemt btn pay-abcbank" id="J_abcbank_pay" data-payment="6">
                            <input type="radio" name="options" autocomplete="off">
                            <i></i>农行-网银
                        </label>
                    </div>
                </div>
                <h3 class="apx-settle-title">
                    <strong>送货清单</strong>
                </h3>
                <!-- todo: product detail -->
                <div class="apx-settle-product-detail">
                    <!-- title -->
                    <ul class="list-inline title">
                        <li>订单信息</li>
                        <li>商品信息</li>
                        <li>单价</li>
                        <li>数量</li>
                        <li>小计</li>
                    </ul>
                    <div id="J_order_box">

                    </div>
                </div>
            </div>
            <div class="apx-settle-total text-right">
                <p>
                    <span>
                        <span class="high-lighted J_total_count"></span>
                         件商品</span>
                    <span class="price-detail">商品金额：
                        <span class="J_orders_price"></span>
                    </span>
                </p>
                <p>
                    <span class="price-detail">优惠减免金额：
                        <span class="J_coupon_price">0</span>
                    </span>
                </p>
                <p>
                    <span class="price-detail">支付方式：
                        <span id="J_payment">余额</span>
                    </span>
                </p>
                <hr>
                <p>
                    <span class="price-detail">
                        <strong>实付金额：</strong>
                        <span class="h3 high-lighted J_total_price"></span>
                    </span>
                </p>
                <p>
                    <strong>寄送至：</strong><span class="J_end_address"></span>
                </p>
                <!-- 满减规则 -->
                <div class="js-rules" style="text-align: right; color: #cf4d44;"></div>
            </div>
            <div class="apx-settle-bar J_cart_affix_bar">
                <div class="container">
                    <div class="affix-info clearfix">
                        <p>
                            <strong>寄送至：</strong><span class="J_end_address"></span>
                        </p>
                        <div class="pull-right">
                            <p>
                                <span>
                                    <span class="high-lighted J_total_count"></span> 件商品</span>
                                <span class="price-detail">商品金额：
                                    <strong class="high-lighted J_orders_price"></strong>
                                </span>
                            </p>
                            <p>
                                <span class="price-detail">已节约：
                                    <span class="J_coupon_price">-¥0
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>

                    <a class="apx-settle-bill-submit pull-right" id="J_submit">提交订单</a>
                </div>
            </div>
            <!-- coupon box -->
            <div class="apx-settle-coupon-box">
                <div class="title">我的优惠券</div>
                <div class="content" id="J_coupon_list">
                    <div class="kong text-center" style="margin-top: 100px">
                        <img src="/images/kong.png" alt="">
                        <p>您当前没有可用优惠券</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<div class="modal fade modal-settle-address-info" id="modal-settle-address-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <span class="close" data-dismiss="modal"></span>
                <div class="title" id="modal_title">
                    新增收货人信息
                </div>
                <div class="form-horizontal">
                    <div class="form-group required">
                        <div class="col-xs-2">
                            <label for="name">收货人：</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" id="modal_name" placeholder="收货人姓名">
                        </div>
                    </div>
                    <div class="form-group required address">
                        <div class="col-xs-2">
                            <label>所在地区：</label>
                        </div>
                        <div class="col-xs-3">
                            <select class="selectpicker J_province" data-width="100%">
                                <option value="">省/直辖市</option>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="selectpicker J_city" data-width="100%">
                                <option value="">市</option>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="selectpicker J_district" data-width="100%">
                                <option value="">区</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="col-xs-2">
                            <label for="address">详细地址：</label>
                        </div>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" id="modal_address" placeholder="街道门牌楼层房间号">
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="col-xs-2">
                            <label for="mobile">手机号码：</label>
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="modal_mobile" maxlength="11" placeholder="手机号">
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="col-xs-2">
                            <label for="zipcode">邮政编码：</label>
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="modal_zipcode" maxlength="6" placeholder="请填写6位邮政编码">
                        </div>
                    </div>
                    <button class="btn btn-danger btn-lg" id="J_reserve_btn">保存</button>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="apx-modal-admin-alert modal fade" id="apxModalPass" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close hidden" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <p class="text-center h3" id="J_bank_success">订单生成成功...</p>
                        <p class="text-center h3 hidden">已支付完成？</p>
                        <p class="h4 text-danger text-center hidden">支付返回信息存在300秒左右的延迟，请勿重复支付订单！</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger hidden" data-dismiss="modal">是</button>
                <button type="button" class="btn btn-lg btn-default hidden" data-dismiss="modal">否</button>
                <button type="button" class="btn btn-lg btn-default" id="J_open_window">前往支付</button>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="J_address_tpl">
    {@each _ as it, index}
        <div class="col-xs-4">
            {@if index == 0}
                {@if it.is_default}
                <div class="apx-settle-address active default" data-id="${it.id}">
                {@else}
                <div class="apx-settle-address active" data-id="${it.id}">
                {@/if}
            {@else}
                <div class="apx-settle-address" data-id="${it.id}">
            {@/if}
                <h5>
                    <strong>${it.consignee}</strong>收
                </h5>
                <p>
                    ${it.province.name} ${it.city.name} ${it.district.name} ${it.detail}
                    <span>${it.mobile}</span>
                </p>
                <i class="icon-checked"></i>
                <i class="icon-edit J_edit_btn" data-id=${index}></i>
            </div>
        </div>
    {@/each}
</script>
<script type="text/template" id="J_order_tpl">
    {@each _ as it}
        {@if it.items[0].length > 0}
        <div class="content clearfix">
            <!-- select -->
            <div class="pull-left">
                <div class="label-customed">
                    定制订单：
                    <span>否</span>
                    <a href="/guide/customization" target="_blank" title="定制类商品，将会根据购买商品的最小单位拆分订单，在支付完成后请在微信公众号内完成定制信息上传操作，点击了解更多">
                        <i></i>
                    </a>
                </div>
                <div class="coupon-select" data-supplier="${it.supplier_id}" data-type="0">
                    <span class="label">优惠券：</span>
                    <select class="selectpicker" data-width="150px">
                        <option value="-1">暂无</option>
                    </select>
                </div>
                <div class="coupon-info">
                    <span>优惠券</span>
                    <p>

                    </p>
                </div>
            </div>
            <!-- detail -->
            <div class="pull-right J_items_box" data-supplier="${it.supplier_id}" data-type="0">
                <!-- single item -->
                {@each it.items[0] as item}
                    <div class="item" data-sku="${item.product_sku_id}">
                        <!-- title -->
                        <div class="title clearfix">
                            <strong class="pull-left">品牌：${it.supplier}</strong>
                            <a href="javascript:;" class="pull-right" onclick="qimoChatClick();">联系客服
                                <i></i>
                            </a>
                        </div>
                        <!-- params -->
                        <div class="detail clearfix">
                            <div class="param clearfix">
                                <div class="pull-left">
                                    <img src="${item.image}" width="94" height="94">
                                </div>
                                <div class="pull-right">
                                    <div class="title">${item.title}</div>
                                    <ul class="list-unstyled">
                                        {@each item.attributes as attr}
                                        <li>${attr.name}：${attr.selected_option.name}</li>
                                        {@/each}
                                        <li class="hidden">
                                            <a href="#">更多</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="price">
                                ￥${item.price}
                            </div>
                            <div class="amount">
                                x${item.count}
                            </div>
                            <div class="sum">
                                ¥${item.price*item.count|ju_price}
                            </div>
                        </div>
                        <!-- remark -->
                        <div class="remark">
                            <div class="label">备注留言:</div>
                            <textarea rows="1" class="J_remark_text" placeholder="如有特殊要求，请详细说明" onfocus="javascript:$(this).attr('rows', 6)" onblur="javascript:$(this).attr('rows', 1)"></textarea>
                        </div>
                        <!-- result -->
                    </div>
                {@/each}
                <div class="result">
                    小计：
                    <span>¥${it.items[0]|total_price}</span>
                </div>
            </div>
        </div>
        {@/if}
        {@each it.items[1] as item}
            {@each i in range(0, item.count)}
            <div class="content clearfix">
                <!-- select -->
                <div class="pull-left">
                    <div class="label-customed">
                        定制订单：
                        <span>是</span>
                        <a href="/guide/customization" target="_blank" title="定制类商品，将会根据购买商品的最小单位拆分订单，在支付完成后请在微信公众号内完成定制信息上传操作，点击了解更多">
                            <i></i>
                        </a>
                    </div>
                    <div class="coupon-select" data-supplier="${it.supplier_id}" data-sku="${item.product_sku_id}" data-type="1">
                        <span class="label">优惠券：</span>
                        <select class="selectpicker" data-width="150px">
                            <option value="-1">暂无</option>
                        </select>
                    </div>
                    <div class="coupon-info">
                        <span>优惠券</span>
                        <p>
                        </p>
                    </div>
                </div>
                <!-- detail -->
                <div class="pull-right J_items_box" data-supplier="${it.supplier_id}" data-sku="${item.product_sku_id}" data-type="1">
                    <!-- single item -->
                    <div class="item" data-sku="${item.product_sku_id}">
                        <!-- title -->
                        <div class="title clearfix">
                            <strong class="pull-left">品牌：${it.supplier}</strong>
                            <a href="javascript:;" class="pull-right" onclick="qimoChatClick();">联系客服
                                <i></i>
                            </a>
                        </div>
                        <!-- params -->
                        <div class="detail clearfix">
                            <div class="param clearfix">
                                <div class="pull-left">
                                    <img src="${item.image}" width="94" height="94">
                                </div>
                                <div class="pull-right">
                                    <div class="title">${item.title}</div>
                                    <ul class="list-unstyled">
                                        {@each item.attributes as attr}
                                        <li>${attr.name}：${attr.selected_option.name}</li>
                                        {@/each}
                                        <li class="hidden">
                                            <a href="#">更多</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="price">
                                ￥${item.price}
                            </div>
                            <div class="amount">
                                x1
                            </div>
                            <div class="sum">
                                ¥ ${item.price|ju_price}
                            </div>
                        </div>
                        <!-- remark -->
                        <div class="remark">
                            <div class="label">备注留言:</div>
                            <textarea rows="1" class="J_remark_text" placeholder="如有特殊要求，请详细说明" onfocus="javascript:$(this).attr('rows', 6)" onblur="javascript:$(this).attr('rows', 1)"></textarea>
                        </div>
                        <!-- result -->
                    </div>
                    <div class="result">
                        小计：
                        <span>¥${item.price|ju_price}</span>
                    </div>
                </div>
            </div>
            {@/each}
        {@/each}
    {@/each}
</script>
<script type="text/template" id="J_coupon_tpl">
    {@each valid as it}
        <div class="coupon">
            <div class="amount">
                <strong>￥${it.price}</strong>
                <div>
                    满${it.limit_price}
                </div>
            </div>
            <div class="expire">截止时间：${it.end_time}</div>
            <div class="status clearfix">
                <div class="pull-left">${it.brand_name}可用</div>
                <div class="pull-right">可用</div>
            </div>
        </div>
    {@/each}
    {@each invalid as it}
        <div class="coupon disabled">
            <div class="amount">
                <strong>￥${it.price}</strong>
                <div>
                    满${it.limit_price}
                </div>
            </div>
            <div class="expire">截止时间：${it.end_time}</div>
            <div class="status clearfix">
                <div class="pull-left">￥${it.brand_name}可用</div>
                <div class="pull-right">不可用</div>
            </div>
        </div>
    {@/each}
</script>
