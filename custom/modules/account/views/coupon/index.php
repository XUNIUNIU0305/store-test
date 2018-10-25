<?php
$this->params = ['js' => 'js/coupon-index.js', 'css' => 'css/coupon-index.css'];
?>

<div class="apx-acc-title bg-danger">
    <strong>优惠券列表</strong>
</div>
<!--tabs-->
<ul class="nav nav-tabs apx-add-order-nav" role="tablist">
    <li role="presentation" class="active">
        <a href="#coupon_available" role="tab" data-toggle="tab">可用优惠券
        </a>
    </li>
    <li role="presentation">
        <a href="#coupon_used" role="tab" data-toggle="tab">已使用优惠券
        </a>
    </li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="coupon_available">
        <ul class="apx-coupon-list list-unstyled clearfix" id="J_coupon_activated">
            <script type="text/template" id="J_tpl_coupon">
                {@each codes as it}
                    <li class="J_coupon_box">
                    {@if it.status == 1}
                        <div class="coupon-item">
                    {@else if it.status == 2}
                        <div class="coupon-item disabled">
                    {@/if}
                            <div class="pull-left">
                                <div class="media">
                                    <div class="media-left">
                                        <small>￥</small>${it.Coupon.price}
                                    </div>
                                    <div class="media-body">
                                        <p>${it.Coupon.name}</p>
                                        <p>满${it.Coupon.consume_limit}元可用</p>
                                    </div>
                                </div>
                                <p>[${it.Coupon.supplier|supplier_build}]</p>
                                <p>序列号：${it.code}</p>
                                {@if it.status == 1}
                                    <p>有效时间：${it.Coupon.start_time}-${it.Coupon.end_time}</p>
                                {@else if it.status == 2}
                                    <p>使用时间：${it.used_time}</p>
                                {@/if}
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
                                </div>
                                {@if it.status == 1}
                                    <a href="/"><span>立即使用</span></a>
                                {@else if it.status == 2}
                                    <a href="#"><span>已使用</span></a>
                                {@/if}
                            </div>
                        </div>
                    </li>
                {@/each}
            </script>
        </ul>
        <!-- pagination -->
        <div class="text-right" id="J_activated_page"></div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="coupon_used">
        <ul class="apx-coupon-list list-unstyled clearfix" id="J_coupon_used"></ul>
        <!-- pagination -->
        <div class="text-right" id="J_used_page"></div>
    </div>
</div>
