;$(function(){
    //订单号
    $('.J_order_no').html('订单号：' + url('?no'));
    //获取订单详细信息
    function getOrderMsg() {
        var data = {no: url('?no')};
        function orderCB(data) {
            // 绑定客服事件
            var _srp = document.createElement('script')
            _srp.type = 'text/javascript'
            _srp.sync = 'sync'
            _srp.src = JDY_SERVICE_LIST[data.supplier]
            if (!JDY_SERVICE_LIST[data.supplier]) {
                _srp.src = JDY_SERVICE_LIST['default']
            }
			$('body').append(_srp)

            var statu = data.status;
            if (statu == 0) {
                $('.J_order_box').addClass('status-pay');
            }
            if (statu == 1) {
                $('.J_order_box').addClass('status-ship');
            }
            if (statu == 2) {
                $('.J_order_box').addClass('status-receive')
                $('.J_next_btn').removeClass('invisible');
            }
            if (statu == 3) {
                $('.J_order_box').addClass('status-confirm');
            }
            if (statu == 4) {
                $('.J_order_box').addClass('status-cancel');
            }
            if (statu == 5) {
                $('.J_order_box').addClass('status-close');
            }
            var content = ['亲，您的订单已生效，现在可以付款了哦！'];
            var Atime = [];
                Atime.push(data.create_time);
            if (data.pay_time != '') {
                var time = data.pay_time.split(' ');
                $('.J_pay_box small').eq(0).html(time[0]);
                $('.J_pay_box small').eq(1).html(time[1]);
                $('.J_pay_box p').html('已付款');
                Atime.push(data.pay_time);
                $('.J_practical_price').html('实付金额:');
                content.push('亲，您的付款已收到，马上为您发货哟！');
                $('.J_order_msg').html('亲，您的付款已收到，马上为您发货哟！');
            }
            if (data.deliver_time != '') {
                var time = data.deliver_time.split(' ');
                $('.J_ship_box small').eq(0).html(time[0]);
                $('.J_ship_box small').eq(1).html(time[1]);
                Atime.push(data.deliver_time);
                $('.J_ship_box p').html('已发货');
                $('.J_order_msg').html('亲，已经为您发货了，请耐心等待送达哟！');
                content.push('亲，已经为您发货了，请耐心等待送达哟！');
                //获取物流
                var ldata = {
                    order_no: url('?no')
                }
                function logisticsCB(data) {
                    if(data.detail){
                        var len = data.detail.length;
                        for (var i = len - 1; i > 0; i--) {
                            Atime.push(data.detail[i].ftime);
                            content.push(data.detail[i].context)
                        }
                    }
                }
                function errCB(data) {
                    if (data.status == 3212) {
                        Atime.push('无');
                        content.push('暂未查询到物流信息')
                    }
                }
                requestUrl('/account/index/express', 'GET', ldata, logisticsCB, errCB, false);
            }
            if (data.receive_time != '') {
                var time = data.receive_time.split(' ');
                $('.J_receive_box small').eq(0).html(time[0]);
                $('.J_receive_box small').eq(1).html(time[1]);
                Atime.push(data.receive_time);
                $('.J_receive_box p').html('已收货');
                $('.J_order_msg').html('亲，本次交易已完成，欢迎再次光临哦！');
                content.push('亲，本次交易已完成，欢迎再次光临哦！');
            }
            if (data.cancel_time != '') {
                Atime.push(data.cancel_time);
                content.push('亲，您的订单已取消！');
                $('.J_order_msg').html('亲，您的订单已取消！');
            }
            if (data.close_time != '') {
                Atime.push(data.close_time);
                content.push('亲，您的订单已关闭！');
                $('.J_order_msg').html('亲，您的订单已关闭！');
            }
            ['status-pay', 'status-ship', 'status-receive'].forEach(function(ele, index) {
                if($('.apx-order-status.' + ele).length) {
                    setInterval(function setTime(){
                        syncOrderStatusTime($('.apx-order-status .col-xs-3').eq(index));
                    }, 1000);
                }
            });
            //订单处理时间列表
            var timeData = {
                Atime: Atime.reverse(),
                content: content.reverse()
            }
            var tpl_time = $('#J_tpl_time').html();
            var result = juicer(tpl_time, timeData);

            $('.J_time_list').append(result);
            $('.J_consignee').html(data.consignee);
            $('.J_address').html(data.address);
            $('.J_mobile').html(data.mobile);
            $('.J_deliver_time').html(data.deliver_time);
            $('.J_order_price').html('¥ ' + data.items_fee.toFixed(2));
            $('.J_end_price').html('¥ ' + data.total_fee.toFixed(2));
            $('.J_refund_price').html('¥ ' + data.refund_rmb.toFixed(2));
            $('.J_coupon_price').html('¥ ' + data.coupon_rmb.toFixed(2));
            // 优惠券信息
            if (data.coupon_rmb > 0) {
                $('.J_coupon_name').html(data.coupon_info.name);
                $('.J_coupon_brand').html(data.coupon_info.supplier);
                $('.J_limit_price').html('¥ ' + parseFloat(data.coupon_info.consumption_limit).toFixed(2));
            }
            // 快递
            $('#J_express_name').html(data.express_corporation);
            $('#J_express_code').html(data.express_number);

            // 支付方式
            $('#J_pay_ment').html(data.pay_method);

            //订单详情
            var pric = function (data){
                return data.toFixed(2)
            }
            juicer.register('pric_build', pric);
            var tpl_detail = $('#J_tpl_detail').html();
            var proContent = juicer(tpl_detail, data);
            $('.J_product_box').html(proContent);
                
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
        requestUrl('/account/order/info', 'GET', data, orderCB);
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