<?php
$this->params = ['js' => 'js/coupon-index.js','css'=>'css/coupon-index.css'];
$this->title = '九大爷平台 - 优惠券列表';
?>
<nav class="bottom-nav bottom-nav-coupon">
    <a href="/member/index">
        <i></i>
    </a>
    <span class="spam-first">优惠券列表</span>
    <span>&nbsp;</span>
    <!-- <span onclick="javascript:location.href='active'">激活</span> -->
</nav>
<nav class="bottom-nav bottom-nav-coupons">
    <span class="spam-first span-available">可用优惠券</span>
    <span class="span-already">已使用优惠券</span>
</nav>
<main class="containers">
    <div class="coupon-body" style="margin-bottom: 50px;">
        <div class="coupon-height"></div>
        <div class="coupon-ul-body">
            <ul class="acc-coupon-info available-info">
                <script type="text/template" id="J_coupon_list">
                        {@each _ as it}
                            <li class="coupon-item">
                                <div class="pull-left">
                                    <div class="price">
                                        <div class="big">
                                            <small>￥</small><label>${it.Coupon.price}</label>
                                        </div>
                                        <p>满${it.Coupon.consume_limit}可用</p>
                                    </div>
                                    <div class="info">
                                        <p class="h2">[${it.Coupon.supplier|supplier_build}]</p>
                                        <p>有效时间：${it.Coupon.start_time}至${it.Coupon.end_time}</p>
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
                                    <a href="/"><span>立即使用</span></a>
                                </div>
                             </li>
                        {@/each}
                </script>
            </ul>
        </div>
        <div class="coupon-ul-body-already">
            <ul class="acc-coupon-info already-info">
                <script type="text/templates" id="J_coupon_list_already">
                        {@each _ as it}
                            <li class="coupon-item disabled">
                                <div class="pull-left">
                                    <div class="price">
                                        <div class="big">
                                            <small>￥</small><label>${it.Coupon.price}</label>
                                        </div>
                                        <p>满${it.Coupon.consume_limit}可用</p>
                                    </div>
                                    <div class="info">
                                        <p class="h2">[${it.Coupon.supplier|supplier_build}]</p>
                                        <p>有效时间：${it.Coupon.start_time}至${it.Coupon.end_time}</p>
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
                                    <a href="#"><span>已使用</span></a>
                                </div>
                             </li>
                        {@/each}
                </script>
            </ul>
        </div>
    </div>
</main>