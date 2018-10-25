<?php
$this->params = ['css' => 'css/list.css', 'js' => 'js/list.js'];
?>
<div class="business-main-wrap">
    <div class="business-water-container">
        <!--table-->
        <table class="table table-hover table-fix business-water-table text-center">
            <thead>
                <tr>
                    <th width="25%">已使用兑换码</th>
                    <th width="25%">购买时间</th>
                    <th width="25%">购买人</th>
                    <th width="25%">使用时间</th>
                </tr>
            </thead>
            <tbody id="J_exchange_list">
            	<script type="text/template" id="J_tpl_list">
            		{@each list as it}
	            		<tr>
		                    <td>${it.pick_id}</td>
		                    <td>${it.pay_time}</td>
		                    <td>${it.custom_user}</td>
		                    <td>${it.pick_time}</td>
		                </tr>
            		{@/each}
            	</script>
            </tbody>
        </table>
        <!-- pagination -->
        <div class="text-right" id="J_exchange_page"></div>
    </div>
</div>
