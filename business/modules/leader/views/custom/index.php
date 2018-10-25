<?php
$this->params = ['css' => 'css/custom.css', 'js' => 'js/custom.js'];
?>
<div class="business-main-wrap">
    <div class="business-sell-detail">
        <div class="header text-center">
            <a href="" class="btn btn-business pull-right hidden" id="J_back_btn">返回上级</a>
        </div>
        <div class="account-info row">
            <div class="msg col-xs-6">
                <ul class="list-unstyled">
                    <li><span class="option">账户ID：</span><span id="J_info_account"></span></li>
                    <li><span class="option">账户名称：</span><span id="J_info_name"></span></li>
                    <li><span class="option">手机号：</span><span id="J_info_mobile"></span></li>
                    <li><span class="option">邮箱：</span><span id="J_info_email"></span></li>
                    <li>
                        <span class="option">区域：</span>
                        <span id="J_info_area"></span>
                        <a id="J_area_edit" class="btn btn-business-edit pull-right">修改</a>
                        <a id="J_area_sure" class="hidden btn btn-business-edit pull-right">确定</a>
                    </li>
            </ul>
        </div>
        <div class="detail col-xs-6">
            <div class="top-box clearfix ">
                <div class="col-xs-6 ">
                    <p class="option "><span></span>昨日消费额：</p>
                    <p class="money " id="J_user_yesterday"></p>
                </div>
                <div class="col-xs-6 ">
                    <p class="option "><span></span>累计消费总额：</p>
                    <p class="money " id="J_user_life"></p>
                </div>
            </div>
            <div class="legend ">
                <p class="option "><span></span>各类型订单金额比例图</p>
                <div id="J_order_legend" style="width: 600px;height:125px;"></div>
            </div>
        </div>
    </div>
    <div class="table-box ">
        <div class="performance">
            <div class="date-selecter clearfix form-inline">
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeStart" value="2017-03-01">
                    <span class="input-group-btn">
                    <button class="btn btn-default date-icon J_timeStart_show" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                </span>
                </div>
                <span class="">到</span>
                <div class="input-group">
                    <input type="text" class="form-control date-picker J_search_timeEnd" value="2017-03-01">
                    <span class="input-group-btn">
                    <button class="btn btn-default date-icon J_timeEnd_show" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                </span>
                </div>
                <div class="input-group">
                    <div class="date-tab" id="date_tab">
                        <span class="active" data-id="3">日</span>
                        <span data-id="2">周</span>
                        <span data-id="1">月</span>
                    </div>
                </div>
                <a class="btn btn-default search" id="J_search_data"><i class="glyphicon glyphicon-search"></i></a>
            </div>
            
            <div class="tab-content">
                <script type="text/template" id="J_tpl_data">
                    {@each _ as it}
                        <tr>
                            <td>${it.date}</td>
                            <td>￥${it|money}</td>
                        </tr>
                    {@/each}
                </script>
                <table>
                    <thead>
                        <tr>
                            <th>日期</th>
                            <th>消费金额</th>
                        </tr>
                    </thead>
                    <tbody id="J_user_data"></tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
