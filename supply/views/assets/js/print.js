$(function() {
	var _id = url('?order_id');
	//获取订单信息
	function getOrderInfo(id) {
		requestUrl('/print/print-order', 'GET', {order_id: id}, function(data) {
			$('#J_order_id').html(data.order_no);
			$('#J_customer_account').html(data.consignee + '（' + data.customer_account + '）');
			$('#J_express_number').html(data.express_number);
			$('#J_mobile').html(data.mobile);
			$('#J_pay_time').html(data.pay_time);
			$('#J_address').html(data.address);
			$('#J_create_time').html(data.create_time);
			var tpl = $('#J_tpl_table').html();
			function attributes(data) {
				var attr = '';
				$.each(data, function(i, v) {
					attr += v.attribute + '：' + v.option + '; '
				})
				return attr
			}
			juicer.register('attributes', attributes);
			var html = juicer(tpl, data);
			$('#J_table_box').html(html);
		})
	}
	getOrderInfo(_id);
	Date.prototype.Format = function (fmt) {
	    var o = {
	        "M+": this.getMonth() + 1, //月份 
	        "d+": this.getDate(), //日 
	        "h+": this.getHours(), //小时 
	        "m+": this.getMinutes(), //分 
	        "s+": this.getSeconds(), //秒 
	        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
	        "S": this.getMilliseconds() //毫秒 
	    };
	    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	    for (var k in o)
	    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
	    return fmt;
	}
	var _date = new Date().Format("yyyy-MM-dd hh:mm:ss");
	$('.time-box').html(_date);
})