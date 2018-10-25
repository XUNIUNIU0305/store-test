<?php
$this->params = ['js' => 'js/address-list.js','css'=>'css/address.css'];
$this->title = '九大爷平台 - 地址列表';

?>

<!--top nav-->
<nav class="top-nav">
    <div class="title">
        地址管理
    </div>
</nav>

<!--main container-->
<main class="container">
    <!--address-->
    <div class="wechat-address-container J_address_list">
        <script type="text/template" id="J_tpl_address">
            {@each _ as it}
        <!--address item-->
        <div class="address-item {@if it.is_default}selected{@/if}">
            <div class="title">
                <span>收货人：${it.consignee}</span>
                <span>${it.mobile}</span>
            </div>
            <div class="detail">收货地址：${it.detail}</div>
            <!--operation bar-->
            <nav class="option-bar in">
                <a href="javascript:;;" class="default J_set_default {@if it.is_default} active{@/if}" data-id="${it.id}"><i></i>默认地址</a>
                <a href="javascript:;;" class="edit J_edit_address" data-id="${it.id}"><i></i>编辑</a>
                <a href="javascript:;;" class="delete J_del_address" data-id="${it.id}"><i></i>删除</a>
            </nav>
        </div>
            {@/each}
        <!--address item-->
        </script>

    </div>
    <!--bottom btn-->
    <a href="/member/address/add" class="btn-block-bottom J_add_address">添加收货地址</a>
</main>
