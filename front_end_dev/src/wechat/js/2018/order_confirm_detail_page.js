$(function(){
    $(".mode-payment-right>span").each(function(item,i){
        $(this).click(function(){
            $(".mode-payment-right>span").removeClass("mode-payment-right-btn");
            $(this).addClass("mode-payment-right-btn");
        })
        
    })
})