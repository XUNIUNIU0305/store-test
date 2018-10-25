<?php
/**
 * @var $this \yii\web\View
 */

use mobile\modules\customization\assets\Asset;

$this->title = '定制上传';
Asset::register($this)->addJs('js/view.js')->addCss('css/view.css');
?>
<div class="customization-upload">
	<!-- class: reject masking transiting 3个状态 -->
	<div class="tip-title">
		<p>生产中</p>
	</div>
	<div class="express-info hidden">
		<div class="title">快递信息</div>
		<p>产品已经寄出，快递公司：<span id="J_express_name"></span>，快递单号：<span id="J_express_no"></span>，请注意查收。</p>
	</div>
	<div class="order-info">
		<div class="img"><img id="J_info_img" src=""></div>
		<div class="text">
			<p class="h1" id="J_info_title"></p>
			<p class="attr" id="J_info_attr"></p>
			<p class="time">付款时间：<span id="J_pay_time"></span></p>
			<p class="price">单价：<span id="J_info_price"></span></p>
		</div>
	</div>
	<div class="select-car">
		<div class="title">请选择需要定制车型：</div>
		<ul>
			<li id="J_select_brand"><span class="left">汽车品牌</span><span class="J_brand_name">请选择</span></li>
			<li id="J_select_type"><span class="left">汽车型号</span><span class="J_type_name">请选择</span></li>
		</ul>
	</div>
	<div class="remark">
		<div class="title">请输入特殊备注（60字内）：</div>
		<textarea id="J_remark" rows="4" maxlength="60"></textarea>
	</div>
	<div class="supply-remark">
        <div class="title">厂家备注</div>
        <p id="J_supply_remark"></p>
    </div>
	<div class="upload-img">
		<div class="title">请上传部位图片</div>
		<div class="img-box" id="J_img_box">
			<label class="img-upload-box" >
                <div class="close hidden">X</div>
                <input type="file" id="upload_mobile_pic">
                <img src="/images/customization/add_p.png">
            </label>
		</div>
	</div>
	<div class="submit">
		<a href="javascript:;" id="J_submit" class="btn">提交</a>
		<a href="/customization/order" id="J_back" class="btn hidden">返回</a>
	</div>
</div>
<div class="mask-container mask-fixed mask-car-brand" id="mask_select_brand">
    <div class="mask-bg"></div>
    <div class="wechat-car-brand-container">
        <div class="title">请选择品牌</div>
        <div class="select-box">
        	<div class="left">
        		<ul id="J_car_list"></ul>
        	</div>
        	<div class="right">
        		<ul id="J_car_brand"></ul>
        	</div>
        </div>
    </div>
</div>
<div class="mask-container mask-fixed mask-car-type" id="mask_select_type">
    <div class="mask-bg"></div>
    <div class="wechat-car-type-container">
        <div class="title">请选择型号</div>
		<ul id="J_car_type"></ul>
    </div>
</div>
