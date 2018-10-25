<?php
use custom\modules\temp\assets\EmptyAsset;
$this->params = ['css' => 'css/groupbuy.css', 'js' => 'js/groupbuy.js'];

EmptyAsset::register($this)->addFiles($this);
?>
<?php $this->beginPage() ?>
<?php $this->head() ?>

<?php $this->beginBody() ?>
<script src="https://cdn.bootcss.com/Snowstorm/20131208/snowstorm-min.js"></script>
<script>
    snowStorm.freezeOnBlur = false;
    snowStorm.snowCharacter = '❄';
    snowStorm.flakeWidth = 100;
    snowStorm.flakeHeight = 100;
    snowStorm.snowStick = false;
</script>
<div class="apex-group-buying" id="backTop">
    <!-- 活动头部 -->
    <div class="apex-activity-banner">
        <div class="loading-ani">
            <img data-src="/images/groupbuying/banner.png" src="/images/groupbuying/banner.png" alt="">
        </div>
    </div>
    <!-- 活动主题大图 -->
    <div class="apex-brand-vip">
        <div class="container">
            <div class="row">
                <div class="vip-box">
                    <div class="default-brand-title">
                        <p>规则说明：1、定制商品金额不计入满减金额内；2、所有满减金额均在结算时直接减去，请放心购买；3、满减活动与秒杀、拼团可叠加；4、1225 当日所有订单无法取消；5、九大爷保留对此活动最终解释权。</p>
                    </div>
                    <div class="default-brand-content">
                        <div class="content-top">
                            <span>品牌名称</span>
                            <span><i></i>岩崎</span>
                            <span><i></i>奔瑞</span>
                            <span><i></i>肖勒</span>
                            <span><i></i>达里亚</span>
                            <span><i></i>车品之巅</span>
                            <span><i></i>3M</span>
                            <span><i></i>gigi</span>
                        </div>
                        <div class="content-bottom">
                            <span>满减规则</span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 1000 减 100；</p>
                            </span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 500 减 20；</p>
                                <p>满 1000 减 50；</p>
                                <p>满 3000 减 200；</p>
                                <p>满 5000 减 500；</p>
                            </span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 1500 减 100；</p>
                                <p>满 3000 减 200；</p>
                                <p>满 5000 减 300；</p>
                                <p>满 7000 减 400；</p>
                                <p>满 9000 减 500；</p>
                                <p>满 20000 减 1000；</p>
                            </span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 1000 减 100；</p>
                                <p>满 2000 减 200；</p>
                            </span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 1000 减 100；</p>
                                <p>满 500 减 50；</p>
                            </span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 880 减 50；</p>
                            </span>
                            <span><i></i>
                                <p>品牌商品</p>
                                <p>满 1000 减 50；</p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 点击进入秒杀 -->
    <div class="apex-quickly-buy-hall">
        <div class="container">
            <div class="row">
                <div class="default-head default-head-hall">
                    <div class="default-text">
                        秒杀会场
                    </div>
                </div>
                <div class="default-main">

                    <div class="default-setting-time">
                        <div class="default-time-box">
                            <div class="box-ready-start">
                                <p>
                                    <strong class="timer-title">距离开始还有：</strong>
                                    <span id="timer_hour">00</span> :
                                    <span id="timer_minute">00</span> :
                                    <span id="timer_second">00</span>
                                </p>
                            </div>
                            <div class="once-loading">
                                <p><span><a href="/temp/alphabet/f" target="_blank">查看更多</a><i></i> </span></p>
                            </div>
                        </div>
                    </div>
                    <div class="default-list-item" id="J_product_box">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 一脱到底-拼团 -->
    <div class="apex-one-of-earth">
        <div class="container">
            <div class="row">
                <div class="default-head default-head-earth">
                    <div class="default-text">
                        一脱到底-拼团
                    </div>
                </div>
                <div class="default-content">
                    <div class="default-content-text">
                        <div class="default-head">
                        规则说明：
                        </div>
                        <p>1、定制商品金额不计入满减金额内；</p>
                        <p>2、所有涉及拼团商品的订单，无法取消，请仔细确认后下单；</p>
                        <p>3、所有拼团商品先收取原价，在当日活动结束后，按照实际成团人数，再将优惠金额返还至用户账户余额；</p>
                        <p>4、1225 当日所有订单无法取消；</p>
                        <p>5、九大爷保留对此活动最终解释权。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 商品类别 -->
    <div class="apex-product-category">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </div>

</div>
<!-- team-buying people number  -->
<div class="aside-bar-menu">
    <div class="list-nav">
        <div class="activity-picture">
            <img data-src="/images/groupbuying/nav_img.png" src="/images/groupbuying/nav_img.png" alt="">
        </div>
        <div class="nav-middle"></div>
        <a href="#backTop"><span>返回顶部</span></a>
    </div>
    <div class="nav-link-img">
        <a href="/temp/rank">
            <img data-src="/images/groupbuying/nav_link.png" src="/images/groupbuying/nav_link.png" alt="">
        </a>
    </div>
</div>

<div class="apex-understand-shadow">
    <div class="default-understand-shadow">
        <div class="default-understand-box">
            <div class="default-head">
                <span>万仕博3D立体汽车座垫 悦己系列（包邮）</span>
            </div>
            <div class="default-content">
                <div class="content-item">
                    <div class="item-list">
                        <!-- <div class="item-list-head">
                            <span>商品属性</span>
                            <span>销售数满40</span>
                            <span>销售数满50</span>
                            <span>销售数满60</span>
                        </div>
                        <div class="item-list-body">
                            <div class="list-body-child">
                                <span>销/售</span>
                                <span>90</span>
                                <span>90</span>
                                <span>90</span>
                            </div>
                            <div class="list-body-child">
                                <span>销/售</span>
                                <span>90</span>
                                <span>90</span>
                                <span>90</span>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="content-foot">
                    <div class="foot-btn">
                        <span name="button" class="default-understand-close">关闭</span>
                        <span name="button" class="active"><a href="" target="_blank">立即购买</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_pro">
    {@each data as it}
        <div class="list-item">
            <div class="default-picture-hall">
                <span><img src="${it.main_image}?x-oss-process=image/resize,w_206,h_206,limit_1,m_lfit" alt="" class="img-responsive"></span>
            </div>
            <div class="default-borderline-hall"></div>
            <div class="default-title-hall">
                <span title="${it.title}">${it.title}</span>
            </div>
            <div class="default-money-hall">
                <span>￥${it.price|price}</span>
            </div>
            <div class="default-once-hall-buy">
                {@if temp === 0}
                    <a href="javascript:;">暂未开始</a>
                {@else}
                    <a href="/product?id=${it.id}" target="_blank">立即购买</a>
                {@/if}
            </div>
        </div>
    {@/each}
</script>
<?php $this->endBody() ?>
<?php $this->endPage() ?>
