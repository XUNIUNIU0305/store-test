<?php
$this->params = ['css' => 'css/area.css', 'js' => 'js/area.js'];
?>
<div class="business-main-wrap">
    <div class="business-area-data">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#J_area_1" role="tab" data-toggle="tab">省</a></li>
            <li role="presentation"><a href="#J_area_2" role="tab" data-toggle="tab">辅导区</a></li>
            <li role="presentation"><a href="#J_area_3" role="tab" data-toggle="tab">督导区</a></li>
            <li role="presentation"><a href="#J_area_4" role="tab" data-toggle="tab">运营商</a></li>
            <li role="presentation"><a href="#J_area_5" role="tab" data-toggle="tab">小组</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" data-level="1" id="J_area_1">
                <div class="content">
                    <div class="header row">
                        <div class="col-xs-2 hidden">
                            <select class="selectpicker btn-group-xs J_top" data-width="110%" data-dropup-auto="false">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="date-selecter clearfix form-inline col-xs-8">
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
                            <button class="btn btn-default search J_search_data" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <div class="col-xs-2 hidden">
                            <a href="#" class="btn btn-business pull-right">导 出</a>
                        </div>
                    </div>
                    <div class="table-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>区域</th>
                                    <th>正常业绩金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="normal" data-flag="false"></i></th>
                                    <th>退换货金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="refund" data-flag="false"></i></th>
                                    <th>驳回金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="reject" data-flag="false"></i></th>
                                </tr>
                            </thead>
                            <tbody class="J_table_data"></tbody>
                        </table>
                    </div>
                    <div class="footer text-right J_data_page_1"></div>
                </div>
                <div class="br"></div>
                <div class="legend">
                    <h4>区域业绩数据对比图</h4>
                    <div id="J_chart_1" style="width: 90%; height: 270px;"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" data-level="2" id="J_area_2">
                <div class="content">
                    <div class="header row">
                        <div class="col-xs-2 hidden">
                            <select class="selectpicker btn-group-xs J_top" data-width="110%" data-dropup-auto="false">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="date-selecter clearfix form-inline col-xs-8">
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
                            <button class="btn btn-default search J_search_data" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <div class="col-xs-2 hidden">
                            <a href="#" class="btn btn-business pull-right">导 出</a>
                        </div>
                    </div>
                    <div class="table-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>区域</th>
                                    <th>正常业绩金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="normal" data-flag="false"></i></th>
                                    <th>退换货金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="refund" data-flag="false"></i></th>
                                    <th>驳回金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="reject" data-flag="false"></i></th>
                                </tr>
                            </thead>
                            <tbody class="J_table_data"></tbody>
                        </table>
                    </div>
                    <div class="footer text-right J_data_page_2"></div>
                </div>
                <div class="br"></div>
                <div class="legend">
                    <h4>区域业绩数据对比图</h4>
                    <div id="J_chart_2" style="width: 90%; height: 270px;"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" data-level="3" id="J_area_3">
                <div class="content">
                    <div class="header row">
                        <div class="col-xs-2 hidden">
                            <select class="selectpicker btn-group-xs J_top" data-width="110%" data-dropup-auto="false">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="date-selecter clearfix form-inline col-xs-8">
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
                            <button class="btn btn-default search J_search_data" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <div class="col-xs-2 hidden">
                            <a href="#" class="btn btn-business pull-right">导 出</a>
                        </div>
                    </div>
                    <div class="table-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>区域</th>
                                    <th>正常业绩金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="normal" data-flag="false"></i></th>
                                    <th>退换货金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="refund" data-flag="false"></i></th>
                                    <th>驳回金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="reject" data-flag="false"></i></th>
                                </tr>
                            </thead>
                            <tbody class="J_table_data"></tbody>
                        </table>
                    </div>
                    <div class="footer text-right J_data_page_3"></div>
                </div>
                <div class="br"></div>
                <div class="legend">
                    <h4>区域业绩数据对比图</h4>
                    <div id="J_chart_3" style="width: 90%; height: 270px;"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" data-level="4" id="J_area_4">
                <div class="content">
                    <div class="header row">
                        <div class="col-xs-2 hidden">
                            <select class="selectpicker btn-group-xs J_top" data-width="110%" data-dropup-auto="false">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="date-selecter clearfix form-inline col-xs-8">
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
                            <button class="btn btn-default search J_search_data" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <div class="col-xs-2 hidden">
                            <a href="#" class="btn btn-business pull-right">导 出</a>
                        </div>
                    </div>
                    <div class="table-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>区域</th>
                                    <th>正常业绩金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="normal" data-flag="false"></i></th>
                                    <th>退换货金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="refund" data-flag="false"></i></th>
                                    <th>驳回金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="reject" data-flag="false"></i></th>
                                </tr>
                            </thead>
                            <tbody class="J_table_data"></tbody>
                        </table>
                    </div>
                    <div class="footer text-right J_data_page_4"></div>
                </div>
                <div class="br"></div>
                <div class="legend">
                    <h4>区域业绩数据对比图</h4>
                    <div id="J_chart_4" style="width: 90%; height: 270px;"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" data-level="5" id="J_area_5">
                <div class="content">
                    <div class="header row">
                        <div class="col-xs-2 hidden">
                            <select class="selectpicker btn-group-xs J_top" data-width="110%" data-dropup-auto="false">
                                <option value="-1">请选择</option>
                            </select>
                        </div>
                        <div class="date-selecter clearfix form-inline col-xs-8">
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
                            <button class="btn btn-default search J_search_data" type="button"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <div class="col-xs-2 hidden">
                            <a href="#" class="btn btn-business pull-right">导 出</a>
                        </div>
                    </div>
                    <div class="table-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>区域</th>
                                    <th>正常业绩金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="normal" data-flag="false"></i></th>
                                    <th>退换货金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="refund" data-flag="false"></i></th>
                                    <th>驳回金额<i class="glyphicon glyphicon-arrow-down J_sort_icon" data-type="reject" data-flag="false"></i></th>
                                </tr>
                            </thead>
                            <tbody class="J_table_data"></tbody>
                        </table>
                    </div>
                    <div class="footer text-right J_data_page_5"></div>
                </div>
                <div class="br"></div>
                <div class="legend">
                    <h4>区域业绩数据对比图</h4>
                    <div id="J_chart_5" style="width: 90%; height: 270px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_data">
    {@each list as it}
        <tr>
            <td>${it.name}<span class="text-business"></span></td>
            <td>￥${it.normal}</td>
            <td>￥${it.refund}</td>
            <td>￥${it.reject}</td>
        </tr>
    {@/each}
</script>