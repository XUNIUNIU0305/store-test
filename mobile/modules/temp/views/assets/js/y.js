$(function(){
    $('main.container').on('scroll', function(e){
        var $btn = $('.wechat-shampoo-buy-btn');
        if($('.shampoo-section-1 .img-btn').offset().top < 0) {
            $btn.addClass('in');
        }
        else{
            $btn.removeClass('in');
        }
    })
    $('.water-strategy').on('click', function() {
    	$('.water-strategy-content').addClass('show');
    })
    $('.water-strategy-content .close').on('click', function() {
    	$('.water-strategy-content').removeClass('show');
    })
})