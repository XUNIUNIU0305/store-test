$(function(){
    var id_arr = [];
    $.each($('.item[data-id]'), function(i, val) {
      id_arr.push($(val).data('id'))
    })
    function getPrice (id) {
      requestUrl(
        '/product-recommend/goods',
        'GET',
        {id:id},
        function (data) {
          $.each(data,function(index,val){
            $('.item[data-id="' + val.id + '"]').find('.J_pro_price').text('￥' + val.price.min) //.toFixed(2)
            $('.item[data-id="' + val.id + '"]').find('.J_pro_title').text(val.title)
            $('.item[data-id="' + val.id + '"]').find('.J_pro_img').attr('src', val.main_image)
          })
        }
      )
    }
    getPrice(id_arr);


    $('.tab-item').on('click', function () {

        var start = $(this).data('status'),
            end = $(this).data('id')

        for (let i = start; i < start + end; i++) {
            $('.J_item').eq(i).removeClass('hidden')
        }

        for (let j = start-1; j>=0; j--) {
            $('.J_item').eq(j).addClass('hidden')
        }

        for (let k = start + end; k< 28; k++) {
            $('.J_item').eq(k).addClass('hidden')
        }
    })

    $('.day-nav-item').on('click', function () {
        $('.nav-item-box').addClass('hidden')
        $('.nav-item-box').eq($(this).data('id')).removeClass('hidden')
    })

  })
