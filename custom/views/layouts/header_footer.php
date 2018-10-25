<?php
use yii\helpers\Html;
use custom\assets\HeaderFooterAsset;

HeaderFooterAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>
<?php $this->beginContent('@custom/views/layouts/global.php'); ?>

<nav class="global-top-nav">
    <div class="container">
        <div class="row">
            <ul class="list-inline pull-right">
                <li class="hidden" id="J_not_login_info">
                    您好，<a href="/login">请登录</a>
                    <a class="actived" href="/register" target="_blank">我要注册</a>
                </li>
                <li class="hidden" id="J_top_user_info">
                    您好，<a class="login" href="/account"></a>
                    <span class="custom-level">
                        <!-- <em class="level-icon"></em> -->
                        <em class="level-user"></em>
                        <em class="level-fenge">|</em>
                        <em class="level-address"></em>
                    </span>
                    <a href="" class="logout">退出</a>
                </li>
                <li class="hidden" id="J_top_my_order">
                    <a href="/account/order/all">我的订单</a>
                </li>
                <li class="hidden">
                    <a href="#">客户服务</a>
                </li>
                <li class="qr">
                    <a href="#">手机端</a>
                    <div class="qr-cnt">
                        <img src="/images/custom/index_new/qrcode-hong.png" class="img-responsive">
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="global-top-search">
    <div class="container">
        <div class="row">
            <!-- logo -->
            <div class="logo-box hidden">
                <div class="logo-img">
                    <img src="/images/new_icon.png" class="img-responsive">
                </div>
                <div class="h2">九大爷平台</div>
                <p>决策圈俱乐部兄弟品牌</p>
            </div>
            <!-- content -->
            <div class="search-content">
                <!-- search bar -->
                <div class="search-bar">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-ipt" placeholder="请输入搜索内容">
                        <span class="input-group-btn"  id="search-btn">
                            <button class="btn" type="button">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                    <ul class="list-inline hidden" id="hot-search">
                        <li><a href="#" class="actived">新品靠枕</a></li>
                        <li><a href="#">新品靠枕</a></li>
                        <li><a href="#">新品靠枕</a></li>
                        <li><a href="#">新品靠枕</a></li>
                        <li><a href="#">新品靠枕</a></li>
                        <li><a href="#">新品靠枕</a></li>
                        <li><a href="#">新品靠枕</a></li>
                    </ul>
                   
                </div>
                <!-- cart btn -->
                <a href="/cart" class="btn btn-default btn-cart" data-toggle="popover_cart">
                    <i class="glyphicon glyphicon-shopping-cart"></i> 我的购物车
                    <span class="badge J_cart_count"></span>
                </a>
            </div>
            <!-- navigation -->
            <div class="search-nav hidden">
                <ul class="list-inline">
                    <li class="actived">
                        <a href="/">首页</a>
                    </li>
                    <li>
                        <a href="/membrane">特供车膜</a>
                    </li>
                </ul>
                <div class="pull-right hidden">
                    <a href="#">
                            <img src="/images/custom/index_new/05-3-广告位.png" alt="">
                        </a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- 客服列表 -->
<div class="apx-service-list">
    <ul class="btn-group">
        <li class="btn-group-bg">
            <span id="service_online" onclick="window.open('https://webchat.7moor.com/wapchat.html?accessId=4b24bec0-607c-11e7-8cfb-197c259b3994&fromUrl=&urlTitle=')" class="zx_online"></span>
            <div class="title">点击在线咨询</div>
        </li>
        <li class="btn-group-bg">
            <span id="service_phone"></span>
            <div class="title">电话客服：400-0318-119</div>
        </li>
        <li class="btn-group-bg">
            <span id="service_email"></span>
            <div class="title">邮件客服：kf@9daye.com.cn</div>
        </li>
        <li class="btn-group-bg">
            <span id="service_wechat" class="zx_weixin test_btn_weixin">
            </span>
            <div class="title">
                <img src="/images/service/bg.png" alt="" class="bg">
                <img src="/images/qrcode.jpg" class="qr">
                <p class="text">微信二维码<br>扫一扫，<span>全知道</span></p>
            </div>
        </li>
        <li class="btn-group-bg toTop">
            <a href="javascript:void(0)" onclick="$(document).scrollTop('0')"></a>
        </li>
    </ul>
