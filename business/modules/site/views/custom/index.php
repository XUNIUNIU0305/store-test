<?php
$this->params = ['css' => 'css/custom.css', 'js' => 'js/custom.js'];
?>
<div class="business-main-wrap">
    <div class="business-build">
        <div class="business-build-top">
            <div class="form-group form-group-sm J_operator">
                <div class="row">
                    <div class="col-xs-7" id="J_select_box"></div>
                    <div class="input-group input-group-sm col-xs-2 pull-left">
                        <div class="input-group-addon J_input_minus"><i class="glyphicon glyphicon-minus"></i></div>
                        <input class="form-control J_only_int" id="J_user_count" value="1" maxlength="3" title="请输入数量最大为10" type="text">
                        <div class="input-group-addon J_input_add"><i class="glyphicon glyphicon-plus"></i></div>
                    </div>
                    <div class="col-xs-2 pull-right add-account"><a href="" class="btn btn-business" data-toggle="modal" data-target="#apxModalBusinessAdd">添加账号</a></div>
                </div>
            </div>
        </div>
        <div class="business-account-list">
            <div class="header">
                <span class="h3">注册码列表</span>
            </div>
            <div class="list-content">
                <div class="head clearfix">
                    <div class="col-xs-1 text-center">
                        <input type="checkbox" name=""> 全选
                    </div>
                    <div class="col-xs-2 text-center">注册码</div>
                    <div class="col-xs-3 text-center">五级信息</div>
                    <div class="col-xs-2 text-center">激活状态
                        <select class="account-status" id="J_user_status">
                            <option value="-1">all</option>
                            <option value="0">未激活</option>
                            <option value="1">已激活</option>
                        </select>
                    </div>
                    <div class="col-xs-2 text-center">生成时间</div>
                    <div class="col-xs-2 text-center">激活时间</div>
                </div>
                <div class="content">
                    <ul class="list-unstyled row" id="J_user_list">
                        <script type="text/template" id="J_tpl_list">
                            {@each list as it}
                                <li>
                                    <div class="col-xs-1 text-center">
                                        <input type="checkbox" name="user">
                                    </div>
                                    <div class="col-xs-2 text-center">${it.account}</div>
                                    <div class="col-xs-3 text-center text-ellipsis" title="${it.area | linkArea}">${it.area | linkArea}</div>
                                    <div class="col-xs-2 text-center ${it.used|userStatus}">${it.used|userStatusText}</div>
                                    <div class="col-xs-2 text-center">${it.create_time}</div>
                                    <div class="col-xs-2 text-center">${it.register_time}</div>
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
<!-- 添加账号弹窗 -->
<div class="apx-modal-business-alert modal fade business-management" id="apxModalBusinessAdd" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <div class="h4" style="padding: 20px 0;">确定要生成新账号吗？</div>
                <span>数量：<span class="user_count"></span></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-business" id="J_sure_add">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
