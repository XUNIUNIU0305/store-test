$(function () {
    $('.J_footer_menu').addClass('hidden')
    var id_arr = [];
    $.each($('.price'), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
            $('.price[data-id="' + val.id + '"]').html('￥' + val.price.min.toFixed(2))
            $('.describe[data-id="' + val.id + '"]').html(val.title)
            $('.pro-img[data-id="' + val.id + '"]').attr('src',val.main_image)
          })
        }
      )
    }
    getPrice (id_arr);
})
