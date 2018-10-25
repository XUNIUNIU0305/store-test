$(function(){
    var url = window.location.search;
    var id = url.substring(url.lastIndexOf('=')+1, url.length);
    if(id == ""|| id == undefined){
        return;
    }

    //轮播数据加载
    var shop_list = $('#J_shop_list').html();
    var compiled_shop = juicer(shop_list);
    //获取数据加载列表
    function getShopList() {
        function listShop(data) {
            function isfrist(num) {
                if( num == 0){
                    return '#b39247';
                }
                if (num == 1){
                    return '#b36247';
                }
            }
            function isshop(shop) {
                if( shop == 0){
                    return '销量冠军';
                }
                if (shop == 1){
                    return '特惠商品';
                }
            }
            juicer.register('first_build', isfrist);
            juicer.register('shop_build', isshop);
            var html = compiled_shop.render(data);
            $('#J_shop_banner').html(html);
            var top = data.top;
            if(top.id == ""){
                $("#J_shop_item").html('<a href="#"><img src="" class="img-responsive" ></a>');
            }else{
                $("#J_shop_item").html('<a href="'+top.image_url+'" target="_blank"><img src="'+top.image_path+'" class="img-responsive img_banner_shop"></a>');
            }
            var supply = data.supply;
            $('#J_media_strong').html('<strong>'+supply.brandName+'</strong><div class="badge">品牌直营</div>');
            $('#J_media_img').find('img').attr('src',supply.headerImg);
            $('#J_media_p').find('p').text("所在地："+supply.province);

        }
        requestUrl('/shop/shop-adv-list', 'GET', {supply_user_id:id}, listShop);
    }
    getShopList()
    var product_list = $('#J_Product_list').html();
    var compiled_product = juicer(product_list);
    function getProductList(page, size) {
            function listProduc(data) {
                var html = compiled_product.render(data.products);
                $('.J_carousel_shop').html(html);
                pagingBuilder.build($('#J_shop_page'),page,size,data.total_count);
                pagingBuilder.click($('#J_shop_page'),function(page){
                    getProductList(page, size);
                })
            }
        requestUrl('/shop/product-list', 'GET', {supply_user_id:id,current_page: page, page_size: size}, listProduc);
    }
    getProductList(1,10);

    $("#J_shop_item img").mouseover(function () {
        $(this).addClass('active');
    })
})
function mouseover() {
    $(".main-banner").addClass('active');
}
function mouseout() {
    $(".main-banner").removeClass('active');
}