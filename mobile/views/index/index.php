<?php
$this->params = ['js' => ['js/swipeSlide.min.js','js/index.js'],'css'=>'css/app.css'];

$this->title = '九大爷平台 - 首页';
?>

<main class="container index-container">
    <!--index-->
    <div class="wechat-index-remaster">
        <!--carousel T-->
        <div id="slide" class="slide"></div>

        <!-- 9/12活动 -->
        <div class="index-activity-tankuang-wrap">
            <div class="index-activity-tankuang">
                <img src="/images/180911/bg.png" alt="">
                <div class="activity-close"></div>
            </div>
        </div>

        <!-- 9/17活动 -->
        <div class="index-activity-popup-wrap">
            <div class="index-activity-popup">
                <img src="/images/20181015/tu.png" alt="">
                <div class="activity-popup-close"></div>
            </div>
            <div class="rush-buy hidden"></div>
        </div>

        <!-- 10/25活动 -->
        <div class="index-activity-1025-wrap">
            <div class="index-activity-1025">
                <img src="/images/181025/bg.png" alt="">
                <div class="activity-close"></div>
            </div>
        </div>
        <!-- 9/19-10/07活动 -->
        <div class="new-activities-wrap1">
            <div class="activities_img">
                <a href="/temp/betabet/n">
                    <div class="new-activities1"></div>
                </a>
            </div>
            <div class="new-activities-close1"></div>
        </div>

        <!-- 历史记录页面 -->
        <div class="search-history hidden">
            <div class="initial-show ">
                <div class="hot-search">
                    <div class="hot-title">热门搜索</div>
                    <div class="hot-contain"></div>
                </div>
                <div class="hist-search">
                    <div class="hist-title">
                        <span>历史搜索</span>
                        <a href='#' class="hist-cancel">清空</a>
                    </div>
                    <div class="hist-cantain">
                        <ul class="uls-list">

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- 导航 T-->
        <div class="nav-group">
            <div class="wang hidden">
                <span>旺</span>
                <span>旺</span>
                <span>旺</span>
            </div>
            <ul>
                <li><a href="/membrane/home"></a></li>
                <li><a href="/temp/betabet/y"></a></li>
                <li><a href="/temp/brand/z"></a></li>
                <li><a href="/search/category"></a></li>
                <li><a href="/shop?id=31"></a></li>
                <li><a href="/shop?id=8"></a></li>
                <li><a href="/shop?id=9"></a></li>
                <li><a href="/shop?id=54"></a></li>
            </ul>
        </div>

        <!-- entrances T-->
        <ul class="entrances hidden">

            <li>
                <a href="/membrane/home">
                    <img src="/images/index_remaster/channel_icon_9.png" alt="">
                    <span>天御车膜</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="/images/index_remaster/channel_icon_2.jpg" alt="">
                    <span>N+专场</span>
                </a>
            </li>
            <li>
                <a href="/temp/brand/z">
                    <img src="/images/index_remaster/channel_icon_3.jpg" alt="">
                    <span>包邮专场</span>
                </a>
            </li>
            <li>
                <a href="/customization/order">
                    <img src="/images/index_remaster/channel_icon_4.jpg" alt="">
                    <span>定制上传</span>
                </a>
            </li>
            <li>
                <a href="/temp/invite">
                    <img src="/images/index_remaster/channel_icon_5.jpg" alt="">
                    <span>邀请门店</span>
                </a>
            </li>
            <li>
                <a href="/member/water">
                    <img src="/images/index_remaster/channel_icon_6.jpg" alt="">
                    <span>兑水券</span>
                </a>
            </li>
            <li>
                <a href="/shop?id=164">
                    <img src="/images/index_remaster/channel_icon_7.jpg" alt="">
                    <span>纸巾专场</span>
                </a>
            </li>
            <li>
                <a href="/search/category">
                    <img src="/images/index_remaster/channel_icon_8.jpg" alt="">
                    <span>分类</span>
                </a>
            </li>
        </ul>

        <!-- headline -->
        <div class="headline hidden">
            <a href="#">
                <i></i>
                <span>精选</span>
                冬季汽车如何正确保养 5个部位须注意
            </a>
            <a href="#" class="btn">更多</a>
        </div>

        <!-- headline -->
        <div class="game-headline hidden">
            <div class="scroll-box">
                <p class="scroll-start"></p>
                <p class="scroll-end"></p>
            </div>
        </div>
        <div class="add-image-box hidden">
            <a href="/temp/brand/x"><img src="/images/xianShiMiaoSha.png" alt=""></a>
        </div>

        <div id="event0909banner" style="margin-top:10px; display:none;">
            <a href="/temp/betabet/j"><img src="/images/event20180909/event-banner.jpg" /></a>
        </div>

        <div id="hot0909banner" style="margin-top:10px; display:none;">
            <img src="/images/event20180909/hot-banner.jpg" />
        </div>

        <!-- 门店优选 T-->
        <div id="jsShowSelection"></div>
        <!-- 新品上市 T-->
        <div id="jsNewProd"></div>
        <div class="add-image-box hidden">
            <a href="/temp/rank"><img src="/images/longHuBang.png" alt=""></a>
        </div>
        <!-- 包邮专场 T-->
        <div id="jsFreeWrapper"></div>
        <!-- 发现好货 T-->
        <div id="jsExcellentWrapper"></div>
        <!-- 汽车装饰 T-->
        <div id="jsDecorationForCarWrapper"></div>
        <!-- 美容用品 T-->
        <div id="jsBeautifyWrapper"></div>
        <!-- 门店装饰 T-->
        <div id="jsDecorationForShopWrapper"></div>
        <!-- 安全出行 T-->
        <div id="jsSaveWrapper"></div>
        <!-- 车载电器 T-->
        <div id="jsElectricWrapper"></div>
        <!-- 汽车内饰 T-->
        <div class="index-floor product-gallary automotive-interior" id="product-gallary">
        </div>
        <!-- 贴膜工具 T-->
        <div class="index-floor auto-maintenance">
        </div>
        <!-- 猜你喜欢 T-->
        <div class="index-like-floor">
            <div class="title"><i></i>猜你喜欢</div>
            <ul id="J_random_list">

            </ul>
        </div>
        <!-- 底线 -->
        <div class="bottom-placeholder">小哥，我们是有底线的~</div>
    </div>
