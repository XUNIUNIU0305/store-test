$(function() {

    function loadOrderInfo(){
        var Url="/member/order/get-order-info";
        var _data = {no:url('?no')}

        function success(data){
            $(".J_order_no").html(_data.no);
            $(".J_create_time").html(data.create_time);
            $(".J_order_img").attr('src', data.items[0].image);
            var status="未支付";
            switch (data.status){
                case 0:
                    status="未支付";
                    break;
                case 1:
                    status="未发货";
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
            $('.J_order_status').html(status);
            var content = ['亲，您的订单已生效，现在可以付款了哦！'];
            var _detail = '';
            if (data.close_time != '') {
                _detail += '<li><p class="list-detail">亲，您的订单已关闭！</p>'
                _detail += '<p class="list-time">' + data.close_time + '</p></li>'
            }
            if (data.cancel_time != '') {
                _detail += '<li><p class="list-detail">亲，您的订单已取消！</p>'
                _detail += '<p class="list-time">' + data.cancel_time + '</p></li>'
            }
            if (data.receive_time != '') {
                _detail += '<li><p class="list-detail">亲，本次交易已完成，欢迎再次光临哦！</p>'
                _detail += '<p class="list-time">' + data.receive_time + '</p></li>'
            }
            // 获取物流
            requestUrl('/member/index/get-express', 'GET', {order_no: url('?no')}, function(data) {
                var _data = data.detail;
                $.each(_data, function(i, val) {
                    _detail += '<li><p class="list-detail">' + val.context + '</p>'
                    _detail += '<p class="list-time">' + val.ftime + '</p></li>'
                })
            }, function(data) {
                if (data.status == 3212) {
                    _detail += '<li><p class="list-detail">暂未查询到物流信息</p>'
                    _detail += '<p class="list-time"></p></li>'
                }
            }, false)
            if (data.deliver_time != '') {
                _detail += '<li><p class="list-detail">亲，已经为您发货了，请耐心等待送达哟！</p>'
                _detail += '<p class="list-time">' + data.deliver_time + '</p></li>'
            }
            if (data.pay_time != '') {
                _detail += '<li><p class="list-detail">亲，您的付款已收到，马上为您发货哟！</p>'
                _detail += '<p class="list-time">' + data.pay_time + '</p></li>'
            }
            $('#J_express_list').html(_detail)
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }
        requestUrl(Url,'get',_data,success,errorCB);
    }

    //加载订单信息
    loadOrderInfo();
    
})