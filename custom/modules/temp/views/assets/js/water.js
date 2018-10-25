$(function() {
	//攻略遮罩层
	$('.water-strategy').on('click', function() {
		$(window).scrollTop(0);
		$('.water-strategy-content').removeClass('hidden');
	})
	$('.water-strategy-content .close').on('click', function() {
		$('.water-strategy-content').addClass('hidden');
	})
})