$(function() {
    $('.J_footer_menu').addClass('hidden')
    var arr = []
    var seckill_arr = [2156,2146,1958,2144,1872,1714,1953,1376]
    var small_pro = [1293,1940,1156,1942,1871,1549,2153,1582,1290,1291,1883,1714]
    var store_arr = [2143,1211,2157,2155,1932,2154]
    var gift_arr = [2148,1708,1300,1842,1951,1795,1801,2147]
    function getPrice (dom,id) {
        $.ajax({
           url: '/product-recommend/goods',
           type: 'GET',
           data: {
               id: id
           },
           success: function (data) {
                $(dom).html('')
                $.each(data.data, function (index,item){
                    $(dom).append('<li><a href="/goods/detail?id=' + item.id + '"><img src="'+ item.main_image +'" /><strong>' + item.title + '</strong><span>￥' + item.price.min.toFixed(2) + '</span></a></li>')
                })
                var docheight = $('.hot-summer-mobile-container').height()
                var h1Height = $('h1').height() / docheight
                var h2Height = $('#better_seckill').height() / docheight
                var h3Height = ($('h3').height() + $('#small_pro').height()) / docheight
                var h4Height = $('#store_nec').height() / docheight

                $(window).scroll(function(){
                    if ($(window).scrollTop() >= h1Height * docheight) {
                        $('.quick-nav').addClass('pos-nav')
                    } else {
                        $('.quick-nav').removeClass('pos-nav')
                    }
                    if($(window).scrollTop() >= h1Height * docheight && $(window).scrollTop() < (h1Height+h2Height) * docheight){
                        $('.quick-nav').find('li').eq(0).addClass('nav-on').siblings().removeClass('nav-on')

                    }else if($(window).scrollTop() >= (h1Height+h2Height) * docheight && $(window).scrollTop() < (h1Height+h2Height+h3Height) * docheight) {

                        $('.quick-nav').find('li').eq(1).addClass('nav-on').siblings().removeClass('nav-on')

                    }else if ($(window).scrollTop() >= (h1Height+h2Height+h3Height) * docheight && $(window).scrollTop() < (h1Height+h2Height+h3Height+h4Height) * docheight) {

                        $('.quick-nav').find('li').eq(2).addClass('nav-on').siblings().removeClass('nav-on')

                    } else if ($(window).scrollTop() >= (h1Height+h2Height+h3Height+h4Height) * docheight) {

                        $('.quick-nav').find('li').eq(3).addClass('nav-on').siblings().removeClass('nav-on')
                    }
                })
           },
           error: function () {
           }
        })

    }

    getPrice('#better_seckill ul',seckill_arr)
    getPrice('#small_pro ul', small_pro)
    getPrice('#store_nec ul', store_arr)
    getPrice('#gift_pro ul', gift_arr)

    $('.quick-nav').on('click', 'li', function () {
        $(this).addClass('nav-on').siblings().removeClass('nav-on')
    })
    $('.to-top').on('click', function() {
        $('.quick-nav').find('li').eq(0).addClass('nav-on').siblings().removeClass('nav-on')
    })
})
