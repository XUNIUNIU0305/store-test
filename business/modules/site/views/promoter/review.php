<?php
$this->params = ['css' => 'css/review.css', 'js' => 'js/review.js'];
?>
<div class="business-main-wrap">
    <div class="business-code-store">
        <div class="code-store-list">
            <a href="/site/promoter/index" class="btn btn-business" style="margin: 10px">返回上级</a>
            <div class="header">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#success" role="tab" data-toggle="tab">注册成功</a></li>
                    <li role="presentation"><a href="#authstr" aria-controls="authstr" role="tab" data-toggle="tab">待审核</a></li>
                    <li role="presentation"><a href="#uncommitted" aria-controls="uncommitted" role="tab" data-toggle="tab">待提交</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="success">
                        <div class="head">
                            <div class="form-inline row">
                                <div class="form-group col-xs-4">
                                    <label class="form-lable">按门店账号：</label>
                                    <input type="text" class="form-control number" id="J_success_account">
                                </div>
                                <div class="form-group col-xs-4">
                                    <label class="form-lable">按手机号：&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" class="form-control number" id="J_success_mobile">
                                </div>
                                <div class="date-box col-xs-4" id="J_success_pay">
                                    <span>按付款时间：</span>
                                    <div class="input-group col-xs-2">
                                        <input type="text " class="form-control date-picker J_search_timeStart" value="2017-03-15">
                                        <span class="input-group-btn ">
                                            <button class="btn btn-default date-icon J_timeStart_show" type="button "><i class="glyphicon glyphicon-calendar "></i></button>
                                        </span>
                                    </div>
                                    <span class=" ">到</span>
                                    <div class="input-group col-xs-2">
                                        <input type="text " class="form-control date-picker J_search_timeEnd" value="2017-03-15">
                                        <span class="input-group-btn ">
                                            <button class="btn btn-default date-icon J_timeEnd_show"> <i class="glyphicon glyphicon-calendar "></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-inline row">
                                <div class="date-box col-xs-4" id="J_success_auth">
                                    <span>按过审时间：</span>
                                    <div class="input-group col-xs-2">
                                        <input type="text " class="form-control date-picker J_search_timeStart" value="2017-03-15">
                                        <span class="input-group-btn ">
                                            <button class="btn btn-default date-icon J_timeStart_show" type="button "><i class="glyphicon glyphicon-calendar "></i></button>
                                        </span>
                                    </div>
                                    <span class=" ">到</span>
                                    <div class="input-group col-xs-2">
                                        <input type="text " class="form-control date-picker J_search_timeEnd" value="2017-03-15">
                                        <span class="input-group-btn ">
                                            <button class="btn btn-default date-icon J_timeEnd_show"> <i class="glyphicon glyphicon-calendar "></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="date-box col-xs-4" id="J_success_valid">
                                    <span>按生效时间：</span>
                                    <div class="input-group col-xs-2">
                                        <input type="text " class="form-control date-picker J_search_timeStart" value="2017-03-15">
                                        <span class="input-group-btn ">
                                            <button class="btn btn-default date-icon J_timeStart_show" type="button "><i class="glyphicon glyphicon-calendar "></i></button>
                                        </span>
                                    </div>
                                    <span class=" ">到</span>
                                    <div class="input-group col-xs-2">
                                        <input type="text " class="form-control date-picker J_search_timeEnd" value="2017-03-15">
                                        <span class="input-group-btn ">
                                            <button class="btn btn-default date-icon J_timeEnd_show"> <i class="glyphicon glyphicon-calendar "></i></button>
                                        </span>
                                    </div>
                                </div>
                                <a class="pull-right btn btn-business col-xs-1" id="J_success_btn">查询</a>
                            </div>
                        </div>
                        <div class="business-account-list">
                            <div class="header">
                                <span class="h3">门店列表</span>
                            </div>
                            <div class="list-content">
                                <div class="head clearfix row">
                                    <div class="col-xs-3 text-center">门店账号</div>
                                    <div class="col-xs-3 text-center">预留手机号</div>
                                    <div class="col-xs-2 text-center">付款时间</div>
                                    <div class="col-xs-2 text-center">过审时间</div>
                                    <div class="col-xs-2 text-center">生效时间</div>
                                </div>
                                <div class="content">
                                    <ul class="list-unstyled row" id="J_list_success"></ul>
                                </div>
                            </div>
                            <div class="footer text-right" id="J_success_page"></div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="authstr">
                        <div class="head clearfix">
                            <div class="form-inline">
                                <div class="form-group col-xs-3">
                                    <label class="form-lable">按门店账号：</label>
                                    <input type="text" class="form-control" placeholder="请输入用户ID" id="J_auth_account">
                                </div>
                                <div class="form-group col-xs-3">
                                    <label class="form-lable">按手机号：</label>
                                    <input type="text" class="form-control" placeholder="请输入手机号" id="J_auth_mobile">
                                </div>
                                <div class="col-xs-4 date-box" id="J_auth_pay">
                                	<span>付款时间：</span>
                                	<div class="input-group col-xs-2">
                                	    <input type="text " class="form-control date-picker J_search_timeStart" value="2017-03-15">
                                	    <span class="input-group-btn ">
                                	        <button class="btn btn-default date-icon J_timeStart_show" type="button "><i class="glyphicon glyphicon-calendar "></i></button>
                                	    </span>
                                	</div>
                                	<span class=" ">到</span>
                                	<div class="input-group col-xs-2">
                                	    <input type="text " class="form-control date-picker J_search_timeEnd" value="2017-03-15">
                                	    <span class="input-group-btn ">
                                	        <button class="btn btn-default date-icon J_timeEnd_show"> <i class="glyphicon glyphicon-calendar "></i></button>
                                	    </span>
                                	</div>
                                </div>
                                <a class="pull-right btn btn-business col-xs-1" id="J_auth_btn">查询</a>
                            </div>
                        </div>
                        <div class="business-account-list">
                            <div class="header">
                                <span class="h3">门店列表</span>
                            </div>
                            <div class="list-content">
                                <div class="head clearfix row">
                                    <div class="col-xs-4 text-center">门店账号</div>
                                    <div class="col-xs-4 text-center">预留手机号</div>
                                    <div class="col-xs-4 text-center">付款时间</div>
                                </div>
                                <div class="content">
                                    <ul class="list-unstyled row" id="J_list_auth"></ul>
                                </div>
                            </div>
                            <div class="footer text-right" id="J_auth_page"></div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="uncommitted">
                        <div class="head clearfix">
                            <div class="form-inline">
                                <div class="form-group col-xs-3">
                                    <label class="form-lable">按门店账号：</label>
                                    <input type="text" class="form-control" placeholder="请输入用户ID" id="J_valid_account">
                                </div>
                                <div class="form-group col-xs-3">
                                    <label class="form-lable">按手机号：</label>
                                    <input type="text" class="form-control" placeholder="请输入手机号" id="J_valid_mobile">
                                </div>
                                <div class="col-xs-4 date-box" id="J_valid_pay">
                                	<span>付款时间：</span>
                                	<div class="input-group col-xs-2">
                                	    <input type="text " class="form-control date-picker J_search_timeStart" value="2017-03-15">
                                	    <span class="input-group-btn ">
                                	        <button class="btn btn-default date-icon J_timeStart_show" type="button "><i class="glyphicon glyphicon-calendar "></i></button>
                                	    </span>
                                	</div>
                                	<span class=" ">到</span>
                                	<div class="input-group col-xs-2">
                                	    <input type="text " class="form-control date-picker J_search_timeEnd" value="2017-03-15">
                                	    <span class="input-group-btn ">
                                	        <button class="btn btn-default date-icon J_timeEnd_show"> <i class="glyphicon glyphicon-calendar "></i></button>
                                	    </span>
                                	</div>
                                </div>
                                <a class="pull-right btn btn-business col-xs-1" id="J_valid_btn">查询</a>
                            </div>
                        </div>
                        <div class="business-account-list">
                            <div class="header">
                                <span class="h3">门店列表</span>
                            </div>
                            <div class="list-content">
                                <div class="head clearfix row">
                                    <div class="col-xs-4 text-center">门店账号</div>
                                    <div class="col-xs-4 text-center">预留手机号</div>
                                    <div class="col-xs-4 text-center">付款时间</div>
                                </div>
                                <div class="content">
                                    <ul class="list-unstyled row" id="J_list_valid"></ul>
                                </div>
                            </div>
                            <div class="footer text-right" id="J_valid_page"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="J_tpl_list1">
	{@each data as it}
		<li>
            <div class="col-xs-4 text-center">${it.account}</div>
            <div class="col-xs-4 text-center">${it.mobile}</div>
            <div class="col-xs-4 text-center">${it.pay_datetime}</div>
        </li>
	{@/each}
</script>
<script type="text/template" id="J_tpl_list2">
	{@each data as it}
		<li>
            <div class="col-xs-3 text-center">${it.account}</div>
            <div class="col-xs-3 text-center">${it.mobile}</div>
            <div class="col-xs-2 text-center">${it.pay_datetime}</div>
            <div class="col-xs-2 text-center">${it.authorized_datetime}</div>
            <div class="col-xs-2 text-center">${it.account_valid_datetime}</div>
        </li>
	{@/each}
</script>
