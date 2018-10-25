<?php
$this->params = ['css' => 'css/stream.css', 'js' => 'js/stream.js'];
?>
<div class="business-flow-record-container">
    <!-- nav bar -->
    <div class="nav-bar">
        <div style="text-align: left; margin: 10px;">
            <a href="/site/promoter/index" class="btn btn-business" style="">返回上级</a>
        </div>
        <div class="form-horizontal">
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group form-group-sm">
                        <label for="account">帐号:</label>
                        <input id="account" type="text" class="form-control">
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="mobile">预留手机号:</label>
                        <input id="mobile" type="text" class="form-control">
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="inviteCode">邀请码:</label>
                        <input id="inviteCode" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group form-group-sm" id="J_auth_time">
                        <label>过审时间:</label>
                        <div class="input-group query_time">
                            <input type="text" class="form-control date-picker J_search_timeStart">
                        </div>
                        <div class="input-group query_time">
                            <input type="text" class="form-control date-picker J_search_timeEnd">
                        </div>
                    </div>
                    <div class="form-group form-group-sm" id="J_valid_time">
                        <label>生效时间:</label>
                        <div class="input-group query_time">
                            <input type="text" class="form-control date-picker J_search_timeStart">
                        </div>
                        <div class="input-group query_time">
                            <input type="text" class="form-control date-picker J_search_timeEnd">
                        </div>
                    </div>
                    <div class="form-group form-group-sm" id="J_pay_time">
                        <label>付款时间:</label>
                        <div class="input-group query_time">
                            <input type="text" class="form-control date-picker J_search_timeStart">
                        </div>
                        <div class="input-group query_time">
                            <input type="text" class="form-control date-picker J_search_timeEnd">
                        </div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="btn-search">
                        <button class="btn btn-business" id="J_search_btn">搜索</button>
                    </div>
                </div>
                <div class="col-xs-3">
                    <ul class="list-unstyled list-info">
                        <li>
                            <strong id="J_data_num"></strong>
                            邀请数量
                        </li>
                        <li>
                            <strong id="J_data_price"></strong>
                            总金额
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--table-->
    <table class="table table-hover table-panel table-fix text-center dashed">
        <thead>
            <tr>
                <th width="200">帐号</th>
                <th width="100">状态</th>
                <th width="100">奖励金额</th>
                <th width="120">预留手机号</th>
                <th>邀请码ID</th>
                <th width="170">付款时间</th>
                <th width="170">审核通过时间</th>
                <th width="170">帐号生效时间</th>
            </tr>
        </thead>
        <tbody id="J_stream_list">
        	<script type="text/template" id="J_tpl_list">
        		{@each data as it}
        			<tr>
		                <td class="text-warning">${it.account}</td>
		                <td>${it.status|status}</td>
		                <td class="text-warning">￥${it.award_rmb}</td>
		                <td>${it.mobile}</td>
		                <td class="text-primary">${it.partner_promoter_id}</td>
		                <td>${it.pay_datetime}</td>
                        {@if it.authorized_datetime === '0000-01-01 00:00:00'}
                        <td></td>
                        {@else}
		                <td>${it.authorized_datetime}</td>
                        {@/if}
                        {@if it.account_valid_datetime === '0000-01-01 00:00:00'}
                        <td></td>
                        {@else}
                        <td>${it.account_valid_datetime}</td>
                        {@/if}
		            </tr>
        		{@/each}
        	</script>
        </tbody>
    </table>
    <!-- pagination -->
    <div class="text-right" id="J_stream_page"></div>
</div>
