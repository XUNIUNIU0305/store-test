// 特惠专区
$(function(){
    $(".J_footer_menu").addClass("hidden");
    var id_arr = [];
    $.each($(".s_price"), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
              $('.s_price[data-id="' + val.id + '"]').parents(".special-item").find(".special-item-pic").attr("src",val.main_image);
              $('.s_price[data-id="' + val.id + '"]').parents(".special-item").find(".special-item-cont1").html(val.title);
              $('.s_price[data-id="' + val.id + '"]').children('span').html(val.price.min)
          })
        }
      )
    }
    getPrice(id_arr);
})
  
  // 限时秒杀
  $(function(){
    var id_arr = [];
    var $originalPrice = $(".second-kill-cont").eq(0).find(".j_price");
    $.each($originalPrice, function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
              $('.j_price[data-id="' + val.id + '"]').parents(".list-item").find(".list-item-pic").attr("src",val.main_image);
              $('.j_price[data-id="' + val.id + '"]').parents(".list-item").find(".list-item-cont").html(val.title);
              $('.j_price[data-id="' + val.id + '"]').children('span').html(val.price.min)
          })
        }
      )
    }
  
    function showPrice(index){
      $('#second-kill-nav li').eq(index).addClass("active").siblings().removeClass("active");
      $(".second-kill-cont").eq(index).removeClass("hidden").siblings().addClass("hidden");
      var $originalPrice = $(".second-kill-cont").eq(index).find(".j_price");
      $.each($originalPrice, function(i, val) {
          id_arr.push($(val).data('id'))
      })
      getPrice(id_arr);
    }
  
    var date1 = new Date("2018/06/28 00:00:00").getTime();
    var date2 = new Date("2018/06/29 00:00:00").getTime();
    var date = new Date().getTime();
    if(date >= date1 && date < date2){
      showPrice(1);
    }else if(date >= date2){
      showPrice(2);    
    }
  
    $('#second-kill-nav li').on("click",function(){
      var $id= $(this).data("id");
      showPrice($id);
    });
  
    getPrice(id_arr);
})