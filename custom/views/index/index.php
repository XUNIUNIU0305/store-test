<?php
$this->params = ['js' => 'js/index.js', 'css' => 'css/index.css'];

$this->title = '九大爷平台 - 首页';
?>

<style type="text/css">
body {
    background-color: #f6f6f6;
    overflow-x: hidden;
}
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
    z-Index:9999;
    display:none;
}
.index-activity-tankuang-wrap .index-activity-tankuang{
    width: 966px;
    height: 571px;
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
}
.index-activity-tankuang-wrap .index-activity-tankuang .activity-close{
    z-Index:99999;
    width: 52px;
    height: 52px;
    overflow: hidden;
    position: absolute;
    top: 5px;
    right: 30px;
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
    z-Index:9999;
    display: none;
}
.index-activity-popup-wrap .index-activity-popup{
    width: 966px;
    height: 468px;
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
}
.index-activity-popup-wrap .index-activity-popup .activity-popup-close{
    z-Index:99999;
    width: 52px;
    height: 52px;
    overflow: hidden;
    position: absolute;
    top: 0px;
    right: 30px;
}
.rush-buy{
    width: 966px;
    height: 120px;
    position: fixed;
    top: 600px;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.new-activities-wrap{
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    z-Index:9999;
    display: none;
}
.new-activities-wrap .new-activities{
    width: 966px;
    height: 498px;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
}
.new-activities-wrap .new-activities-close{
    z-Index: 99999;
    width: 52px;
    height: 52px;
    overflow: hidden;
    position: absolute;
    right: 30%;
    top: 25%;
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
    z-Index:9999;
    display:none;
}
.index-activity-1025-wrap .index-activity-1025{
    width: 966px;
    height: 468px;
    position:fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    overflow: hidden;
    cursor:pointer;
}
.index-activity-1025-wrap .index-activity-1025 .activity-close{
    z-Index:99999;
    width: 52px;
    height: 52px;
    overflow: hidden;
    position: absolute;
    top: 5px;
    right: 30px;
}
</style>
<script src="https://cdn.bootcss.com/Clamp.js/0.5.1/clamp.min.js"></script>

<!-- 9/9预热弹窗 -->
<div class="national-first activity-pic hidden" id="hot99">
    <img src="/images/event20180909/hot99.jpg" alt="">
    <img class="invisible" src="/images/18-6-29/index_new/middle.jpg" alt="">
    <img src="/images/event20180909/sliderleft.jpg" alt="">
    <img src="/images/event20180909/sliderright.jpg" alt="">
    <img src="/images/event20180909/hot99-big.jpg" alt="">
</div>
<!-- 9/9活动弹窗 -->
<div class="national-first activity-pic hidden" id="event99">
    <img src="/images/event20180909/event99.jpg" alt="">
    <img class="invisible" src="/images/18-6-29/index_new/middle.jpg" alt="">
    <img src="/images/event20180909/sliderleft.jpg" alt="">
    <img src="/images/event20180909/sliderright.jpg" alt="">
    <img src="/images/event20180909/event99-big.jpg" alt="">
</div>
<span id="closeSilder"></span>
<span id="logoCon"></span>
<!-- 活动 -->
<div class="national-first activity-pic hidden" id="topBanner">
    <img src="/images/18-6-29/index_new/top.jpg" alt="">
    <img class="invisible" src="/images/18-6-29/index_new/middle.jpg" alt="">
    <img src="/images/18-6-29/index_new/left.jpg" alt="">
    <img src="/images/18-6-29/index_new/right.jpg" alt="">
</div>
<div class="national-first activity-pic hidden">
    <img data-src="/images/10-01/24/title.png" alt="">
    <img data-src="/images/10-01/24/laodong.png" alt="">
    <img data-src="/images/10-01/24/left.png" alt="">
    <img data-src="/images/10-01/24/right.png" alt="">
</div>

<div class="new-year-gif">
    <div class="lantern">
        <img data-src="/images/index_img/img_0.png" alt="">
        <img data-src="/images/index_img/img_1.png" alt="">
    </div>
    <div class="lantern right">
        <img data-src="/images/index_img/img_0.png" alt="">
        <img data-src="/images/index_img/img_1.png" alt="">
    </div>
    <img data-src="/images/index_img/fireworks.gif" alt="" class="img-float fire-left">
    <img data-src="/images/index_img/fireworks.gif" alt="" class="img-float fire-right">
    <img data-src="/images/10-01/19/img1.png" alt="" class="img-float img-left-t">
    <img data-src="/images/10-01/19/img2.png" alt="" class="img-float img-left-b">
    <img data-src="/images/10-01/19/img3.png" alt="" class="img-float img-right-t">
    <img data-src="/images/10-01/19/img4.png" alt="" class="img-float img-right-b">
</div>
<div class="fixed-header">
    <div class="layout">
        <img src="/images/index_hk/logo.png">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="请输入搜索内容" id="search-ipts">
            <span class="input-group-btn" id="search-btns">
                <button class="btn" type="button">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
            </span>
        </div>
    </div>
</div>

<div class="index-update-container">
    <div class="header clearfix">
        <div class="pro-sort pull-left" id="jsGroup"></div>
        <div class="pull-left carousel-container">
            <div id="apx-index-carousel" class="carousel slide" data-interval="false">
                <!-- Indicators -->
                <ol class="carousel-indicators" id="J_indicators_list">
                    <script type="text/template" id="J_tpl_indicators">
                        {@each _ as it, index}
                            {@if index == 0}
                            <li data-target="#apx-index-carousel" data-slide-to="${index}" class="active">
                            {@else}
                            <li data-target="#apx-index-carousel" data-slide-to="${index}">
                            {@/if}
                                <img class="static" src="${it.img_url}">
                            </li>
                        {@/each}
                    </script>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox" id="J_banner_list">
                    <script type="text/template" id="J_tpl_banner">
                        {@each _ as it, index}
                            {@if index == 0}
                                <div class="item active">
                            {@else}
                                <div class="item">
                            {@/if}
                                    <a href="${it.product_url}" target="_blank">
                                        <div class="img-container" style="background-image: url('${it.img_url}');"></div>
                                    </a>
                                </div>
                        {@/each}
                    </script>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#apx-index-carousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-menu-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#apx-index-carousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-menu-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="pull-left account-info">
            <div class="info-box">
                <div class="header-img">
                    <img src="/images/indexUpdate/logo-icon.png">
                </div>
                <p class="tip">Hi，<span id="J_login_user_name">欢迎来到九大爷平台</span></p>
                <div class="btn-box hidden" id="J_index_logon_info">
                    <a href="/login" class="btn btn-left">登录</a>
                    <a href="/register" class="btn btn-right">注册</a>
                </div>
                <div class="btn-box hidden" id="J_index_logout_info">
                    <a href="/loginout" class="btn btn-left">退出</a>
                </div>
            </div>
            <div class="notice-board">
                <p class="notice-title">
                    <span>公告</span>
                    <a href="" class="hidden">更多</a>
                </p>
                <ul>
                    <li>
                        <a href="javascript:;" id="J_buy_msg"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="select-the-brand" id="jsBrandWrapper"></div>
    <div class="rank-in hidden">
        <div class="rank-in-box">
            <div class="rank-in-img">
                <div>
                    <a href="/temp/alphabet/f" target="_blank"><img src="/images/zuo.png" alt=""></a>
                </div>
                <div>
                    <a href="/temp/rank" target="_blank"><img src="/images/y.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 新品上市 -->
    <div id="newProdWrapper"></div>

    <!-- 门店优选 -->
    <div id="newProdWrapper2"></div>
    
    <div class="index-section-1 clearfix">
        <!-- 包邮专场 -->
        <div id="freeWrapper"></div>
        <!-- 发现好货 -->
        <div id="excellentWrapper"></div>
    </div>
    <!-- 汽车装饰 -->
    <div class="index-section-2" id="decorationWrapper"></div>
    <!-- 美容用品 -->
    <div class="index-section-3" id="beautifyWrapper"></div>
    <!-- 门店装饰 -->
    <div class="index-section-4" id="decorationForShopWrapper"></div>
    <div class="index-section-5 clearfix">
        <!-- 安全自驾 -->
        <div id="saveWrapper"></div>
        <!-- 车载电器 -->
        <div id="electronicWrapper"></div>
    </div>
    <!-- 汽车内饰 -->
    <div class="index-section-3" id="decorationForCarWrapper"></div>
    <!-- 贴膜工具 -->
    <div class="index-section-5 clearfix" id="toolsWrapper"></div>
    <!-- 还没逛够 -->
    <div class="index-section-6" id="moreWrapper"></div>
</div>

<!-- 浮动图标 -->
<div class="apx-water-join hidden">
    <a href="/product?id=875"><img src="/images/custom_index/1yuanbuy_bg-2.png"></a>
</div>

<!-- 抽奖入口 -->
<div class="game-icon hidden">
    <img src="/images/icons/game_qr.png" alt="">
</div>

<!-- free lunch modal -->
<div class="apx-modal-activity-0904 modal fade" id="modalFreeLunch" tabindex="-1">
    <a href="javascript:void(0)" class="close-btn text-right" data-dismiss="modal">
        <img src="/images/2018-06-modal/x-pc.png" alt="">
    </a>
    <div class="modal-dialog">
        <div class="sub-content in">
            <a href="/temp/betabet/m" id="J_modal_main_link">
                <img id="J_modal_main_img" src="/images/hot_summer_alert.png" class="img-responsive">
            </a>
            <div class="btn-row hidden">
                <a href="" class="btn"></a>
            </div>
        </div>
    </div>
</div>
<div class="apx-modal-activity-0904 modal fade" id="modalFreeLunch2" tabindex="-1">
    <a href="javascript:void(0)" class="close-btn" data-dismiss="modal">
        <img src="/images/modal/close.png" alt="">
    </a>
    <div class="modal-dialog">
        <div class="sub-content in">
            <a href="/product?id=1773">
                <img src="/images/modal/img.png" class="img-responsive">
            </a>
            <div class="btn-row">
                <a href="/product?id=1773" class="btn">
                    <img src="/images/modal/buy_btn.png" alt="">
                </a>
            </div>
        </div>
    </div>
</div>
<!-- 9/12活动 -->
<div class="index-activity-tankuang-wrap">
    <div class="index-activity-tankuang">
        <div class="activity-close"></div>
    </div>
</div>
<!-- 9/17活动 -->
<div class="index-activity-popup-wrap">
    <div class="index-activity-popup">
        <div class="activity-popup-close"></div>
    </div>
    <div class="rush-buy"></div>
</div>
<!-- 9/19活动 -->
<div class="new-activities-wrap">
    <a href="/temp/betabet/n">
        <div class="new-activities"></div>
    </a>
    <div class="new-activities-close"></div>
</div>
<!-- 10/25活动 -->
<div class="index-activity-1025-wrap">
    <div class="index-activity-1025">
        <div class="activity-close"></div>
    </div>
</div>
