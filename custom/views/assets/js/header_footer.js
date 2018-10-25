var CUSTOM_USER_LOGIN = false;
//获取角标显示
function getCount() {
	function countCB(data) {
		$('.J_cart_count').html(data.quantity);
	}
	requestUrl('/cart/quantity', 'GET', '', countCB)
}
	
$(function(){
	//获取登录状态
	function getStatus() {
		function statusCB(data) {
			if (data.name) {
				$('#J_top_user_info').removeClass('hidden').find('.login').html(data.nick_name + ' (' + data.name + ")");
				var url = window.location.origin;
				$('#J_top_user_info .logout').attr('href', '/login/logout?redirect=' + url);
				$('#J_top_my_order').removeClass('hidden');
				CUSTOM_USER_LOGIN = true;
				if ($('#J_index_logout_info')) {
					$('#J_index_logout_info a').attr('href', '/login/logout?redirect=' + url);
	                $('#J_login_user_name').html(data.nick_name);
				}
				window.CUSTOM_USER_LEVEL = data.level
				// if (data.level == 2) {
				// 	$('.custom-level').addClass('level-3');
				// }
				// if (data.level == 3) {
				// 	$('.custom-level').addClass('level-2');
				// }
				// if (data.level == 4) {
				// 	$('.custom-level').addClass('level-1');
				// }
				// console.log(data.business_area[4])
				if (data.level == 2) {
					$('.custom-level').addClass('custom-level-3');
					// $('.level-icon img').attr("src","/images/icons/u399.png");
					$('.level-user').text("加盟店");
					$('.level-address').text(data.business_area[4])
					
				}
				if (data.level == 3) {
					$('.custom-level').addClass('custom-level-2');
					// $('.level-icon img').attr("src","/images/icons/u16.png");
					$('.level-user').text("体系内");
					$('.level-address').text(data.business_area[4])
				}
				if (data.level == 4) {
					$('.custom-level').addClass('custom-level-1');
					// $('.level-icon img').attr("src","/images/icons/u15.png");
					$('.level-user').text("运营商");
					$('.level-address').text(data.business_area[4])
				}
				if (data.tag == 1) {
					$('.level-user').text("团长");
				}
			} else {
				$('#J_not_login_info').removeClass('hidden');
			}
		}
		requestUrl('/index/userinfo', 'GET', '', statusCB, '', false)
	}
	getStatus();
	
	//判断是否登录
	if (CUSTOM_USER_LOGIN) {
		getCount();
		if (cartPopover != undefined) cartPopover();
	}
	// 购物车按钮
	//获取购物车列表
	function getList() {
		var data = {
			page_size: 9999
		}
		function listCB(data) {
			var len = 0;
			for (var i = 0; i < data.items.length; i++) {
				len += data.items[i].items.length;
			}
			var cart_overflow = len > 5 ? (len - 5) : 0; //超出商品显示限制条数
			var ele = $('[data-toggle="popover_cart"]').eq(0);
			if ($('.popover-content')) {
				$('.popover-content').html('');
			}
	        ele.popover({
	            trigger: 'manual',
	            placement: 'bottom',
	            html: 'true',
	            content: setCartContent(data, cart_overflow)
	        });
            ele.popover("show");
            ele.on('click', function(e) {
            	window.location.href = '/cart'
            	return true
            });
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

			//绑定删除事件
		    $('.J_cart_delete').on('click', function() {
		    	var id = [];
		    	id.push($(this).attr('cart_id'));
		    	delProduct(id);
			})
		}
		requestUrl('/cart/list', 'GET', data, listCB)
	}
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
	    var count = 0;
	    for (var i = 0; i < item.items.length; i++) {
	    	for (var j = 0; j < item.items[i].items.length; j++) {
	    		_item += '<li class="mini-cart-item clearfix J_pro_box" data-count="' + item.items[i].items[j].count + '" data-price="' + item.items[i].items[j].price + '">\
		                    <div class="pull-left">\
		                        <a href="/product?id=' + item.items[i].items[j].product_id + '"><img src="' + item.items[i].items[j].image + '" class="img-responsive"></a>\
		                    </div>\
		                    <div class="pull-right text-right">\
		                        <p><strong>¥' + '' + item.items[i].items[j].price.toFixed(2) + '</strong>' + '×' + '<strong>' + item.items[i].items[j].count + '</strong>' + '</p>\
		                        <a href="javascript:;" class="J_cart_delete" cart_id="' + item.items[i].items[j].id + '">删除</a>\
		                    </div>\
		                    <div class="mini-cart-item-detail">\
		                        <p><a href="/product?id=' + item.items[i].items[j].product_id + '">' + item.items[i].items[j].title + '</a></p>'
		        for (var k = 0; k < item.items[i].items[j].attributes.length; k++) {
		        	_item += '<span>' + item.items[i].items[j].attributes[k].name + ': ' + item.items[i].items[j].attributes[k].selected_option.name + '</span>'
		        }
		        _item += '</div>\
		                <li>';
		        total_price += item.items[i].items[j].price * item.items[i].items[j].count;
	    		count += item.items[i].items[j].count; 
	    	}
	    }

	    //return the whole cart list string
	    return  '<h5>最近加入的商品</h5>' +
				'<div class="mini-cart-scroll">' +
	            	'<ul class="list-unstyled mini-cart">' +
	                	_item +
	            	'</ul>' +
				'</div>' +
	            '<div class="mini-cart-footer clearfix">' +
	                '<span class="mini-cart-total-ammount">共<strong class="J_pro_count">' + count + '</strong>件商品</span>' +
	                '<span class="mini-cart-total-price">总计¥<strong class="J_pro_price">' + total_price.toFixed(2) + '</strong></span>' +
	                '<a class="btn btn-warning btn-sm pull-right cart-btn" href="/cart">去购物车</a>' +
	            '</div>';
	};

	
	var cart_timer; // timer

	function cartPopover() {
	    $('[data-toggle="popover_cart"]')
	    .one('touchstart.show_cart mouseenter.show_cart', function(e) {
	        var ele = $(this);
	        getList();
	        $('body').one('click.body_hide_cart touchstart.body_hide_cart', function(e) {
				ele.popover('destroy');
	            $('[data-toggle="popover_cart"]').off('.show_cart');
	            cartPopover();
	            $('body').off('.body_hide_cart');
	        });
	    })
	    .off('click touchstart.cartClick')
		.on('click touchstart.cartClick', function(e){
            e.preventDefault();
			e.stopPropagation();
		})
	}
	//删除购物车商品
	function delProduct(id) {
        var data = {items_id: id};
        function delCB(data) {
        	$('.J_cart_delete[cart_id="' + id[0] + '"]').parents('.mini-cart-item').remove();
            var x = getTotal();
	    	$('.J_pro_count').html(x[0]);
	    	$('.J_pro_price').html(x[1].toFixed(2));
	    	getCount();
        }
        requestUrl('/cart/remove', 'POST', data, delCB)
    }

    //重新计算价格数量
    function getTotal() {
    	var len = $('.J_pro_box').length;
    	var count = 0, price = 0;
    	for (var i = 0; i < len; i++) {
    		count += $('.J_pro_box').eq(i).data('count');
    		price += $('.J_pro_box').eq(i).data('count') * $('.J_pro_box').eq(i).data('price');
    	}
    	return [count, price];
    }
})

$(function() {
	$('#hot-search').hide();
    $('#search-btn').click(function (){
    	var keyword = $('#search-ipt').val();
    	if(keyword) {
			window.location.href = "/search/index?keyword="+keyword;
    	}
    });
    $('#search-ipt').on('keypress',function(event) {
        var ipt_val = $(this).val();
        if(event.keyCode === 13) {
            if(ipt_val) {
              window.location.href = "/search/index?keyword="+ipt_val;
            }
        }
    });

})
