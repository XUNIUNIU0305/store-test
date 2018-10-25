$(function() {
    var ids = [];
    $.each($('.price'), function(i, v) {
        ids.push($(this).data('id'));
    })
    function getPrice(id) {
		requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
			$.each(data, function(i, val) {
				$('.price[data-id="' + val.id + '"]').html(val.price.min)
				$('.item-img[data-id="' + val.id + '"]').attr('src',val.main_image)
				$('.item-title[data-id="' + val.id + '"]').html(val.title)
			})
		})
    }
    getPrice(ids)
})
