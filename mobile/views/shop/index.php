<?php
$this->params = ['js' => ['js/swipeSlide.min.js','js/shop-index.js'], 'css' => 'css/shop-index.css'];
?>
<style>
    main.container {
        background: #fff;
    }
</style>
<div class="shop-nav-search-box">
    <a href="/"></a>
    <input type="search" name="" id="">
    <a href="/shopping/index"></a>
    <a href="/member/index"></a>
    <span data-type="search">搜索</span>
    <span data-type="close">取消</span>
</div>
<main class="container" id="top">
    <div class="brand-page-container" style="min-height: 100.1vh;">
        <div class="brand-banner hidden">
            <img src="/images/brand_page/1.png" alt="">
        </div>
        <!--carousel-->
        <div id="shop-slide" class="slide hidden">
            <script type="text/template" id="J_carousel_tpl">
                <ul>
                    {@each _ as it}
                        <li>
                            <a href="${it.image_url}"><img src="${it.image_path}"></a>
                        </li>
                    {@/each}
                </ul>
                <div class="dot">
                {@each _ as it, index}
                    {@if index === 0}
                        <span class="cur"></span>
                    {@else}
                        <span></span>
                    {@/if}
                {@/each}
                </div>
            </script>
        </div>
        <!-- 历史记录页面 -->
        <div class="shop-search-history hidden">
            <div class="initial-show ">
                <div class="hist-search">
                    <div class="hist-title">
                        <span>历史搜索</span>
                    </div>
                    <div class="hist-cantain">
                        <ul class="uls-list">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="recommend-pro hidden">
            <div class="sec-title"><span>甄选推荐</span></div>
            <div class="pro-box" id="J_selected_box">
                <script type="text/template" id="J_selected_tpl">
                    {@each _ as it}
                    <div class="pro-item">
                        <div class="img">
                            <img src="${it.image_path}" alt="">
                        </div>
                        <div class="content">
                            <div class="info">
                                <p class="item-title">${it.show_title}</p>
                                <p class="sub-title">${it.show_message}</p>
                            </div>
                            <a class="btn-buy" href="/goods/detail?id=${it.product_id}">立即购买</a>
                        </div>
                    </div>
                    {@/each}
                </script>
            </div>
        </div>
        <div class="store-pro">
            <div class="sec-title"><span>店铺商品</span></div>
            <div class="sort-list">
                <span class="active down" data-type="sales">销量</span>
                <span data-type="price">价格</span>
            </div>
            <div class="items-list" id="J_allpro_box">
                <script type="text/template" id="J_allpro_tpl">
                    {@each _ as it}
                        <div class="item">
                            <a href="/goods/detail?id=${it.id}">
                                <div class="img">
                                    <img src="${it.mainImage}" alt="">
                                </div>
                                <p class="item-title">${it.title}</p>
                                <p class="price">
                                    ￥<span>${it.price.min}</span>
                                </p>
                            </a>
                        </div>
                    {@/each}
                </script>
            </div>
        </div>
    </div>
</main>
<a class="brand-page-back-top hidden">
    <img src="/images/brand_page/back-top.png" alt="">
    顶部
</a>