$(function() {
    $('.nav-list').on('click', 'a', function() {
        $('.nav-list a').removeClass('active')
        $(this).addClass('active')
    })

    var id_arr = [];
    $.each($('.price'), function (i, val) {
        id_arr.push($(val).data('id'))
    })
    function getPrice(id) {
        requestUrl('/product-recommend/goods', 'GET', {
            id: id
        }, function (data) {
            $.each(data, function (index, val) {
                $('.price[data-id="' + val.id + '"] span').html(val.price.min.toFixed(2))
            })
        })
    }
    getPrice(id_arr);
})