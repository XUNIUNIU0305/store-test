$(function(){
	//item detail gallary config and lazy load
    $('.carousel-stage').jcarousel({
        // Configuration goes here
    }).on('jcarousel:targetin', 'li', function(event, carousel) {
        //Triggered when the item becomes the targeted item.
        // lazyload
        var ele = $(this).find("img");
        var src_cur = ele.attr("ssrc");
        ele.attr("src", src_cur);
    });
    // gallary photo override
    $("[gallary-override] > *").each(function() {
        var ele = $(this),
            target = $(".detail-gallary-override");
        var arg = ele.parent('[gallary-override]').attr("gallary-override"),//override the gallary photo or not
            src_400 = ele.find("img").attr("src");
        ele.on("click", function() {
            if (arg == "true") {
                target.find("img").attr("src",src_400.replace("40x40","400x400"));
                target.show();
            }
            else target.hide();
        });
    });
    $('.J_edit_product_order').on('click', function (e) {
        $(this).parents('.apx-item-edit').addClass('edit-order');
    })
    // disable the disabled button
    $(document).on('click', '.btn.disabled', function(e){
        e.preventDefault();
        e.stopPropagation();
    })
    // kindeditor 
    window.KindEditor && KindEditor.ready(function(K) {
        // for pc
        window.editor = K.create('#apx_editor',{
            items: [
                'source', 'preview', '|', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                'flash', 'emoticons', 'link'
            ]
        });
        // for mobile
        K.create('#apx_editor_mobile',{
            items: [
                'source', 'preview', '|', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                'flash', 'emoticons', 'link'
            ]
        });
    });
    $('.J_acc_expand').on('click', function() {
        $('.apx-acc-aside').toggleClass('acc_expand');
    })

    // 移动端禁止首页商品自动轮播
    var ua  = navigator.userAgent.toLowerCase();
    if(ua.indexOf('android') != -1 || ua.indexOf('iphone') != -1 || ua.indexOf('ipad') != -1){
        $('.apx-index-section .carousel').carousel({
            interval: 999999999
        });
    }
    
    // 购物车按钮
    // function for setting the html template of the cart details
    function setCartContent(item, overflow) {
        //hide the "You have xx more items" when the num is 0
        if (overflow) {
            var _overflowStr = '<div class="mini-cart-hint cf">\
                                    <p class="fll">\
                                        You have <em>' + overflow + '</em>more items</p>\
                                    <p class="flr">\
                                        Total: <em>S$234.23</em>\
                                    </p>\
                                </div>';
        }
        else _overflowStr = '';
        var _item = '';
        var total_price = 0;
        $.each(item, function(i, data) {
            _item += '<li class="mini-cart-item clearfix">\
                        <div class="pull-left">\
                            <img src="' + data.cart_p_image + '" class="img-responsive">\
                        </div>\
                        <div class="pull-right text-right">\
                            <p><strong>¥' + '' + data.cart_p_exchange_price + '</strong>' + '×' + '<strong>' + data.cart_p_exchange_ammount + '</strong>' + '</p>\
                            <a href="#" class="J_cart_delete" cart_id="' + data.cart_id + '">删除</a>\
                        </div>\
                        <div class="mini-cart-item-detail">\
                            <p><a href="#">' + data.cart_p_name + '</a></p>\
                            <span>' + data.cart_p_attr + '</span>\
                        </div>\
                    <li>';
            total_price += (data.cart_p_exchange_price - 0);
        });

        //return the whole cart list string
        return  '<h5>最近加入的商品</h5>' +
                '<div class="mini-cart-scroll">' +
                    '<ul class="list-unstyled mini-cart">' +
                        _item +
                    '</ul>' +
                '</div>' +
                '<div class="mini-cart-footer clearfix">' +
                    '<span class="mini-cart-total-ammount">共<strong>' + item.length + '</strong>件商品</span>' +
                    '<span class="mini-cart-total-price">总计<strong>¥' + total_price.toFixed(2) + '</strong></span>' +
                    '<a class="btn btn-warning btn-sm pull-right" href="#">去购物车</a>' +
                '</div>';
    };

    // mockup data
    var cart_item = [
        {
            cart_p_image: '../images/slider02.jpg',
            cart_p_name: '月卡家用洗车器，园艺浇花水枪水管',
            cart_p_attr: '( 20米 )',
            cart_p_exchange_price: '99.90',
            cart_p_exchange_ammount: '1',
            cart_id: 'mock_1'
        },
        {
            cart_p_image: '../images/slider02.jpg',
            cart_p_name: '月卡家用洗车器，园艺浇花水枪水管',
            cart_p_attr: '( 20米 )',
            cart_p_exchange_price: '99.90',
            cart_p_exchange_ammount: '1',
            cart_id: 'mock_1'
        },
        {
            cart_p_image: '../images/slider02.jpg',
            cart_p_name: '月卡家用洗车器，园艺浇花水枪水管',
            cart_p_attr: '( 20米 )',
            cart_p_exchange_price: '99.90',
            cart_p_exchange_ammount: '1',
            cart_id: 'mock_1'
        }
    ]; 
    var cart_timer; // timer

    function cartPopover() {
	    $('[data-toggle="popover_cart"]')
	    .one('touchstart.show_cart mouseenter.show_cart', function(e) {
	        var ele = $(this);
	        // ele.addClass("active");
	        var cart_overflow = cart_item.length > 5 ? (cart_item.length - 5) : 0; //超出商品显示限制条数
	        ele.popover({
	            trigger: 'manual',
	            placement: 'bottom',
	            html: 'true',
	            content: setCartContent(cart_item, cart_overflow)
	        });
            ele.popover("show");
            ele.one('touchstart', function(e){
                e.stopPropagation();
	            $('[data-toggle="popover_cart"]').off('.show_cart');
                ele.popover('destroy');
	            cartPopover();
	            $('body').off('.body_hide_cart');
            })
	        ele.siblings(".popover")
	        .one("touchstart mouseenter", function() {
	            cart_timer && clearTimeout(cart_timer);
	        })
	        .on("click touchstart",function(e) {
	            e.stopPropagation();
	        });
	        $('body').one('click.body_hide_cart touchstart.body_hide_cart', function(e) {
				ele.popover('destroy');
	            // ele.removeClass("active");
	            $('[data-toggle="popover_cart"]').off('.show_cart');
	            cartPopover();
	            $('body').off('.body_hide_cart');
	        });
	    })
		.on('click touchstart', function(e){
            e.preventDefault();
			e.stopPropagation();
		})
	}

    cartPopover();

    // init the affix bar
    $('.J_cart_affix_bar').length && initCartBarAffix();
    $(window).on('resize', function(){
        // refresh the affix bar
        $('.J_cart_affix_bar').length && destroyCartBarAffix();
        $('.J_cart_affix_bar').length && initCartBarAffix();
    })
    // affix bar for cart page
    function initCartBarAffix() {
        $('.J_cart_affix_bar').affix({
            offset: {
                top: ($('.J_cart_affix_bar').offset().top - $(window).innerHeight() + $('.J_cart_affix_bar').innerHeight())
            }
        })
    }
    function destroyCartBarAffix() {
        // destroy affix manually
        $('.J_cart_affix_bar').removeClass('affix-top').removeData();
        $(window).off('.affix');
    }

    // address page
    $(document).on('click', '.J_address_delete_btn', function (e) {
        $(this).popover({
            placement: 'manual',
            placement: 'top',
            html: 'true',
            content: '<p>确定要删除此地址吗</p><button class="btn J_adress_delete-confirm">确认</button><button class="btn J_adress_delete-cancel">取消</button>'
        }).popover('show');
    })
    // 取消
    $(document).on('click', '.J_adress_delete-cancel', function (e) {
        $('.J_address_delete_btn').popover('destroy');
    })
    // 省市区下拉菜单同步
    $('.apx-edit-address-form .dropdown .dropdown-menu a').click(function (e) { 
        e.preventDefault();
        e.stopPropagation();
        $(this).parents('.dropdown').removeClass('open');
        $(this).parents('.dropdown').find('.btn > span').text($(this).text());
     })

     // 滚动屏幕到表单
    function scrollToAddAddressForm() {
        $('html, body').animate({
            scrollTop: $('.apx-edit-address-subtitle').eq(1).offset().top
        }, 400)
    }
     $('.J_address_add').click(scrollToAddAddressForm);

    //  同步订单状态时间
    function syncOrderStatusTime($target) {
        var currentTime = new Date();
        var info = [
            currentTime.getMonth() + 1, //月份 
            currentTime.getDate(), //日 
            currentTime.getHours(), //小时 
            currentTime.getMinutes(), //分 
            currentTime.getSeconds() //秒 
        ];
        for (var idx = 0, len = info.length; idx < len; idx++) {
            info[idx] = info[idx].toString().length === 1 ? ('0' + info[idx]) : info[idx];
        }
        $target.find('small').eq(0).html(currentTime.getFullYear() + '-' + info[0] + '-' + info[1]);
        $target.find('small').eq(1).html(info[2] + ' : ' + info[3] + ' : ' + info[4]);
    }
    // setInterval(syncOrderStatusTime, 1000)
    ['status-pay', 'status-ship', 'status-receive', 'status-confirm'].forEach(function(ele, index) {
        if($('.apx-order-status.' + ele).length) {
            setInterval(function(){
                syncOrderStatusTime($('.apx-order-status .col-xs-3').eq(index));
            }, 1000);
        }
    })
    
    // index nav
    var timer_indexNavCate_detail, timer_indexNavCate_hide; // timers
    if ($('.apx-section-nav').length) {
        $('#collapseIndexNav').on('hide.bs.collapse', function() {
            // remove all the changes if the collapse hide
            timer_indexNavCate_hide && clearTimeout(timer_indexNavCate_hide);
            timer_indexNavCate_detail && clearTimeout(timer_indexNavCate_detail);
            hideIndexNavCate();
        })
        $('.apx-section-nav [data-nav-cate]')
            .on('mouseenter', function() {
                // add active class and show detail of the category
                var $this = $(this);
                if ($this.hasClass('active')) return;
                timer_indexNavCate_hide && clearTimeout(timer_indexNavCate_hide);
                timer_indexNavCate_detail && clearTimeout(timer_indexNavCate_detail);
                var attrCat = $this.attr('data-nav-cate');
                $this.siblings().removeClass('active');
                $this.addClass('active');
                $('.nav-category-detail-container').addClass('in');
                $('.nav-category-detail').removeClass('in');
                timer_indexNavCate_detail = setTimeout(function() {
                    $('.nav-category-detail#' + attrCat).addClass('in');
                }, 200);
            })
            .on('mouseleave', function() {
                // reverse the change when mouse leave
                timer_indexNavCate_hide && clearTimeout(timer_indexNavCate_hide);
                timer_indexNavCate_hide = setTimeout(hideIndexNavCate, 1000);
            });
        $('.nav-category-detail-container')
            .on('mouseenter', function() {
                // clear reverse timer in mouse enter 
                timer_indexNavCate_hide && clearTimeout(timer_indexNavCate_hide);
            })
            .on('mouseleave', function() {
                // reverse the change when mouse leave
                timer_indexNavCate_hide && clearTimeout(timer_indexNavCate_hide);
                timer_indexNavCate_hide = setTimeout(hideIndexNavCate, 1000);
            });

        // hide all the nav categories
        function hideIndexNavCate() {
            $('[data-nav-cate]').removeClass('active');
            $('.nav-category-detail-container').removeClass('in');
            $('.nav-category-detail').removeClass('in');
        }
    }
    
});