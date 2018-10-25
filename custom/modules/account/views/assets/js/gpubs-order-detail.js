;$(function(){
    
    //获取订单详细信息
    function getOrderMsg() {
        var data = {order_id: url('?order_id')};
        function orderCB(data) {
            //订单号
            $('.J_order_no').html('订单号：' + data.detail_number);
            $('.J_group_no').text('拼团编号：' + data.group_number)
            var statu = data.status;
            if (statu == 1) {
                $('.J_order_box').addClass('status-pay');
            }
            if (statu == 2) {
                $('.J_order_box').addClass('status-ship');
            }
            if (statu == 3) {
                $('.J_order_box').addClass('status-receive')
                $('.J_next_btn').removeClass('invisible');
                $('.J_pick_num').text(data.picked_up_quantity + '/' + data.quantity)
            }
            if (statu == 4) {
                $('.J_order_box').addClass('status-confirm');
            }
            if (statu == 5) {
                $('.J_order_box').addClass('status-cancel');
            }
            if (statu == 0) {
                $('.J_order_box').addClass('status-close');
            }
            if (statu === 2 || statu === 3) {
                $('.J_picking_up_number').parent().removeClass('hidden')
            }

            $('.J_picking_up_number').html(data.picking_up_number)
            
            // ['status-pay', 'status-ship', 'status-receive'].forEach(function(ele, index) {
            //     if($('.apx-order-status.' + ele).length) {
            //         setInterval(function setTime(){
            //             syncOrderStatusTime($('.apx-order-status .col-xs-3').eq(index));
            //         }, 1000);
            //     }
            // });
            
            var tpl_time = $('#J_tpl_time').html();
            var result = juicer(tpl_time, data.picking_up_log);

            $('.J_time_list').append(result);
            $('.J_consignee').html(data.picking_up_address.consignee);
            $('.J_address').html(data.picking_up_address.full_address);
            $('.J_mobile').html(data.picking_up_address.mobile);
            $('.J_mobile').html(data.picking_up_address.mobile);
            $('.J_order_price').html('¥ ' + data.product.total_fee.toFixed(2));
            $('.J_end_price').html('¥ ' + data.product.total_fee.toFixed(2));
            

            //订单详情
            var pric = function (data){
                return data.toFixed(2)
            }
            juicer.register('pric_build', pric);
            var tpl_detail = $('#J_tpl_detail').html();
            var proContent = juicer(tpl_detail, data.product);
            $('.J_product_box').html(proContent);

            $('.remark').text(data.comment)
                
            //确认收货
            $('.J_next_btn').on('click', function() {
                var yes = confirm('确认收货？');
                if (yes == false) return;
                var _data = {no: url('?no')};
                function mustCB(data) {
                    window.location.reload()
                }
                requestUrl('/account/order/confirm', 'POST', _data, mustCB);
            })    
        }
        requestUrl('/gpubs/api/order-detail', 'GET', data, orderCB);
    }
    getOrderMsg();

    
    //  同步订单状态时间
    function syncOrderStatusTime($target) {
        var currentTime = new Date();
        var info = [
            currentTime.getMonth() + 1, //月份 
            currentTime.getDate(), //日 
            currentTime.getHours(), //小时 
            currentTime.getMinutes(), //分 
            currentTime.getSeconds() //秒 
        ];
        for (var idx = 0, len = info.length; idx < len; idx++) {
            info[idx] = info[idx].toString().length === 1 ? ('0' + info[idx]) : info[idx];
        }
        $target.find('small').eq(0).html(currentTime.getFullYear() + '-' + info[0] + '-' + info[1]);
        $target.find('small').eq(1).html(info[2] + ' : ' + info[3] + ' : ' + info[4]);
    }
    
});