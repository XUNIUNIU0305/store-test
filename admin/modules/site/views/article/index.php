<?php
$this->params = ['js' => 'js/article_index.js', 'css' => 'css/article_index.css'];
?>

<div class="admin-main-wrap">
    <!--editor container-->
    <div class="admin-wechat-list">
        <!--nav-->
        <ul class="nav nav-tabs">
            <li><strong>文章发布管理</strong></li>
        </ul>
        <a href="/site/article/create" class="btn btn-block btn-danger">发布新文章</a>
        <!--panel-->
        <div class="admin-management-panel">
            <div class="header cleafix">
                <div class="col-xs-6"><strong class="h4">文章列表</strong></div>
                <div class="col-xs-6"><strong class="h4">文章详情</strong></div>
            </div>
            <div class="content with-header with-footer clearfix">
                <div class="col-xs-6">
                    <ul class="list-unstyled" id="J_article_list">
                    	<script type="text/template" id="J_tpl_list">
                    		{@each codes as it}
	                    		<li class="J_article_box" data-id="${it.id}">
		                            <div class="media">
		                                <div class="media-left media-middle">
		                                    <label class="img-upload-box media-object">
		                                        <img src="${it.path}">
		                                    </label>
		                                </div>
		                                <div class="media-body media-middle">
                                            <div class="col-xs-10">${it.title}</div>
                                            <div class="col-xs-2">
                                                <a data-toggle="modal" data-target="#apxModalAdminDel" data-id="${it.id}" class="btn btn-danger">删除</a>
                                            </div>
		                                </div>
		                            </div>
		                        </li>
                    		{@/each}
                    	</script>
                    </ul>
                </div>
                <div class="col-xs-6">
                    <div class="wechat-content-wrap">
                        <!--true content-->
                        <div class="wechat-content" id="J_wechat_content"></div>
                    </div>
                </div>
            </div>
            <div class="footer" id="J_article_page"></div>
        </div>
    </div>
</div>

<!-- 删除提示 -->
<div class="apx-modal-admin-alert modal fade admin-management" id="apxModalAdminDel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body management-authen">
                <strong>确认删除该文章 &nbsp;？</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-danger" id="J_del_article">确认</button>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
