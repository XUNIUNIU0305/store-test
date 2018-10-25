<?php
$this->params = ['css' => 'css/technican.css', 'js' => ['js/technican.js']];
?>
<!-- main area start -->
<div class="business-frame-main">
    <div class="business-main-wrap">
        <!-- 技师列表 -->
        <div class="business-technicain">
            <div class="business-technicain-head">
                <div class="clearfix">
                    <div class="col-xs-2">
                        <select class="selectpicker select-area btn-group-xs" id="J_top" data-width="100%">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <select class="selectpicker select-area btn-group-xs" id="J_second" data-width="100%">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <select class="selectpicker select-area btn-group-xs " id="J_third" data-width="100%">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <select class="selectpicker select-area btn-group-xs " id="J_four" data-width="100%">
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-lg btn-round J_btn_search">搜 索</button>
                        <button class="btn btn-lg btn-round J_btn_add_employee">添加员工</button>
                    </div>
                </div>
            </div>
            <div class="business-management-panel">
                <div class="header">
                    <div class="col-xs-2 text-center">姓名</div>
                    <div class="col-xs-2 text-center">手机号</div>
                    <div class="col-xs-4 text-center">备注</div>
                    <div class="col-xs-4 text-center">操作</div>
                </div>
                <div class="iscroll_container with-header with-footer">
                    <ul class="list-unstyled dashed-split J_user_list">
                        <script type="text/template" id="J_tpl_list">
                            {@each codes as it}
                            <!--single li-->
                        <li >
                            <div class="row ">
                                <div class="col-xs-2 text-center ">${it.name}</div>
                                <div class="col-xs-2 text-center">${it.mobile}</div>
                                <div class="col-xs-4 text-center text-ellipsis">${it.remark}</div>
                                <div class="col-xs-4 text-center btn-box">
                                    <a href="" class="btn btn-edit J_btn_modify" data-id="${it.id}" data-area="${it.area_id}">修改</a>
                                    <a href="" class="btn btn-del J_btn_remove" data-id="${it.id}">删除</a>
                                </div>
                            </div>
                        </li>
                            {@/each}
                        </script>




                    </ul>
                </div>
                <div class="footer J_user_page">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- 删除提示信息 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessDel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;"><i class="glyphicon glyphicon-alert"></i>删除不可恢复，确定要删除吗？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business J_btn_del_confirm" >确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<!-- 修改 -->
<div class="apx-modal-business-manage-staff modal fade" id="apxModalManageEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">修改员工信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-1" for="employee_name">姓名：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="employee_name" maxlength="12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-1" for="employee_mobile">手机：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" maxlength="11" id="employee_mobile">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-1" for="employee_remarks">备注：</label>
                        <div class="col-xs-11">
                            <textarea class="form-control" id="employee_remarks" rows="8" placeholder="备注内容"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-business J_edit_sure" >确认</button>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>