<?php
use yii\helpers\Html;

$this->params = ['js' => 'js/release/category.js', 'css' => 'css/release/category.css'];
?>

<!-- supply/views/category/index.php -->
<!-- 类目搜索 -->
<div class="search-bar input-group input-group-lg">
	<span class="input-group-addon">搜索类目:</span>
	<input type="text" class="form-control" placeholder="请输入商品名/型号">
	<span class="input-group-btn">
	  	<button class="btn btn-default btn-lg" type="button">快速找到类目</button>
	</span>
</div>
<!-- 多列表选择 -->
<div class="apx-seller-multi-list-wrap clearfix">
	<div class="apx-seller-multi-list clearfix pull-left">
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
							<span class="start-alpha"></span>
							${it.title}
							<i class="glyphicon glyphicon-chevron-right pull-right"></i>
						</a>
					</li>
					{@/each}
				</script>
			</ul>
		</div>
		<div class="col-xs-4">
			<div class="input-group input-group-sm">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
			  	<input type="text" class="form-control" placeholder="输入名称/拼音首字母">
			</div>
			<ul class="list-unstyled">
			</ul>
		</div>
		<div class="col-xs-4">
			<div class="input-group input-group-sm">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
			  	<input type="text" class="form-control" placeholder="输入名称/拼音首字母">
			</div>
			<ul class="list-unstyled">
			</ul>
		</div>
	</div>
</div>
<div class="apx-seller-tip-box">
	您当前选择的是：<span></span> <span></span> <span></span>
</div>
<button class="apx-seller-launch-btn btn btn-lg btn-default disabled">我已阅读以下规则，现在发布宝贝</button>
<div class="high-lighted text-center"><input type="checkbox" class="J_read_rule">《发布宝贝规则》</div>
<div class="apx-seller-rule-box">
	<p><strong class="high-lighted">发布须知: </strong>APEX汽配商城禁止发布侵犯他人知识产权的商品，请确认商品符合知识产权保护的规定</p>
	<hr>
	<h5><strong>汽配商城规则</strong></h5>
	<p class="title">第一章 概述</p>
	<p>第一条</p>
	<p>为促进开放、透明、分享、责任的新商业文明，保障淘宝用户合法权益，维护淘宝正常经营秩序</p>
	<p>汽配商城规则，是对淘宝用户增加基本义务或限制基本权利的条款。</p>
	<p>第三条</p>
	<p>违规行为的认定与处理，应基于淘宝认定的事实并严格依规执行。淘宝用户在适用规则上一律平等。</p>
	<p>第四条</p>
	<p>用户应遵守国家法律、行政法规、部门规章等规范性文件。对任何涉嫌违反国家法律、行政法规、部门规章等规范性文件的行为，本规则已有规定的，适用本规则；</p>
	<p>本规则尚无规定的，淘宝有权酌情处理。但淘宝对用户的处理不免除其应尽的法律责任。用户在淘宝的任何行为，应同时遵守与淘宝及其关联公司所签订的各项协议。</p>
	<p>淘宝有权随时变更本规则并在网站上予以公告。若用户不同意相关变更，应立即停止使用淘宝的相关服务或产品。淘宝有权对用户行为及应适用的规则进行单方认定，</p>
	<p>并据此处理。</p>
</div>
