<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '下单情款分布';
$asset = \business\modules\data\assets\BasicAsset::register($this);
$asset->js[] = 'js/buy.js';
$asset->css[] = 'css/buy.css';

?>

<div class="business-main-wrap">
    <!-- business chart order container -->
    <div class="business-chart-order-container">
        <!-- nav -->
        <div class="chart-nav">
            <ul class="pull-left list-inline">
                <li><a href="#">综合数据</a></li>
                <li class="actived">下单情况分析</li>
            </ul>
            <ul class="list-inline nav-tabs clearfix">
                <li class="active">
                    <a href="#refer_date" class="btn btn-sm btn-default" data-toggle="tab">
                        按日期
                    </a>
                </li>
                <li>
                    <a href="#refer_region" class="btn btn-sm btn-default" data-toggle="tab">
                        按区域
                    </a>
                </li>
                <li>
                    <a href="/data" class="btn btn-link">返回上一级</a>
                </li>
            </ul>
        </div>
        <!-- tab panel -->
        <div class="tab-content">
            <!-- 按日期 -->
            <div role="tabpanel" class="tab-pane clearfix fade in active" id="refer_date">
                <!-- form content -->
                <div class="form-content clearfix">
                    <div class="col-xs-2">
                        <label>对象：</label>
                        <select class="selectpicker" data-width="100%" id="J_user_level" data-max-options="3" multiple>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <label>时间选择：</label>
                        <input type="text" name="" id="J_buy_time" class="time-input date-time-input">
                    </div>
                    <div class="col-xs-2">
                        <label>显示设置</label>
                        <select class="selectpicker" id="J_type_list" data-width="100%">
                            <option value="day">按天</option>
                            <option value="week">按周</option>
                            <option value="month">按月</option>
                        </select>
                    </div>
                    <div class="col-xs-4 text-right">
                        <label>&nbsp;</label>
                        <a href="javascript:;" class="btn btn-primary" id="J_search_buy">立即搜索</a>
                    </div>
                </div>
                <!-- charts -->
                <div class="chart-content">
                    <ul class="list-inline nav-tabs clearfix">
                        <li class="active">
                            <a href="#date_chart_dots" class="btn-chart-dot" data-toggle="tab"></a>
                        </li>
                        <li class="">
                            <a href="#date_chart_bars" class="btn-chart-bar" data-toggle="tab"></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane clearfix fade in active" id="date_chart_dots">
                            <div id="date-chart-dot-container" class="chart-container"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane clearfix fade" id="date_chart_bars">
                            <div id="date-chart-bar-container" class="chart-container"></div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <div class="clearfix"></div>
                <div class="table-content text-center">
                    <div class="h3">下单情况</div>
                    <small id="J_buy_subhead"></small>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="25%">时间</th>
                                <th width="25%">下单（元）</th>
                                <th width="25%">订单付款（元）</th>
                                <th width="25%">取消付款（元）</th>
                            </tr>
                        </thead>
                        <tbody id="J_pay_table">
                        	<script type="text/tempalte" id="J_tpl_pay">
                        		{@each _ as it}
                        			<tr>
                        				<td>${it.date.key}</td>
                        				<td>${it.total}</td>
                        				<td>${it.payedTotal}</td>
                        				<td>${it.cancelTotal}</td>
                        			</tr>
                        		{@/each}
                        	</script>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 按区域 -->
            <div role="tabpanel" class="tab-pane clearfix fade" id="refer_region">
                <!-- form content -->
                <div class="form-content clearfix">
                    <div class="col-xs-10 no-padding">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label for="type">类型</label>
                                <select class="selectpicker" data-width="100%" id="J_buy_type">
                                    <option value="1">下单</option>
                                    <option value="3">订单付款</option>
                                    <option value="2">取消付款</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-10 row" id="J_select_box">
                        </div>
                    </div>
                    <div class="col-xs-2 text-right">
                        <label>&nbsp;</label>
                        <a href="javascript:;" class="btn btn-primary" id="J_add_area">追加区域</a>
                    </div>
                    <div class="col-xs-4">
                        <label>时间选择:</label>
                        <input type="text" name="" class="time-input date-time-input" id="J_area_time">
                    </div>
                    <div class="col-xs-2">
                        <label>显示设置</label>
                        <select class="selectpicker" data-width="100%" id="J_area_type_list">
                            <option value="day">按天</option>
                            <option value="week">按周</option>
                            <option value="month">按月</option>
                        </select>
                    </div>
                    <hr class="col-xs-12">
                    <div class="col-xs-10" id="J_search_box">
                    </div>
                    <div class="col-xs-2 text-right">
                        <a href="javascript:;" class="btn btn-primary" id="J_area_btn">立即搜索</a>
                    </div>
                </div>
                <!-- charts -->
                <div class="chart-content">
                    <ul class="list-inline nav-tabs clearfix">
                        <li class="active">
                            <a href="#region_chart_dots" class="btn-chart-dot" data-toggle="tab"></a>
                        </li>
                        <li class="">
                            <a href="#region_chart_bars" class="btn-chart-bar" data-toggle="tab"></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane clearfix fade in active" id="region_chart_dots">
                            <div id="region-chart-dot-container" class="chart-container"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane clearfix fade" id="region_chart_bars">
                            <div id="region-chart-bar-container" class="chart-container"></div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <div class="clearfix"></div>
                <div class="table-content text-center">
                    <div class="h3">下单情况</div>
                    <small id="J_area_subhead">2017-8-1至2017-8-14|本月</small>
                    <table class="table table-hover table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td class="no-padding" width="135">
                                    <div class="scroll-area">
                                        <table class="table table-hover table-bordered table-striped">
                                            <tbody id="J_area_table_time">
                                                <tr>
                                                    <td>时间</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td class="no-padding">
                                    <div class="scroll-area">
                                        <table class="table table-hover table-bordered table-striped">
                                            <tbody id="J_area_table_detail">
                                                <tr>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                    <td width="175">北京 | 订单付款</td>
                                                </tr>
                                                <tr>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                </tr>
                                                <tr>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                </tr>
                                                <tr>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                </tr>
                                                <tr>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                </tr>
                                                <tr>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                </tr>
                                                <tr>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                    <td width="175">2000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