</main>
<!-- search bar -->
<div class="search-box">
    <div class="slide-search-bar">
        <i></i>
        <form action="#"><input type="search" data-default="安程" placeholder="赶紧搜索“安程”"></form>
    </div>
    <span class="btn-cancel">取消</span>
</div>

<!--free lunch-->
<div class="mask-container wechat-activity-0904-mask" id="J_index_modal_1">
    <div class="mask-bg"></div>
    <div class="wechat-activity-0904">
        <div class="sub-content entrance in" style="top: 15%;">
            <img src="/images/event20180909/hot-alert.png" class="img-responsive">
        </div>
        <a href="javascript:void(0)" class="close-btn" data-dismiss="mask"></a>
    </div>
</div>
<div class="mask-container wechat-activity-0904-mask" id="J_index_modal_2">
    <div class="mask-bg"></div>
    <div class="wechat-activity-0904">
        <div class="sub-content entrance in" style="top: 15%;">
            <img src="/images/event20180909/event-alert.png" class="img-responsive">
        </div>
        <a href="javascript:void(0)" class="close-btn" data-dismiss="mask"></a>
    </div>
</div>

<!-- 游戏入口 -->
<div class="game-icon hidden">
    <a href="/lottery">
        <img src="/images/game_icon.png">
    </a>
</div>

<style>
.index-activity-tankuang-wrap{
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    z-Index:999999;
    display:none;
}
.index-activity-tankuang-wrap .index-activity-tankuang{
    position:absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}
.index-activity-tankuang-wrap .index-activity-tankuang img{
    width: 90%;
    /* height: auto; */
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
}
.index-activity-tankuang-wrap .index-activity-tankuang .activity-close{
    width: 30px;
    height: 30px;
    position: absolute;
    top: 34%;
    right: 6%;
    z-Index:9999999;

}
/* 1025 */
.index-activity-1025-wrap{
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    z-Index:999999;
    display:none;
}
.index-activity-1025-wrap .index-activity-1025{
    position:absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}
.index-activity-1025-wrap .index-activity-1025 img{
    width: 90%;
    /* height: auto; */
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
}
.index-activity-1025-wrap .index-activity-1025 .activity-close{
    width: 30px;
    height: 30px;
    position: absolute;
    top: 34%;
    right: 6%;
    z-Index:9999999;

}

.index-activity-popup-wrap{
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    z-Index:999999;
    display:none;
}
.index-activity-popup-wrap .index-activity-popup{
    position:absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}
.index-activity-popup-wrap .index-activity-popup img{
    width: 90%;
    /* height: auto; */
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
}
.index-activity-popup-wrap .index-activity-popup .activity-popup-close{
    width: 30px;
    height: 30px;
    position: absolute;
    top: 34%;
    right: 6%;
    z-Index:9999999;
}
.rush-buy{
    width: 5rem;
    height: 2rem;
    position: fixed;
    top: 15rem;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}
.new-activities-wrap1{
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    z-Index:999999;
    display:none;
}
.new-activities-wrap1 .activities_img{
    position: absolute;
    width: 80%;
    height: 15rem;
    top: 30%;
    left: 10%;
}
.new-activities-wrap1 .activities_img .new-activities1{
    position:absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}
.new-activities-wrap1 .new-activities-close1{
    width: 30px;
    height: 30px;
    position: absolute;
    top: 30%;
    right: 10%;
    z-Index: 9999999;
}
</style>
