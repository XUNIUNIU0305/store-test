<script type="text/javascript" src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>
<?php
$this->params = ['js' => ['js/Swiper_4.3.0_js_swiper.js', 'js/goods-detail.js'], 'css' => ['css/swiper.css','css/goodsDetail.css']];

$this->title = '九大爷平台 - 商品详情';
?>

<div class="container">
    <div id="topAnchor"></div>
    <!-- 快速导航列表 -->
    <div class="fast-guid-list hidden" id="fast-guid-list"> </div>
    <div class="guid-list" id="guid-list">
        <div class="pack-up" id="pack-up"></div>
        <ul class="list">
            <li class="pingou-index"></li>
            <!-- <li class="search"></li> -->
            <li class="my-pingou"></li>
            <li class="ziti-pingou-tihuo"></li>
        </ul>
    </div>
    <!-- 返回顶部 -->
    <a href="#topAnchor"><div href="#topAnchor" class="G_group-share-back-top"></div></a>
    <div class="wechat-group-goods-detail">
        <div class="goods-detail-wrap">
            <!-- 详情页大图 -->
            <div class="goods-detail-image">
                <a class="shopcart-logo" href="/shopping/index"></a>
                <div class="swiper-container">
                    <div class="swiper-wrapper" id="swiper-wrapper">
                    
                    </div>
                </div>
                <div class="goods-detail-pages">
                    <div class="pages">
                        <span class="change-pages"></span>
                        /
                        <span class="totle-pages"></span>
                    </div>
                </div>
            </div>
            <!-- 拼购倒计时 -->
            <div class="goods-detail-count-down hidden">
                <!-- 倒计时剩余时间 -->
                <div class="count-down">
                    <div class="timer">
                        <span class="timer-bg same" id="day"></span><span class="same">天</span><span class="timer-bg same" id="hour"></span><span class="same">:</span><span class="timer-bg same" id="minute"></span><span class="same">:</span><span class="timer-bg same" id="second"></span>
                    </div>
                </div>
            </div>
            <!-- 商品信息 -->
            <div class="goods-detail-mess">
                <div class="mess">
                    <!-- 价格 -->
                    <div class="goods-detail-price">
                        <div class="new-price">
                            <span class="fuhao"></span>
                            <span class="min">
                                <span class="min-price price"></span>
                                <span class="min-price-decimals"></span>
                            </span>
                            <!-- - -->
                            <span class="max">
                                <span class="max-price price"></span>
                                <span class="max-price-decimals"></span>
                            </span>
                        </div>
                        <div class="old-price">
                        <span class="min-price"></span><span class="max-price"></span>
                        </div>
                    </div>
                    <!-- 起拼-送货 -->
                    <div class="spell-delivery hidden">
                        <!-- 起拼按钮 -->
                        <div class="spell same">
                            <!-- 5人5件起拼 -->
                        </div>
                        <!-- 送货按钮 -->
                        <div class="delivery same">
                            <!-- 送货 -->
                        </div>
                    </div>
                    <!-- 商品介绍 -->
                    <div class="goods-intro">
                    </div>
                    <div class="goods-intro-mess">
                    </div>
                </div>
            </div>
            <!-- 规格-地址 -->
            <div class="specifications-address">
                <!-- 规格 -->
                <div class="specifications common">
                    <div class="title ">规格</div>
                    <!-- 规格信息 -->
                    <div class="specifications-mess">
                        <span class="mess">请选择</span>
                        <span class="more" id="specifications-more"></span>
                    </div>
                </div>
            </div>
            <!-- 规格详情 -->
            <div class="specifications-detail-wrap hidden" id="specifications-detail-wrap">
                <div class="specifications-detail">
                    <div class="specifications-detail-mess">
                        <div class="detail-img">
                            <img src="" alt="">
                        </div>
                        <div class="detail-mess">
                            <div class="close" id="specifications-detail-wrap-close"></div>
                            <!-- 起拼-送货 -->
                            <div class="spell-delivery">
                                <!-- 起拼按钮 -->
                                <div class="spell same">
                                </div>
                                <!-- 送货按钮 -->
                                <div class="delivery same">
                                    <!-- 送货 -->
                                </div>
                            </div>
                            <p class="detail-newprice">
                                <span class="fuhao"></span>
                                <span class="new-price">
                                    <span class="min-price"></span>
                                    <span class="max-price"></span>
                                </span>
                            </p>
                            <p class="detail-oldprice">
                                <span class="old-price"><span class="min-price"></span><span class="max-price"></span>
                                </span>
                                <!-- 库存 -->
                                <span class="inventory">库存 <span class="inventory-num" id="inventory-num"></span> 件</span>
                            </p>
                        </div>
                        
                    </div>
                    <!-- 请选择规格-颜色 -->
                    <div class="choose-specifications-color">
                        <!-- 请选择 -->
                        <span class="please-choose">请选择：</span>
                        <span class="yet-choose"></span>
                    </div>
                    <!-- 具体规格及颜色数量 -->
                    <div class="detail-main">
                        <!-- 规格及数量 -->
                        <div class="standard same" id="standard">
                            <script type="text/template" id="J_tpl_buy_attribute">
                                {@each _ as it,index}
                                <div class="standard-title title" id="standard-title">
                                    <span data-id="${it.id}">${it.name}</span>
                                    <ul class="item" data-id="${it.id}">
                                        {@each it.options as option,index2}
                                        <li attr_id="${option.id}" product_sku_id="${option.id}">
                                            ${option.name}</li>
                                        {@/each}
                                    </ul>
                                </div>
                                {@/each}
                            </script>
                        </div>
                        <!-- 数量 -->
                        <div class="number same">
                            <div class="number-title title">
                                <span>数量</span>
                            </div>
                            <div class="number-button">
                                <div class="button">
                                    <span class="reduce" id="deliver-goods-reduce"></span>
                                    <input class="text" id="deliver-goods-text" type="number" value="1">
                                    <span class="add" id="deliver-goods-add"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer">
                    <div class="confirm hidden" id="confirm" status="1">确定</div>
                        <!-- 加入购物车按钮 -->
                        <div class="purchased-separately same addCart" status="0" id="addCart-qubie">
                            加入购物车
                        </div>
                        <!-- 立即购买 -->
                        <div class="open-group same purchase-now" id="purchase-now-qubie" status="1">
                            立即购买
                        </div>
                        <!-- 单独购买按钮 -->
                        <div class="purchased-separately same purchase-separately" id="purchase-separately-qubie" status="1">
                            <div class="price"></div>
                            <div class="text">单独购买</div>
                        </div>
                        <!-- 我要开团 -->
                        <div class="open-group same want-open-group" id="want-open-group-qubie">
                            <div class="price"></div>
                            <div class="text">我要开团</div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- 拼购页单独购买规格详情 -->
            <div class="specifications-detail-wrap hidden" id="alone-specifications-detail-wrap">
                <div class="specifications-detail">
                    <div class="specifications-detail-mess">
                        <div class="detail-img">
                            <img src="" alt="">
                        </div>
                        <div class="detail-mess">
                            <div class="close" id="alone-specifications-detail-wrap-close"></div>
                            <!-- 起拼-送货 -->
                            <div class="spell-delivery">
                                <!-- 起拼按钮 -->
                                <div class="spell same">
                                </div>
                                <!-- 送货按钮 -->
                                <div class="delivery same">
                                    <!-- 送货 -->
                                </div>
                            </div>
                            <p class="detail-newprice">
                                <span class="fuhao"></span>
                                <span class="new-price">
                                    <span class="min-price"></span>
                                    <span class="max-price"></span>
                                </span>
                            </p>
                            <p class="detail-oldprice">
                                <span class="old-price"><span class="min-price"></span><span class="max-price"></span>
                                </span>
                                <!-- 库存 -->
                                <span class="inventory">库存 <span class="inventory-num" id="alone-inventory-num"></span> 件</span>
                            </p>
                        </div>
                        
                    </div>
                    <!-- 请选择规格-颜色 -->
                    <div class="choose-specifications-color">
                        <!-- 请选择 -->
                        <span class="please-choose">请选择：</span>
                        <!-- 已选择 -->
                        <span class="yet-choose"></span>
                    </div>
                    <!-- 具体规格及颜色数量 -->
                    <div class="detail-main">
                        <!-- 规格及数量 -->
                        <div class="standard same" id="standard_alone">
                            <script type="text/template" id="J_tpl_buy_attribute_alone">
                                {@each _ as it,index}
                                <div class="standard-title title" id="alone-standard-title">
                                    <span data-id="${it.id}">${it.name}</span>
                                    <ul class="item" data-id="${it.id}">
                                        {@each it.options as option,index2}
                                        <li attr_id="${option.id}" product_sku_id="${option.id}">
                                            ${option.name}</li>
                                        {@/each}
                                    </ul>
                                </div>
                                {@/each}
                            </script>
                        </div>
                        <!-- 数量 -->
                        <div class="number same">
                            <div class="number-title title">
                                <span>数量</span>
                            </div>
                            <div class="number-button">
                                <div class="button">
                                    <span class="reduce" id="alone-deliver-goods-reduce"></span>
                                    <input class="text" id="alone-deliver-goods-text" type="number" value="1">
                                    <span class="add" id="alone-deliver-goods-add"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer">
                        <div class="confirm" id="alone-confirm" status="1">确定</div>
                        
                    </div>
                </div>
            </div>
            <!-- 服务说明 -->
            <div class="service-policy hidden">
                <div class="service-policy-description">
                    <div class="title ">服务</div>
                    <div class="description-mess">
                        <span class="mess"></span>
                        <span class="more" id="service-policy-more"></span>
                    </div>
                </div>
            </div>
            <!-- 服务说明详情 -->
            <div class="service-policy-detail-wrap hidden" id="service-policy-detail-wrap">
                <div class="service-policy-detail">
                    <div class="title">服务说明<div class="close" id="service-policy-detail-wrap-close"></div></div>
                    <div class="mess" id="service-policy-detail-shouhuo">
                        <div class="mess-item">
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- 拼购人数 -->
            <div class="count-down-mess-wrap hidden">
                <div class="count-down-mess">
                    <!-- 拼购总人数 -->
                    <div class="title-mess">
                        <div class="title">
                            <img src="/images/group_goods_detail/jdy.png" alt="">
                        </div>
                        <div class="totle-count">已有<span class="people-num"></span>拼团成功</div>
                    </div>
                    <!-- 还未开团 -->
                    <div class="notyet-group hidden">
                            <div class="title">商品太热门了，都被抢完了！</div>
                            <div class="my-open-group">
                                我要开团
                            </div>
                        </div>
                    <div class="count-down-one-wrap">
                        <div class="wrap-count-down-one">
                        
                        </div>
                    </div>
                </div>
            </div>
            <!-- 拼购规则 -->
            <div class="count-down-rule-wrap hidden">
                <div class="count-down-rule">
                    <div class="rule-title">
                        <div class="title">拼购规则</div>
                        <div class="rule-more" id="rule-more">查看详情<span class="more-icon"></span></div>
                    </div>
                    <div class="rule-main">
                        <div class="main-item">
                            <span class="num">1</span>参团/开团
                        </div>
                        <div class="arr-right"></div>
                        <div class="main-item">
                            <span class="num">2</span>邀请好友
                        </div>
                        <div class="arr-right"></div>
                        <div class="main-item">
                            <span class="num">3</span>拼购成功
                        </div>
                    </div>
                </div>
            </div>
            <!-- 爱车屋 -->
            <div class="acw-wrap">
                <div class="acw">
                    <div class="left">
                        <div class="logo"><img src="" alt=""></div>
                        <div class="acw-main">
                            <div class="title"></div>
                            <div class="location"></div>
                        </div>
                    </div>
                    <div class="right">
                        <p class="good-num"></p>
                        <p class="all-good">全部商品</p>
                    </div>
                </div>
            </div>
            <!-- 商品参数 -->
            <div class="good-params-wrap">
                <div class="good-params" id="J_show_params">
                    <div class="params-title">商品参数</div>
                    <div class="params-more"></div>
                </div>
            </div>
            <!-- 商品详情 -->
            <div class="good-detail-wrap">
                <div class="good-detail">
                    <div class="detail-title">商品详情</div>
                    <div class="detail-main">
                    </div>
                </div>
            </div>
            <!-- 到底提示 -->
            <div class="footer-hint-wrap">
                <div class="footer-hint">
                    <div class="hint"></div>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
