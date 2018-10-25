<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '邀请门店数据分析';
$asset = \business\modules\data\assets\BasicAsset::register($this);
$asset->js[] = 'js/invite.js';
$asset->css[] = 'css/invite.css';
?>

<div class="business-main-wrap">
    <div class="business-data-invite">
        <div class="data-top-title">
            <span class="tip ds">综合数据</span>
            <span class="second">邀请门店数据分析</span>
            <a href="/data" class="btn-back">返回上一级</a>
        </div>
        <div class="sell-search">
            <div class="row">
                <div class="col-xs-8">
                    <div class="col-xs-2">
                        <label>邀请来源:</label>
                        <select class="selectpicker btn-group-xs" id="J_invite_source" data-width="80%">
                            <option value="1">运营商</option>
                            <option value="2">门店</option>
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <label>时间选择:</label>
                        <input type="text" name="" class="time-input date-time-input" id="J_invite_time">
                    </div>
                    <div class="col-xs-2">
                        <label>显示设置:</label>
                        <select class="selectpicker btn-group-xs" id="J_by_type" data-width="100%">
                            <option value="day">按天</option>
                            <option value="week">按周</option>
                            <option value="month">按月</option>
                        </select>
                    </div>
                </div>
                <div class="pull-right col-xs-1">
                    <label>&nbsp;</label>
                    <a href="javascript:;" id="J_search_btn" class="btn btn-search">立即搜索</a>
                </div>
            </div>
        </div>
        <div class="invite-total-data">
            <div class="total-item">
                <p class="item-title">平均审核通过时间</p>
                <p class="item-num" id="J_passTime"></p>
            </div>
            <div class="total-item">
                <p class="item-title">平均消费金额</p>
                <p class="item-num" id="J_averFee"></p>
            </div>
            <div class="total-item">
                <p class="item-title">每日平均邀请人数</p>
                <p class="item-num" id="J_averNum"></p>
            </div>
            <div class="total-item">
                <p class="item-title">客单价</p>
                <p class="item-num" id="J_unitPrice"></p>
            </div>
        </div>
        <div class="invite-detail">
            <div class="chart-container" style="overflow: hidden;">
                <div class="tab-list" id="J_chart_type">
                    <span class="line active" data-type="line"></span>
                    <span class="bar hidden" data-type="bar"></span>
                </div>
                <div id="H_chart_box" style="width: 99%; margin: 0 auto; height: 560px; overflow: hidden;">
                	
                </div>
            </div>
            <div class="pro-box">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">热门商品</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">热门地区</a></li>
                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">账号排名</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <div class="pro-scroll iscroll_container">
                            <ul id="J_pro_list">
                            	<script type="text/template" id="J_tpl_pro">
                            		{@each _ as it, index}
	                            		<li>
		                                    <p class="item-title"><span class="sort">NO.${index - 0 + 1}</span><span class="pull-right id">商品ID：${it.id}</span></p>
		                                    <p class="item-detail">${it.title}</p>
		                                    <p class="item-price">单价：￥<span>${it.price}</span></p>
		                                    <div class="br"></div>
		                                    <p class="total">总销售额(元)：<span>${it.total}</span></p>
		                                </li>
                            		{@/each}
                            	</script>
                            </ul>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
                        <div class="pro-scroll iscroll_container">
                            <ul id="J_area_box">
                            	<script type="text/template" id="J_tpl_area">
                            		{@each _ as it, index}
                            			<li>
		                                    <p class="item-title">NO：${index - 0 + 1}</p>
                                    		<p class="h3 text-center">${it.area.name}</p>
                                    		<p class="item-price">商品ID：${it.product.id}</p>
		                                    <p class="item-price">${it.product.title}<span>${it.product.total}</span></p>
		                                    <div class="br"></div>
		                                    <p class="total">总销售额(元)：<span>${it.total}</span></p>
		                                </li>
                            		{@/each}
                            	</script>
                            </ul>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="settings">
                        <div class="pro-scroll iscroll_container">
                            <ul id="J_user_list">
                                <script type="text/template" id="J_tpl_user">
                                	{@each _ as it, index}
                            		<li>
                            			<p class="item-title"><span class="sort">NO.${index - 0 + 1}</span><span class="pull-right id">账号：${it.account}</span></p>
                            			<p class="h5">预留手机号：${it.mobile}</p>
                            			<p class="h5">默认收货人：${it.name}</p>
                            			<p class="address">地址：${it.addr}</p>
                            			<p class="address">五级区域：${it.area}</p>
                            		</li>
                            		{@/each}
                                </script>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="table">
            <p class="table-title">销售额</p>
            <p class="table-subhead" id="J_subhead"></p>
            <div class="table-box">
                <table>
                    <tbody id="J_table_box">
                        <script type="text/template" id="J_tpl_tab">
	                        <tr>
	                            <td>时间</td>
	                            <td>邀请人数</td>
	                            <td>通过人数</td>
	                            <td>消费（元）</td>
	                        </tr>
                        	{@each _ as it}
								<tr>
									<td>${it.date.key}</td>
									<td>${it.inviteNum}</td>
									<td>${it.passNum}</td>
									<td>${it.total}</td>
								</tr>
                        	{@/each}
                        </script>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>