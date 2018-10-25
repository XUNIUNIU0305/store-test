<?php 
use yii\helpers\Html;
use supply\assets\MainAsset;

MainAsset::register($this)->addJs(isset($this->params['js']) ? $this->params['js'] : null)->addCss(isset($this->params['css']) ? $this->params['css'] : null);
?>

<?php $this->beginContent('@supply/views/layouts/global.php'); ?>

<!-- layouts/main.php -->

<!-- banner start -->
<nav class="apx-top-nav navbar navbar-default navbar-lg top-fixed">
    <div class="container">
        <div class="row">
            <!-- mobile icon -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="row">
                    <a class="navbar-brand" href="/">
                        <img src="/images/new_icon.png" class="logo-icon">
                    </a>
                    <ul class="nav navbar-nav navbar-right with-seperator">
                        <li>
                            <a href="#">商城首页</a>
                        </li>
                        <li>
                            <a href="#">在线帮助</a>
                        </li>
                        <li>
                            <a href="#">店铺信息修改</a>
                        </li>
                        <li>
                            <a href="/index/logout">退出</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="top-fixed-placeholder"></div>
<div class="apx-seller-banner container-fluid">
    <div class="container">
        <div class="row">
            <div class="media col-xs-4">
                <a class="media-left" href="javascript:;">
                	<div class="media-left-inner">
                    	<img class="img-responsive J_header_img" src="/images/seller/shop_avatar.jpg" alt="Image">
                    </div>
                </a>
                <div class="media-body media-middle">
                    <h3 class="media-heading J_brand_name"></h3>
					<p class="h4 J_company_name"></p>
					<p class="J_address"></p>
				</div>
			</div>
            <ul class="list-inline text-center col-xs-4">
                <li class="col-xs-4"><a href="#"><div class="round-icon"><img src="/images/seller/tixian.png"></div>账户提现</a></li>
                <li class="col-xs-4"><a href="http://dz.9daye.com.cn/view_page/view_image" target="_blank"><div class="round-icon"><img src="/images/seller/dianpu.png"></div>查看定制图片</a></li>
                <li class="col-xs-4"><a href="#" data-target="#modalSupShopInfo" data-toggle="modal"><div class="round-icon"><img src="/images/seller/tuiguang.png"></div>退换货信息</a></li>
            </ul>
			<div class="col-xs-4 text-center with-seperator">
				<div class="col-xs-4">
					<div class="seller-icon"><img src="/images/seller/yue.png"></div>
					<p>账户余额</p>
					<h4>0.00</h4>
				</div>
				<div class="col-xs-4">
					<div class="seller-icon"><img src="/images/seller/rili.png"></div>
					<p>昔日订单</p>
					<h4>0</h4>
				</div>
				<div class="col-xs-4">
					<div class="seller-icon"><img src="/images/seller/chuli.png"></div>
					<p>待处理订单</p>
					<h4>0</h4>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- banner end -->

<!-- main content start -->
<div class="container">
    <div class="row">
        <!-- aside menu start -->
        <div>
            <script type="text/template" id="J_tpl_menu">
                {@each _ as it}
                    <a data-toggle="collapse" href="#aside_panel_${it.id}">
                        <span><img src="${it.img}"></span>${it.title}<i class="glyphicon glyphicon-chevron-down"></i>
                    </a>
                    <div class="collapse in" id="aside_panel_${it.id}">
                        <ul class="list-unstyled clearfix">
                        {@each it.children as child}
                            <li class="col-xs-6 ${child.url|class_build}">
                                <a href="${child.url}">${child.title}</a>
                            </li>
                        {@/each}
                        </ul>
                    </div>
                {@/each}
            </script>
            <div class="apx-seller-aside loading"></div>
        </div>
        <!-- aside menu end -->
        <div class="apx-has-seller-aside">
			<?= $content ?>
        </div>
    </div>
</div>
<!-- main content end -->

<!-- footer start -->
<footer class="apx-footer_seller container">
    <div class="row">
        <ul class="list-inline">
            <!-- <li><a href="#">关于APEX</a></li> -->
            <!-- <li><a href="#">合作伙伴</a></li> -->
            <!-- <li><a href="#">营销中心</a></li> -->
            <li><a href="#">联系客服</a></li>
            <li><a href="#">诚征英才</a></li>
            <li><a href="#">联系我们</a></li>
            <!-- <li><a href="#">法律声明</a></li> -->
        </ul>
    </div>
</footer>
<!-- footer end -->

<!--信息修改弹窗-->
<div class="apx-modal-supply-shop-info modal fade" id="modalSupShopInfo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title" id="myModalLabel">
                    <strong><span class="h3">退换货信息</strong>
                </div>
            </div>
            <div class="modal-body">
                <div class="media">
                    <div class="media-left">
                        <label for="shop_info_avatar" class="apx-update-photo">
                            <input id="shop_info_avatar" type="file">
                            <img class="img-responsive J_header_img_up hidden" src="">
                        </label>
                    </div>
                    <div class="media-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="brand_name" class="col-xs-3 text-right control-label">品牌名：</label>
                                <div class="col-xs-9">
                                    <input type="text" id="brand_name" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="co_name" class="col-xs-3 text-right control-label">公司名：</label>
                                <div class="col-xs-9">
                                    <input type="text" id="co_name" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="real_name" class="col-xs-3 text-right control-label">收货人：</label>
                                <div class="col-xs-9">
                                    <input type="text" id="real_name" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mobile" class="col-xs-3 text-right control-label">手机号：</label>
                                <div class="col-xs-9">
                                    <input type="text" id="mobile" class="form-control" maxlength="11" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tel_prefex" class="col-xs-3 text-right control-label">固定电话：</label>
                                <div class="col-xs-3">
                                    <input type="text" id="tel_prefex" maxlength="4" class="form-control" value="">
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" id="tel_tail" maxlength="8" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_detail" class="col-xs-3 text-right control-label">退换货地址：</label>
                                <div class="col-xs-3">
                                    <select name="" id="address_province" class="form-control">
                                        <option value="-1">请选择</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <select name="" id="address_city" class="form-control">
                                        <option value="-1">请选择</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <select name="" id="address_district" class="form-control">
                                        <option value="-1">请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-9 col-xs-offset-3">
                                    <input type="text" id="address_detail" class="form-control" value="">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" id="J_sure_edit">确定递交</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消递交</button>
                <div class="error-msg text-danger hide">
                    <strong><i class="glyphicon glyphicon-warning-sign"></i><span class="error_msg"></span></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>
