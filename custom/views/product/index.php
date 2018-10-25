<?php
$this->params = ['js' => ['js/qrcode.js', 'js/product.js', 'js/requestAnimationFrame.js', 'js/jquery.fly.min.js'], 'css' => 'css/product.css'];

$this->title = '九大爷平台 - 商品详情';
?>
<div class="apx-shop-head container-fluid">
    <div class="container">
        <div class="row">
            <!-- detail -->
            <div class="media">
                <div class="media-left media-middle">
                    <div class="media-left-inner"><img src="/images/new_icon.png" class="img-responsive"></div>
                </div>
                <div class="media-body media-middle">
                    <div class="media-heading h4"><strong>九大爷直营商城</strong><span><img src="/images/brand_express.jpg" alt=""></span></div>
                    <!-- <p>主营品牌：APEX/欧帕斯</p> -->
                    <p>所在地：上海</p>
                </div>
                <div class="apx-shop-score invisible">
                    <strong>店铺动态评分</strong>
                    <ul class="list-unstyled">
                        <li>描述相符：<strong class="high-lighted"></strong></li>
                        <li>描述相符：<strong class="high-lighted"></strong></li>
                        <li>描述相符：<strong class="high-lighted"></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="apx-team-buying">
    <div class="container">
        <div class="row">
            <div class="team-buying-head">
                <div class="default-address default-style-team-buying" id="address-string">
                    安徽
                </div>
                <div class="default-name default-style-team-buying">
                    爆破团
                </div>
                <div class="default-target default-style-team-buying">
                    <span>目标</span>
                    <span class="team-list-children-target" id="target-data"></span>
                    <span>件</span>
                </div>
                <div class="default-complate default-style-team-buying">
                    <span class="tram-list-children-complate" id="complate-data">已完成：<em></em>%</span>
                    <div class="progress-out implement-process">
                        <div class="percent-show">
                            <span></span>%
                        </div>
                        <div class="progress-in"></div>
                    </div>
                </div>
                <div class="default-detail-more default-style-team-buying">
                    <button type="button" name="button" class="tram-list-children-detail-more" id="detail-more-link"><a href="/temp/groupbuy">了解更多</a></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container detail-expo">
    <div class="row" id="J_product_detail">
        <div class="detail-gallary">
            <!-- gallary carousels start -->
            <div class="connected-carousels">
                <div class="stage">
                    <div class="carousel carousel-stage J_big_img">
                        <ul></ul>
                    </div>
                    <div class="detail-gallary-override">
                        <img class="img-responsive" src="https://img.alicdn.com/bao/uploaded/i3/TB12d6DJFXXXXXxXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg" alt="">
                    </div>
                    <a href="#" class="prev prev-stage"><span>&lsaquo;</span></a>
                    <a href="#" class="next next-stage"><span>&rsaquo;</span></a>
                </div>
                <div class="navigation">
                    <!-- <a href="#" class="prev prev-navigation">&lsaquo;</a> -->
                    <!-- <a href="#" class="next next-navigation">&rsaquo;</a> -->
                    <div class="carousel carousel-navigation J_small_img">
                        <ul gallary-override="false"></ul>
                    </div>
                </div>
            </div>
            <!-- gallary carousels end -->
            <div class="detail-social">
                <ul class="list-inline">
                    <li><a href="#" class="btn btn-link"><i class="glyphicon glyphicon-share"></i>分享</a></li>
                    <li><a href="#" class="btn btn-link"><i class="glyphicon glyphicon-star-empty"></i>收藏</a></li>
                </ul>
            </div>
        </div>
        <form class="detail-promo">
            <!-- title -->
            <div class="h4 J_product_title"></div>
            <div class="sub-title J_product_description"></div>
            <div class="detail-promo-price clearfix" style="position: relative;">
                <dl class="col-xs-9">
                    <dt class="pull-left" style="width: auto"><span class="J_group_pro_num">限时特价</span></dt>
                    <dd class="J_product_price"></dd>
                </dl>
                <div class="col-xs-3">
                    <ul class="list-unstyled">
                        <li><span class="pull-left">月销量</span><span class="J_product_sales"></span></li>
                        <li><span class="pull-left">累计评价</span><span></span></li>
                    </ul>
                </div>
                <div class="col-xs-12 group-top-origin-price">
                    <div class="group-pro-tip hidden"></div>
                    <div class="origin-price hidden">
                        原价：<span class="J_origin_price"></span>
                    </div>
                </div>
                <div class="col-xs-12 group-end-time-box hidden">
                    <div class="time-label">距离结束：</div>
                    <div class="time-content">
                        <span class="countstyle day-box">00</span>天
                        <span class="countstyle hour-box">00</span>时
                        <span class="countstyle minu-box">00</span>分
                        <span class="countstyle sec-box">00</span>秒
                    </div>
                </div>
                <div class="col-xs-12 item-tag J_customization_box">
                    
                </div>
                <div class="service-click-container" onClick="qimoChatClick();">
                    联系客服
                </div>
            </div>
            <div class="detail-promo-para clearfix bg-lite">
            </div>
            <div class="detail-promo-para clearfix">
                <div id="J_sell_attr">
                    <script type="text/template" id="J_tpl_attr">
                        {@each _ as it, index}
                            <dl class="col-xs-12 J_attr_box" data-id="${it.id}">
                                <dt class="pull-left">${it.name}</dt>
                                <dd class="high-lighted">
                                    <div data-toggle="buttons">
                                    {@each it.options as item, index2}
                                        <label class="btn btn-default" attr_id="${item.id}">
                                            <input type="radio" name="attr${index}" calss="J_attr_input${index}" value="${index2}" autocomplete="off">${item.name}
                                        </label>
                                    {@/each}
                                    </div>
                                    <div class="help-block with-errors">至少选择一种</div>
                                </dd>
                            </dl>
                        {@/each}
                    </script>
                </div>
                <dl class="col-xs-12">
                    <dt class="pull-left">数量</dt>
                    <dd>
                        <div class="input-group input-group-sm detail-promo-ammount">
                            <div class="input-group-addon J_input_minus"><i class="glyphicon glyphicon-minus"></i></div>
                            <input class="form-control J_only_int" value="1" maxlength="3" title="请输入数量" type="text">
                            <div class="input-group-addon J_input_add"><i class="glyphicon glyphicon-plus"></i></div>
                        </div>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>库存<span class="J_product_inventory">2135</span>件</span>
                    </dd>
                </dl>
                <dl class="col-xs-12 btn-group pro-btn-container">
                    <div class="col-xs-4">
                        <a href="javascript:void(0);" class="btn btn-lg btn-block pull-left J_buy_now">立即购买</a>
                    </div>
                    <div class="col-xs-4 col-xs-offset-1">
                        <a href="javascript:void(0);" class="btn btn-lg btn-block pull-right J_btn_shopping"><i class="glyphicon glyphicon-shopping-cart"></i>加入购物车</a>
                    </div>
                </dl>
                <dl class="col-xs-12 btn-group hidden group-pro-btn-container">
                    <div class="col-xs-6 now-buy-group">
                        <a href="javascript:void(0);" class="btn btn-lg btn-block pull-left">开团价：<span class="J_group_pro_price"></span></a>
                        <div class="wechat-group-qr">
                            <div style="font-weight: bold;">微信扫一扫立即开团</div>
                            <div id="qr-code-container" style="width: 125px;height:125px;margin: 6px auto;"></div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-xs-offset-1">
                        <a href="javascript:void(0);" class="btn btn-lg btn-block pull-right J_btn_shopping">单独购买</a>
                    </div>
                </dl>
            </div>
        </form>
        <div class="detail-sidebar invisible">
            <div class="detail-sidebar-inner">
                <div class="detail-sidebar-title text-center"><span>看了又看</span></div>
                <!-- carousel -->
                <div id="apx-item-carousel" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <!-- single item -->
                            <a href="#">
                                <div class="apx-item">
                                    <div class="item-pic">
                                        <img src="/images/item/thumb.jpg" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="text-center">
                                            <small>¥129.00</small>
                                        </div>
                                        <div class="item-cnt-title ellipsis">米其林米其林米其林米其林米其林米其林米其林米其林米其林米其林</div>
                                    </div>
                                </div>
                            </a>
                            <!-- single item -->
                            <a href="#">
                                <div class="apx-item">
                                    <div class="item-pic">
                                        <img src="/images/item/thumb.jpg" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="text-center">
                                            <small>¥129.00</small>
                                        </div>
                                        <div class="item-cnt-title ellipsis">米其林米其林米其林米其林米其林米其林米其林米其林米其林米其林</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <!-- single item -->
                            <a href="#">
                                <div class="apx-item">
                                    <div class="item-pic">
                                        <img src="/images/item/thumb.jpg" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="text-center">
                                            <small>¥129.00</small>
                                        </div>
                                        <div class="item-cnt-title ellipsis">米其林米其林米其林米其林米其林米其林米其林米其林米其林米其林</div>
                                    </div>
                                </div>
                            </a>
                            <!-- single item -->
                            <a href="#">
                                <div class="apx-item">
                                    <div class="item-pic">
                                        <img src="/images/item/thumb.jpg" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="text-center">
                                            <small>¥129.00</small>
                                        </div>
                                        <div class="item-cnt-title ellipsis">米其林米其林米其林米其林米其林米其林米其林米其林米其林米其林</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <!-- single item -->
                            <a href="#">
                                <div class="apx-item">
                                    <div class="item-pic">
                                        <img src="/images/item/thumb.jpg" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="text-center">
                                            <small>¥129.00</small>
                                        </div>
                                        <div class="item-cnt-title ellipsis">米其林米其林米其林米其林米其林米其林米其林米其林米其林米其林</div>
                                    </div>
                                </div>
                            </a>
                            <!-- single item -->
                            <a href="#">
                                <div class="apx-item">
                                    <div class="item-pic">
                                        <img src="/images/item/thumb.jpg" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="text-center">
                                            <small>¥129.00</small>
                                        </div>
                                        <div class="item-cnt-title ellipsis">米其林米其林米其林米其林米其林米其林米其林米其林米其林米其林</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#apx-item-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#apx-item-carousel" data-slide-to="1"></li>
                            <li data-target="#apx-item-carousel" data-slide-to="2"></li>
                            <!-- Controls -->
                            <a class="left carousel-control" href="#apx-item-carousel" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#apx-item-carousel" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- team-buying people number  -->
