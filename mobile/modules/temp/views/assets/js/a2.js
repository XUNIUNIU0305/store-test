$(function() {
    $('.bottom-nav.J_footer_menu').addClass('hidden')

    var ids = [];
    $.each($('.item[data-id]'), function(i, v) {
        ids.push($(this).data('id'));
    })
    function getPrice(id) {
		requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
			$.each(data, function(i, val) {
                if (val.price.min) {
                    $('.item[data-id="' + val.id + '"]').find('.J_pro_price').text('￥' + val.price.min)
                } else {
                    $('.item[data-id="' + val.id + '"]').find('.J_pro_price').text('￥' + val.price.min)
                }
				$('.item[data-id="' + val.id + '"]').find('.J_pro_title').text(val.title)
				$('.item[data-id="' + val.id + '"]').find('.J_pro_img').attr('src' ,val.main_image)
			})
		})
    }
    getPrice(ids)

    // 秒杀切换
    $('#J_seckill_nav').on('click', 'a', function(e) {
        e.preventDefault()
        var type = $(this).data('type')
        $('#floor-1 .item-list').eq(type - 1).removeClass('hidden').siblings('.item-list').addClass('hidden')
    })
    // tab切换
    $('#J_footer_nav').on('click', 'a', function(e) {
        e.preventDefault()
        var type = $(this).data('type')
        $('#floor-5 .item-list').eq(type).removeClass('hidden').siblings('.item-list').addClass('hidden')
    })

    var now = new Date();
    var t2 = new Date('2018/06/28 00:00:00')
    var t3 = new Date('2018/06/29 00:00:00')
    if (now > t2 && now < t3) {
        $('#floor-1 .item-list').eq(1).removeClass('hidden').siblings('.item-list').addClass('hidden')
    }
    if (now > t3) {
        $('#floor-1 .item-list').eq(2).removeClass('hidden').siblings('.item-list').addClass('hidden')
    }
})
