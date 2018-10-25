<?php
$this->params = ['js' => 'js/article_detail.js', 'css' => 'css/article_detail.css'];

$this->title = '九大爷平台 - 文章详情';
?>
<div class="apx-acc-wechat-detail">
    <div class="top-title">文章 <i class='img-symbol'></i><span class='article-title'></span></div>
    <div class="pull-left">
        <div class="article-box">
            <div class="title">
                <strong><p id="J_article_title"></p></strong>
                <p class="article-time">2017-01-13 12:12:34</p>
            </div>
            <div id="J_article_content"></div>
            <div class="footer">
                <!--editor preview-->
                <div id="J_article_footer"></div>
                <button class="btn btn-sm btn-default btn-compile" id="J_edit_footer">编辑页脚</button>
                <div class="hidden" id="J_edit_box">
                	<!--editor content -->
	                <textarea id="apx_editor" name="content" style="width: 100%; height: 120px;"></textarea>
	                <button class="btn btn-sm btn-default" id="J_create_footer">确定</button>
	                <button class="btn btn-link btn-sm btn-cancel" id="J_edit_cancel">取消</button>
                </div>
            </div>
        </div>
        <div class="aside">
            <!-- <a href="/account/article/index" class="btn btn-block btn-danger">返回文章列表 <i class="glyphicon glyphicon-share-alt"></i></a> -->
            <div class="qr-box text-center">
                <img src="" id="J_article_img">
                <p>扫描二维码分享文章</p>
            </div>
        </div>
    </div>
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