<div class="apx-join-team-buying">
    <div class="container">
        <div class="row">
            <div class="apx-join-team-buying-box">
                <div class="join-team-buying-list">
                    <ul>
                        <div class="ulhead">
                            <div class="head">
                                <div class="">
                                    <div class="default-teamCount">
                                        已售 <span id="teamCount-data"></span> 件
                                    </div>
                                    <div class="default-currentBusiness">
                                        现拼团价：<span id="currentBusiness-data"></span>元
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 推荐商品 -->
<div class="recommend-group hidden">
    <div class="recommend-title">
        <span>推荐套餐</span>
    </div>
    <!-- carousel -->
    <div id="recommend-carousel" class="carousel slide">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox" id="J_recommend_pro">
            <script type="text/template" id="J_tpl_recommend">
                {@each _ as it, index}
                    {@if index == 0}
                    <div class="item active">
                    {@else}
                    <div class="item">
                    {@/if}
                        <div class="pro-box">
                        {@each it.items as item}
                            <a href="/product?id=${item.id}">
                                <div class="recommend-item">
                                    <div class="item-pic">
                                        <img src="${item.main_image}?x-oss-process=image/resize,w_130,h_130,limit_1,m_lfit" alt="" class="img-responsive">
                                    </div>
                                    <div class="item-cnt">
                                        <div class="item-cnt-title ellipsis">${item.title}</div>
                                        <p class="price">￥${item.price.min}</p>
                                    </div>
                                </div>
                            </a>
                        {@/each}
                        </div>
                    </div>
                {@/each}
            </script>
        </div>
        <!-- Controls -->
        <a class="left carousel-control" href="#recommend-carousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#recommend-carousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<!-- 商品详情 -->
