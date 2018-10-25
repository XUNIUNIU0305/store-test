<?php
$this->params = ['js' => 'js/article_index.js', 'css' => 'css/article_index.css'];

$this->title = '九大爷平台 - 文章列表';
?>
<div class="top-title">文章</div>
<div class="apx-acc-wechat-list">
    <ul class="list-unstyled" id="J_article_list">
    	<script type="text/template" id="J_tpl_list">
    		{@each codes as it}
    			<li>
		            <div class="media">
		                <div class="media-left media-middle">
		                    <img src="${it.path}">
		                </div>
		                <div class="media-body">
						<a href="/account/article/detail?id=${it.id}"><span class="h3">${it.title}</span></a>
		                    <div class="pull-left text-center text-muted">
		                        <span>${it.create_time}</span>
							</div>
							<a target="_blank" href="/account/article/detail?id=${it.id}" class="btn btn-link btn-xs">查看详情</a>
		                </div>
		            </div>
		        </li>
    		{@/each}
    	</script>
    </ul>
</div>
<!-- pagination -->
<div class="text-right" id="J_article_page"></div>
