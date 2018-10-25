$(function() {
	var top_height = $('.banner1').height() + $('.banner2').height();
	$('main.container').on('touchmove scroll',function() {
		var _top = $('main.container').scrollTop();
		if (_top > top_height)
		{
			$('.to-top').removeClass('hidden');
			$('.ceiling-img').removeClass('hidden');
		} else {
			$('.to-top').addClass('hidden');
			$('.ceiling-img').addClass('hidden');
		}
	});
	var tpl = $('#J_tpl_list').html();
	function price(data) {
		if (data.min === data.max) {
			return parseFloat(data.min).toFixed(2)
		} else {
			return parseFloat(data.min).toFixed(2) + '-' + parseFloat(data.max).toFixed(2)
		}
	}
	juicer.register('price', price);
	function getPro() {
		var id = [328,329,692,545,548,44,582,389,380,392,387,143,1209,30,119,784,332,51,132,395,134,361,355,204,207,163,170,173,175,370];
		requestUrl('/product-recommend/goods', 'GET', {id: id}, function(data) {
			$('#J_list_box').html(juicer(tpl, data))
		})
	}
	getPro()
})