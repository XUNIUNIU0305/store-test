$(function(){
    var list = "";
    var quantity = 0;
    var serial = 0;
    var option = "";
    var number = url('?account');
    var current_page = 1;
    var address = "";
    $(".import-box").find("input").val(number);
    if(location.hostname.indexOf("test") != -1) {
        address = "http://test.api.9daye.com.cn";
    } else {
        address = "http://api.9daye.com.cn";
    }
    function getList(top_area_id,secondary_area_id,tertiary_area_id,quaternary_area_id,account,current_page) {
        $.ajax({
            type: "get",
            url: address+"/djy/order-list",
            data: {
                "top_area_id":top_area_id,
                "secondary_area_id":secondary_area_id,
                "tertiary_area_id":tertiary_area_id,
                "quaternary_area_id":quaternary_area_id,
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
                    $(".load-more").text("加载更多");
                    $(".load-more").attr("flag",'true');
                    $(".quantity").text(quantity);
                    $("tfoot").removeClass("hidden");
                }
            }
         });
    }
    
    function getArea(id,top_area_id) {
        var parent_area_id;
        if(top_area_id){
            parent_area_id = top_area_id;
        } else {
            parent_area_id = id;
        }
        $.ajax({
            type: "get",
            url: address+"/djy/area-list",
            
            data: "parent_area_id="+parent_area_id,
            success: function(data){
                option = "<option id='' value ='全部' >全部</option>";
                if(top_area_id.length==0){

                } else {
                    $.each(data.data.list,function(index,item){
                        option += `<option id="${item.id}" value ="${item.name}" >${item.name}</option>`;
                    })
                }
                
                $(".select"+id).html(option)
            }
            
        });
        
        
    }
    getArea(0,"0")
    if(number != "undefined"){
        getList('','','','',number,0)
    }
    $(".load-more").click(function(){
        if($(this).attr("flag") == 'true'){
            $(".load-more").text("加载中，请稍候")
            $(this).attr("flag",'false')
            current_page +=1;
            var top_area_id = $(".select0").find("option:selected").attr("id");
            var secondary_area_id = $(".select1").find("option:selected").attr("id");
            var tertiary_area_id = $(".select2").find("option:selected").attr("id");
            var quaternary_area_id = $(".select3").find("option:selected").attr("id");
            var account = $(".import-box").find("input").val();
            getList(top_area_id,secondary_area_id,tertiary_area_id,quaternary_area_id,account,current_page);
        }
    })
    $(".inquire").click(function(){
        if($(this).attr("flag") == 'true'){
            current_page = 1;
            $(".load-more").addClass("hidden");
            $(this).attr("flag",'false');
            $("tbody").html("");
            $("tfoot").addClass("hidden")
            list = "";
            serial = 0;
            quantity = 0;
            var top_area_id = $(".select0").find("option:selected").attr("id");
            var secondary_area_id = $(".select1").find("option:selected").attr("id");
            var tertiary_area_id = $(".select2").find("option:selected").attr("id");
            var quaternary_area_id = $(".select3").find("option:selected").attr("id");
            var account = $(".import-box").find("input").val();
            getList(top_area_id,secondary_area_id,tertiary_area_id,quaternary_area_id,account);
        }
    })
    $(".select0").change(function(){
        var top_area_id = $(".select0").find("option:selected").attr("id");
        getArea(1,top_area_id);
        if(top_area_id.length == 0){
            getArea(2,top_area_id);
            getArea(3,top_area_id);
        } else {
            getArea(2,"");
            getArea(3,"");
        }
        
    })
    $(".select1").change(function(){
        var top_area_id = $(".select1").find("option:selected").attr("id");
        getArea(2,top_area_id)
        if(top_area_id.length == 0){
            getArea(3,top_area_id);
        } else {
            getArea(3,"");
        }
    })
    $(".select2").change(function(){
        var top_area_id = $(".select2").find("option:selected").attr("id");
        getArea(3,top_area_id)
    })
})