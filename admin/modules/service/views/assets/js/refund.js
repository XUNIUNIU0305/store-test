/*!
 * Lightbox v2.9.0
 * by Lokesh Dhakar
 *
 * More info:
 * http://lokeshdhakar.com/projects/lightbox2/
 *
 * Copyright 2007, 2015 Lokesh Dhakar
 * Released under the MIT license
 * https://github.com/lokesh/lightbox2/blob/master/LICENSE
 */
!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],b):"object"==typeof exports?module.exports=b(require("jquery")):a.lightbox=b(a.jQuery)}(this,function(a){function b(b){this.album=[],this.currentImageIndex=void 0,this.init(),this.options=a.extend({},this.constructor.defaults),this.option(b)}return b.defaults={albumLabel:"Image %1 of %2",alwaysShowNavOnTouchDevices:!1,fadeDuration:600,fitImagesInViewport:!0,imageFadeDuration:600,positionFromTop:50,resizeDuration:700,showImageNumberLabel:!0,wrapAround:!1,disableScrolling:!1,sanitizeTitle:!1},b.prototype.option=function(b){a.extend(this.options,b)},b.prototype.imageCountLabel=function(a,b){return this.options.albumLabel.replace(/%1/g,a).replace(/%2/g,b)},b.prototype.init=function(){var b=this;a(document).ready(function(){b.enable(),b.build()})},b.prototype.enable=function(){var b=this;a("body").on("click","a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]",function(c){return b.start(a(c.currentTarget)),!1})},b.prototype.build=function(){var b=this;a('<div id="lightboxOverlay" class="lightboxOverlay"></div><div id="lightbox" class="lightbox"><div class="lb-outerContainer"><div class="lb-container"><img class="lb-image" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" /><div class="lb-nav"><a class="lb-prev" href="" ></a><a class="lb-next" href="" ></a></div><div class="lb-loader"><a class="lb-cancel"></a></div></div></div><div class="lb-dataContainer"><div class="lb-data"><div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div><div class="lb-closeContainer"><a class="lb-close"></a></div></div></div></div>').appendTo(a("body")),this.$lightbox=a("#lightbox"),this.$overlay=a("#lightboxOverlay"),this.$outerContainer=this.$lightbox.find(".lb-outerContainer"),this.$container=this.$lightbox.find(".lb-container"),this.$image=this.$lightbox.find(".lb-image"),this.$nav=this.$lightbox.find(".lb-nav"),this.containerPadding={top:parseInt(this.$container.css("padding-top"),10),right:parseInt(this.$container.css("padding-right"),10),bottom:parseInt(this.$container.css("padding-bottom"),10),left:parseInt(this.$container.css("padding-left"),10)},this.imageBorderWidth={top:parseInt(this.$image.css("border-top-width"),10),right:parseInt(this.$image.css("border-right-width"),10),bottom:parseInt(this.$image.css("border-bottom-width"),10),left:parseInt(this.$image.css("border-left-width"),10)},this.$overlay.hide().on("click",function(){return b.end(),!1}),this.$lightbox.hide().on("click",function(c){return"lightbox"===a(c.target).attr("id")&&b.end(),!1}),this.$outerContainer.on("click",function(c){return"lightbox"===a(c.target).attr("id")&&b.end(),!1}),this.$lightbox.find(".lb-prev").on("click",function(){return 0===b.currentImageIndex?b.changeImage(b.album.length-1):b.changeImage(b.currentImageIndex-1),!1}),this.$lightbox.find(".lb-next").on("click",function(){return b.currentImageIndex===b.album.length-1?b.changeImage(0):b.changeImage(b.currentImageIndex+1),!1}),this.$nav.on("mousedown",function(a){3===a.which&&(b.$nav.css("pointer-events","none"),b.$lightbox.one("contextmenu",function(){setTimeout(function(){this.$nav.css("pointer-events","auto")}.bind(b),0)}))}),this.$lightbox.find(".lb-loader, .lb-close").on("click",function(){return b.end(),!1})},b.prototype.start=function(b){function c(a){d.album.push({link:a.attr("href"),title:a.attr("data-title")||a.attr("title")})}var d=this,e=a(window);e.on("resize",a.proxy(this.sizeOverlay,this)),a("select, object, embed").css({visibility:"hidden"}),this.sizeOverlay(),this.album=[];var f,g=0,h=b.attr("data-lightbox");if(h){f=a(b.prop("tagName")+'[data-lightbox="'+h+'"]');for(var i=0;i<f.length;i=++i)c(a(f[i])),f[i]===b[0]&&(g=i)}else if("lightbox"===b.attr("rel"))c(b);else{f=a(b.prop("tagName")+'[rel="'+b.attr("rel")+'"]');for(var j=0;j<f.length;j=++j)c(a(f[j])),f[j]===b[0]&&(g=j)}var k=e.scrollTop()+this.options.positionFromTop,l=e.scrollLeft();this.$lightbox.css({top:k+"px",left:l+"px"}).fadeIn(this.options.fadeDuration),this.options.disableScrolling&&a("body").addClass("lb-disable-scrolling"),this.changeImage(g)},b.prototype.changeImage=function(b){var c=this;this.disableKeyboardNav();var d=this.$lightbox.find(".lb-image");this.$overlay.fadeIn(this.options.fadeDuration),a(".lb-loader").fadeIn("slow"),this.$lightbox.find(".lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption").hide(),this.$outerContainer.addClass("animating");var e=new Image;e.onload=function(){var f,g,h,i,j,k,l;d.attr("src",c.album[b].link),f=a(e),d.width(e.width),d.height(e.height),c.options.fitImagesInViewport&&(l=a(window).width(),k=a(window).height(),j=l-c.containerPadding.left-c.containerPadding.right-c.imageBorderWidth.left-c.imageBorderWidth.right-20,i=k-c.containerPadding.top-c.containerPadding.bottom-c.imageBorderWidth.top-c.imageBorderWidth.bottom-120,c.options.maxWidth&&c.options.maxWidth<j&&(j=c.options.maxWidth),c.options.maxHeight&&c.options.maxHeight<j&&(i=c.options.maxHeight),(e.width>j||e.height>i)&&(e.width/j>e.height/i?(h=j,g=parseInt(e.height/(e.width/h),10),d.width(h),d.height(g)):(g=i,h=parseInt(e.width/(e.height/g),10),d.width(h),d.height(g)))),c.sizeContainer(d.width(),d.height())},e.src=this.album[b].link,this.currentImageIndex=b},b.prototype.sizeOverlay=function(){this.$overlay.width(a(document).width()).height(a(document).height())},b.prototype.sizeContainer=function(a,b){function c(){d.$lightbox.find(".lb-dataContainer").width(g),d.$lightbox.find(".lb-prevLink").height(h),d.$lightbox.find(".lb-nextLink").height(h),d.showImage()}var d=this,e=this.$outerContainer.outerWidth(),f=this.$outerContainer.outerHeight(),g=a+this.containerPadding.left+this.containerPadding.right+this.imageBorderWidth.left+this.imageBorderWidth.right,h=b+this.containerPadding.top+this.containerPadding.bottom+this.imageBorderWidth.top+this.imageBorderWidth.bottom;e!==g||f!==h?this.$outerContainer.animate({width:g,height:h},this.options.resizeDuration,"swing",function(){c()}):c()},b.prototype.showImage=function(){this.$lightbox.find(".lb-loader").stop(!0).hide(),this.$lightbox.find(".lb-image").fadeIn(this.options.imageFadeDuration),this.updateNav(),this.updateDetails(),this.preloadNeighboringImages(),this.enableKeyboardNav()},b.prototype.updateNav=function(){var a=!1;try{document.createEvent("TouchEvent"),a=this.options.alwaysShowNavOnTouchDevices?!0:!1}catch(b){}this.$lightbox.find(".lb-nav").show(),this.album.length>1&&(this.options.wrapAround?(a&&this.$lightbox.find(".lb-prev, .lb-next").css("opacity","1"),this.$lightbox.find(".lb-prev, .lb-next").show()):(this.currentImageIndex>0&&(this.$lightbox.find(".lb-prev").show(),a&&this.$lightbox.find(".lb-prev").css("opacity","1")),this.currentImageIndex<this.album.length-1&&(this.$lightbox.find(".lb-next").show(),a&&this.$lightbox.find(".lb-next").css("opacity","1"))))},b.prototype.updateDetails=function(){var b=this;if("undefined"!=typeof this.album[this.currentImageIndex].title&&""!==this.album[this.currentImageIndex].title){var c=this.$lightbox.find(".lb-caption");this.options.sanitizeTitle?c.text(this.album[this.currentImageIndex].title):c.html(this.album[this.currentImageIndex].title),c.fadeIn("fast").find("a").on("click",function(b){void 0!==a(this).attr("target")?window.open(a(this).attr("href"),a(this).attr("target")):location.href=a(this).attr("href")})}if(this.album.length>1&&this.options.showImageNumberLabel){var d=this.imageCountLabel(this.currentImageIndex+1,this.album.length);this.$lightbox.find(".lb-number").text(d).fadeIn("fast")}else this.$lightbox.find(".lb-number").hide();this.$outerContainer.removeClass("animating"),this.$lightbox.find(".lb-dataContainer").fadeIn(this.options.resizeDuration,function(){return b.sizeOverlay()})},b.prototype.preloadNeighboringImages=function(){if(this.album.length>this.currentImageIndex+1){var a=new Image;a.src=this.album[this.currentImageIndex+1].link}if(this.currentImageIndex>0){var b=new Image;b.src=this.album[this.currentImageIndex-1].link}},b.prototype.enableKeyboardNav=function(){a(document).on("keyup.keyboard",a.proxy(this.keyboardAction,this))},b.prototype.disableKeyboardNav=function(){a(document).off(".keyboard")},b.prototype.keyboardAction=function(a){var b=27,c=37,d=39,e=a.keyCode,f=String.fromCharCode(e).toLowerCase();e===b||f.match(/x|o|c/)?this.end():"p"===f||e===c?0!==this.currentImageIndex?this.changeImage(this.currentImageIndex-1):this.options.wrapAround&&this.album.length>1&&this.changeImage(this.album.length-1):("n"===f||e===d)&&(this.currentImageIndex!==this.album.length-1?this.changeImage(this.currentImageIndex+1):this.options.wrapAround&&this.album.length>1&&this.changeImage(0))},b.prototype.end=function(){this.disableKeyboardNav(),a(window).off("resize",this.sizeOverlay),this.$lightbox.fadeOut(this.options.fadeDuration),this.$overlay.fadeOut(this.options.fadeDuration),a("select, object, embed").css({visibility:"visible"}),this.options.disableScrolling&&a("body").removeClass("lb-disable-scrolling")},new b});
//# sourceMappingURL=lightbox.min.map
//灯箱插件
$(function() {
	var scrolls = [];
	$('.iscroll_container').each(function () {
	    scrolls.push(new IScroll(this, {
	        mouseWheel: true,
	        scrollbars: true,
	        scrollbars: 'custom',
	        preventDefault: false
	    }))
	})
	var _g = {
		'1': {},
		'-2': {},
		'8': {},
		'9': {}
	}
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var status = $('#J_tab_box li[class*="active"]').data('status');
		if (!_g['-2'].count || !_g['8'].count || !_g['9'].count) {
			getRfundList(1, 20, status);
		}
		scrolls.forEach(function (scroll) {
			scroll.refresh();
		})
	});
	function refreshScroll() {
		setTimeout(function() {
			scrolls.forEach(function (scroll) {
				scroll.refresh();
			})
		}, 300)
	}

	// 切换售后类型
	$('#J_toggle_type input[name="refund_type"]').on('click', function() {
		if ($(this).val() == 2) {
			$('.refund-money').removeClass('hidden')	
		} else {
			$('.refund-money').addClass('hidden')	
		}
	})
	//获取退换单列表
	var tpl_list = $('#J_tpl_list').html();
	var tpl_service = $('#J_tpl_service').html();
	var tpl_order = $('#J_tpl_order').html();
	function statusname(data) {
		if (data == 0) {
			return '待客服审核'
		}
		if (data == 1) {
			return '待厂家审核'
		}
		if (data == 2) {
			return '已驳回'
		}
		if (data == 3) {
			return '已过审'
		}
		if (data == 4) {
			return '退回中'
		}
		if (data == 5) {
			return '已退款'
		}
		if (data == 6) {
			return '换货中'
		}
		if (data == 7) {
			return '已换货'
		}
		if (data == 8) {
			return '确认退款'
		}
		if (data == 9) {
			return '已取消'
		}
	}
	function buildPrice(data) {
		return parseFloat(data).toFixed(2)
	}
	juicer.register('buildPrice', buildPrice);
	juicer.register('statusname_build', statusname);
	function getRfundList(page, size, status, sort, code) {
		var data = {
			current_page: page,
			page_size: size,
			status: status,
			sort: sort,
			refund_code: code
		}
		requestUrl('/service/refund/order-list', 'GET', data, function(data) {
			var html = juicer(tpl_list, data);
			var pages = getPagination(page, Math.ceil(data.total_count/size));
			_g[status] = data;
			if (status == 0) {
				$('#J_todo_list').html(html);
				$('#J_todo_detail').addClass('hidden');
				scrolls[0].refresh();
				//分页
				pagingBuilder.build($('#J_todo_page'), page, size, data.total_count)
				pagingBuilder.click($('#J_todo_page'), function(page) {
					getRfundList(page, size, status, sort)
				})
			} else if (status == -2 || status == 9) {
				$('#J_done_list').html(html);
				$('#J_done_detail').addClass('hidden');
				scrolls[3].refresh();
				//分页
				pagingBuilder.build($('#J_done_page'), page, size, data.total_count)
				pagingBuilder.click($('#J_done_page'), function (page) {
					getRfundList(page, size, status, sort)
				})
			} else if (status == 8) {
				$('#J_money_list').html(html);
				$('#J_money_detail').addClass('hidden');
				scrolls[4].refresh();
				//分页
				pagingBuilder.build($('#J_money_page'), page, size, data.total_count)
				pagingBuilder.click($('#J_money_page'), function (page) {
					getRfundList(page, size, status, sort)
				})
			}
			$('.J_data_refresh').html('刷新');
			$('.J_user').on('click', function() {
				$(this).addClass('active').siblings('.J_user').removeClass('active');
				var no = $(this).data('no');
				$('#J_todo_edit').data('no', no);
				$('#J_done_edit').data('no', no);
				$('#J_money_edit').data('no', no);
				$('textarea').val('');
				$('#J_service_img').html('');
				$('#upload_service_img').val('');
				requestUrl('/service/refund/order-info', 'GET', {refund_code: no}, function(data) {
					var status = $('#J_tab_box li[class*="active"]').data('status');
					// 待处理详情
					if (status == 0) {
						$('#J_todo_detail').removeClass('hidden');
						var _maxP = 0;
						if (data.order_info.count * data.order_info.price <= (data.original_order_info.total_fee - data.apply_refund_rmb)) {
							_maxP = (data.order_info.count * data.order_info.price).toFixed(2)
						} else {
							_maxP = parseFloat(data.original_order_info.total_fee - data.apply_refund_rmb).toFixed(2)
						}
						$('#J_todo_detail .J_max_price').html(_maxP).data('price', _maxP);
						$('input[name=refund_type]').prop('checked', false);
						if (data.customization == 1) {
							$('input#refund').parent('label').addClass('hidden');
						} else {
							$('input#refund').parent('label').removeClass('hidden');
						}
						if (data.seller.headerImg == '') {
							$('#J_todo_detail .J_customer_header').attr('src', '/images/ensurance/icon1.png');
						} else {
							$('#J_todo_detail .J_customer_header').attr('src', data.seller.headerImg);
						}
						// 订单信息
						$('#J_todo_detail .order-info').html(juicer(tpl_order, data.original_order_info.items));
						$('#J_todo_detail .J_coupon_price').html(data.original_order_info.coupon_rmb.toFixed(2));
						$('#J_todo_detail .J_items_price').html(data.original_order_info.items_fee.toFixed(2));
						$('#J_todo_detail .J_refund_price').html(data.original_order_info.refund_rmb.toFixed(2));
						$('#J_todo_detail .J_total_price').html(data.original_order_info.total_fee.toFixed(2));

						$('#J_todo_detail .J_customer_account').html(data.seller.account);
						$('#J_todo_detail .J_customer_mobile').html(data.customer.receive_mobile);
						$('#J_todo_detail .J_customer_email').html(data.seller.email);
						$('#J_todo_detail .J_customer_operater').html(data.seller.carrieroperator);
						$('#J_todo_detail .J_customer_area').html(data.seller.area);
						$('#J_todo_detail .J_pro_img').attr('src', data.order_info.image);
						$('#J_todo_detail .J_pro_title').html(data.order_info.title);
						var attrs = '';
						for (var i = 0; i < data.order_info.attributes.length; i++) {
							attrs += data.order_info.attributes[i].attribute + '：' + data.order_info.attributes[i].option
						}
						$('#J_todo_detail .J_pro_attr').html(attrs);
						$('#J_todo_detail .J_pro_price').html(data.order_info.price.toFixed(2));
						$('#J_todo_detail .J_pro_count').html(data.order_info.count);
						$('#J_todo_detail .J_pro_total').html((data.order_info.count * data.order_info.price).toFixed(2));
						$('#J_todo_detail .J_supply_company').html(data.supply.brand_name);
						$('#J_todo_detail .J_supply_name').html(data.supply.company_name);
						$('#J_todo_detail .J_supply_mobile').html(data.supply.mobile);
						$('#J_todo_detail .J_supply_telephone').html(data.supply.telephone);
						$('#J_todo_detail .J_supply_address').html(data.supply.address);
						$('#J_todo_detail .J_refund_reason').html(data.reason);
						var imgs = '';
						for (var i = 0; i < data.customer_image.length; i++) {
							imgs += '<li><a href="' + data.customer_image[i].path + '" data-lightbox="unique-mark0" target="_blank"><img src="' + data.customer_image[i].path + '" class="img-responsive"></a></li>'
						}
						$('#J_todo_detail .J_refund_img').html(imgs);
						refreshScroll()
					} else if (status == -2 || status == 9) {
						// 已处理详情
						$('#J_done_detail').removeClass('hidden');
						if (data.refund_status == 0) {
							var stat = '待客服审核';
						}
						if (data.refund_status == 1) {
							var stat = '待厂家审核';
						}
						if (data.refund_status == 2) {
							var stat = '已驳回';
						}
						if (data.refund_status == 3) {
							var stat = '已过审';
							$('.help-supply').removeClass('hidden');
						} else {
							$('.help-supply').addClass('hidden');
						}
						if (data.refund_status == 4) {
							var stat = '退回中';
						}
						if (data.refund_status == 5) {
							var stat = '已退款';
						}
						if (data.refund_status == 6) {
							var stat = '换货中';
						}
						if (data.refund_status == 7) {
							var stat = '已换货';
						}
						if (data.refund_status == 8) {
							var stat = '确认退款';
						}
						if (data.refund_status == 9) {
							var stat = '已取消';
							$('.refund-cancel').removeClass('hidden');
							$('.J_cancel_reason').html(data.cancel_reason);
						} else {
							$('.refund-cancel').addClass('hidden');
						}
						if (data.refund_status == 1 || data.refund_status == 3 || data.refund_status == 4 || data.refund_status == 6 || data.refund_status == 8) {
							$('#J_cancel_btn').removeClass('hidden');
						} else {
							$('#J_cancel_btn').addClass('hidden');
						}
						if (data.refund_status == 5 || data.refund_status == 8) {
							$('.refund-info').removeClass('hidden');
							$('.J_order_refund_price').html(data.refund_rmb.toFixed(2))
						} else {
							$('.refund-info').addClass('hidden');
						}
						// 订单信息
						$('#J_done_detail .order-info').html(juicer(tpl_order, data.original_order_info.items));
						$('#J_done_detail .J_coupon_price').html(data.original_order_info.coupon_rmb.toFixed(2));
						$('#J_done_detail .J_items_price').html(data.original_order_info.items_fee.toFixed(2));
						$('#J_done_detail .J_refund_price').html(data.original_order_info.refund_rmb.toFixed(2));
						$('#J_done_detail .J_total_price').html(data.original_order_info.total_fee.toFixed(2));

						$('#J_done_detail .J_refund_statu').html(stat);
						$('#J_done_detail .J_customer_account').html(data.seller.account);
						$('#J_done_detail .J_customer_mobile').html(data.customer.receive_mobile);
						$('#J_done_detail .J_customer_email').html(data.seller.email);
						$('#J_done_detail .J_customer_operater').html(data.seller.carrieroperator);
						$('#J_done_detail .J_customer_area').html(data.seller.area);
						$('#J_done_detail .J_refund_reason').html(data.reason);
						if (data.seller.headerImg == '') {
							$('#J_done_detail .J_customer_header').attr('src', '/images/ensurance/icon1.png');
						} else {
							$('#J_done_detail .J_customer_header').attr('src', data.seller.headerImg);
						}
						var attrs = '';
						for (var i = 0; i < data.order_info.attributes.length; i++) {
							attrs += data.order_info.attributes[i].attribute + '：' + data.order_info.attributes[i].option
						}
						$('#J_done_detail .J_pro_img').attr('src', data.order_info.image);
						$('#J_done_detail .J_pro_title').html(data.order_info.title);
						$('#J_done_detail .J_pro_attr').html(attrs);
						$('#J_done_detail .J_pro_price').html(data.order_info.price.toFixed(2));
						$('#J_done_detail .J_pro_count').html(data.order_info.count);
						$('#J_done_detail .J_pro_total').html((data.order_info.count * data.order_info.price).toFixed(2));
						var list = '';
						list += '<li><span class="time text-success">' + data.create_time + '</span>申请售后</li>';
						if (data.service_agree_time != '') {
							list += '<li><span class="time text-success">' + data.service_agree_time + '</span>客服审核</li>';
						}
						if (data.service_reject_time != '') {
							list += '<li><span class="time text-danger">' + data.service_reject_time + '</span>客服驳回</li>';
						}
						if (data.supply_agree_time != '') {
							list += '<li><span class="time text-success">' + data.supply_agree_time + '</span>厂家审核</li>';
						}
						if (data.customer_send_back_time != '') {
							list += '<li><span class="time text-success">' + data.customer_send_back_time + '</span>退回中&nbsp;&nbsp;物流公司为：' + data.customer.shipping_company + ' 物流单号为：' + data.customer.shipping_code + '</li>';
						}
						if (data.supply_refund_money_time != '') {
							list += '<li><span class="time text-success">' + data.supply_refund_money_time + '</span>已退款</li>';
						}
						if (data.supply_refund_send_time != '') {
							list += '<li><span class="time text-success">' + data.supply_refund_send_time + '</span>换货中&nbsp;&nbsp;物流公司为：' + data.supply.shipping_company + ' 物流单号为：' + data.supply.shipping_code + '</li>';
						}
						if (data.finished_time != '') {
							list += '<li><span class="time text-success">' + data.finished_time + '</span>换货结束</li>';
						}
						if (data.cancel_time != '') {
							list += '<li><span class="time text-success">' + data.cancel_time + '</span>已取消</li>';
						}
						$('#J_edit_log').html(list);
						var imgs = '';
						for (var i = 0; i < data.customer_image.length; i++) {
							imgs += '<li><a href="' + data.customer_image[i].path + '" data-lightbox="unique-mark0" target="_blank"><img src="' + data.customer_image[i].path + '" class="img-responsive"></a></li>'
						}
						$('#J_done_detail .J_refund_img').html(imgs);
						var service_img = juicer(tpl_service, data);
						$('#J_done_detail .J_service_box').html(service_img);
						refreshScroll()
					} else if (status == 8) {
						// 确认退款详情
						$('#J_money_detail').removeClass('hidden');
						var _maxP = 0;
						if (data.order_info.count * data.order_info.price <= (data.original_order_info.total_fee - data.apply_refund_rmb)) {
							_maxP = (data.order_info.count * data.order_info.price).toFixed(2)
						} else {
							_maxP = parseFloat(data.original_order_info.total_fee - data.apply_refund_rmb).toFixed(2)
						}
						$('#J_money_detail .J_max_price').html(_maxP).data('price', _maxP);
						$('#J_money_detail .J_want_price').html(data.refund_rmb.toFixed(2)).data('price', data.refund_rmb.toFixed(2));
						$('#J_money_end_input').val(data.refund_rmb.toFixed(2));
						if (data.customization == 1) {
							$('input#refund').parent('label').addClass('hidden');
						} else {
							$('input#refund').parent('label').removeClass('hidden');
						}
						if (data.seller.headerImg == '') {
							$('#J_money_detail .J_customer_header').attr('src', '/images/ensurance/icon1.png');
						} else {
							$('#J_money_detail .J_customer_header').attr('src', data.seller.headerImg);
						}
						// 订单信息
						$('#J_money_detail .order-info').html(juicer(tpl_order, data.original_order_info.items));
						$('#J_money_detail .J_coupon_price').html(data.original_order_info.coupon_rmb.toFixed(2));
						$('#J_money_detail .J_items_price').html(data.original_order_info.items_fee.toFixed(2));
						$('#J_money_detail .J_refund_price').html(data.original_order_info.refund_rmb.toFixed(2));
						$('#J_money_detail .J_total_price').html(data.original_order_info.total_fee.toFixed(2));

						$('#J_money_detail .J_customer_account').html(data.seller.account);
						$('#J_money_detail .J_customer_mobile').html(data.customer.receive_mobile);
						$('#J_money_detail .J_customer_email').html(data.seller.email);
						$('#J_money_detail .J_customer_operater').html(data.seller.carrieroperator);
						$('#J_money_detail .J_customer_area').html(data.seller.area);
						$('#J_money_detail .J_pro_img').attr('src', data.order_info.image);
						$('#J_money_detail .J_pro_title').html(data.order_info.title);
						var attrs = '';
						for (var i = 0; i < data.order_info.attributes.length; i++) {
							attrs += data.order_info.attributes[i].attribute + '：' + data.order_info.attributes[i].option
						}
						$('#J_money_detail .J_pro_attr').html(attrs);
						$('#J_money_detail .J_pro_price').html(data.order_info.price.toFixed(2));
						$('#J_money_detail .J_pro_count').html(data.order_info.count);
						$('#J_money_detail .J_pro_total').html((data.order_info.count * data.order_info.price).toFixed(2));
						$('#J_money_detail .J_supply_company').html(data.supply.brand_name);
						$('#J_money_detail .J_supply_name').html(data.supply.company_name);
						$('#J_money_detail .J_supply_mobile').html(data.supply.mobile);
						$('#J_money_detail .J_supply_telephone').html(data.supply.telephone);
						$('#J_money_detail .J_supply_address').html(data.supply.address);
						$('#J_money_detail .J_refund_reason').html(data.reason);
						var imgs = '';
						for (var i = 0; i < data.customer_image.length; i++) {
							imgs += '<li><a href="' + data.customer_image[i].path + '" data-lightbox="unique-mark0" target="_blank"><img src="' + data.customer_image[i].path + '" class="img-responsive"></a></li>'
						}
						$('#J_money_detail .J_refund_img').html(imgs);
						refreshScroll()
					}
				})
			})
		})
	}
	getRfundList(1, 20, 0)
	//排序调整
	$('.J_date_sort').on('click', function() {
		$(this).addClass('active').siblings('.J_date_sort').removeClass('active');
		var sort = $(this).data('sort');
		var status = $('#J_tab_box li[class*="active"]').data('status');
		getRfundList(1, 20, status, sort);
	})
	//搜索
	$('#J_todo_search_btn').on('click', function() {
		var val = $(this).siblings('input').val().trim();
		getRfundList(1, 20, 0, 0, val);
	})
	$('#J_done_search_btn').on('click', function() {
		var val = $(this).siblings('input').val().trim();
		var status = $('#J_tab_box li[class*="active"]').data('status');
		getRfundList(1, 20, status, 0, val);
	})
	$('input.search').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$(this).siblings('span').click();
		}
	})

	//获取上传文件后缀
    function getSuffix(filename) {
        var pos = filename.lastIndexOf('.');
        var suffix = '';
        if (pos != -1) {
            suffix = filename.substring(pos + 1)
        }
        return suffix;
    }
    //配置上传参数
    function setUpParam($target ,data) {
    	var formData = new FormData();
    	$.each(data, function(i, n) {
    		formData.append(i, n)
    	})
    	formData.append('file', $target[0].files[0]);
    	return formData;
    }
    //取得上传回调
    function uploadImg(obj, formData, callback) {
    	$.ajax({
    		url: obj.host,
		    type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function(data) {
            callback(data);
        })
        .fail(function() {
            setTimeout(function() {
                upload_img(obj, formData);
            }, 1000)
        })
    }
	//上传图片
	$('#upload_service_img').on('change', function() {
		if ($('#J_service_img img').length > 8) {
			alert('最多上传9张图片！')
			return
		}
		var $this = $(this);
    	var imgName = $(this).val();
    	if (imgName === '') {
    		return false
    	}
    	var suffix = getSuffix(imgName);
    	//上传成功回调处理
    	function succesCB(data) {
    		if (data.status == 200) {
    			var img = '<label class="img-upload-box">\
                                <img src="' + data.data.url + '" data-filename="' + data.data.filename + '">\
                                <span class="close">&times;</span>\
                            </label>';
                $('#J_service_img').append(img);
    		} else {
    			$('#J_alert_content').html(data.data.errMsg);
    			$('#apxModalAdminAlert').modal('show');
    		}
			$('#upload_service_img').val('');
			if ($('#J_service_img img').length > 5) {
				refreshScroll()
			}
    	}
    	//请求OSS回调
    	requestUrl('/site/carousel/get-oss-permission', 'GET', {file_suffix: suffix}, function(data) {
    		var formData = setUpParam($this, data);
    		uploadImg(data, formData, succesCB);
    	}, function(data) {
			$('#upload_service_img').val('');
    		$('#J_alert_content').html('图片不符合规范，请确认后提交！');
    		$('#apxModalAdminAlert').modal('show');
    	})
	})
	//删除图片
	$('#J_service_img').on('click', '.close', function() {
		$(this).parent('label').remove()
	})
	$('#J_service_add_img').on('click', '.close', function() {
		$(this).parent('label').remove()
	})
	//客服操作
	$('#J_edit_sure').on('click', function() {
		var imgs = [];
		for (var i = 0; i < $('#J_service_img img').length; i++) {
			imgs.push($('#J_service_img img').eq(i).data('filename'))
		}
		if (imgs.length > 0 && $('#advice').val().trim() == '') {
			$('#apxModalAdminConfrimEdit').modal('hide');
			$('#J_alert_content').html('请添加文字说明！');
    		$('#apxModalAdminAlert').modal('show');
			return
		}
		var type = $('input[name="refund_type"]:checked').val();
		if (type == undefined) {
			$('#apxModalAdminConfrimEdit').modal('hide');
			$('#J_alert_content').html('请选择操作！');
    		$('#apxModalAdminAlert').modal('show');
			return
		}
		var data = {
			refund_code: $('#J_todo_edit').data('no'),
			reason: $('#advice').val().trim() || '',
			type: type,
			images: imgs
		}
		var _rmb = $('#J_money_input').val();
		if (type == 2) {
			data.refund_rmb = _rmb;
			if (_rmb == '') {
				alert('请输入退款金额');
				return
			}
			if (_rmb.toString().search(/[^0-9.]/) != -1) {
				alert('请输入数字！')
				return
			}
			if (_rmb > parseFloat($('#J_todo_detail .J_max_price').data('price'))) {
				alert('退款金额不能大于最大可退金额！')
				return
			}
		}
		$('#apxModalAdminConfrimEdit').modal('hide');
		requestUrl('/service/refund/check-order', 'POST', data, function(data) {
			$('#J_alert_content').html('操作成功！');
    		$('#apxModalAdminAlert').modal('show');
    		getRfundList(1, 20, 0)
		})
	})
	$('#J_money_sure').on('click', function() {
		var _rmb = $('#J_money_end_input').val();
		var data = {
			refund_code: $('#J_money_edit').data('no'),
			refund_rmb: _rmb
		}
		if (_rmb == '') {
			alert('请输入退款金额');
			return
		}
		if (_rmb.toString().search(/[^0-9.]/) != -1) {
			alert('请输入数字！')
			return
		}
		if (_rmb > parseFloat($('#J_money_detail .J_max_price').data('price'))) {
			alert('退款金额不能大于最大可退金额！')
			return
		}
		$('#apxModalAdminConfrimEdit2').modal('hide');
		requestUrl('/service/refund/refund-rmb', 'POST', data, function (data) {
			$('#J_alert_content').html('操作成功！');
			$('#apxModalAdminAlert').modal('show');
			getRfundList(1, 20, 8)
		})
	})
	//追加备注
	$('#J_edit_add_sure').on('click', function() {
		var code = $('#J_done_edit').data('no');
		var reason = $('#J_service_remark').val().trim();
		var img = [];
		for (var i = 0; i < $('#J_service_add_img img').length; i++) {
			img.push($('#J_service_add_img img').eq(i).data('filename'))
		}
		if (img.length > 0 && reason == '') {
			$('#J_alert_content').html('请添加文字说明！');
    		$('#apxModalAdminAlert').modal('show');
			return
		}
		$('#apxModalAdminConfrimAddMsg').modal('hide');
		requestUrl('/service/refund/add-comments', 'POST', {refund_code: code, reason: reason, images: img}, function(data) {
			$('#J_alert_content').html('添加成功');
			$('#apxModalAdminAlert').modal('show');
			$('#J_done_list .J_user[class*="active"]').click();
		})
	})
	//上传图片
	$('#upload_service_add_img').on('change', function() {
		if ($('#J_service_add_img img').length > 8) {
			$('#J_alert_content').html('最多上传9张图片！');
    		$('#apxModalAdminAlert').modal('show');
			return
		}
		var $this = $(this);
    	var imgName = $(this).val();
    	if (imgName === '') {
    		return false
    	}
    	var suffix = getSuffix(imgName);
    	//上传成功回调处理
    	function succesCB(data) {
    		if (data.status == 200) {
    			var img = '<label class="img-upload-box">\
                                <img src="' + data.data.url + '" data-filename="' + data.data.filename + '">\
                                <span class="close">&times;</span>\
                            </label>';
                $('#J_service_add_img').append(img);
    		} else {
    			$('#J_alert_content').html(data.data.errMsg);
    			$('#apxModalAdminAlert').modal('show');
    		}
    		$('#upload_service_add_img').val('');
    	}
    	//请求OSS回调
    	requestUrl('/site/carousel/get-oss-permission', 'GET', {file_suffix: suffix}, function(data) {
    		var formData = setUpParam($this, data);
    		uploadImg(data, formData, succesCB);
    	}, function(data) {
			$('#upload_service_img').val('');
    		$('#J_alert_content').html('图片不符合规范，请确认后提交！');
    		$('#apxModalAdminAlert').modal('show');
    	})
	})
	$('#apxModalAdminConfrimAddMsg').on('show.bs.modal', function() {
		$('#J_service_remark').val('');
		$('#J_service_add_img').html('')
	})
	$('.J_data_refresh').on('click', function() {
		var status = $('#J_tab_box li[class*="active"]').data('status');
		var sort = $(this).siblings('.J_date_sort[class*="active"]').data('sort');
		$(this).html('刷新中');
		getRfundList(1, 20, status, sort);
	})

	// 取消售后
	$('#J_cancel_sure').on('click', function(e) {
		var code = $('#J_done_edit').data('no');
		var cancel_reason = $('#J_cancel_reason').val();
		if (cancel_reason == '') {
			alert('请填写取消原因！')
			return
		}
		var _target = $('#J_tab_box li[class*="active"]').data('status');
		if ($('#J_cancel_confirm').hasClass('hidden')) {
			$(this).siblings().after($(this)[0]);
			$('#J_cancel_confirm').removeClass('hidden');
			$('#J_cancel_sure').html('再次确认');
			return
		}
		var _data = {
			refund_code: code,
			cancel_reason: cancel_reason
		}
		requestUrl('/service/refund/cancel-refund', 'POST', _data, function(data) {
			getRfundList(1, 20, _target);
		})
		$('#apxModalAdminConfrimCancelRefund').modal('hide');
	})
	$('#apxModalAdminConfrimCancelRefund').on('hidden.bs.modal', function() {
		$('#J_cancel_sure').siblings().before($('#J_cancel_sure')[0]);
		$('#J_cancel_reason').val('');
		$('#J_cancel_sure').html('确认');
		$('#J_cancel_confirm').addClass('hidden');
	})
	// 帮门店提交物流信息
	var storagedData = {}
	$('.choose-express > input').on('click', function () {
		var _this = this;
		apex.apexExpressDropDownList.initDropDownListWidthSearch($(this).siblings('div')[0], function (data) {
			$(_this).siblings('select').html('<option>' + data.name + '</option>')
			storagedData.expressIdForSearching = data.id
			apex.apexExpressDropDownList.removeDropDownList()
		})
	})
	$('#J_submit_express').on('click', function() {
		var id = storagedData.expressIdForSearching;
		var shipping_code = $('#J_express_no').val();
		if (!id) {
			alert('请选择物流公司！')
			return
		}
		if (shipping_code == '') {
			alert('请填写物流单号！')
			return
		}
		var _data = {
			refund_code: $('#J_done_edit').data('no'),
			express_corporation_id: id,
			shipping_code: shipping_code
		}
		requestUrl('/service/refund/install-custom-sending', 'POST', _data, function(data) {
			getRfundList(1, 20, '-2');
		})
	})
})