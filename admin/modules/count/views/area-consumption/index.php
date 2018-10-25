<?php
$this->params = ['js' => 'js/areaconsumption.js', 'css' => 'css/areaconsumption.css'];
?>

<div class="admin-main-wrap">
    <div class="admin-data-statistics">
        <h2 class="text-center">区域数据统计</h2>
        <div class="row">
            <div class="col-xs-6">
                <ul class="list-unstyled hide">
                    <li><i class="indicator red"></i>消费金额</li>
                    <li><i class="indicator blue"></i>消费金额</li>
                    <li><i class="indicator purple"></i>消费金额</li>
                </ul>
            </div>
            <div class="col-xs-6 date-picker-box">
                <span>开始日期:</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeStart" value="">
                    <span class="input-group-btn J_date_btn">
                        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <span>结束日期:</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeEnd" value="">
                    <span class="input-group-btn J_date_btn">
                        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                    </span>
                </div>
                <button class="btn btn-default" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        <!-- 柱状图 -->
        <div class="row">
            <div class="col-xs-12">
                <div class="chart-title">区域柱状图显示区</div>
                <div class="chart-wrap">
                    <div id="bigBarChart" class="chart-canvas"></div>
                </div>
            </div>
        </div>
        <!--图表-->
        <div class="row">
            <div class="col-xs-6">
                <div class="chart-title">区域显示区</div>
                <div class="chart-wrap">
                    <div id="mapChart" class="chart-canvas"></div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="chart-title">图表显示区</div>
                <div class="chart-wrap">
                    <div id="barChart" class="chart-canvas"></div>
                </div>
            </div>
        </div>
    </div>
</div>
