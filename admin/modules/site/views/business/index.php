<?php
$this->params = ['js' => 'js/business.js', 'css' => 'css/business.css'];
?>

<div class="admin-main-wrap">
    <!--management main content-->
    <div class="admin-management-container admin-management-store">
        <div class="row">
            <!--省份-->
            <div class="col-xs-3">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-12">省</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split J_area" id="J_province_list" data-id="1">
                            <script type="text/template" id="J_tpl_list">
                                {@each _ as it}
                                    <li class="J_parent" data-id="${it.id}">
                                        <div class="col-xs-7 text-left J_name">${it.title}</div>
                                        <div class="col-xs-5">
                                            <a href="" class="btn btn-warning J_edit" data-toggle="modal" data-target="#apxModalAdminAlertEdit">修改</a>
                                            <a href="" class="btn btn-danger" data-toggle="modal" data-target="#apxModalAdminAlertDel">删除</a>
                                        </div>
                                    </li>
                                {@/each}
                            </script>
                        </ul>
                    </div>
                    <div class="footer text-center">
                        <button class="btn btn-danger btn-round J_add_province" data-toggle="modal" data-target="#apxModalAdminAlertEdit">添加省</button>
                    </div>
                </div>
            </div>
            <!--城市-->
            <div class="col-xs-3">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-12">城市</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split J_area" id="J_city_list"  data-id="2"></ul>
                    </div>
                    <div class="footer text-center">
                        <button class="btn btn-danger btn-round J_add_city hide" data-toggle="modal" data-target="#apxModalAdminAlertEdit">添加城市</button>
                    </div>
                </div>
            </div>
            <!--督导区-->
            <div class="col-xs-3">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-12">督导区</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split J_area" id="J_district_list"  data-id="3"></ul>
                    </div>
                    <div class="footer text-center">
                        <button class="btn btn-danger btn-round J_add_district hide" data-toggle="modal" data-target="#apxModalAdminAlertEdit">添加督导区</button>
                    </div>
                </div>
            </div>
            <!--组-->
            <div class="col-xs-3">
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-12">组</div>
                    </div>
                    <div class="iscroll_container with-header with-footer">
                        <ul class="list-unstyled dashed-split J_area" id="J_group_list"  data-id="4"></ul>
                    </div>
                    <div class="footer text-center">
                        <button class="btn btn-danger btn-round J_add_group hide" data-toggle="modal" data-target="#apxModalAdminAlertEdit">添加组</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-management-panel hide" id="J_detail_list">
            <div class="header">
                <div class="col-xs-3 J_top"></div>
                <div class="col-xs-3 J_second"></div>
                <div class="col-xs-3 J_third"></div>
                <div class="col-xs-3 J_fourth"></div>
            </div>
            <div class="with-header">
                <ul class="list-unstyled">
                    <li>&nbsp;</li>
                    <li>
                        <div class="col-xs-6"><strong class="J_leader"></strong></div>
                        <div class="col-xs-6"><button class="btn btn-lg btn-default J_edit_btn_one" data-toggle="modal" data-target="#apxModalManageStore">修改</button></div>
                    </li>
                    <li class="J_more_leader">&nbsp;</li>
                    <li>&nbsp;</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- 修改 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertEdit" tabindex="-1">
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
                        <label for="edit_val">修改为：</label>
                        <input type="text" class="form-control" id="edit_val">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-lg btn-danger" id="J_edit_sure">确认</a>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-admin-manage-store modal fade" id="apxModalManageStore" tabindex="-1">
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
                    <input type="text" class="form-control" id="J_search_input" placeholder="请输入人名查询">
                    <span class="input-group-btn">
                        <button class="btn" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></button>
                    </span>
                </div>
                <div class="admin-management-panel">
                    <div class="header">
                        <div class="col-xs-2 text-left">序号</div>
                        <div class="col-xs-2">姓名</div>
                        <div class="col-xs-4">手机号</div>
                        <div class="col-xs-4 text-left">备注</div>
                    </div>
                    <div class="iscroll_container with-header">
                        <ul class="list-unstyled dashed-split" id="J_employee_list">
                            <script type="text/template" id="J_tpl_employee">
                                {@each _ as it}
                                    <li class="J_employee" data-id="${it.id}">
                                        <div class="col-xs-2 text-left">${it.id}</div>
                                        <div class="col-xs-2">${it.name}</div>
                                        <div class="col-xs-4">${it.mobile}</div>
                                        <div class="col-xs-4 text-left textext-ellipsis">${it.remark}</div>
                                    </li>
                                {@/each}
                            </script>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-danger" id="J_replace_sure">确认</button>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminAlertDel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4 text-danger"><i class="glyphicon glyphicon-alert"></i>确定要删除 <span class="J_del_cont"></span> 吗？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_dele_sure">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>