$(function() {
    var ids = [];
    $.each($('.price'), function(i, v) {
        ids.push($(this).data('id'));
	})
    function getPrice(id) {
		requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
			$.each(data, function(i, val) {
				$('[data-id="' + val.id + '"] span').html(val.price.min.toFixed(2))
			})
		})
    }
    getPrice(ids)

    $('.container').on('scroll touchmove', function() {
        var top = $('.get-money-nav').offset().top;
        if (top < -36) {
            $('.get-money-container-nav-list').addClass('in')
        } else {
            $('.get-money-container-nav-list').removeClass('in')
        }
    })
    $('[data-link-section]').click(function(e){
        var _href = $(this).attr('href')
        $('.nav-list').find('a').removeClass('active')
        $('.nav-list').find('[href*="' + _href + '"]').addClass('active')
    })
})