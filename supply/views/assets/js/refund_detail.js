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
	//获取售后单详情
	requestUrl('/refund/get-refund-order-info', 'GET', {refund_code: url("?code")}, function(data) {
		$('.J_refund_no').html(data.refund_code);
		$('.J_order_no').html(data.order_code);
		getOrderinf(data.order_code);
		$('.J_refund_price').html(parseFloat(data.refund_rmb).toFixed(2));
		if (data.refund_status == 1) {
			$('#J_new_refund').removeClass('hidden');
			if (data.refund_type == 1) {
				$('#J_new_refund .J_refund_status').html('待审核（换货）');
				$('.J_tip_title').html('同意对方换货申请，收到货后立即给对方发货');
			} else if (data.refund_type == 2) {
				$('#J_new_refund .J_refund_status').html('待审核（退货）');
				$('.J_tip_title').html('同意对方退货申请，确认收到货后系统将自动退款');
			}
			$('#J_new_refund .J_refund_address').html('地址：' + data.supply.address);
			$('#J_new_refund .J_refund_name').html('收件人：' + data.supply.company_name);
			$('#J_new_refund .J_refund_mobile').html('联系电话：' + data.supply.mobile);
			$('#J_sure_order').on('click', function() {
				var yes = confirm('确定同意退换？');
				if (!yes) return;
				requestUrl('/refund/agree-custom-refund', 'POST', {refund_code: data.refund_code}, function(data) {
					window.location.reload()
				})
			})
		}
		if (data.refund_status == 3) {
			$('#J_refund_pass').removeClass('hidden');
			if (data.refund_type == 1) {
				$('#J_refund_pass .J_refund_status').html('已过审（换货）');
			} else if (data.refund_type == 2) {
				$('#J_refund_pass .J_refund_status').html('已过审（退货）');
				$('.refund_box').removeClass('hidden');
			}
		}
		if (data.refund_status == 4) {
			if (data.refund_type == 1) {
				$('#J_exchange_underway').removeClass('hidden');
				$('#J_exchange_underway .J_refund_status').html('退回中（换货）');
				$('#J_exchange_underway .J_refund_address').html('地址：' + data.supply.address);
				$('#J_exchange_underway .J_refund_name').html('收件人：' + data.supply.company_name);
				$('#J_exchange_underway .J_refund_mobile').html('联系电话：' + data.supply.mobile);
				$('#J_exchange_underway .J_refund_telephone').html('联系电话：' + data.supply.telephone);
				$('#J_exchange_underway .J_shipping_company').html('物流公司：' + data.customer.shipping_company);
				$('#J_exchange_underway .J_shipping_code').html('物流单号：' + data.customer.shipping_code);
				$('.J_customer_name').html(data.customer.receive_consignee);
				$('.J_customer_mobile').html(data.customer.receive_mobile);
				$('.J_customer_address').html(data.customer.receive_address);
				var api = '';
				requestUrl('/api-hostname', 'GET', '', function(data) {
					api = data.hostname;
					requestUrl(api + '/express/get-company', 'GET', '', function(data) {
						var express = ''
						for (var i = 0; i < data.length; i++) {
							express += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
						}
						$('#J_express_list').append(express);
					})
				})
				$('#J_sure_send').on('click', function() {
					var id = $('#J_express_list').val();
					var no = $('#J_express_no').val();
					if (id == -1) {
						alert('请选择快递公司！');
						return
					}
					if (no == '') {
						alert('请输入快递单号！');
						return
					}
					var yes = confirm('确定提交？');
					if (!yes) return;
					requestUrl('/refund/supply-send-back', 'POST', {refund_code: data.refund_code, shipping_code: no, shipping_company: id}, function(data) {
						window.location.reload()
					})
				})
			} else if (data.refund_type == 2) {
				$('#J_refund_underway').removeClass('hidden');
				$('#J_refund_underway .J_refund_status').html('退回中（退货）');
				$('#J_refund_underway .J_refund_address').html('地址：' + data.supply.address);
				$('#J_refund_underway .J_refund_name').html('收件人：' + data.supply.company_name);
				$('#J_refund_underway .J_refund_mobile').html('联系电话：' + data.supply.mobile);
				$('#J_refund_underway .J_refund_telephone').html('固定电话：' + data.supply.telephone);
				$('#J_refund_underway .J_customer_company').html(data.customer.shipping_company);
				$('#J_refund_underway .J_customer_code').html(data.customer.shipping_code);
				$('.J_customer_name').html(data.customer.receive_consignee);
				$('.J_customer_mobile').html(data.customer.receive_mobile);
				$('.J_customer_address').html(data.customer.receive_address);
				$('#J_sure_refund_over').on('click', function() {
					var yes = confirm('确认收货？');
					if (!yes) return;
					requestUrl('/refund/agree-refund-money', 'POST', {refund_code: data.refund_code}, function(data) {
						window.location.reload()
					})
				})
			}
		}
		if (data.refund_status == 5) {
			$('#J_finished_refund').removeClass('hidden');
			$('#J_finished_refund .J_refund_status').html('已退款');
			$('#J_finished_refund .J_shipping_company').html(data.customer.shipping_company);
			$('#J_finished_refund .J_shipping_code').html(data.customer.shipping_code);
		}
		if (data.refund_status == 6) {
			$('#J_finished_underway').removeClass('hidden');
			$('#J_finished_underway .J_refund_status').html('换货中');
			$('.J_customer_name').html(data.customer.receive_consignee);
			$('.J_customer_mobile').html(data.customer.receive_mobile);
			$('.J_customer_address').html(data.customer.receive_address);
			$('.J_customer_company').html(data.supply.shipping_company);
			$('.J_customer_code').html(data.supply.shipping_code);
		}
		if (data.refund_status == 7) {
			$('#J_exchange_finished').removeClass('hidden');
			$('#J_exchange_finished .J_refund_status').html('已换货');
			$('.J_customer_name').html(data.customer.receive_consignee);
			$('.J_customer_mobile').html(data.customer.receive_mobile);
			$('.J_customer_address').html(data.customer.receive_address);
			$('.J_customer_company').html(data.supply.shipping_company);
			$('.J_customer_code').html(data.supply.shipping_code);
		}
		if (data.refund_status == 8) {
			$('#J_sure_refund').removeClass('hidden');
			$('#J_sure_refund .J_refund_status').html('确认退款');
			$('#J_sure_refund .J_shipping_company').html(data.customer.shipping_company);
			$('#J_sure_refund .J_shipping_code').html(data.customer.shipping_code);
		}
		if (data.refund_status == 9) {
			$('#J_refund_cancel').removeClass('hidden');
			$('#J_refund_cancel .J_refund_status').html('已取消');
			$('.J_refund_reason').html(data.cancel_reason);
		}
		//操作日志
		var logs = '<li><span class="time">' + data.service_agree_time + '</span>我们已核实过用户的售后请求，信息准确无误，请厂家尽快确认此(退货/换货)请求 <small class="high-lighted">Ps: 审核通过后，用户会将问题商品寄回厂家(运费由厂家承担)</small></li>';
		if (data.supply_agree_time != '') {
			logs += '<li><span class="time">' + data.supply_agree_time + '</span>此售后请求已通过审核，请耐心等待买家退回商品。</li>';
		}
		if (data.customer_send_back_time != '') {
			logs += '<li><span class="time">' + data.customer_send_back_time + '</span>问题商品已在退回途中，请耐心等待。</li>';
		}
		if (data.supply_refund_money_time != '') {
			logs += '<li><span class="time">' + data.supply_refund_money_time + '</span>此退货已经将商品金额退回用户账户。</li>';
		}
		if (data.supply_refund_send_time != '') {
			logs += '<li><span class="time">' + data.supply_refund_send_time + '</span>商品已寄出，请耐心等待用户确认收货。</li>';
		}
		if (data.supply_receive_confirm_time != '') {
			logs += '<li><span class="time">' + data.supply_receive_confirm_time + '</span>您已确认退款，客服确认后，退款金额将会直接退回至客户账户！</li>';
		}
		if (data.finished_time != '') {
			if (data.refund_type == 1) {
				logs += '<li><span class="time">' + data.finished_time + '</span>买家已确认收到换货商品，换货流程已结束。</li>';
			} else {
				logs += '<li><span class="time">' + data.finished_time + '</span>买家收到退款，退货流程已结束。</li>';
			}
		}
		if (data.cancel_time != '') {
			logs += '<li><span class="time">' + data.cancel_time + '</span>由于特殊原因，我们已取消了此次售后，如有疑问可咨询我们的客服</li>';
		}
		$('.J_refund_log').html(logs);
		var img = '';
		for (var i = 0; i < data.customer_image.length; i++) {
			img += '<li>\
                        <a href="' + data.customer_image[i].path + '" data-lightbox="unique-mark0" target="_blank"><img src="' + data.customer_image[i].path + '"></a>\
                    </li>'
		}
		$('#J_history_msg .J_img_list').html(img);
		$('#J_history_msg .J_reason').html(data.reason);
		//获取退货商品信息
		$('#J_refund_product .J_refund_img').attr('src', data.order_info.image);
		$('#J_refund_product .J_refund_title').html(data.order_info.title);
		var attrs = ''
		for (var i = 0; i < data.order_info.attributes.length; i++) {
			attrs += '<li>' + data.order_info.attributes[i].attribute + '：' + data.order_info.attributes[i].option + '</li>'
		}
		$('#J_refund_product .J_refund_attrs').html(attrs);
		$('#J_refund_product .J_refund_count').html(data.order_info.count);
		$('#J_refund_product .J_item_price').html((data.order_info.price - 0).toFixed(2));
		$('#J_refund_product .J_refund_total').html((data.order_info.price * data.order_info.count).toFixed(2));
		//客服反馈
		var tpl = $('#J_tpl_service').html();
		var html = juicer(tpl, data);
		$('#J_service_msg').html(html);


		//获取关联订单信息
		function ju_price(data) {
			return parseFloat(data).toFixed(2)
		}
		juicer.register('ju_price', ju_price);
		function getOrderinf (order_code) {
			var tpl = $('#J_tpl_orders').html();
			requestUrl('/order/get-detail','GET',{order_id:order_code},function (data) {
				$('#J_orders_info').prepend(juicer(tpl, data.items));
				$('#J_items_count').html(data.items.length);
				$('.J_items_fee').html('¥' + data.items_fee.toFixed(2));
				$('.J_total_fee').html('¥' +data.total_fee.toFixed(2));
				$('.J_coupon_price').html('¥' +data.coupon_rmb.toFixed(2));
				$('.J_order_refund_price').html('¥' +data.refund_rmb.toFixed(2));
			})
		}
	})













})