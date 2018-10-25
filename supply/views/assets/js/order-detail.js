$(function() {
	var tpl = $('#J_tpl_item').html();
	// 获取订单详情
	function getOrderInfo(no) {
		requestUrl('/order/get-detail', 'GET', {order_id: no}, function(data) {
			$('#J_order_no').html(data.order_no);
			$('#J_create_time').html(data.create_time);
            $('#J_pay_time').html(data.pay_time);
			$('#J_pay_ment').html(data.pay_method);
			$('#J_consignee').html(data.consignee);
			$('#J_mobile').html(data.mobile);
            $('#J_address').html(data.address);
            $('#J_express_name').html(data.express_corporation);
            $('#J_express_code').html(data.express_number);
            $('#J_coupon_price').html(parseFloat(data.coupon_rmb).toFixed(2));
            $('#J_refund_price').html(parseFloat(data.refund_rmb).toFixed(2));
            
            
            $('#J_items_box').html(juicer(tpl, data));
            
			if (data.status === 1) {
				$('.supply-order-status').addClass('status-untreated');
			}
			if (data.status === 2) {
				$('.supply-order-status').addClass('status-shipped');
			}
			if (data.status === 3) {
				$('.supply-order-status').addClass('status-received');
			}
			if (data.status === 4) {
				$('.supply-order-status').addClass('status-cancel');
			}
			if (data.status === 5) {
				$('.supply-order-status').addClass('status-closed');
			}
			$('#J_count').html(data.items.length);
            $('.J_items_fee').html(data.items_fee);
			$('.J_total_fee').html(data.total_fee);
			// 状态列表
			var content = ['亲，您的订单已生效，现在可以付款了哦！'];
            var Atime = [];
                Atime.push(data.create_time);
            if (data.pay_time != '') {
                Atime.push(data.pay_time);
                content.push('亲，您的付款已收到，马上为您发货哟！');
            }
            if (data.deliver_time != '') {
                Atime.push(data.deliver_time);
                content.push('亲，已经为您发货了，请耐心等待送达哟！');
                //获取物流
                var ldata = {
                    order_id: url('?no')
                }
                function logisticsCB(data) {
                    var len = data.detail.length;
                    for (var i = len - 1; i > 0; i--) {
                        Atime.push(data.detail[i].ftime);
                        content.push(data.detail[i].context)
                    }
                }
                function errCB(data) {
                    if (data.status == 3212) {
                        Atime.push('无');
                        content.push('暂未查询到物流信息')
                    }
                }
                requestUrl('/order/express-detail', 'GET', ldata, logisticsCB, errCB, false);
            }
            if (data.receive_time != '') {
                Atime.push(data.receive_time);
                content.push('亲，本次交易已完成，欢迎再次光临哦！');
            }
            if (data.cancel_time != '') {
                Atime.push(data.cancel_time);
                content.push('亲，您的订单已取消！');
            }
            if (data.close_time != '') {
                Atime.push(data.close_time);
                content.push('亲，您的订单已关闭！');
            }
            //订单处理时间列表
            var timeData = {
                Atime: Atime.reverse(),
                content: content.reverse()
            }
            var tpl_time = $('#J_tpl_time').html();
            var result = juicer(tpl_time, timeData);

            $('#J_time_list').append(result);
		})
	}
	getOrderInfo(url('?no'));
})