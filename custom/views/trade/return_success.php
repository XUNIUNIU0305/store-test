<?php
$this->params = ['js' => 'js/return_success.js', 'css' => 'css/return_success.css'];
?>
<div class="container-fluid apx-cart-container bg-muted">
    <div class="container">
        <div class="row">
            <div class="apx-cart-pay-success clearfix">
                <!--广告位-->
                <div class="pull-right hidden">
                    <a href="#" target="_blank"><img src="/images/row01.jpg"></a>
                </div>
                <!--内容-->
                <div>
                    <h3>支付成功！</h3>
                    <ul class="list-unstyled">
<?php if(isset($tradeNo) && !empty($tradeNo)): ?>
<li><strong>网站交易单号</strong>：<?= $tradeNo ?></li>
<?php endif; ?>
<?php if(isset($alipayNo) && !empty($alipayNo)): ?>
    <li><strong>支付宝交易单号</strong>：<?= $alipayNo ?></li>
<?php endif; ?>
<?php if(isset($totalFee) && !empty($totalFee)): ?>
    <li><strong>支付总额</strong>：<?= $totalFee ?>元</li>
<?php endif; ?>
                    </ul>
                    <a href="/account" class="btn">返回账户中心</a>
                    <a href="/" class="btn">返回商城首页</a>
                    <small>
                        重要提示：本平台及销售商不会以<span class="text-danger">订单异常、系统升级</span>为由，要求您点击任何链接进行退款。
                    </small>
                </div>
            </div>
            <!--轮播标题-->
            <div class="apx-cart-carousel-title hidden">
                <div class="list-title">
                    <div class="pull-right">hi</div>
                    <p>爆款推荐</p>
                    <span>hot item</span>
                </div>
            </div>
            <!--商品轮播-->
            <div id="apx-cart-carousel-list-1" class="apx-cart-product-slider carousel slide hidden" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item clearfix active">
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                    </div>
                    <div class="item clearfix">
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                        <!-- single item -->
                        <div class="apx-item pull-left">
                            <div class="item-pic">
                                <a href="#"><img src="/images/slider01.jpg" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item-cnt">
                                <div class="item-cnt-title ellipsis"><a href="#">标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</a></div>
                                <div class="text-center">
                                    ¥129.00
                                </div>
                                <button class="add-cart"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Wrapper for slides -->
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#apx-cart-carousel-list-1" data-slide-to="0" class="active"></li>
                    <li data-target="#apx-cart-carousel-list-1" data-slide-to="1"></li>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#apx-cart-carousel-list-1" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#apx-cart-carousel-list-1" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </ol>
            </div>
        </div>
    </div>
</div>
