$(function() {
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