$(function(){ 
    
    var tpl=$("#order_tpl").html();
    function getMobileList(page,page_size,status){
        var data = { 
            page: page, 
            page_size: page_size,
            status:status
        };
        if(status == 1){
            $('#status-span nav').find("span").eq(0).addClass('active_span');
        }else if(status == 2){
            $('#status-span nav').find("span").eq(1).addClass('active_span');
        }else{
            $('#status-span nav').find("span").eq(2).addClass('active_span');
        }
        requestUrl('/customization/order/search', 'GET', data, function(data){
             var html=juicer(tpl, data);
             $("#list_box").html(html);
        });
    }
    
    if(url("?status")){
        getMobileList(1, 999, url("?status"));
    }else{
        getMobileList(1, 999, 1);
    }

    $(".C_order_info").on("click", function () {
        var status = $(this).data("status");
        if(status == 1 || status == 2){
           $(".nav_content").css("display","none");
           $(".old-tip").addClass('top');
        }else{
           $(".nav_content").css("display","flex");
           $(".old-tip").removeClass('top');
        }
        $('#status-span span').removeClass('active_span');
        $(this).addClass('active_span'); 
        getMobileList(1, 999, status);
    });
    $(".content_info").on("click",function(){
        $('.nav_content span').removeClass('active_content_nav');
        $(this).addClass('active_content_nav');
        var status = $(this).data("status");
        getMobileList(1, 999, status)
    })

    $("#list_box").on("click",".cancel_order",function(e){
        e.preventDefault();
        var yes = confirm('确定取消该订单？');
        if (!yes) return;
        var prames={};
        prames.order_number=$(this).parents("li").data("order_number");
        requestUrl('/customization/order/cancel', 'POST', prames, function(data){
            alert("取消成功");
            getMobileList(1, 999, 1);
        });
    })
        
})