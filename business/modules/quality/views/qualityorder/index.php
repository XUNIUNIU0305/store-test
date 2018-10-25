<?php
$this->params = ['css' => 'css/quality.css', 'js' => ['js/date.js', 'js/index.js']];
?>
<!-- main area start -->

<div class="business-main-wrap">
    <div class="row">
        <div class="business-warranty-container">
            <!--查询导航-->
            <div class="nav-bar " id="J_search_box" data-type="0">
                <form class="form-inline">
                    <div class="form-group">
                        <label for="select_query">查询方式：</label>
                        <select id="select_query" class="form-control">
                            <option value="1">按质保卡号查询</option>
                            <option value="2">按管芯号查询</option>
                            <option value="3" selected>按施工日期查询</option>
                            <option value="4">按姓名查询</option>
                            <option value="5">按车牌号查询</option>
                            <option value="6">按车架号查询</option>
                        </select>

                    </div>
                    <div class="input-group query_common">
                        <input type="text" class="form-control" id="J_search_input" placeholder="查询">
                        <span class="input-group-btn J_search_btn">
                            <a class="btn"><i class="glyphicon glyphicon-search"></i></a>
                        </span>
                    </div>
                    <div class="input-group query_time in">
                        <label>开始日期：</label>
                        <input type="text" class="form-control date-picker J_search_timeStart" value="2017-03-01">
                        <span class="input-group-btn J_date_btn">
                            <a class="btn"><i class="glyphicon glyphicon-calendar"></i></a>
                        </span>
                    </div>
                    <div class="input-group query_time in">
                        <label>截止日期：</label>
                        <input type="text" class="form-control date-picker J_search_timeEnd" value="2017-03-15">
                        <span class="input-group-btn J_date_btn">
                            <a class="btn"><i class="glyphicon glyphicon-calendar"></i></a>
                        </span>
                    </div>
                    <a class="query_time in btn btn-business J_search_btn">查询</a>
                </form>
            </div>
            <!--panel-->
            <div class="warranty-panel">
                <div class="header">
                    <div class="col-xs-4">
                        <div class="col-xs-4">质保号码</div>
                        <div class="col-xs-4">姓名</div>
                        <div class="col-xs-4">手机</div>
                    </div>
                    <div class="col-xs-4">
                        <div class="col-xs-5">车牌号</div>
                        <div class="col-xs-7">车架号</div>
                    </div>
                    <div class="col-xs-4">
                        <div class="col-xs-7">施工日期</div>
                        <div class="col-xs-5">操作</div>
                    </div>
                </div>
                <div class="iscroll_container with-header">
                    <ul class="list-unstyled dashed-split J_user_list">
                        <!--single li-->
                        <script type="text/template" id="J_tpl_list">
                            {@each codes as it}
                        <li>
                            <div class="col-xs-4">
                                <div class="col-xs-4">${it.code}</div>
                                <div class="col-xs-4">${it.name}</div>
                                <div class="col-xs-4">${it.mobile}</div>
                            </div>
                            <div class="col-xs-4">
                                <div class="col-xs-5">${it.car_code}</div>
                                <div class="col-xs-7">${it.car_frame}</div>
                            </div>
                            <div class="col-xs-4">
                                <div class="col-xs-7">${it.construct_date}</div>
                                <div class="col-xs-5"><a class="text-info" href="/quality/qualityorder/detail?id=${it.id}">查看</a></div>
                            </div>
                        </li>
                        <!--single li-->
                            {@/each}
                        </script>

                    </ul>
                </div>
            </div>
            <!-- pagination -->
            <div class="text-right J_user_page">

            </div>
        </div>
    </div>
</div>