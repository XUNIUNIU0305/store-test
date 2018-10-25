<?php
/**
 * Created by PhpStorm.
 * User: wang li
 * @var $this \yii\web\View
 */

$this->title = '个人日报';
$asset = \business\modules\data\assets\BasicAsset::register($this);
$asset->js[] = 'js/day-index.js';
$asset->css[] = 'css/day-index.css';
?>

<div class="business-main-wrap">
	<div class="business-data-daily">
	    <h2 class="title">数据总览</h2>
	    <div class="J_total_loading_box">
	    	<div class="top row">
	    	    <div class="col-xs-4">
	    	        <div class="data-chart has-shadow">
	    	            <div class="chart-box" id="container"></div>
	    	            <div class="detail">
	    	                <p class="h2">总转化率</p>
	    	                <p class="h3">参与过消费的账号数/总注册数=总转化率<br><span id="J_total_num"></span>(个)/<span class="J_custom_num"></span>(个)=<span id="J_total_conversion"></span></p>
	    	            </div>
	    	            <div class="detail">
	    	                <p class="h2">日活跃度</p>
	    	                <p class="h3">当日消费人数/总注册人数=日活跃度<br>
	    	                <span class="J_day_fee"></span>(人)/<span class="J_custom_num"></span>(人)=<span id="J_day_activity"></span></p>
	    	            </div>
	    	        </div>
	    	    </div>
	    	    <div class="col-xs-8 right-box">
	    	        <div class="has-shadow">
	    	            <p class="title">日总金额</p>
	    	            <p class="content"><span id="J_day_total_fee"></span>元</p>
	    	        </div>
	    	        <div class="has-shadow">
	    	            <p class="title">日订单数</p>
	    	            <p class="content"><span id="J_day_order"></span>单</p>
	    	        </div>
	    	        <div class="has-shadow">
	    	            <p class="title">日消费人数</p>
	    	            <p class="content"><span class="J_day_fee"></span>人</p>
	    	        </div>
	    	        <div class="has-shadow">
	    	            <p class="title">实控门店</p>
	    	            <p class="content"><span class="J_custom_num"></span>家</p>
	    	        </div>
	    	    </div>
	    	</div>
	    	<div class="data-middle">
	    	    <div class="has-shadow">
	    	        <p class="title">新增注册门店数</p>
	    	        <p class="content"><span id="J_register_num"></span>家</p>
	    	    </div>
	    	    <div class="has-shadow">
	    	        <p class="title">新增邀请门店数</p>
	    	        <p class="content"><span id="J_code_num"></span>家</p>
	    	    </div>
	    	    <div class="has-shadow">
	    	        <p class="title">客单价</p>
	    	        <p class="content"><span id="J_unit_price"></span>元/单</p>
	    	    </div>
	    	    <div class="has-shadow">
	    	        <p class="title">客单量</p>
	    	        <p class="content"><span id="J_unit_num"></span>单/个</p>
	    	    </div>
	    	</div>
	    </div>
	    <div class="data-sort J_total_loading_box">
	        <div class="has-shadow pointer J_pro_buy_ranking" data-id="0">
	            <p class="title">热销单品</p>
	            <div class="pro">
	                <img id="J_hot_pro_img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTMyIDc5LjE1OTI4NCwgMjAxNi8wNC8xOS0xMzoxMzo0MCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUuNSAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzcwNjU4RTM3RTY5MTFFNzlFMTREQjM4Qjk3M0JCOEQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzcwNjU4RTQ3RTY5MTFFNzlFMTREQjM4Qjk3M0JCOEQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NzA2NThFMTdFNjkxMUU3OUUxNERCMzhCOTczQkI4RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3NzA2NThFMjdFNjkxMUU3OUUxNERCMzhCOTczQkI4RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pmp7MZ4AAAAPSURBVHjaYvj//z9AgAEABf4C/i3Oie4AAAAASUVORK5CYII=">
	                <div class="detail">
	                    <p class="h3" id="J_hot_pro_title"></p>
	                    <p class="price">单价：￥<span id="J_hot_pro_price"></span></p>
	                </div>
	            </div>
	            <p class="count">采购量(件)：<span id="J_hot_pro_count"></span></p>
	        </div>
	        <div class="has-shadow pointer J_pro_buy_ranking" data-id="1">
	            <p class="title">销售冠军</p>
	            <div class="pro">
	                <img id="J_sell_img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTMyIDc5LjE1OTI4NCwgMjAxNi8wNC8xOS0xMzoxMzo0MCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUuNSAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzcwNjU4RTM3RTY5MTFFNzlFMTREQjM4Qjk3M0JCOEQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzcwNjU4RTQ3RTY5MTFFNzlFMTREQjM4Qjk3M0JCOEQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NzA2NThFMTdFNjkxMUU3OUUxNERCMzhCOTczQkI4RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3NzA2NThFMjdFNjkxMUU3OUUxNERCMzhCOTczQkI4RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pmp7MZ4AAAAPSURBVHjaYvj//z9AgAEABf4C/i3Oie4AAAAASUVORK5CYII=">
	                <div class="detail">
	                    <p class="h3" id="J_sell_title"></p>
	                    <p class="price">单价：￥<span id="J_sell_price"></span></p>
	                </div>
	            </div>
	            <p class="count">总销售额(元)：<span id="J_sell_total"></span></p>
	        </div>
	        <div class="has-shadow pointer J_pro_buy_ranking" data-id="2">
	            <p class="title">喜爱品牌</p>
	            <div class="pro">
	                <img id="J_brand_img" src="">
	            </div>
	            <p class="count" id="J_brand_name"></p>
	        </div>
	    </div>
	    <div class="ranking-pro hidden J_pro_ranking_info">
	        <span class="close">X关闭</span>
	        <p class="pro-title">数据详情</p>
	        <table>
	            <thead>
	                <tr>
	                    <td>序号</td>
	                    <td>属性</td>
	                    <td></td>
	                    <td>数量(件)</td>
	                    <td class="sort"></td>
	                </tr>
	            </thead>
	            <tbody id="J_hot_pro_detail"></tbody>
	        </table>
	    </div>
	    <div class="ranking-price ranking-pro hidden J_pro_ranking_info">
	        <span class="close">X关闭</span>
	        <p class="pro-title">数据详情</p>
	        <table>
	            <thead>
	                <tr>
	                    <td>序号</td>
	                    <td>属性</td>
	                    <td></td>
	                    <td>数量(件)</td>
	                    <td class="sort"></td>
	                </tr>
	            </thead>
	            <tbody  id="J_sell_detail"></tbody>
	        </table>
	    </div>
	    <div class="ranking-love hidden J_pro_ranking_info" id="J_ranking_love">
	        <span class="close">X关闭</span>
	        <p class="love-title">排行榜</p>
	        <div class="love-items" id="J_love_items">
            	<script type="text/template" id="J_tpl_pro">
            		{@each _ as it}
            			<div class="item">
			                <div class="pro">
			                    <img src="${it.image}" alt="">
			                    <div class="detail">
			                        <p class="h3">${it.title}</p>
			                        <p class="price">单价：<span>${it|price}</span></p>
			                    </div>
			                </div>
			                <p class="count-title">总采购金额(元)：</p>
			                <p class="count">${it.total}</p>
			            </div>
            		{@/each}
            	</script>
	        </div>
	    </div>
	    <div class="data-ranking">
	        <div class="ranking-title">
	            <p id="J_ranking_tab">
	                <span class="active" data-type="area">区域排名</span>
	                <span data-type="store">门店排名</span>
	            </p>
	        </div>
	        <div class="ranking-area">
	            <div class="type" id="J_area_tabs">
	                <span class="active" data-level="1">省级区域</span>
	                <span  data-level="2">辅导区</span>
	                <span  data-level="3">督导区</span>
	                <span  data-level="4">运营商</span>
	                <span  data-level="5">小组</span>
	            </div>
	            <div id="area_loading_box">
	            	<div class="content">
	            	    <div class="left">
	            	        <p class="num-title">NO.1</p>
	            	        <div class="info">
	            	            <p class="h1 J_area_name text-ellipsis">暂无</p>
	            	            <p class="J_area_price">销售额：<span>0</span>元</p>
	            	            <p class="J_area_unit">客单价：<span>0</span>元</p>
	            	            <p class="J_area_total_account">总账户数：<span>0</span>个</p>
	            	            <p class="J_area_total_fee">总消费账户数：<span>0</span>个</p>
	            	            <p class="J_area_day_fee">单日消费账户数：<span>0</span>个</p>
	            	        </div>
	            	    </div>
	            	    <div class="right">
	            	        <div>
	            	            <p class="num-title">NO.2&nbsp;&nbsp;&nbsp;<span class="J_area_name text-ellipsis">暂无</span></p>
	            	            <div class="info">
	            	                <p class="J_area_price">销售额：<span>0</span>元</p>
	            	                <p class="J_area_unit">客单价：<span>0</span>元</p>
	            	                <p class="J_area_total_account">总账户数：<span>0</span>个</p>
	            	                <p class="J_area_total_fee">总消费账户数：<span>0</span>个</p>
	            	                <p class="J_area_day_fee">单日消费账户数：<span>0</span>个</p>
	            	            </div>
	            	        </div>
	            	        <div>
	            	            <p class="num-title">NO.3&nbsp;&nbsp;&nbsp;<span class="J_area_name text-ellipsis">暂无</span></p>
	            	            <div class="info">
	            	                <p class="J_area_price">销售额：<span>0</span>元</p>
	            	                <p class="J_area_unit">客单价：<span>0</span>元</p>
	            	                <p class="J_area_total_account">总账户数：<span>0</span>个</p>
	            	                <p class="J_area_total_fee">总消费账户数：<span>0</span>个</p>
	            	                <p class="J_area_day_fee">单日消费账户数：<span>0</span>个</p>
	            	            </div>
	            	        </div>
	            	        <div>
	            	            <p class="num-title">NO.4&nbsp;&nbsp;&nbsp;<span class="J_area_name text-ellipsis">暂无</span></p>
	            	            <div class="info">
	            	                <p class="J_area_price">销售额：<span>0</span>元</p>
	            	                <p class="J_area_unit">客单价：<span>0</span>元</p>
	            	                <p class="J_area_total_account">总账户数：<span>0</span>个</p>
	            	                <p class="J_area_total_fee">总消费账户数：<span>0</span>个</p>
	            	                <p class="J_area_day_fee">单日消费账户数：<span>0</span>个</p>
	            	            </div>
	            	        </div>
	            	        <div>
	            	            <p class="num-title">NO.5&nbsp;&nbsp;&nbsp;<span class="J_area_name text-ellipsis">暂无</span></p>
	            	            <div class="info">
	            	                <p class="J_area_price">销售额：<span>0</span>元</p>
	            	                <p class="J_area_unit">客单价：<span>0</span>元</p>
	            	                <p class="J_area_total_account">总账户数：<span>0</span>个</p>
	            	                <p class="J_area_total_fee">总消费账户数：<span>0</span>个</p>
	            	                <p class="J_area_day_fee">单日消费账户数：<span>0</span>个</p>
	            	            </div>
	            	        </div>
	            	    </div>
	            	</div>
	            </div>
	        </div>
	        <div class="ranking-store hidden">
	            <p class="method">&nbsp;</span></p>
	            <div id="store_loading_box">
	            	<div class="items" id="J_store_ranking">
	            		<script type="text/template" id="J_tpl_store">
	            			{@each _ as it}
	            				<div class="item">
				                    <p class="item-title">账号：<span>${it.user.account}</span>&nbsp;&nbsp;&nbsp;&nbsp;预留手机号：<span>${it.user.mobile}</span></p>
				                    <p class="h5">默认收货地址<span>￥${it.total}</span></p>
				                    <p class="address-info">姓名：${it.user.receiver}</p>
				                    <p class="address-info">地址：${it.user.address}</p>
				                    <p class="address-info">五级区域：${it.user.area}</p>
				                </div>
	            			{@/each}
	            		</script>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>

</div>