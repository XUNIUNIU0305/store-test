<?php
$this->params = ['css' => 'css/area.css', 'js' => 'js/area.js'];
?>

<div class="business-main-wrap">
    <div class="row business-area">
        <div class="col-xs-6">
            <div class="business-manage-area">
                <div class="header">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" id="J_tab_list"></ul>
                    <!-- Tab panes -->
                    <div class="tab-content" id="J_tab_content"></div>
                </div>
                <div class="footer" id="J_tab_footer">
                    <p class="col-xs-9"><span></span><span></span><span></span><span></span></p>
                    <div class="col-xs-2 add-icon pull-right" data-toggle="modal" data-target="#apxModalBusinessAdd"><a href="#"><i class="glyphicon glyphicon-plus-sign"></i></a></div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="detail-msg">
                <div class="content-layout">
                    <div class="iscorll_container">
                        <div class="head-title">
                            相关人员
                        </div>
                        <div class="leader" id="J_leader_box"></div>
                        <div class="performance">
                            <div class="performance-title">业绩</div>
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
                                            <th>销售金额</th>
                                        </tr>
                                    </thead>
                                    <tbody id="J_user_data"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="iScrollVerticalScrollbar iScrollLoneScrollbar">
                            <div class="iScrollIndicator"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 列表模板 -->
<script type="text/template" id="J_tpl_area_list">
    {@each list as it}
        <li class="J_area_box" data-haschild="${has_child}" data-id="${it.id}" data-name="${it.name}">
            <div class="col-xs-8 text-left">${it.name}</div>
            <div class="col-xs-4">
                <a class="btn btn-edit" data-toggle="modal" data-target="#apxModalBusinessEdit" data-id="${it.id}" data-name="${it.name}">修改</a> &nbsp;
                {#<a class="btn btn-del">删除</a>}
            </div>
        </li>
    {@/each}
</script>
<!-- 删除提示 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessAdd" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <label for="J_new_area">新增：<span class="area_name"></span></label>
                        <input type="text" class="form-control" maxlength="20" id="J_new_area">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_add_btn">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 修改负责人 -->
<div class="apx-modal-business-manage-store modal fade" id="apxModalManageStore" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">修改负责人</h4>
            </div>
            <div class="modal-body">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="J_search_input" placeholder="">
                    <span class="input-group-btn">
                        <button class="btn" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></button>
                    </span>
                </div>
                <div class="business-management-panel">
                    <div class="header">
                        <div class="col-xs-2 text-left">账号</div>
                        <div class="col-xs-2">姓名</div>
                        <div class="col-xs-4">手机号</div>
                        <div class="col-xs-4 text-left">角色</div>
                    </div>
                    <div class="iscroll_container with-header">
                        <ul class="list-unstyled dashed-split" id="J_account_list">
                            <script type="text/template" id="J_tpl_account">
                                {@each _ as it}
                                    <li class="J_account" data-id="${it.id}">
                                        <div class="col-xs-2 text-left">${it.account}</div>
                                        <div class="col-xs-2">${it.name}</div>
                                        <div class="col-xs-4">${it.mobile}</div>
                                        <div class="col-xs-4 text-left textext-ellipsis">${it.role}</div>
                                    </li>
                                {@/each}
                            </script>
                        </ul>
                        <div class="iScrollVerticalScrollbar iScrollLoneScrollbar">
                            <div class="iScrollIndicator"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-business" id="J_replace_sure">确认</button>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 修改区域名称 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessEdit" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">修改</h4>
            </div>
            <div class="modal-body management-authen">
                <div class="form">
                    <div class="form-group form-group-sm">
                        <label for="J_new_area_name">名称：<span class="area_name"></span></label>
                        <input type="text" class="form-control" maxlength="20" id="J_new_area_name">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_edit_btn">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
