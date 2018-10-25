<?php
$this->params = ['js' => 'js/category.js', 'css' => 'css/category.css'];
?>
<div class="admin-main-wrap">
    <!-- 多列表选择 -->
    <div class="apx-admin-multi-list-wrap clearfix" id="J_menu_box">
        <div class="apx-admin-multi-list clearfix pull-left">
            <div class="col-xs-4">

                <div class="input-group input-group-sm">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    <input type="text" class="form-control" placeholder="输入名称/拼音首字母">
                </div>
                <ul class="list-unstyled">
                    <script type="text/template" id="J_tpl_list">
                        {@each _ as it}
                            <li data-id="${it.id}">
                                <a href="javascript:void(0)">
                                    ${it.title}
                                </a>
                                <div class="pull-right invisible">
                                    <a href="javascript:void(0)" class="btn btn-xs btn-warning J_edit_sort">修改</a>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger J_dele_sort">删除</a>
                                </div>
                            </li>
                        {@/each}
                    </script>
                </ul>
                <a href="#" data-toggle="modal" data-target="#modalAddType" class="btn btn-danger btn-block J_add_sort">添加新分类</a>
            </div>
            <div class="col-xs-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    <input type="text" class="form-control" placeholder="输入名称/拼音首字母">
                </div>
                <ul class="list-unstyled">
                </ul>
                <a href="#" data-toggle="modal" data-target="#modalAddType" class="btn btn-danger btn-block invisible J_add_sort">添加新分类</a>
            </div>
            <div class="col-xs-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    <input type="text" class="form-control" placeholder="输入名称/拼音首字母">
                </div>
                <ul class="list-unstyled J_list_end">
                </ul>
                <a href="#" data-toggle="modal" data-target="#modalAddType" class="btn btn-danger btn-block invisible J_add_sort">添加新分类</a>
            </div>
        </div>  
    </div>
    <!-- 新增查询修改 -->
    <div class="apx-admin-sort-search-input J_sort_keyword invisible">
        <p>分类关键字：</p>
        <div class="input_list">
            <div class="input_list_detail"></div>
            <div class="input_list_detail"></div>
            <div class="input_list_detail"></div>
            <div class="input_list_detail"></div>
            <div class="input_list_detail"></div>
            <div class="input_list_detail"></div>
        </div>
        <div class="btn-contain">
           
        </div>
        <!-- <span type="button" class="input-confirm-btn J_modify_btn" data-id="revise">修改</span> -->
        <!-- <span type="button" class="input-confirm-btn J_sure_btn" data-id="revise" data-toggle="modal" data-target="#apxModalAdminAlert">确定</span>  -->
    </div>
<!-- 添加提示信息 -->
    <div class="apx-modal-admin-alert modal fade admin-management add-confirm" id="apxModalAdminAlert" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <a type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></a>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body">
                    <div class="h4" style="padding: 36px 0;">请确认修改？</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-danger" data-dismiss='modal' id="add_conf">确认</button>
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal" id="add_cancel">取消</button>
                </div>
            </div>
        </div>
    </div>
<!-- 新增结束 -->
    <div class="apx-admin-multi-list-detail J_attr_big invisible">
        <div class="title clearfix text-center">
            <div class="col-xs-4"></div>
            <div class="col-xs-4"></div>
            <div class="col-xs-4"></div>
        </div>
        <div id="J_attrs_box">
            <script type="text/template" id="J_tpl_attr">
                {@each _ as it,index}
                    <dl>
                        <dt class="pull-left btn btn-danger" data-toggle="modal" data-target="#modalAddAttr" data-id="${it.id}"><span>${it.name}</span>：</dt>
                        <dd class="high-lighted">
                            <divs>
                                {@each it.options as x}
                                    <div class="btn-group active J_dele_one_attr">
                                        <label class="btn btn-default" data-id="${x.id}">
                                            ${x.name}
                                        </label>
                                        <span class="btn btn-danger invisible"><i class="glyphicon glyphicon-remove"></i></span>
                                    </div>
                                {@/each}
                            </div>
                    </dl>
                {@/each}
            </script>
        </div>
        <div class="panel-body text-center">
            <button data-toggle="modal" data-target="#modalAddAttr" class="btn btn-default newflag">+添加新的类别规范</button>
        </div>
    </div>
</div>

<!-- 添加新类别 -->
<div class="apx-modal-admin-add-type modal fade" id="modalAddType" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">添加新类别</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="p_type">请输入商品类别：</label>
                        <input type="text" class="form-control" id="p_type">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-danger J_add_end">确认提交</button>
            </div>
        </div>
    </div>
</div>
<!-- 添加新的类型规范 -->
<div class="apx-modal-admin-add-attr-detail modal fade" id="modalAddAttr" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">新类别规范</h4>
            </div>
            <div class="modal-body">
                <div class="form-group-attr-box clearfix J_modal_admin_add_attr">
                    <label for="attr-name" class="col-xs-2 text-right control-label">属性名称：</label>
                    <div class="form-group col-xs-4 form-group-sm">
                        <input type="text" id="attr-name" maxlength="7" class="form-control">
                    </div>
                    <label class="text-right col-xs-2 control-label">选项数量：</label>
                    <div class="input-group col-xs-4 input-group-sm detail-promo-ammount">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-minus"></i></div>
                        <input class="form-control" value="12" min="1" maxlength="2" title="请输入数量" type="text">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-plus"></i></div>
                    </div>
                </div>
                <div class="attr-detail-box row clearfix">
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-xs-4">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-offset-2 col-xs-8">
                    <a = class="btn btn btn-danger J_attr_end">确认递交</a>
                </div>
            </div>
        </div>
    </div>
</div>

