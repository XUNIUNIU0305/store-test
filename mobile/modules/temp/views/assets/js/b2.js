$(function(){
    $('.J_footer_menu').addClass('hidden')
    var id_arr = [];
    $.each($('.product-item'), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
            $('.product-item[data-id="' + val.id + '"]').find('img').attr('src', val.main_image)
            $('.product-item[data-id="' + val.id + '"]').find('.item-describe').text(val.title).attr('title', val.title)
            $('.product-item[data-id="' + val.id + '"]').find('.price').text('￥' + val.price.min)
          })
        }
      )
    }
    getPrice(id_arr);
  })
