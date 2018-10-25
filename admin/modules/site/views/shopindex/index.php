<?php
$this->params = ['js' => 'js/shopindex.js', 'css' => 'css/shopindex.css'];
?>
<!-- main area start -->
   <div class="admin-main-wrap">
        <div class="admin-edit-shop">
            <ul class="nav nav-tabs">
                <li><strong>店铺页图片管理</strong></li>
                <li class="pull-right">
                    <label>店铺名称：</label>
                    <select class="selectpicker J_shop_list_select" data-width="" data-live-search="true" id="J_supply">

                    </select>
                </li>
            </ul>
            <div class="edit-shop-content">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">电脑端</a></li>
                    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">手机端</a></li>
                </ul>
            </div>
            <div class="tab-content hidden" id="shop-edit-content">
                <div role="tabpanel" class="tab-pane " id="home">
                    <!-- Tab panes -->
                    <div class="apx-shop-upload-box row" id="J_shop_list">
                        <div class="col-xs-12" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="0">
                            <img src="" data-filename="" data-url=""/>
                        </div>
                        <div class="col-xs-6" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="1">
                            <img src="" data-filename="" data-url=""/>
                        </div>
                        <div class="col-xs-6" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="1">
                            <img src="" data-filename="" data-url=""/>
                        </div>
                        <div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2">
                            <img src="" data-filename="" data-url=""/>
                        </div>
                        <div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2">
                            <img src="" data-filename="" data-url=""/>
                        </div>
                        <div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2">
                            <img src="" data-filename="" data-url=""/>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane active" id="profile">
                    <div class="mobile-store-content">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#storeBg" role="tab" data-toggle="tab">店铺主图</a></li>
                            <li role="presentation"><a href="#bannerImg" role="tab" data-toggle="tab">轮播图</a></li>
                            <li role="presentation"><a href="#pickPro" role="tab" data-toggle="tab">甄选商品</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- 店铺主图部分 -->
                            <div role="tabpanel" class="tab-pane active" id="storeBg">
                                <div class="main-img-upload">
                                    <img id="J_main_img" src="/images/admin/shop/upload.png" alt="">
                                    <span class="btn btn-upload">点击上传</span>
                                    <input type="file" name="" id="upload-main-file">
                                </div>
                                <p class="annotation high-lighted">*注：图片尺寸为750*600像素，大小不得大于2M，支持格式为JPG、PNG。</p>
                            </div>
                            <!-- 轮播图部分 -->
                            <div role="tabpanel" class="tab-pane" id="bannerImg">
                                <button class="button primary" id="J_add_newbanner_btn">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    <span>添加轮播</span>
                                </button>
                                <div class="row-group banner-img-box">
                                    <!--轮播列表头-->
                                    <div class="row header">
                                        <div class="col-xs-3">序号</div>
                                        <div class="col-xs-3">图片（750*600）</div>
                                        <div class="col-xs-3">链接</div>
                                        <div class="col-xs-3">操作</div>
                                    </div>
                                    <div class="banner-list-container" id="J_banner_list">
                                        <script type="text/template" id="J_tpl_banner">
                                            {@each _ as it, index}
                                                <div class="row list banner-item" data-index="${index}" data-id="${it.id}">
                                                    <div class="col-xs-3">${it.sort}</div>
                                                    <div class="col-xs-3">
                                                        <div class="img-upload-box">
                                                            <img class="img-responsive" data-filename="${it.file_name}" src="${it.image_path}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">${it.image_url}</div>
                                                    <div class="col-xs-3">
                                                        <a href="#" class="btn btn-warning btn-sm J_del_banner_btn">删除</a>
                                                        <a href="#" class="btn btn-danger btn-sm J_edit_banner_btn">修改</a>
                                                    </div>
                                                </div>
                                            {@/each}
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <!-- 甄选商品部分 -->
                            <div role="tabpanel" class="tab-pane" id="pickPro">
                                <div class="selection-pro-container">
                                    <div class="pro-tabs">
                                        <div class="pro-list J_selected_pro_list">
                                            
                                        </div>
                                        <div class="pro-handle">
                                            <span data-toggle="modal" data-target="#J_add_newpro_modal">新增</span>
                                        </div>
                                    </div>
                                    <div class="pro-content" id="J_selected_pro_container">
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
      </div>
