<?php
/**
 * @var $this \yii\web\View
 */
use custom\modules\membrane\assets\MembraneAsset;

$this->title = '订单列表';
MembraneAsset::register($this)->addJs('js/order.js')->addCss('css/order.css');
?>

<div class="film-order-list">
    <nav id="J_nav_list">
        <span class="active" data-status="">全部</span>
        <span data-status="2">已付款</span>
        <span data-status="3">已接单</span>
        <span data-status="4">已完成</span>
        <span data-status="5">已取消</span>
    </nav>
    <div class="list-content" id="J_list_box">
        <ul id="J_order_list"></ul>
    </div>
    <footer>已经到底了！</footer>
</div>
<script type="text/template" id="J_tpl_list">
    {@each items as it}
        <li>
            <div class="list-top">
                <span>订单编号：${it.no}</span>
                <span>${it.status|status}</span>
            </div>
            {@each it.items as item}
                <div class="list-main" data-no="${it.no}">
                    <img src="${item.membrane_product_id === 1 ? '/images/film/address/pro-pic.jpg' : '/images/film/address/apex.jpg'}" alt="">
                    <div class="list-txt">
                        <p>${item.name}</p>
                        <p>
                        {@each item.attributes as attr}
                            <span>${attr.block}:${attr.type};</span>
                        {@/each}
                        </p>
                        <div class="bottom price">¥${item.price}</div>
                        <div class="bottom account">x1</div>
                    </div>
                </div>
            {@/each}
            {@if it.status == 2}
                <div class="list-cancel">
                    <span class="btn J_cancel_btn" data-no="${it.no}">取消订单</span>
                </div>
            {@/if}
        </li>
	{@/each}
</script>