$(function() {

    var arr = []
    var seckill_arr = [2156,2146,1958,2144,1872,1714,1953,1376]
    var small_pro = [1293,1940,1156, 1942,1871,1549,2153,1582,1290,1291,1883,1714]
    var store_arr = [2143,1211, 2157,2155,1932,2154]
    var gift_arr = [2148,1708,1300,1842,1951,1795,1801, 2147]
    function getPrice (dom,id) {
        requestUrl(
          '/product-recommend/goods',
          'GET',
          {id:id},
          function (data) {
            arr = []
            $.each(data, function (index,item){
                arr.push('<li><a href="/product?id=' + item.id + '"><img src="'+ item.main_image +'" /><strong>' + item.title + '</strong><span>￥' + item.price.min.toFixed(2) + '</span></a></li>')
            })
            dom.append(arr)
          }
        )
    }

    getPrice($('#better_seckill ul'),seckill_arr)
    getPrice($('#small_pro ul'), small_pro)
    getPrice($('#store_nec ul'), store_arr)
    getPrice($('#gift_pro ul'), gift_arr)

    $(window).on('scroll',function () {
        var _scrollTop = $(this).scrollTop()
        _scrollTop > 500 ? $('.quick-nav').removeClass('hidden') : $('.quick-nav').addClass('hidden')
    })
})
