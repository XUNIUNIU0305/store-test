$(function() {
    var ids = [];
    $.each($('[data-id]'), function(i, v) {
        ids.push($(this).data('id'));
    })
    function getPrice(id) {
		requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
			$.each(data, function(i, val) {
				$('[data-id="' + val.id + '"]').html(val.price.min.toFixed(2))
			})
		})
    }
    getPrice(ids)
    var $navLis = $("#product-listing-nav>ul>li");
    var $infos = $("#product-listing-cont>div");
    $navLis.on("click",function(){
        var $index = $(this).index();
        $(this).addClass("nav-active").siblings().removeClass("nav-active");
        $($infos[$index]).addClass("product-listing-cont-active").siblings().removeClass("product-listing-cont-active");
    });
    // 绑定商品跳转
    $('.product-listing-cont-details').on('click', 'li', function() {
        var id = $(this).find('[data-id]').data('id')
        if (id) {
            location.href = '/goods/detail?id=' + id;
        }
    })
})