</div>

<?= $content ?>

    <!-- ensurance bar -->
    <div class="apx-index-new-ensurance container-fluid">
        <div class="container">
            <div class="row text-center">
                <div class="col-xs-3">
                    <div class="ensurance_icon icon_1"></div>
                    <div class="h3">正品保障</div>
                    <p>品质护航 购物无忧</p>
                </div>
                <div class="col-xs-3">
                    <div class="ensurance_icon icon_2"></div>
                    <div class="h3">极速物流</div>
                    <p>极速物流 急速送达</p>
                </div>
                <div class="col-xs-3">
                    <div class="ensurance_icon icon_3"></div>
                    <div class="h3">无忧售后</div>
                    <p>无忧售后 后顾无忧</p>
                </div>
                <div class="col-xs-3">
                    <div class="ensurance_icon icon_4"></div>
                    <div class="h3">品类丰富</div>
                    <p>丰富品类 随心购买</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- site map -->
    <div class="apx-index-new-site-map container-fluid" >
        <div class="container">
            <div class="qr-section pull-right">
                <img src="/images/qrcode.jpg" alt="qr" width="100">
                 <p>九大爷平台官方微信公众号</p>
            </div>
            <div class="list-section clearfix">
                <div class="col-xs-3">
                    <strong>购物指南</strong>
                    <ul>
                        <li><a href="/guide/register" class="tmp" target="_blank">注册账户演示</a></li>
                        <li><a href="/guide/shopping" class="tmp" target="_blank">导购演示</a></li>
                        <li><a href="/guide/customization" class="tmp" target="_blank">定制流程演示</a></li>
                        <li><a href="/temp/alphabet/i" class="tmp" target="_blank">1225视频</a></li>
                        <li>客服24小时在线</li>
                        <!-- <li>7天无理由退换货</li> -->
                        <li>支付方式</li>
                    </ul>
                </div>
                <div class="col-xs-3">
                    <strong>物流配送</strong>
                    <ul>
                        <li>免运费政策</li>
                        <li>签收验货</li>
                        <li>配送服务承诺</li>
                        <li>物流查询</li>
                        <li>海外配送</li>
                        <li>配送费收取标注</li>
                    </ul>
                </div>
                <div class="col-xs-3">
                    <strong>帮助中心</strong>
                    <ul>
                        <li>商家进驻</li>
                        <li><!-- <a href="/quality/price" class="tmp" target="_blank"> -->APEX售价查询<!-- /a> --></li>
                        <li><a href="/downloads/supplier_register.pdf" class="tmp" target="_blank">供应商信息登记</a></li>
                        <li>商家帮助</li>
                        <li><a href="/temp/youga" class="tmp" target="_blank">御甲星座选号</a></li>
                        <li>退换货政策</li>
                         <li><a href="/quality/quality-search/index" class="tmp" target="_blank">质保单查询</a></li>
                    </ul>
                </div>
                <div class="col-xs-3">
                    <strong>关于我们</strong>
                    <ul>
                        <li><a href="/temp/alphabet/g?type=1" target="_blank" class="tmp">联系我们</a></li>
                        <li><a href="http://business.9daye.com.cn" class="tmp" target="_blank">业绩管理</a></li>
                        <li><a href="/temp/activity" class="tmp" target="_blank">活动说明</a></li>
                        <li>法律申明</li>
                        <li><a href="/corporation/employ" class="tmp" target="_blank">诚聘英才</a></li>
                        <li><a href="/temp/alphabet/g?type=0" target="_blank" class="tmp">公司简介</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <footer class="apx-footer container-fluid text-center">
        <a href="http://www.miitbeian.gov.cn" target="_blank">苏ICP备16057447号-2</a>&nbsp;&nbsp;|&nbsp;
        <span>创智汇（苏州）电子商务有限公司版权所有</span>&nbsp;&nbsp;|&nbsp;
        <span>电话：400-0318-119   &nbsp;&nbsp;|&nbsp;  邮箱：<a href="mailto: kf@9daye.com.cn ">kf@9daye.com.cn </a></span>
    </footer>
<?php $this->endContent(); ?>
