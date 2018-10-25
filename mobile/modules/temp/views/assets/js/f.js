$(function() {
    var ids = [];
    $.each($('span[data-id]'), function(i, v) {
        ids.push($(this).data('id'));
    })
    function getPrice(id) {
		requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
			$.each(data, function(i, val) {
                $('[data-id="' + val.id + '"]').html(val.price.min.toFixed(2))
            })
            $.each(data, function(i, val) {
				$('[data-id-o="' + val.id + '"]').html((val.price.max * 1.1).toFixed(2))
			})
		})
    }
    getPrice(ids)

    // 导航显示
    $('.container').on('scroll', function() {
        var top = $(this).scrollTop();
        if (top > 700) {
            $('.nav-box-float').addClass('in')
        } else {
            $('.nav-box-float').removeClass('in')
        }
    })
})