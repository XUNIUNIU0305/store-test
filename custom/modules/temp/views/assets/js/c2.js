$(function(){
    var id_arr = [];
    $.each($(".item"), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
              $('.item[data-id="' + val.id + '"]').find(".img-box").children("img").attr("src",val.main_image);
              $('.item[data-id="' + val.id + '"]').find(".item-title").html(val.title);
              $('.item[data-id="' + val.id + '"]').find(".a-price").html(val.price.min);
          })
        }
      )
    }
    getPrice(id_arr);
})
 