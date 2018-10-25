<?php
use yii\helpers\Html;

$this->params = ['js' => 'js/release/product.js', 'css' => 'css/release/product.css'];
?>
<div class="apx-seller-form-wrap clearfix">
    <div class="apx-seller-form-title">
        <i class="glyphicon glyphicon-edit"></i>填写商品信息
    </div>
    <div class="apx-seller-form-info">
        <!-- 左侧信息 -->
        <strong>产品信息</strong>
        <p>
            类目：<span id="J_category_box"></span>
            <a href="/release" class="btn btn-xs btn-default" id="J_edit_category">编辑类目</a>
        </p>
    </div>
    <!-- 表单 -->
    <form class="apx-seller-form form-horizontal clearfix">
        <div class="h4">1. 商品基本信息</div>
        <div class="form-group form-group-sm">
            <label class="col-xs-1 control-label">商品属性:</label>
            <div class="col-xs-11">
                <div class="apx-seller-form-box clearfix J_prod_attr_form_box">
                    <script type="text/template" id="J_tpl_list">
                        {@each _ as it, index}
                            {@if index % 2 === 0}
                                <div class="form-group form-group-sm">       
                            {@/if}
                            <label for="prod-attr${it.id}" class="col-xs-1 control-label">${it.name}:</label>
                            <div class="col-xs-5">
                                <select id="prod-attr${it.id}" data-id="${it.id}" class="form-control">
                                    <option value="">请选择</option>
                                    {@each it.options as x}
                                        <option value="${x.id}">${x.name}</option>   
                                    {@/each}
                                </select>
                            </div>
                            {@if index % 2 === 1}
                                </div>      
                            {@/if}
                        {@/each}
                    </script>
                </div>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="title" class="col-xs-1 control-label">商品标题:</label>
            <div class="col-xs-8">
                <input type="text" maxlength="30" class="form-control J_input_count" id="title">
            </div>
            <div class="col-xs-3">
                <p class="form-control-static">还能输入 <span class="high-lighted J_pro_title" data-count-from="title">0</span> 字</p>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="spotlight" class="col-xs-1 control-label">商品卖点:</label>
            <div class="col-xs-8">
                <textarea rows="3" maxlength="150" class="form-control J_input_count" id="spotlight"></textarea>
            </div>
            <div class="col-xs-3">
                <p class="form-control-static">还能输入 <span class="high-lighted J_pro_trait" data-count-from="spotlight">0</span> 字</p>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="locale" class="col-xs-1 control-label">采购地:</label>
            <div class="col-xs-11">
                <label class="radio-inline">
                    <input type="radio" name="locale" id="inlineRadio1" value="0"> 国内
                </label>
                <label class="radio-inline">
                    <input type="radio" name="locale" id="inlineRadio2" value="1"> 海外及港澳台
                </label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="checklist" class="col-xs-1 control-label">发票:</label>
            <div class="col-xs-11">
                <label class="radio-inline">
                    <input type="radio" name="checklist" id="checklist_yes" value="0"> 无
                </label>
                <label class="radio-inline">
                    <input type="radio" name="checklist" id="checklist_no" value="1"> 有
                </label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="ensurance" class="col-xs-1 control-label">保修:</label>
            <div class="col-xs-11">
                <label class="radio-inline">
                    <input type="radio" name="ensurance" id="ensurance_yes" value="0"> 无
                </label>
                <label class="radio-inline">
                    <input type="radio" name="ensurance" id="ensurance_no" value="1"> 有
                </label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="order" class="col-xs-1 control-label">是否定制:</label>
            <div class="col-xs-11">
                <label class="radio-inline">
                    <input type="radio" name="order" id="order_yes" value="0"> 否
                </label>
                <label class="radio-inline">
                    <input type="radio" name="order" id="order_no" value="1"> 是
                </label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="userRole" class="col-xs-1 control-label">可购买用户:</label>
            <div class="col-xs-11">
                <label class="radio-inline">
                    <input type="radio" name="userRole" id="order_yes" value="2"> 加盟
                </label>
                <label class="radio-inline">
                    <input type="radio" name="userRole" id="order_no" value="3"> 体系
                </label>
                <label class="radio-inline">
                    <input type="radio" name="userRole" id="order_no" value="4"> 运营商
                </label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-xs-1 control-label">商品图片:</label>
            <div class="col-xs-11">
                <div class="apx-seller-form-box pic-box clearfix">
                    <div class="title">本地上传</div>
                    <div class="text-center">选择本地图片:
                    <span class="input_file_btn btn btn-default btn-sm">
                    	文件上传
                    	<input type="file" name="file" id="J_img_upload">
                    </span>
                    
                    <ol class="text-muted text-left" id="J_text_label">
                        <li>本地上传图片大小不能超过<span>0</span>M。</li>
                        <li>本类目下您最多可以上传<span>0</span>张图片。</li>
                    </ol>
                    </div>
                    <div class="pic-box-gallary">
                        <p><span class="high-lighted">700*700</span>以上图片可以在详情页主图提供图片放大功能</p>
                        <div id="J_imgs_box"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-xs-1 control-label">商品描述:</label>
            <div class="col-xs-11 edit-box">
                <!--nav tabs-->
                <ul class="apx-seller-form-box clearfix nav nav-tabs">
                    <li class="editor-title active">
                        <a href="#tab_pc" data-toggle="tab">电脑端</a>
                    </li>
                    <li class="editor-title">
                        <a href="#tab_mobile" data-toggle="tab">手机端</a>
                    </li>
                </ul>
                <label class="radio-inline">
                    <input type="file" name="file" id="J_editor_img_upload" data-target="#apx_editor" style='display: none'>
                </label>
                <!--tabs-->
                <div class="tab-content">
                    <div id="tab_pc" class="tab-pane fade in active">
                        <textarea id="apx_editor" name="content" style="width: 100%; height: 420px">
                        </textarea>
                        <span>当前屏数: 0 <i class="glyphicon glyphicon-question-sign text-success"></i></span>
                    </div>
                    <div id="tab_mobile" class="tab-pane fade">
                        <textarea id="apx_editor_mobile" name="content" style="width: 100%;height: 420px">
                        </textarea>
                        <span>当前屏数: 0 <i class="glyphicon glyphicon-question-sign text-success"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-2">
                <button type="button" class="btn btn-primary btn-block" id="J_publish">发布</button>
                <span></span>
                <button type="button" class="btn btn-primary btn-block hidden" id="J_edit_pro">修改</button>
            </div>
        </div>
    </form>
    <!-- 新增商品关键字添加 -->
    <div class="goods-key-words" style="display: none;">
        <p class="key-title">商品关键字</p>
        <div class="key-ipt-list">
            <div class="input-detail"></div>
            <div class="input-detail">
            </div>
            <div class="input-detail">
            </div>
            <div class="input-detail">
            </div>
            <div class="input-detail">
            </div>
            <div class="input-detail">
            </div>
        </div>
        <div class="btn-contain">
            <!-- <span class="button key-words-btn J_modify_btn">修改</span> -->
            <!-- <span class="button key-words-btn J_sure_btn" data-id="revise" data-toggle="modal" data-target="#apxModalAdminAlert">确定</span> -->
          <!--   <span type="button" class="input-confirm-btn J_sure_btn" data-id="revise" data-toggle="modal" data-target="#apxModalAdminAlert">确定</span> -->
        </div>
    </div>
    <!-- 新增商品关键字添加结束 -->
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
                <div class="h4" style="padding: 36px 0;">请确认是否修改？</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" data-dismiss='modal'>确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal" id="add_cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- 新增结束 -->
<!-- editor script -->
<script src="/vender/kindeditor/kindeditor-all-min.js"></script>
<script>
	// kindeditor 
	window.KindEditor && KindEditor.ready(function(K) {
	    window.editor = K.create('#apx_editor',{
	        items: [
	            'source', 'preview', '|', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright',
	            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
	            'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
	            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
	            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
	            'flash', 'emoticons', 'link'
	        ]
	    });
        // for mobile
        window.mobileEditor = K.create('#apx_editor_mobile',{
            items: [
                'source', 'preview', '|', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                'flash', 'emoticons', 'link'
            ]
        });
	});
</script>
