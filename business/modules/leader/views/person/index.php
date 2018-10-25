<?php
$this->params = ['css' => 'css/person.css', 'js' => 'js/person.js'];
?>
<div class="business-main-wrap">
    <div class="business-salseman">
        <div class="business-salesman-head">
            <div class="clearfix">
                <div class="form-inline pull-left">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="请输入" id="J_search_input">
                        <span class="input-group-btn">
                            <a class="btn" id="J_search_btn"><i class="glyphicon glyphicon-search"></i></a>
                        </span>
                    </div>
                    <!-- /input-group -->
                </div>
                <div class="pull-right">
                    <button class="btn btn-lg btn-round" data-toggle="modal" data-target="#apxModalBusinessDel" data-btn="batch">批量删除</button>
                    <button class="btn btn-lg btn-round" data-toggle="modal" data-target="#apxModalManageAddPerson">添加员工</button>
                </div>
            </div>
        </div>
        <div class="business-management-panel">
            <div class="header">
                <div class="col-xs-1 text-center"></div>
                <div class="col-xs-2 text-center">账户ID</div>
                <div class="col-xs-1 text-center">姓名</div>
                <div class="col-xs-2 text-center">手机号</div>
                <div class="col-xs-2 text-center">职位</div>
                <div class="col-xs-4 text-center">操作</div>
            </div>
            <div class="iscroll_container with-header with-footer">
                <ul class="list-unstyled dashed-split" id="J_account_list">
                    <script type="text/template" id="J_tpl_list">
                        {@each data as it}
                            <li class="J_account_box" data-id="${it.id}" data-name="${it.name}">
                                <div class="row">
                                    <div class="col-xs-1 text-center">
                                        <input type="checkbox" name="account">
                                    </div>
                                    <div class="col-xs-2 text-center">${it.account}</div>
                                    <div class="col-xs-1 text-center">${it.name}</div>
                                    <div class="col-xs-2 text-center">${it.mobile}</div>
                                    <div class="col-xs-2 text-center">${it.role}</div>
                                    <div class="col-xs-4 text-center btn-box">
                                        <a role="button" data-toggle="collapse" href="#collapse${it.id}" class="btn btn-success" data-id='111'>详情</a>
                                        <a href="#" class="btn btn-edit" data-toggle="modal" data-target="#apxModalManageEdit">修改</a>
                                        <a href="#" data-toggle="modal" data-target="#apxModalBusinessDel" class="btn btn-del">删除</a>
                                        <a href="#" class="btn btn-black" data-toggle="modal" data-target="#apxModalBusinessReset">解除职务</a>
                                    </div>
                                </div>
                                <div class="collapse collapse-info collapse-flag" id="collapse${it.id}" data-status="true">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <h3 class="title">职位</h3>
                                            <p class="h5">身份：<span class="account_role h5">组长</span></p>
                                            <p class="h5">所属区域：<span class="account_area h5"></span></p>
                                        </div>
                                        <div class="col-xs-4">
                                            <h3 class="title">业绩</h3>
                                            <p class="h5">昨日业绩：<span class="high-lighted h5 yesterday_achievement"></span></p>
                                            <p class="h5">个人累计业绩：<span class="high-lighted h5 life_achievement"></span></p>
                                        </div>
                                        <div class="col-xs-4">
                                            <h3 class="title">备注</h3>
                                            <p class="account_remark"></p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        {@/each}
                    </script>
                </ul>
                <div class="iScrollVerticalScrollbar iScrollLoneScrollbar">
                    <div class="iScrollIndicator"></div>
                </div>
            </div>
            <div class="footer" id="J_account_page"></div>
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
                <span>姓名：<span class="user_name"></span></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_sure_del">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 解除角色提示信息 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessReset" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">确定要解除该用户角色吗？</div>
                <span>姓名：<span class="user_name"></span></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_sure_reset">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 导出提示信息 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessExport" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="checkbox">
                    <label>
                        <input type="radio"> 导出已选项
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="radio"> 导出所有
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business">确认</button>
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
                        <label class="col-xs-2" for="J_edit_name">姓名：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="J_edit_name">
                        </div>
                    </div>
                    <div class="form-group hidden">
                        <label class="col-xs-2" for="J_new_password">新密码：</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" maxlength="11" id="J_new_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-2" for="J_edit_remark">备注：</label>
                        <div class="col-xs-10">
                            <textarea class="form-control" id="J_edit_remark" rows="8" maxlength="255" placeholder="备注内容"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-business" id="J_edit_sure">确认</button>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 添加员工 -->
<div class="apx-modal-business-manage-staff modal fade" id="apxModalManageAddPerson" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">添加员工</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-1" for="J_new_name">姓名：</label>
                        <div class="col-xs-6">
                            <input type="text" maxlength="30" class="form-control" id="J_new_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-1" for="J_new_remark">备注：</label>
                        <div class="col-xs-11">
                            <textarea class="form-control" id="J_new_remark" rows="8" maxlength="255" placeholder="备注内容"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-business" id="J_add_new_person">确认</button>
                <button type="button" class="btn btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>