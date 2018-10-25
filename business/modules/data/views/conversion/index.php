<?php
/**
 * @var $this \yii\web\View
 */

use business\modules\data\assets\BasicAsset;

$this->title = '转化率阶梯分析';
$asset = BasicAsset::register($this);
$asset->js[] = 'js/conversion.js';
$asset->css[] = 'css/conversion.css';
?>

<div class="business-main-wrap">
    <div class="business-data-conversion">
        <div class="data-top-title">
            <span class="tip ds">综合数据</span>
            <span class="second">转化率阶梯分析</span>
            <a href="/data" class="btn-back">返回上一级</a>
        </div>
        <div class="sell-search">
            <div class="row">
                <div class="col-xs-8 row" id="J_select_box">
                    <div class="col-xs-2">
                        <label>五级区域:</label>
                        <select class="selectpicker btn-group-xs J_area_box" data-width="100%" data-haschild="true" data-level="1" id="J_area_province">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <label>对象:</label>
                    <select class="selectpicker btn-group-xs" data-width="80%" id="J_user_list" data-max-options="3" multiple>
                        <option value="4">运营商店</option>
                        <option value="3" selected>体系内店</option>
                        <option value="2">加盟店</option>
                    </select>
                </div>
            </div>
            <div class="pull-right search-btn-box">
                <label>&nbsp;</label>
                <a href="javascript:;" class="btn btn-search" id="J_search_btn">立即搜索</a>
            </div>
            <div class="row">
	            <div class="pull-left time-box">
	                <label>时间选择:</label>
	                <input type="text" name="" class="time-input date-time-input" id="J_time_box">
	            </div>
	            <div class="col-xs-2" style="margin-top: 20px;">
	                <label>显示设置:</label>
	                <select class="selectpicker btn-group-xs" data-width="100%" id="J_type_list">
	                    <option value="day">按天</option>
	                    <option value="week">按周</option>
	                    <option value="month">按月</option>
	                </select>
	            </div>
            </div>
        </div>
        <div id="data_loading_box">
            <div class="items-box" id="J_items_box">
                <div class="item has-shadow active" data-type="first">
                    <p class="h3">第一次下单人数</p>
                    <p class="h2"><span id="J_first_num"></span>人</p>
                    <p class="h4">转化率<span id="J_first_con"></span>%</p>
                </div>
                <div class="item has-shadow" data-type="payed">
                    <p class="h3">支付人数</p>
                    <p class="h2"><span id="J_payed_num"></span>人</p>
                    <p class="h4">转化率<span id="J_payed_con"></span>%</p>
                </div>
                <div class="item has-shadow" data-type="finish">
                    <p class="h3">完成订单人数</p>
                    <p class="h2"><span id="J_finish_num"></span>人</p>
                    <p class="h4">转化率<span id="J_finish_con"></span>%</p>
                </div>
                <div class="item has-shadow" data-type="second">
                    <p class="h3">二次消费人数</p>
                    <p class="h2"><span id="J_second_num"></span>人</p>
                    <p class="h4">转化率<span id="J_second_con"></span>%</p>
                </div>
                <div class="item has-shadow" data-type="three">
                    <p class="h3">三次消费人数</p>
                    <p class="h2"><span id="J_three_num"></span>人</p>
                    <p class="h4">转化率<span id="J_three_con"></span>%</p>
                </div>
            </div>
            <div class="chart-container" id="H_chart_box">
                
            </div>
        </div>
    </div>
</div>
