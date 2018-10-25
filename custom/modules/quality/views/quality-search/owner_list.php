<?php
$this->params = ['js' => ['js/quality_global.js', 'js/owner_list.js'], 'css' => [ 'css/quality_global.css', 'css/owner_list.css']];

$this->title = '九大爷平台 - 质保单查询 - 车主查询列表';
?>
<div class="custom-quality-result">
    <div class="quality-nav-text">
        车主查询&nbsp;&gt;车主质保单列表
    </div>
    <div class="quality-title">车主质保单列表</div>
    <div class="quality-result-container">
        <div class="result-title">
            <span>查询结果：</span>请点击质保单号查询详情
        </div>
        <table class="result-list">
            <thead>
                <tr>
                    <th>序号</th>
                    <th>品牌</th>
                    <th>质保单号</th>
                    <th>质保单生成日期</th>
                </tr>
            </thead>
            <tbody id="J_owner_list">
                
            </tbody>
        </table>
    </div>
</div>

<script type="text/template" id="J_tpl_list">
    {@each _ as it, index}
        <tr>
            <td>${index - 0 + 1}</td>
            <td>${it.membraneBrand}</td>
            <td>
                <a href="/quality/quality-search/owner-detail?order_code=${it.code}" target="_blank">${it.code}</a>
            </td>
            <td>${it.construct_date}</td>
        </tr>
    {@/each}
</script>