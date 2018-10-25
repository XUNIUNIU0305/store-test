$(function(){
    var $navLis = $("#product-listing-nav>ul>li");
    var $infos = $("#product-listing-cont>div");
    $navLis.on("click",function(){
        var $index = $(this).index();
        $(this).addClass("nav-active").siblings().removeClass("nav-active");
        $($infos[$index]).addClass("product-listing-cont-active").siblings().removeClass("product-listing-cont-active");
    });
})