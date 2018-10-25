$(function () {
    $('.J_footer_menu').addClass('hidden')
    var u = navigator.userAgent;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1
    var scroll_len = 0;
    if (isAndroid) {
        scroll_len = -63
    } else {
        scroll_len = 0
    }
    $(window).on('scroll',function(){
        var _scrollTop = $(this).scrollTop()
        if (_scrollTop > 271 + scroll_len) {
            $('.fixed-nav').removeClass('hidden')
        }
        if (_scrollTop < 271 + scroll_len) {
            $('.fixed-nav').addClass('hidden')
            $('a[href="#better-seckill"]').addClass('choice').siblings().removeClass('choice')
        }
        if (_scrollTop < 831 + scroll_len) {
            $('a[href="#better-seckill"]').addClass('choice').siblings().removeClass('choice')
        }
        if (_scrollTop >(831+scroll_len) && (_scrollTop < 1519+scroll_len)) {
            $('a[href="#better-product"]').addClass('choice').siblings().removeClass('choice')
        }
        if (_scrollTop > (1519 + scroll_len) && (_scrollTop < 2244 + scroll_len )) {
            $('a[href="#cushion"]').addClass('choice').siblings().removeClass('choice')
        }
        if (_scrollTop >(2244 + scroll_len)) {
            $('a[href="#decorate-product"]').addClass('choice').siblings().removeClass('choice')
        }
    });

    $('.nav-style').on('click', 'a', function () {
        var _href = $(this).attr('href')
        $('a[href="'+ _href +'"]').addClass('choice').siblings().removeClass('choice')
    })


    var data = new Date().getDate();
    if (data == '27') {
        showData(0)
    }
    if (data == '28') {
        showData(4)
    }
    if (data == '29') {
        showData(8)
    }
    if (data < 27) {
        showData(0)
    }
    function showData(n) {
        for (let i = n; i < n + 4; i++) {
            $('.product-items').children().eq(i).removeClass('hidden')
        }
        if (n == 0) {
            $('.time').children().eq(0).addClass('time-on').siblings().removeClass('time-on')
            for (let j = 4; j < 12; j++) {
                $('.product-items').children().eq(j).addClass('hidden')
            }
        }

        if ( n == 4) {
            $('.time').children().eq(1).addClass('time-on').siblings().removeClass('time-on')
            for(let n = 0; n < 4; n++) {
                $('.product-items').children().eq(n).addClass('hidden')
            }
            for (let m = 8; m < 12; m++) {
                $('.product-items').children().eq(m).addClass('hidden')
            }
        }
        if (n == 8) {
            $('.time').children().eq(2).addClass('time-on').siblings().removeClass('time-on')
            for (let k = 0; k < 8; k++) {
                $('.product-items').children().eq(k).addClass('hidden')
            }
        }
    }

    $('.time').on('click', 'span', function () {
        $(this).addClass('time-on').siblings().removeClass('time-on')
        showData($(this).attr('data-id'))
    })

    var id_arr = [];
    $.each($('.J_pro_price'), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
            $('.J_pro_price[data-id="' + val.id + '"]').html(val.price.min.toFixed(2))
            $('.item-title[data-id="'+ val.id +'"]').html(val.title)
            $('.item-name[data-id="'+ val.id +'"]').html(val.title)
            $('.item-describe[data-id="' + val.id + '"]').html(val.description)
            $('.product-img[data-id="' + val.id + '"]').attr('src',val.main_image)
          })
        }
      )
    }
    getPrice (id_arr);

    $('.to-shop').on('click', function (e){
        e.cancelBubble = true;
        e.stopPropagation();
        e.preventDefault();
        location.href = '/shop?id=' + $(this).attr('data-id')
    })
})
