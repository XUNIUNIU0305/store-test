<?php
$this->params = ['js' => 'js/franchiser-index.js','css'=>'css/franchiser-index.css'];
$this->title = '自提拼购提货核销';
?>

<style>
    body {
        background-color: #f2f2f2;
    }
</style>

<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>

<div class="self-lifting-cancellation-box">
    <div class="self-lifting-nav">
        <span class="nav-tit">提货码</span>
        <div class="nav-prompt">
            <input class="nav-input" type="text" placeholder="请输入提货码" id="nav-input" />
            <img class="nav-pic" id="nav-pic" src="/images/self_lifting_cancellation/scan_icon.png" alt="">
        </div>
        <a href="javascript:void(0)" class="nav-btn" id="th-btn">提货</a>
    </div>
    <div class="filter-cont">
        <span class="filter-txt" id="filter-txt">按条件筛选</span>
    </div>
    <ul class="self-lifting-section" id="section-list">
        
    </ul>
    <div class="unrelated hidden" id="unrelated">
        <img class="unrelated-pic" src="/images/complete_collage/spelling_no.png" alt="">
        <div>啊哦~你暂无相关拼购哦!</div>
        <a class="unrelated-btn" href="/">前往首页逛逛</a>
    </div>
    <div class="self-lifting-footer hidden" id="self-lifting-footer">
        <span class="footer-line"></span>
        <span class="footer-txt">我是有底线的~</span>
        <span class="footer-line"></span>
    </div>
</div>

<!-- 提货条件筛选 -->
<div class="condition-screening-box hidden">
    <div class="condition-screening">
        <div class="order-status">
            <span class="status-tit">订单状态</span>
            <ul class="status-list" id="order-status">
                <li class="status-item active" data-status="">全部</li>
                <li class="status-item" data-status="2">未提货</li>
                <li class="status-item" data-status="3">部分提货</li>
                <li class="status-item" data-status="4">全部提货</li>
            </ul>
        </div>
        <div class="order-status">
            <span class="status-tit">提货日期</span>
            <ul class="status-list status-date" id="status-date">
                <li class="status-item active" data-index="0">全部</li>
                <li class="status-item" data-index="1">0-1个月</li>
                <li class="status-item" data-index="2">1-3个月</li>
                <li class="status-item" data-index="3">3-6个月</li>
                <li class="status-item" data-index="4">6-12个月</li>
                <li class="status-item" data-index="5">1年</li>
                <li class="status-item" data-index="6">1年以上</li>
            </ul>
        </div>
        <div class="order-attr" id="order-attr">
            <span class="order-attr-tit">商品名称</span>
            <input class="order-attr-prompt" type="text" placeholder="请输入商品名称" id="shop-name" />
            <span class="order-attr-tit">团编号</span>
            <input class="order-attr-prompt" type="text" placeholder="请输入拼购编号" id="collage-number" maxlength="8" />
            <span class="order-attr-tit">门店账号</span>
            <input class="order-attr-prompt" type="text" placeholder="请输入门店账号" id="store-number" maxlength="9" />
        </div>
    </div>
    <div class="order-footer">
        <a href="javascript:void(0)" class="order-btn btn-reset" id="btn-reset">重置</a>
        <a href="javascript:void(0)" class="order-btn btn-confirm" id="btn-confirm">确定</a>
    </div>
</div>

<!-- 提货核销 -->
<div class="pick-up-goods hidden" id="pick-up-goods">
    <div class="goods-box" id="write-off-box">

    </div>
    <div class="goods-footer">
        <a href="javascript:void(0)" class="order-btn btn-reset" id="hx-btn-reset">重置</a>
        <a href="javascript:void(0)" class="order-btn btn-confirm" id="hx-btn-confirm">确定</a>
    </div>
</div>

<script type="text/template" id="section-cont">
    {@if total > 0}
        {@each data as it}
            <li class="section-item">
                <div class="section-tit">
                    <span class="section-txt">团编号</span>
                    <span class="section-txt">${it.group_number}</span>
                    {@if it.status == 2}
                        <span class="section-status">未提货</span>
                    {@else if it.status == 3}
                        <span class="section-status">部分提货</span>
                    {@else if it.status == 4}
                        <span class="section-status">全部提货</span>
                    {@/if}
                </div>
                <ul class="attribute-list">
                    <li class="attribute-item">
                        <span class="attribte-tit">商品名称</span>
                        <span class="attribute-cont">${it.product_title}</span>
                    </li>
                    <li class="attribute-item">
                        <span class="attribte-tit">商品属性</span>
                        <span class="attribute-cont">
                            {@each it.sku_attributes as item}
                                ${item.selectedOption.name}&nbsp;
                            {@/each}
                        </span>
                    </li>
                    <li class="attribute-item">
                        <span class="attribte-tit">门店账号</span>
                        <span class="attribute-cont">${it.custom_user_account}</span>
                    </li>
                    <li class="attribute-item">
                        <span class="attribte-tit">手机号</span>
                        <span class="attribute-cont">${it.custom_user_mobile}</span>
                    </li>
                    <li class="attribute-item">
                        <span class="attribte-tit">数量</span>
                        <span class="attribute-cont">${it.picked_up_quantity}/${it.total_quantity}</span>
                    </li>
                    <li class="attribute-item">
                        <span class="attribte-tit">提货日期</span>
                        {@if it.status == 2}
                            <span class="attribute-cont">暂无</span>
                        {@else if it.status == 3 || it.status == 4}
                            <span class="attribute-cont">${it.last_pick_up_datetime}</span>
                        {@/if}
                    </li>
                </ul>
            </li>
        {@/each}
    {@/if}
</script>

<script type="text/template" id="write-off-cont">
    <div class="goods-tit">
        <span>提货核销</span>
        <span class="goods-icon" id="goods-icon"></span>
    </div>
    <div class="goods-sec">
        <a class="goods-pic" href="/goods/detail?id=${data.sku_attributes[0].id}"><img src="${data.product_image}" alt=""></a>
        <div class="goods-cont">
            <span class="J-txt-1">${data.product_title}</span>
            <span class="J-txt-2">
                <span>
                    {@each data.sku_attributes as it}
                        ${it.name}-${it.selectedOption.name}
                    {@/each}<br/>
                </span>
            </span>
        </div>
    </div>
    <div class="goods-attr">
        <span class="g-tit">订单总数量<span>${data.quantity}件</span></span>
        <span class="g-tit">可提取数量<span data-number="${number}" id="kt-number">${number}件</span></span>
    </div>
    <div class="goods-number">
        <span>提取数量</span>
        <span class="btn-box">
            {@if number <= 1}
                <a class="number-btn" href="javascript:void(0)" id="J-del" style="color:rgb(204,204,204);">-</a>
                <input class="number-btn btn-sty" id="J-number" value="1" type="number" />
                <a class="number-btn" href="javascript:void(0)" id="J-add" style="color:rgb(204,204,204);">+</a>
            {@else}
                <a class="number-btn" href="javascript:void(0)" id="J-del" style="color:rgb(204,204,204);">-</a>
                <input class="number-btn btn-sty" id="J-number" value="1" type="number" />
                <a class="number-btn" href="javascript:void(0)" id="J-add" style="color:rgb(0,0,0);">+</a>
            {@/if}
        </span>
    </div>
</script>