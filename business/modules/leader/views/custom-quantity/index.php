<?php
$this->params = ['css' => 'css/custom-quantity.css', 'js' => 'js/custom-quantity.js'];
?>
<div class="business-main-wrap">
    <!--branches container-->
    <div class="business-branches-container">
        <h3>数据每日凌晨更新</h3>
        <!--一级目录 start-->
        <ul class="root" id="J_list_box"></ul>
    </div>
</div>
<script type="text/template" id="J_tpl_list">
    {@each data as it}
        {@if it.has_child === true || it.level == 5}
            <li>
                <ul class="branch" data-page="1"></ul>
                <div class="content" data-level=${it.level}>
                    <i></i>
                    <span class="branch-box" data-id="${it.id}">${it.name}（<span class="high-lighted">${it.custom_quantity}</span>）</span>
                </div>
            </li>
        {@else}
            <li>
                <div class="content" data-level=${it.level}>
                    <i></i>
                    <span class="branch-box" data-id="${it.id}">${it.name}（<span class="high-lighted">${it.custom_quantity}</span>）</span>
                </div>
            </li>
        {@/if}
    {@/each}
</script>
<div class="J_info_area"></div>