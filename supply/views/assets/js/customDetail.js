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
$(function(){

    g = {
        remark: '',
        express: [],
        expressId: ''
    }

    function show(title) {
        $('#customizationAlert .J_confrim_content').html(title);
        $('#customizationAlert').modal('show');
    }
    // 提交备注
    function submitRemark(val) {
        requestUrl('/custom/note', 'POST', {order_number: url('?order_number'), text: val}, function(data) {
            window.location.reload()
        })
    }
    // 公共confirm
    $('#customizationConfirm').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        $('#customizationConfirm .J_confrim_content span').html($this.data('title'));
        var _val = $('#J_supply_remark').val();
        if ($this.data('title') == '接单') {
            $('#J_sure_btn').off().on('click', function() {
                $('#customizationConfirm').modal('hide')
                requestUrl('/custom/hold', 'POST', {order_number: url('?order_number')}, function(data) {
                    window.location.reload()
                })
                if (_val !== g.remark) {
                    submitRemark(_val)
                }
            })
        }
        if ($this.data('title') == '拒绝') {
            $('#J_sure_btn').off().on('click', function() {
                $('#customizationConfirm').modal('hide')
                if (_val == '') {
                    show('请在备注处说明原因！')
                    return
                }
                requestUrl('/custom/reject', 'POST', {order_number: url('?order_number'), 'text': _val}, function(data) {
                    window.location.reload()
                })
            })
        }
        if ($this.data('title') == '提交备注') {
            $('#J_sure_btn').off().on('click', function() {
                $('#customizationConfirm').modal('hide');
                var val = $('#J_supply_remark').val();
                submitRemark(val)
            })
        }
    })


    //获取定制订单详情
    function getOrderInfo(no) {
    	requestUrl('/custom/one', 'GET', {order_number: no}, function(data) {
    		// 订单详情
    		var info = data.order;
    		var _status = ['', '未上传', '未处理', '生产中', '已发货', '已拒绝', '已取消'];
    		$('.J_order_status').html(_status[info.status]);
    		$('#J_info_img').attr('src', info.items[0].image);
    		var _attrs = '';
    		$.each(info.items[0].attributes, function(i, v) {
    			_attrs += v.attribute + '：' + v.option + '；'
    		})
    		$('#J_info_attr').html(_attrs);
    		$('#J_info_price').html(info.items[0].price.toFixed(2));
    		$('#J_info_no').html(info.order_no);
    		$('#J_create_time').html(info.create_time);
    		$('#J_pay_time').html(info.pay_time);
    		$('#J_info_name').html(info.consignee);
    		$('#J_info_mobile').html(info.mobile);
    		$('#J_info_address').html(info.address);
    		$('#J_info_remark').html(info.items[0].comments);
            if (info.express_corporation) {
                $('.express').removeClass('hidden');
                $('#J_express_name').html(info.express_corporation);
                $('#J_express_code').html(info.express_number);
            }
            if (info.coupon_rmb > 0) {
                $('#J_coupon_price').html(parseFloat(info.coupon_rmb).toFixed(2) + '元（满' + info.coupon_info.consumption_limit + '元使用）');
            }
            $('#J_refund_price').html(parseFloat(info.refund_rmb).toFixed(2));
            // 支付方式
            $('#J_pay_ment').html(info.pay_method);

    		// 定制信息
    		var custom = data.custom;
    		$('#J_car_brand').html(custom.carBrandName);
    		$('#J_car_type').html(custom.carTypeName);
    		$('#J_upload_time').html(custom.upload_date);
    		$('#J_edit_time').html(custom.update_date);
    		for (var i = 0; i < data.notes.length; i++) {
    			if (data.notes[i].type == 1) {
    				$('#J_customer_remark').html(data.notes[i].text);
	    			break;
    			}
    		}
    		for (var i = 0; i < data.supplyNotes.length; i++) {
    			if (data.supplyNotes[i].type == 2) {
    				$('#J_supply_remark').val(data.supplyNotes[i].text).keyup();
                    g.remark = data.supplyNotes[i].text;
	    			break;
    			}
    		}

    		// 定制图片
    		var _imgs = '',
    			host = data.upload_url;
    		$.each(data.pics, function(i ,v) {
    			_imgs += '<div class="item">\
                            <a href="' + host + '/' + v.filename + '" data-lightbox="unique-mark1"><img src="' + host + '/' + v.filename + '" alt=""></a>\
                            <div class="item-hover">\
                                <p>点击预览</p>\
                            </div>\
                        </div> '
    		})
    		$('#J_img_box').html(_imgs);

    		// 按钮状态
            if (info.status == 2) {
                $('#J_reject').removeClass('hidden')
            } else if (info.status == 3) {
                $('#J_over').removeClass('hidden')
            } else {
                $('.footer-box .remark').removeClass('hidden')
            }
    	})
    }
    getOrderInfo(url('?order_number'))

    // 监听输入长度
    $('#J_supply_remark').on('keyup', function() {
        var len = $(this).val().length;
        $('.input-last').html($(this).attr('maxLength') - len);
    })

   // 发货
    $('#customizationOrder').on('show.bs.modal', function(e) {
      $(this).find('.choose-express > input').on('click', function () {
        var _this = this;

        apex.apexExpressDropDownList.initDropDownList($(this).siblings('div')[0], function (data) {
          $(_this).siblings('select').html('<option>' + data.name + '</option>')
          g.expressId = data.id
          apex.apexExpressDropDownList.removeDropDownList()
        })
      })
    })
    $('#J_shipments').on('click', function() {
        var id = g.expressId
        var code = $('#express_code').val().trim();

        if (!id) {
            show('请选择物流公司')
            return
        }

        if (code === '' || /^\s*$/.test(code)) {
            show('请填写物流单号！')
            return
        }
        requestUrl('/custom/ship', 'POST', {order_number: url('?order_number'), express_corporation: Number(id), express_number: code}, function(data) {
            window.location.reload()
        })
    })
})
