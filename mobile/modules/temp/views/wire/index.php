<?php
$this->params = ['css' => 'css/wire.css', 'js' => 'js/wire.js'];

$this->title = '皇乐师H4s乐光宝盒线束查询';
?>
<main class="container">
    <div class="harness-query">
        <div class="page-title">
            <img src="/images/hys_logo.png" alt="">
        </div>
        <div class="nav-tabs">
            <span class="active" data-type="car" id="byCar">按车型</span>
            <span data-type="line" id="byLine">按线束</span>
        </div>
        <div class="query-container"  data-type="car">
            <div class="select-box">
                <span>车系</span>
                <div class="cnt">请选择车系</div>
                <select name="" id="J_car_brand">
                    <option value="-1">请选择车系</option>
                </select>
            </div>
            <div class="select-box hidden J_car_type_box">
                <span>车型</span>
                <div class="cnt">请选择车型</div>
                <select name="" id="J_car_type">
                    <option value="-1">请选择车型</option>
                </select>
            </div>
            <div class="section hidden J_wire_box">
                <div class="s-title"><span id="J_type_name"></span>：线束<span id="J_wire_name"></span></div>
                <div class="img-box" style="min-height:300px">
                    <img id="J_type_wire_img" alt="">
                    <strong id="wireRemark"></strong>
                    <span id="J_query_other">查看其他适用车型</span>
                </div>
            </div>
        </div>
        <div class="query-container hidden"  data-type="line">
            <div class="select-box">
                <span>线束</span>
                <div class="cnt">请选择线束</div>
                <select name="" id="J_wire_list">
                    <option value="-1">请选择</option>
                </select>
            </div>
            <div class="section hidden J_wire_result">
                <div class="s-title">所用线束</div>
                <div class="img-box">
                    <img id="J_wire_img" alt="">
                </div>
            </div>
            <div class="section hidden" id="J_apply_type_list">
                <script type="text/template" id="J_tpl_list">
                    {@each _ as it}
                    <div class="panel">
                        <div class="panel-heading">${it.brand}</div>
                        <div class="panel-body">
                            {@each it.style as item}
                                <span>${item}</span>
                            {@/each}
                        </div>
                    </div>
                    {@/each}
                </script>
            </div>
        </div>
    </div>
</main>