<div class="container">
    <div class="row">
        <div class="apx-detail-aside pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><strong>九大爷直营商城</strong></h3>
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li><span>电话:</span>400-0318-119</li>
                        <li><span>邮箱:</span>service@9daye.com.cn</li>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default apx-detail-ranking invisible">
                <div class="panel-heading">
                    <div class="panel-title">店内销量排行榜</div>
                </div>
                <div class="panel-body">
                    <a href="#">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="media-left-inner">
                                    <img src="/images/item/carousel.jpg" class="img-responsive">
                                    <span class="badge">1</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <p>壳牌机油 黄壳黄喜力正品X5 10w-40</p>
                                <p class="high-lighted"></p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="media-left-inner">
                                    <img src="/images/item/carousel.jpg" class="img-responsive">
                                    <span class="badge">2</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <p>壳牌机油 黄壳黄喜力正品X5 10w-40</p>
                                <p class="high-lighted"></p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="media-left-inner">
                                    <img src="/images/item/carousel.jpg" class="img-responsive">
                                    <span class="badge">3</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <p>壳牌机油 黄壳黄喜力正品X5 10w-40</p>
                                <p class="high-lighted"></p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="media-left-inner">
                                    <img src="/images/item/carousel.jpg" class="img-responsive">
                                    <span class="badge">4</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <p>壳牌机油 黄壳黄喜力正品X5 10w-40</p>
                                <p class="high-lighted"></p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="media-left-inner">
                                    <img src="/images/item/carousel.jpg" class="img-responsive">
                                    <span class="badge">5</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <p>壳牌机油 黄壳黄喜力正品X5 10w-40</p>
                                <p class="high-lighted"></p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="media-left-inner">
                                    <img src="/images/item/carousel.jpg" class="img-responsive">
                                    <span class="badge">6</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <p>壳牌机油 黄壳黄喜力正品X5 10w-40</p>
                                <p class="high-lighted"></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="apx-detail-main">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#item-details" aria-controls="item-details" role="tab" data-toggle="tab">商品详情</a>
                    </li>
                </ul>
                <div id="detail-attr" class="clearfix">
                    <div class="col-xs-12"><strong>产品参数：</strong></div>
                </div>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="item-details">
                        <div class="apx-detail-layout">
                            <div class="apx-detail-layout-inner J_product_details">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
