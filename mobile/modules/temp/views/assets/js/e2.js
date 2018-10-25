$(function(){
  $('.J_footer_menu').addClass('hidden');
  var id_arr = [];
  var $originalPrice1 = $(".time-limit-product").eq(0).find(".describe-price");
  $.each($originalPrice1, function(i, val) {
      id_arr.push($(val).data('id'))
  })

  function getPrice2 (id) {
    requestUrl( 
      '/product-recommend/goods',
      'GET',
      {id:id},
      function (data) {
        $.each(data,function(index,val){
            $('.describe-price[data-id="' + val.id + '"]').parents('.product-item').find('.product-item-cont').attr("src",val.main_image);
            $('.describe-price[data-id="' + val.id + '"]').parents('.product-item').find('.p_item').text(val.title);
            $('.describe-price[data-id="' + val.id + '"]').children('span').html(val.price.min)
      })
    })
  }

  function showPrice(index){
      $('#time-limit-nav li').eq(index).addClass("active").siblings().removeClass("active");
      $(".time-limit-product").eq(index).removeClass("hidden").siblings().addClass("hidden");
      var $originalPrice2 = $(".time-limit-product").eq(index).find(".describe-price");
      $.each($originalPrice2, function(i, val) {
          id_arr.push($(val).data('id'))
      })
      getPrice2(id_arr);
  }

  var date1 = new Date("2018/06/28 00:00:00").getTime();
  var date2 = new Date("2018/06/29 00:00:00").getTime();
  var time = new Date().getTime();
  if(time >= date1 && time < date2){
    showPrice(1);
  }else if(time >= date2){
    showPrice(2);    
  }
  
  $('#time-limit-nav li').on("click",function(){
    var $id= $(this).data("id");
    showPrice($id);
  });

  getPrice2(id_arr);
});