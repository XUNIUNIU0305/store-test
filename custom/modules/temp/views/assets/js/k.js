$(function(){

  $('body').scrollspy({
    target: '#float-nav'
  })

  // 规则弹窗
    $('.btn-rule').on('click', function(e) {
        $(this).siblings('.rule').removeClass('hidden')
    })
    $(document).on('click', function(e) {
        if (!$(e.target).hasClass('btn-rule')) {
            $('.rule').addClass('hidden')
        }
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
        })
      }
    )
  }
  getPrice(id_arr);
})