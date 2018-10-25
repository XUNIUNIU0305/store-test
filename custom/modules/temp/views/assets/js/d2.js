$(function(){
    var id_arr = [];
    $.each($('.item-cont-price'), function(i, val) {
      id_arr.push($(val).data('id'));
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
            $.each(data,function(index,val){
                $('.item-cont-price[data-id="' + val.id + '"]').parents(".boutique-regiment-item").find(".item-pic").attr("src",val.main_image);
                $('.item-cont-price[data-id="' + val.id + '"]').parents(".boutique-regiment-item").find(".item-tit").html(val.title);
                $('.item-cont-price[data-id="' + val.id + '"]').children('span').html(val.price.min);
          })
        }
      )
    }
    getPrice (id_arr);
})