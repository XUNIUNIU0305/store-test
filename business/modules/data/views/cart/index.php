<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '购物车数据分析';
$asset = \business\modules\data\assets\BasicAsset::register($this);
$asset->js[] = 'js/cart.js';
$asset->css[] = 'css/cart.css';

?>

<div class="business-main-wrap">
    <div class="business-data-sell">
        <div class="data-top-title">
            <span class="tip ds">综合数据</span>
            <span class="second">购物车数据分析</span>
            <a href="/data" class="btn-back">返回上一级</a>
            <div class="tabs-btn" id="J_tabs_list">
                <a href="javascript:;" class="btn-tab active" data-type="area">各级销售情况</a>
                <a href="javascript:;" class="btn-tab" data-type="detail">精准查询</a>
            </div>
        </div>
        <div class="sell-search J_search_area">
            <div class="row">
                <div class="col-xs-2">
                    <label>选择级别:</label>
                    <select class="selectpicker btn-group-xs" id="J_area_level" data-width="80%">
                        <option value="-1">请选择</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <label>对象:</label>
                    <select class="selectpicker btn-group-xs" id="J_user_level" data-width="80%" data-max-options="3" multiple>
                    </select>
                </div>
                <div class="col-xs-4">
                    <label>时间选择:</label>
                    <input type="text" name="" class="time-input date-time-input" id="J_level_time">
                </div>
                <div class="col-xs-1 pull-right">
                    <label>&nbsp;</label>
                    <a href="javascript:;" class="btn btn-search" id="J_level_btn">立即搜索</a>
                </div>
            </div>
        </div>
        <div class="sell-search J_search_detail hidden">
            <div class="row">
                <div class="col-xs-9 row" id="J_select_box">
                    <div class="col-xs-2">
                        <label>五级区域:</label>
                        <select class="selectpicker btn-group-xs J_area_box" data-width="100%" data-haschild="true" data-level="1" id="J_area_province">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-1 pull-right">
                    <label>&nbsp;</label>
                    <a href="javascript:;" class="btn btn-search" id="J_add_area">追加区域</a>
                </div>
            </div>
            <div class="row">
                <div class="pull-left time-box">
                    <label>时间选择:</label>
                    <input type="text" name="" class="time-input date-time-input" id="J_detail_time">
                </div>
                <div class="col-xs-2 time-box">
                    <label>对象:</label>
                    <select class="selectpicker btn-group-xs" id="J_user_level2" data-width="40%" data-max-options="3" multiple>
                    </select>
                </div>
                <div class="col-xs-2 time-box">
                    <label>显示设置:</label>
                    <select class="selectpicker btn-group-xs" id="J_type_box" data-width="40%">
                        <option value="day">按天</option>
                        <option value="week">按周</option>
                        <option value="month">按月</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="search-items clearfix J_search_detail hidden">
            <div class="col-xs-11" id="J_search_box">
            </div>
            <div class="col-xs-1">
                <a href="javascript:;" class="btn btn-search" id="J_detail_btn">立即搜索</a>
            </div>
        </div>
        <div class="chart-container J_search_area">
            <p class="tab-list" id="J_level_list">
                <span class="line active" data-type="line"></span>
                <span class="bar" data-type="bar"></span>
            </p>
            <div class="chart-box" id="H_chart_box">
                
            </div>
        </div>
        <div class="chart-container J_search_detail hidden">
            <p class="tab-list" id="J_level_list2">
                <span class="line active" data-type="line"></span>
                <span class="bar" data-type="bar"></span>
            </p>
            <div class="chart-box" id="H_chart_box2">
                
            </div>
        </div>
        <div class="table">
            <p class="table-title">购物车数据</p>
            <p class="title-subhead" id="J_time_title"></p>
            <div class="table-box" id="J_level_table"></div>
            <div class="table-detail hidden" id="J_detail_table">
                <table>
                    <tbody id="J_detail_box">
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
