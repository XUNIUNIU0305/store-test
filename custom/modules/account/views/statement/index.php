<?php
$this->params = ['js' => 'js/statement.js', 'css' => 'css/statement.css'];

$this->title = '九大爷平台 - 交易中心';
?>
<div class="top-title">交易记录</div>
<div class="apx-acc-title statement-bg statement-container">
    <ul class="nav nav-tabs apx-acc-datepicker-nav">
        <li class="pull-right pull-left">
            <select class="form-control J_search_select" onchange="$(this).siblings().removeClass('in');$('.' + $(this).val()).addClass('in');">
                <option value="query_time">按时间段</option>
            </select>
            <div class="query_time in">
                <span>起:</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeStart" value="">
                    <span class="input-group-btn J_date_btn">
                        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <span>止:</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeEnd" value="">
                    <span class="input-group-btn J_date_btn">
                        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <button class="btn btn-default style-search" id="J_search_btn" data-method="">筛选</button>
            </div>
        </li>
    </ul>
</div>
<div class="tab-content statement-tab">
    <div role="tabpanel" class="tab-pane fade in active">
        <table class="table table-hover table-panel table-fix text-center dashed statement-table">
            <thead class='style-thead'>
                <tr class='style-control'>
                    <th width="100">类别</th>
                    <th>内容</th>
                    <th width="100">金额</th>
                    <th width="200">时间</th>
                    <th width="140">交易后账户金额</th>
                    <th width="110">状态</th>
                </tr>
            </thead>
            <tbody id="J_statement_box">
            	<script type="text/template" id="J_tpl_statementList">
            		{@each statements as it}
            			<tr>
		                    <td>${it|type_build}</td>
		                    <td><span data-toggle="tooltip" data-placement="bottom" title="${it.content.title}"><div class="text-ellipsis text-left style-control">$${it.content.message}</div></span></td>
		                    {@if it.alteration_type == 1}
		                    	<td><span class="text-success style-success">+ ${it.alteration_amount|money_build}</span></td>
		                    {@else}
		                    	<td><span class="text-danger">- ${it.alteration_amount|money_build}</span></td>
		                    {@/if}
		                    <td>${it.alteration_datetime}</td>
		                    <td>${it.rmb_after|money_build}</td>
		                    <td><span class="text-success style-success">成功</span></td>
		                </tr>
            		{@/each}
            	</script>
            </tbody>
        </table>
    </div>
</div>
<!-- pagination -->
<div class="text-right" id="J_page_list">
    
</div>
