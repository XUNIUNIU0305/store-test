$(function(){
    $(".J_footer_menu").addClass("hidden");
    var id_arr = [];
    var $originalPrice1 = $(".boutique-regiment").eq(0).find(".item-cont-price");
    $.each($originalPrice1, function(i, val) {
        id_arr.push($(val).data('id'))
    })
    function getPrice2(id) {
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
      })
    }
    $("#boutique-regiment-nav li").on("click",function(){
        var index = $(this).data("id");
        $(".boutique-regiment").eq(index).removeClass("hidden").siblings().addClass("hidden");
        var $originalPrice2 = $(".boutique-regiment").eq(index).find(".item-cont-price");
        $.each($originalPrice2, function(i, val) {
            id_arr.push($(val).data('id'))
        })
        getPrice2(id_arr);
    });
    getPrice2(id_arr);
});