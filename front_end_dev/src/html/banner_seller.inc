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
                    <a class="navbar-brand" href="#">
                        <img src="/images/logo_dark.png" height="36">
                    </a>
                    <ul class="nav navbar-nav navbar-right with-seperator">
                        <li>
                            <a href="#">商城首页</a>
                        </li>
                        <li>
                            <a href="#">在线帮助</a>
                        </li>
                        <li>
                            <a href="#" data-target="#modalSupShopInfo" data-toggle="modal">店铺信息修改</a>
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
            <div class="media col-xs-3">
                <a class="media-left" href="#">
                	<div class="media-left-inner">
                    	<img class="img-responsive" src="/images/seller/shop_avatar.jpg" alt="Image">
                    </div>
                </a>
                <div class="media-body media-middle">
                    <h3 class="media-heading">店铺品牌名</h3>
					<p class="h4">备注信息</p>
					<p>备注信息</p>
				</div>
			</div>
			<div class="col-xs-4">
				<ul class="list-inline text-center">
					<li><a href="#"><div class="round-icon"><img src="/images/seller/tixian.png"></div>账户提现</a></li>
					<li><a href="#"><div class="round-icon"><img src="/images/seller/dianpu.png"></div>店铺商品</a></li>
					<li><a href="#"><div class="round-icon"><img src="/images/seller/tuiguang.png"></div>店铺推广</a></li>
				</ul>
			</div>
			<div class="col-xs-5 text-center with-seperator">
				<div class="col-xs-3">
					<div class="seller-icon"><img src="/images/seller/yue.png"></div>
					<p>账户余额</p>
					<h4>23454.00</h4>
				</div>
				<div class="col-xs-3">
					<div class="seller-icon"><img src="/images/seller/dongjie.png"></div>
					<p>冻结金额</p>
					<h4>2000.00</h4>
				</div>
				<div class="col-xs-3">
					<div class="seller-icon"><img src="/images/seller/rili.png"></div>
					<p>昔日订单</p>
					<h4>1234</h4>
				</div>
				<div class="col-xs-3">
					<div class="seller-icon"><img src="/images/seller/chuli.png"></div>
					<p>待处理订单</p>
					<h4>320</h4>
				</div>
			</div>
		</div>
	</div>
</div>

<!--信息修改弹窗-->
<div class="apx-modal-supply-shop-info modal fade" id="modalSupShopInfo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <div class="modal-title" id="myModalLabel">
                    <strong><span class="h3">修改信息</strong>
                </div>
            </div>
            <div class="modal-body">
                <div class="media">
                    <div class="media-left">
                        <label for="shop_info_avatar" class="apx-update-photo">
                            <input id="shop_info_avatar" type="file">
                            <!--<img class="img-responsive" src="/images/row03.jpg">-->
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
                                <label for="mobile" class="col-xs-3 text-right control-label">手机号：</label>
                                <div class="col-xs-9">
                                    <input type="text" id="mobile" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tel_prefex" class="col-xs-3 text-right control-label">固定电话：</label>
                                <div class="col-xs-3">
                                    <input type="text" id="tel_prefex" class="form-control" value="">
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" id="tel_tail" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_detail" class="col-xs-3 text-right control-label">退换货地址：</label>
                                <div class="col-xs-3">
                                    <select name="" id="address_1" class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <select name="" id="address_2" class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <select name="" id="address_3" class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
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
                <button type="button" class="btn btn-danger">确定递交</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消递交</button>
                <div class="error-msg text-danger">
                    <strong><i class="glyphicon glyphicon-warning-sign"></i>品牌名不能为空</strong>
                </div>
            </div>
        </div>
    </div>
</div>