<!-- main area start -->

<script type="text/template" id="J_tpl_selected_pro">
    {@each _ as it, index}
        <div class="selected-items ${index == 0 ? '': 'hidden'}" data-id="${it.id}">
            <ul>
                <li>
                    <div class="p-title">商品ID：</div>
                    <div class="p-val">${it.product_id}</div>
                </li>
                <li>
                    <div class="p-title">商品名称：</div>
                    <div class="p-val">${it.title}</div>
                </li>
                <li>
                    <div class="p-title">价格区间：</div>
                    <div class="p-val high-lighted">￥${it.min_price}~￥${it.max_price}</div>
                </li>
                <li>
                    <div class="p-title">商品卖点：</div>
                    <div class="p-val">${it.description}</div>
                </li>
                <li>
                    <div class="p-title">供应商：</div>
                    <div class="p-val">${it.supply_name}</div>
                </li>
                <li>
                    <div class="p-title">展示用图片：</div>
                    <div class="p-val upload-box">
                        <img src="${it.image_path?it.image_path:'/images/admin/shop/upload.png'}" data-filename="${it.file_name}" alt="" class="J_show_img">
                        <input type="file" name="" class="J_show_img_input" id="">
                        <div class="high-lighted">*图片像素为750*350</div>
                    </div>
                </li>
                <li>
                    <div class="p-title">展示标题：</div>
                    <div class="p-val title-input-box">
                        <input type="text" class="J_show_title" maxlength="10" name="" id="" value="${it.show_title}">
                    </div>
                </li>
                <li>
                    <div class="p-title">展示卖点：</div>
                    <div class="p-val title-textarea-box">
                        <textarea class="J_show_description" maxlength="15">${it.show_message}</textarea>
                    </div>
                </li>
            </ul>
            <div class="pro-sure-handle">
                <span class="btn btn-danger J_sure_create_selected_item_btn" data-id="${it.id}">保存</span>
                <span class="btn btn-default J_sure_del_selected_item_btn" data-id="${it.id}">删除</span>
            </div>
        </div>
    {@/each}
</script>

<!-- 图片相关信息 -->
<div class="apx-modal-admin-brand-img modal fade" id="apxModalAdminShopImgs" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">图片相关信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <!--图片-->
                    <div class="form-group">
                        <label class="col-xs-3 control-label">图片：</label>
                        <div class="col-xs-3" id = "shop_img">
                            <label class="upload-box" for="brand_img">
                                <input id="brand_img" type="file">
                                <img src="">
                            </label>
                        </div>
                        <div class="col-xs-6 text-danger">（图片尺寸要求：478*287）</div>
                    </div>
                    <!--链接-->
                    <div class="form-group">
                        <label for="brand_url" class="col-xs-3 control-label">对应链接：</label>
                        <div class="col-xs-7" id="shop_inp">
                            <input type="text" class="form-control" id="brand_url">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-block">提交</button>
            </div>
        </div>
    </div>
</div>
<!-- 选择商品 -->
<div class="apx-modal-admin-select-pro modal fade" id="J_add_newpro_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">添加商品</h4>
            </div>
            <div class="modal-body">
                <div class="input-box">
                    <input value="" id="J_query_pro_input" autocomplete="off" type="text" placeholder="请输入商品名称或ID" maxlength="140" ></input>
                    <span id="J_query_pro_btn"></span>
                </div>
                <div class="select-pro-list">
                    <table>
                        <thead>
                            <tr>
                                <th width="40"></th>
                                <th>图片</th>
                                <th>商品ID</th>
                                <th width="150">商品名称</th>
                                <th>价格</th>
                                <th>所属供应商</th>
                            </tr>
                        </thead>
                        <tbody id="J_all_pro_box">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="J_select_pro_btn">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="J_tpl_allpro">
    {@each _ as it, index}
        <tr data-id="${it.id}">
            <td class="checkbox" data-index="${index}">
                <span></span>
            </td>
            <td>
                <div class="img">
                    <img src="${it.image_path}" alt="">
                </div>
            </td>
            <td>${it.id}</td>
            <td>
                <div class="pro-title">${it.title}</div>
            </td>
            <td>￥${it.price.min}~￥${it.price.max}</td>
            <td>${it.supplier}</td>
        </tr>
    {@/each}
</script>