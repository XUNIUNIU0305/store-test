<?php
$this->params = ['css' => 'css/index.css', 'js' => 'js/index.js'];
?>
<div class="business-main-wrap">
    <div class="h3 J_back_btn hidden">
        <p class="text-right"><a class="btn btn-business">返回上级</a></p>
    </div>
    <div class="business-personal-detail">
        <div class="header">
            <div class="col-xs-4">
                <div class="col-xs-4">
                    <img id="J_user_head" src="/images/business/personal/Profile.png" alt="">
                </div>
                <div class="col-xs-8 info-box">
                    <p>姓名：<span id="J_user_name"></span></p>
                    <p>手机号：<span id="J_user_mobile"></span></p>
                    <p class="hidden">
                        <label for="upload_head" class="btn btn-business">上传头像</label>
                        <input type="file" name="" id="upload_head">
                    </p>
                </div>
            </div>
            <div class="col-xs-8">
                <div class="col-xs-4">
                    <div class="content">
                        <h3>￥<span id="J_user_yestarday"></span></h3>
                        <p>昨日业绩</p>
                        <h3>￥<span id="J_user_life"></span></h3>
                        <p>个人累计业绩</p>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="content person">
                        <h3>￥<span id="J_user_position"></span></h3>
                        <p>职位累计业绩</p>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="money">
                        <p>账户余额</p>
                        <h4 id="J_user_balance"></h4>
                    </div>
                    <p class="hidden">
                        <a href="/bank/draw-list" class="link">提现记录</a>
                        <a href="/bank/draw-apply" class="btn btn-business">提现</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="account-info row">
            <div class="msg col-xs-6">
                <ul class="list-unstyled">
                    <li><span class="h4">身份：</span><span id="J_user_role"></span></li>
                    <li><span class="h4">所在区域：</span><span id="J_user_area"></span></li>
                    <li><span class="h4">管理门店数：</span><span id="J_user_custom"></span></li>
                    <li><span class="h4">累计下单量：</span><span id="J_user_order"></span></li>
                </ul>
            </div>
            <div class="detail col-xs-6 ">
                <div class="legend ">
                    <p class="option h3 "><span></span>各类型订单金额比例图</p>
                </div>
                <div id="J_order_legend" style="width: 600px; height: 225px;">
                	
                </div>
            </div>
        </div>
        <div class="table-box ">
            <div class="performance ">
                <div class="date-selecter clearfix form-inline ">
                    <div class="input-group ">
                        <input type="text " class="form-control date-picker J_search_timeStart" value="">
                        <span class="input-group-btn ">
                    <button class="btn btn-default date-icon J_timeStart_show" type="button "><i class="glyphicon glyphicon-calendar "></i></button>
                </span>
                    </div>
                    <span class=" ">到</span>
                    <div class="input-group ">
                        <input type="text " class="form-control date-picker J_search_timeEnd" value="">
                        <span class="input-group-btn ">
                    <button class="btn btn-default date-icon J_timeEnd_show"> <i class="glyphicon glyphicon-calendar "></i></button>
                </span>
                    </div>
                    <div class="input-group date-tab" id="date_tab">
                            <span class="active " data-id="3">日</span>
                            <span data-id="2">周</span>
                            <span data-id="1">月</span>
                        </div>
                    <button class="btn btn-default search " type="button " id="J_search_btn"><i class="glyphicon glyphicon-search "></i></button>
                </div>
                <div class="tab-content clearfix">
                    <div class="col-xs-6 table-div">
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
                                    <th>销售金额</th>
                                </tr>
                            </thead>
                            <tbody id="J_user_data"></tbody>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <div id="J_bar_chart" style="min-width: 600px; height: 550px">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