<!-- 底部导航 -->
<div class="footer-wrap">
    <div class="footer">
        <!-- 拼购首页按钮 -->
        <div class="pingou-index-button logo"></div>
        <!-- 联系客服按钮 -->
        <div class="contact-service-button logo" onclick="qimoChatClick();"></div>
        <!-- 加入购物车按钮 -->
        <div class="purchased-separately same addCart" status="0" id="addCart">
            加入购物车
        </div>
        <!-- 立即购买 -->
        <div class="open-group same purchase-now" id="purchase-now" status="1">
            立即购买
        </div>
        
        <!-- 单独购买按钮 -->
        <div class="purchased-separately same purchase-separately hidden" id="detail-purchase-separately" status="1">
            <div class="price"></div>
            <div class="text">单独购买</div>
        </div>
        <!-- 我要开团 -->
        <div class="open-group same want-open-group hidden kaituan" id="detail-want-open-group">
            <div class="price"></div>
            <div class="text">我要开团</div>
        </div>
    </div>
</div>
<div class="goodsInfo hidden ">
    <ul class="J_goods_attribute">
        <script type="text/template" id="J_tpl_goods_attribute">
            {@each SPU as it}
            <li>${it.name}：<span>${it.selected_option.name}</span></li>
            {@/each}
        </script>
     </ul>
</div>

<!-- 产品参数 -->
<div class="mask-container mask-fixed" id="mask_select_payment">
    <div class="mask-bg"></div>
    <div class="product-position-select-container">
        <div class="title">商品参数<span class="close"></span></div>
        <ul class="J_goods_attribute"></ul>
    </div>
</div>

