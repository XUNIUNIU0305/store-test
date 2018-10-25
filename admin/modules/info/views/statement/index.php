<?php
$this->params = ['js' => 'js/statement.js', 'css' => 'css/statement.css'];
?>
<div class="admin-main-wrap">
    <div class="admin-trading-record-container">
        <!--nav-->
        <div class="admin-trading-record-title bg-danger">
            <ul class="nav nav-tabs admin-trading-record-datepicker-nav">
                <li><strong>交易记录</strong></li>
                <li class="pull-right">
                    <select class="selectpicker" data-width="120">
                        <option value="">按账户</option>
                    </select>
                    <!--<span>起:</span>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="2017-05-23">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>
                    <span>止:</span>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="2017-05-23">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </div>-->
                    <div class="input-group">
                        <input type="text" id="J_search_input" class="form-control" placeholder="请输入用户账号">
                        <span class="input-group-btn" id="J_search_btn">
                            <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>
                </li>
            </ul>
        </div>
        <!--table-->
        <table class="table table-hover table-panel table-fix text-center dashed">
            <thead>
                <tr>
                    <th width="100">类别</th>
                    <th width="120">账户</th>
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
		                    <td>${it.account}</td>
		                    <td><span data-toggle="tooltip" data-placement="bottom" title="${it.content.title}"><div class="text-ellipsis text-left">$${it.content.title}</div></span></td>
		                    {@if it.alteration_type == 1}
		                    	<td><span class="text-success">+ ${it.alteration_amount|money_build}</span></td>
		                    {@else}
		                    	<td><span class="text-danger">- ${it.alteration_amount|money_build}</span></td>
		                    {@/if}
		                    <td>${it.alteration_datetime}</td>
		                    <td>${it.rmb_after|money_build}</td>
		                    <td><span class="text-warning">成功</span></td>
		                </tr>
            		{@/each}
            	</script>
            </tbody>
        </table>
        <!-- pagination -->
        <div class="text-right" id="J_page_list"></div>
    </div>
</div>

