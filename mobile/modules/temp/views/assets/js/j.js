$(function() {
	$('.not_buy').on('click', function(e) {
		e.preventDefault()
		alert('该商品已下架，更多商品敬请期待！')
		return
	})
})