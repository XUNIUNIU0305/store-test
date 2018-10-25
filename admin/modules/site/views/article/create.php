<?php
$this->params = ['js' => 'js/article_create.js', 'css' => 'css/article_create.css'];
?>
<div class="admin-main-wrap">
    <!--editor container-->
    <div class="admin-wechat-editor">
        <!--nav-->
        <ul class="nav nav-tabs">
            <li><strong>文章发布编辑</strong></li>
        </ul>
        <!--panel-->
        <div class="admin-management-panel">
            <div class="header"></div>
            <div class="content with-header with-footer clearfix">
                <div class="col-xs-6">
                    <div class="title h4 text-danger text-center">
                        <strong>标题</strong>
                    </div>
                    <textarea class="textarea-title" id="J_title_content" rows="4" maxlength="20"></textarea>
                    <div class="lint">还可以输入<span id="J_title_limit">20</span>字</div>
                    <hr>
                    <div class="title h4 text-danger text-center">
                        <strong>封面</strong>
                    </div>
                    <div class="media">
                        <div class="media-left media-middle">
                            <label class="img-upload-box media-object" for="upload_img_input">
                                <input type="file" id="upload_img_input">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWP4////fwAJ+wP9CNHoHgAAAABJRU5ErkJggg==">
                            </label>
                        </div>
                        <div class="media-body media-middle" id="J_title_preview"></div>
                    </div>
                    <hr>
                </div>
                <div class="col-xs-6 text-center">
                    <div class="title h4 text-danger">
                        <strong>正文</strong>
                        <input type="file" name="file" id="J_editor_img_upload" style='display: none'>
                    </div>
                    <textarea id="apx_editor" name="content" style="width: 320px;height: 340px"></textarea>
                </div>
            </div>
            <div class="footer"></div>
        </div>
        <div class="text-center">
            <div class="btn-group">
                <button class="btn btn-default btn-lg" data-toggle="modal" data-target="#apxModalPreview">预览</button>
                <button class="btn btn-danger btn-lg" data-toggle="modal" data-target="#apxModalAdminDel">发布</button>
            </div>
        </div>
    </div>
</div>
<!-- 删除警示 -->
<div class="apx-modal-admin-alert modal fade" id="apxModalAdminDel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body" style="padding: 80px 0 40px">
                点击发布后，内容将不可再编辑，确定现在发布吗？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_sure_submit">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<div class="apx-modal-wechat-preview modal fade" id="apxModalPreview" tabindex="-1">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-content">-->
    <div class="iphone-container">
        <div class="wechat-content-wrap">
            <!--true content-->
            <div class="wechat-content" style="overflow-y: hidden;">
                <div class="wechat-hint-wrap" onclick="document.querySelector('.wechat-content').style.overflowY = 'auto';document.querySelector('.wechat-hint-wrap').style.opacity = 0;">
                    <div class="wechat-hint"></div>
                </div>
                <div id="J_wechat_preview"></div>
            </div>
        </div>
    </div>
    <!--</div>-->
    <!--</div>-->
</div>

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
	            'flash', 'link'
	        ]
	    });
	});
</script>