$(function () {

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
        for (let i = n; i<n+4; i++) {
        $('.product-items').children().eq(i).removeClass('hidden')
        }
        if (n == 0) {
            $('.time').find('span').eq(0).addClass('time-on').siblings().removeClass('time-on')
            for (let j = 4; j < 12; j++) {
                $('.product-items').children().eq(j).addClass('hidden')
            }
        }

        if ( n == 4) {
            $('.time').find('span').eq(1).addClass('time-on').siblings().removeClass('time-on')
            for(let n = 0; n < 4; n++) {
                $('.product-items').children().eq(n).addClass('hidden')
            }
            for (let m = 8; m < 12; m++) {
                $('.product-items').children().eq(m).addClass('hidden')
            }
        }
        if (n == 8) {
            $('.time').find('span').eq(2).addClass('time-on').siblings().removeClass('time-on')
            for (let k = 0; k < 8; k++) {
                $('.product-items').children().eq(k).addClass('hidden')
            }
        }
    }

    $('.time').on('click', 'span', function () {
        $(this).addClass('time-on').siblings().removeClass('time-on')
        showData($(this).attr('data-id'))
    })

    //拉去数据
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
            $('.item-name[data-id="' + val.id + '"], .item-title[data-id="' + val.id + '"]').html(val.title)
            $('.item-title[data-id="' + val.id + '"]').html(val.title)
            $('.product-img[data-id="' + val.id + '"]').attr('src',val.main_image)
            $('.item-img[data-id="' + val.id + '"]').attr('src',val.main_image)
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
