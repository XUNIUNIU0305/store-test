<?php
$this->params = ['css' => 'css/custom-list.css', 'js' => 'js/custom-list.js'];
?>
<div class="business-main-wrap">
    <div class="business-stores">
        <div class="business-stores-top">
            <div class="form-group form-group-sm">
                <div class="col-xs-12">
                    <div class="form-inline pull-left">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="请输入账号ID" maxlength="9" id="J_search_input">
                            <span class="input-group-btn">
                                <a class="btn btn-search" id="J_search_account"><i class="glyphicon glyphicon-search"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 hidden">
                    <select class="selectpicker btn-group-xs" data-width="110%" data-dropup-auto="false">
                        <option value="-1">请选择绑定状态</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-xs-9 pull-left" id="J_select_box"></div>
                    <div class="col-xs-3">
                        <a class="btn btn-business" id="J_search_area">查 询</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="business-account-list">
            <div class="header">
                <span class="h3">门店列表</span>
            </div>
            <div class="list-content">
                <div class="head clearfix">
                    <div class="col-xs-2 text-center">账户ID</div>
                    <div class="col-xs-5 text-center">五级信息</div>
                    <div class="col-xs-2 text-center">组长</div>
                    <div class="col-xs-2 text-center">政委</div>
                </div>
                <div class="content">
                    <ul class="list-unstyled row" id="J_user_list">
                        <script type="text/template" id="J_tpl_list">
                            {@each list as it}
                                <li>
                                    <div class="col-xs-2 text-center"><a href="javascript:;" class="text-business account_detail" data-account="${it.account}">${it.account}</a></div>
                                    <div class="col-xs-5 text-center text-ellipsis" title="${it.area | linkArea}">${it.area | linkArea}</div>
                                    <div class="col-xs-2 text-center">${it.leader}</div>
                                    <div class="col-xs-2 text-center">${it.commissar}</div>
                                </li>
                            {@/each}
                        </script>
                    </ul>
                </div>
                <div class="footer" id="J_user_page"></div>
            </div>
        </div>
    </div>
</div>
