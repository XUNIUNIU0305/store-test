//custom/modules/temp/views/assets/js/djy-order.js
$(function(){
    var list = "";
    var quantity = 0;
    var serial = 0;
    var number = url('?account');
    var current_page = 1;
    // var flag = false;
    // var state = false;
    $(".import-box").find("input").val(number);
    
    if(number != undefined && number.length != 0) {
        getList(number);
    }
    function getList(account,current_page) {
        $.ajax({
            type: "get",
            url: "/temp/djy/order-list",
            data: {
                "account":account,
                "current_page":current_page,
                "page_size":10,
            },
            success: function(data){
                $(".inquire").attr("flag",'true')
                if(data.data.list.length <= 0){
                    $("tbody").addClass("hidden");
                    $("tfoot").addClass("hidden");
                    $(".load-more").addClass("hidden");
                    $(".no-data").removeClass("hidden");
                } else {
                    if(data.data.total_count > 10) {
                        $(".load-more").removeClass("hidden");
                    } else {
                        $(".load-more").addClass("hidden");
                    }
                    $("tbody").removeClass("hidden");
                    $("tfoot").removeClass("hidden");
                    $(".no-data").addClass("hidden");
                    $.each(data.data.list,function(index,item){
                        serial +=1;
                        var sku_attributes = "";
                        $.each(item.sku_attributes,function(i,j){
                            sku_attributes += j.attribute +":"+j.option+";";
                        })
                        sku_attributes = sku_attributes.substr(0,sku_attributes.length-1);
                        list += `
                            <tr class="order-details-content">
                                <td style="width:52px;">${serial}</td>
                                <td style="width:124px;">${item.order_number}</td>
                                <td style="width:124px;">${item.custom_account}</td>
                                <td style="width:106px;">${item.custom_mobile}</td>
                                <td style="width:150px;" title='${sku_attributes}'>${sku_attributes}</td>
                                <td style="width:52px;">${item.sku_quantity}</td>
                                <td style="width:213px;" title='${item.area}'>${item.area}</td>
                                <td style="width:80px;">${item.commander_name}</td>
                                <td style="width:106px;">${item.commander_mobile}</td>
                                <td style="width:193px;" title='${item.commander_address}'>${item.commander_address}</td>
                            </tr>
                        `
                        quantity += item.sku_quantity;
                    })
                    if(data.data.total_count == serial) {
                        $(".load-more").addClass("hidden");
                    }
                    $("tbody").html(list);
                    $(".load-more").text("加载更多")
                    $(".load-more").attr("flag",'true')
                    $(".quantity").text(quantity);
                    $("tfoot").removeClass("hidden")
                }
            }
         });
    }
    
    $(".load-more").click(function(){
        if($(this).attr("flag") == 'true'){
            current_page +=1;
            $(".load-more").text("加载中，请稍候")
            $(this).attr("flag",'false')
            var account = $(".import-box").find("input").val();
            getList(account,current_page);
        }
    })
    
    $(".inquire").click(function(){
        if($(this).attr("flag") == 'true'){
            current_page = 1;
            $(".load-more").addClass("hidden");
            $("tbody").html("");
            $(this).attr("flag",'false')
            $("tfoot").addClass("hidden")
            list = "";
            serial = 0;
            quantity = 0;
            var account = $(".import-box").find("input").val();
            if(account.length == 0){
                alert("请输入门店账号");
            } else {
                getList(account);
            }
        }
        
        
    })
})