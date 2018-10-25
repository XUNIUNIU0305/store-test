$(function() {
    // 规则弹窗
    $('.btn-rule').on('click', function(e) {
        $(this).siblings('.rule').removeClass('hidden')
    })
    $(document).on('click', function(e) {
        if (!$(e.target).hasClass('btn-rule')) {
            $('.rule').addClass('hidden')
        }
    })

    var ids = [];
    $.each($('.data-price'), function(i, v) {
        ids.push($(this).data('price'));
    })
    function getPrice(id) {
		requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
			$.each(data, function(i, val) {
				$('[data-price="' + val.id + '"]').html(val.price.min.toFixed(2))
			})
		})
    }
    getPrice(ids)
})