<?php
$this->params = ['js' => 'js/brandindex.js', 'css' => 'css/brandindex.css'];
?>

<div class="admin-main-wrap">
        <!-- 首页管理tabs -->
        <div class="admin-edit-brands">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#edit_carousels" data-toggle="tab">主广告</a>
                </li>
                <li>
                    <a href="#edit_tops" data-toggle="tab">热销品牌</a>
                </li>
                <li>
                    <a href="#edit_specials" data-toggle="tab">品牌特辑</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!--主广告 start-->
                <div role="tabpanel" class="admin-edit-brands-carousels tab-pane fade in active" id="edit_carousels">
                    <!--按钮-->
                    <div class="btn btn-danger" id="J_add_carousel"><i class="glyphicon glyphicon-plus-sign"></i>添加广告轮播</div>
                    <!--标题-->
                    <div class="h4"><i class="glyphicon glyphicon-th-large"></i>轮播列表</div>
                    <!--轮播列表-->
                    <div class="row-group">
                        <!--轮播列表头-->
                        <div class="row header">
                            <div class="col-xs-3">序号</div>
                            <div class="col-xs-3">图片（1190*380，可见区1190px）</div>
                            <div class="col-xs-3">链接</div>
                            <div class="col-xs-3">操作</div>
                        </div>
                        <div id="J_carousel_box">
                            <!--普通-->
                             <script type="text/templates" id="J_tpl_list">
                             {@each _ as it}
                                 <div class="row list J_carousel" data-id="${it.id}">
                                     <div class="col-xs-3 J_carousel_no">${it.sort}</div>
                                          <div class="col-xs-3">
                                               <div class="img-upload-box J_carousel_img">
                                                    <img class="img-responsive" src="${it.path}" data-filename="${it.file_name}">
                                               </div>
                                          </div>
                                          <div class="col-xs-3 J_carousel_url">${it.url}</div>
                                          <div class="col-xs-3 J_carousel_btn">
                                                 <a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-warning btn-sm" data-no="brand_delarr">删除</a>
                                                 <a href="#" class="btn btn-danger btn-sm J_edit_btn">修改</a>
                                           </div>
                                 </div>
                           {@/each}
                          </script>
                          </div>
                    </div>
                </div>
                <!--主广告 end-->

                <!--热销品牌 start-->
                <div role="tabpanel" class="admin-edit-brands-tops tab-pane fade" id="edit_tops">
                    <div class="row" id="J_Selling_box">
                         <script type="text/templates" id="J_Selling_list">
                           {@each _ as it}
                            {@if it.id==1}
                                {@if it.file_name!=""&&it.file_name!=""&&it.path!=""}
                                     <div class="col-xs-5 J_Selling" data-id="${it.id}">
                                        <div class="admin-edit-brands-panel">
                                             <div class="header"><strong>小广告</strong></div>
                                                  <div class="content with-header">
                                                       <div class="media">
                                                              <div class="media-left media-middle" id="J_Selling_img_min">
                                                                    <img src="${it.path}" data-filename="${it.file_name}" class="media-object">
                                                              </div>
                                                              <div class="media-body media-middle" id="J_Selling_input_min">
                                                                    <strong>图片大小：</strong> 435*284px
                                                                     <div>链接：</div>
                                                                     <p> ${it.url}</p>
                                                                     <button class="btn btn-danger btn-block J_Selling_but">修改</button>
                                                               </div>
                                                        </div>
                                                    </div>
                                              </div>
                                         </div>
                                     </div>
                                {@else}
                                    <div class="col-xs-5 J_Selling" data-id="${it.id}">
                                        <div class="admin-edit-brands-panel">
                                             <div class="header"><strong>小广告</strong></div>
                                                  <div class="content with-header">
                                                       <div class="media">
                                                              <div class="media-left media-middle" id="J_Selling_img_min">
                                                                   <label class="img-upload-box media-object" for="upload_img_1">
                                                                         <input type="file" id="upload_img_1">
                                                                         <img src="" >
                                                                   </label>
                                                              </div>
                                                              <div class="media-body media-middle" id="J_Selling_input_min">
                                                                    <strong>图片大小：</strong> 435*284px
                                                                     <div>链接：</div>
                                                                     <input type="text" class="form-control">
                                                                     <button class="btn btn-danger btn-block J_sure_btn">确认</button>
                                                               </div>
                                                        </div>
                                                    </div>
                                              </div>
                                         </div>
                                     </div>
                                {@/if}
                            {@else}
                                {@if it.file_name!=""&&it.file_name!=""&&it.path!=""}
                                    <div class="col-xs-7 J_Selling" data-id="${it.id}">
                                        <div class="admin-edit-brands-panel">
                                            <div class="header"><strong>长广告</strong></div>
                                            <div class="content with-header">
                                                <div class="media">
                                                    <div class="media-left media-middle" id="J_Selling_img_max">
                                                         <img src="${it.path}" data-filename="${it.file_name}" class="media-object">
                                                    </div>
                                                    <div class="media-body media-middle" id="J_Selling_input_max">
                                                        <strong>图片大小：</strong> 1170*140px
                                                        <div>链接：</div>
                                                        <p>${it.url}</p>
                                                        <button class="btn btn-danger btn-block J_Selling_but">修改</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {@else}
                                    <div class="col-xs-7 J_Selling" data-id="${it.id}">
                                        <div class="admin-edit-brands-panel">
                                            <div class="header"><strong>长广告</strong></div>
                                            <div class="content with-header">
                                                <div class="media">
                                                    <div class="media-left media-middle" id="J_Selling_img_max">
                                                        <label class="img-upload-box media-object" for="upload_img_2">
                                                            <input type="file" id="upload_img_2">
                                                            <img src="">
                                                        </label>
                                                    </div>
                                                    <div class="media-body media-middle" id="J_Selling_input_max">
                                                        <strong>图片大小：</strong> 1170*140px
                                                        <div>链接：</div>
                                                        <input type="text" class="form-control">
                                                        <button class="btn btn-danger btn-block J_sure_btn">确认</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {@/if}
                            {@/if}
                        {@/each}
                        </script>
                    </div>
                    <div class="admin-edit-brands-panel">
                        <div class="header"><strong>品牌 </strong><small class="high-lighted"> （图片尺寸：137*137px）</small></div>
                        <div class="content with-header with-footer iscroll_container">
                            <div id="J_brands_boxImg">
                            <ul class="list-unstyled">
                             <script type="text/templates" id="J_brands_list">
                                   {@each _ as it}
                                    <li>
                                        <div class="brand-item" data-edit="" data-id='${it.id}' data-status="${it.status}">
                                            <label class="switch-btn" id="switch-checkbox">
                                                {@if it.status==1}
                                                <input type="checkbox" checked></input>
                                                {@else}
                                                <input type="checkbox"></input>
                                                {@/if}
                                                <div><div></div></div>
                                        </label>
                                        <button class="cancel-btn"><a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="cancel-btn" data-no="brand_delete">删除</a></button>
                                            <img src="${it.path}" data-filename="${it.file_name}" class="img-upload-box media-object">
                                        <div class="form-group form-group-sm J_brands_no">
                                            <label for="" class="pull-left">序号：</label>
                                            <div>
                                               <p class="form-control-static">${it.sort}</p>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm J_brands_url">
                                            <label for="" class="pull-left">链接：</label>
                                            <div>
                                                <p class="form-control-static">${it.url}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                {@/each}
                             </script>
                            </ul>
                            </div>
                        </div>
                        <div class="footer clearfix">
                            <div class="col-xs-6 operation">
                                <button class="btn btn-sm btn-danger J_add_btn" data-add="add">添加</button>
                                <button class="btn btn-sm btn-danger J_update_btn" data-update="update">修改</button>
                                <a href="#" data-toggle="modal"  class="btn-danger btn btn-sm btn-danger J_delete_btn" data-delete="delete" >删除</a>
                            </div>
                            <div class="text-right col-xs-6" id="J_brand_page">

                            </div>
                        </div>
                    </div>
                 </div>
                <!--热销品牌 end-->

                <!--品牌特辑 start-->
                <div role="tabpanel" class="admin-edit-brands-specials tab-pane fade" id="edit_specials">
                    <!--按钮-->
                    <div class="btn btn-danger" id="J_add_brand"><i class="glyphicon glyphicon-plus-sign"></i> 添加品牌</div>
                    <div class="row J_brand_box" id="J_brand_box">
                      <script type="text/templates" id="J_brand_list">
                        {@each _ as it}
                        <div class="col-xs-6 J_brand" data-id='${it.id}'>
                            <div class="admin-edit-brands-panel">
                                <div class="content with-footer">
                                    <div class="row">
                                        <div class="col-xs-7" id="J_brand_img_bg">
                                            <img class="img-upload-box" src="${it.background_path}" data-filename="${it.background}">
                                            <small class="high-lighted">背景图（576*330px）</small>
                                        </div>
                                        <div class="col-xs-5" id="J_brand_img_logo">
                                            <img  class="img-upload-box" src="${it.logo_path}" data-filename="${it.logo}">
                                            <small class="high-lighted">LOGO（140*60px）</small>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="" class="pull-left">序号：</label>
                                        <div id="J_brand_no">
                                            <p class="form-control-static">${it.sort}</p>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="" class="pull-left">链接：</label>
                                        <div id="J_brand_url">
                                            <p class="form-control-static">${it.url}</p>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="" class="pull-left">标题：</label>
                                        <div  id="J_brand_title">
                                            <p class="form-control-static">${it.title}</p>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="" class="pull-left">说明：</label>
                                        <div id="J_brand_explain">
                                            <p class="form-control-static">${it.introduction}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <div class="text-right operation" id="J_brand_but">
                                        <a href="#" class="btn btn-warning J_brand_but">修改</a>
                                        <a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-danger J_brand" data-no="brand">删除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                         {@/each}
                        </script>
                    </div>
                </div>
                <!--品牌特辑 end-->
            </div>
        </div>
    </div>
    <!-- 删除警示 -->
    <div class="apx-modal-admin-alert modal fade" id="apxModalAdminAdvertising" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <a type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></a>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body">
                    <i class="glyphicon glyphicon-warning-sign"></i> 确定要删除吗？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-danger" id="J_common_sure">确认</button>
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

