$(function(){

    function loadOrderInfo(){
        var Url="/member/order/get-order-info";
        var _data={no:url('?no')}

        function success(data){

             // 绑定客服事件
             var _srp = document.createElement('script')
             _srp.type = 'text/javascript'
             _srp.sync = 'sync'
             _srp.src = JDY_SERVICE_LIST[data.supplier]
             if (!JDY_SERVICE_LIST[data.supplier]) {
                 _srp.src = JDY_SERVICE_LIST['default']
             }
             $('body').append(_srp)

            $(".J_order_no").html(_data.no);
            $(".J_order_total").html("￥"+data.total_fee);
            $(".J_create_time").html(data.create_time);
            var status="未支付";
            switch (data.status){
                case 0:
                    status="未支付";
                    break;
                case 1:
                    status="待发货";
                    break;
                case 2:
                    status="已发货";
                    break;
                case 3:
                    status="已确认收货";
                    $(".J_status_text").removeClass('hidden');
                    break;
                case 4:
                    status="已取消";
                    break;
                case 5:
                    status="已关闭";
                    break;
            }
            if (data.status > 1 && data.status != 4) {
                $('.query-express').removeClass('hidden');
            }
            $(".J_return_url").attr("href",'/member/order/index?status='+data.status);
            $(".J_order_status").html(status);
            $(".J_consignee").html(data.consignee);
            $(".J_mobile").html(data.mobile);
            $(".J_address").html(data.address);
            $(".J_storename").html(data.storename);
            //解析订单详情
            var detail_list=$("#J_tpl_detail").html();
            var detail_html = juicer(detail_list, data);
            $('.J_order_detail').html(detail_html);
            $(".J_count").html(data.items.length);
            $(".J_total").html("￥" + parseFloat(data.items_fee).toFixed(2));
            $(".J_paid").html("￥" + parseFloat(data.total_fee).toFixed(2));
            $('#J_coupon_price').html("￥" + parseFloat(data.coupon_rmb).toFixed(2));
            // 支付方式
            if(data.pay_method != null && data.pay_method.length > 0){
                $('#J_pay_ment').html(data.pay_method);
            }else {
                $('#J_pay_ment').html('未付款');
            }
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }

        requestUrl(Url,'get',_data,success,errorCB);
    }

    //加载订单信息
    loadOrderInfo();

    // 跳转物流查询页
    $('.J_jump_express').on('click', function() {
        location.href = '/member/express?no=' + url('?no')
    })
})
