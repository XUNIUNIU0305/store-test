<?php
$this->params = ['css' => 'css/contrast.css', 'js' => 'js/contrast.js'];
?>
<div class="business-main-wrap">
    <div class="business-data-comparison">
        <div class="business-comparison">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs hidden" role="tablist">
                <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">不同区域，相同时间</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="header">
                        <div class="date-selecter clearfix form-inline date-box">
                            <div class="input-group">
                                <input type="text" class="form-control date-picker J_search_timeStart" value="2017-03-01">
                                <span class="input-group-btn J_timeStart_show">
                                <button class="btn btn-default date-icon" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                            </div>
                            <span class="">到</span>
                            <div class="input-group">
                                <input type="text" class="form-control date-picker J_search_timeEnd" value="2017-03-01">
                                <span class="input-group-btn J_timeEnd_show">
                                <button class="btn btn-default date-icon" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                            </div>
                            <div class="input-group">
                                <div class="date-tab" id="date_tab">
                                    <span class="active " data-type="3">日</span>
                                    <span data-type="2">周</span>
                                    <span data-type="1">月</span>
                                </div>
                            </div>
                        </div>
                        <div class="search-box">
                            <div class="form-inline">
                                <div class="row clearfix">
                                    <div class="col-xs-9 row" id="J_select_box"></div>
                                    <div class="col-xs-3 text-right"><a class="btn btn-add" id="J_add_supplier">添 加</a></div>
                                </div>
                            </div>
                            <div class="content" id="J_search_box"></div>
                        </div>
                        <div class="search-btn-box clearfix">
                            <a class="btn btn-business pull-right" id="J_search_data_btn">搜 索</a>
                        </div>
                    </div>
                    <div class="table-box">
                        <div class="table-content">
                            <table id="J_table_data"></table>
                        </div>
                        <div id="J_chart_box" style="width: 90%;height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

