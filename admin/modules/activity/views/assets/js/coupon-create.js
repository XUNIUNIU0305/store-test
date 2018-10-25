$(function () {
    // init the datepicker
    $('.date-picker').datetimepicker({
        locale: 'zh-cn',
        format: "YYYY-MM-DD HH:mm:ss",
        defaultDate: new Date()
    });
    // show date
    $('.date-show').on('click', function() {
        $(this).siblings('input').focus()
    })
    // 错误提示
    function errShow(dom, title) {
    	dom.parents('.form-group').find('.error-msg').html(title).addClass('show');
    }
    //获取店铺列表
    function getSupplier() {
    	requestUrl('/site/supply/get-supply-list', 'GET', {current_page: 1, page_size: 9999}, function(data) {
    		var options = '<option value="-1">选择店铺</option>';
    		for (var i = 0; i < data.codes.length; i++) {
    			options += '<option value="' + data.codes[i].id +'">' + data.codes[i].brand_name + '(ID：' + data.codes[i].id + ')'  + '</option>'
    		}
    		$('#J_supplier_list').html(options);
    		$('.selectpicker').selectpicker('refresh');
    		$('.selectpicker').selectpicker('show');
    	})
    }
    getSupplier()
    //店铺选择
    $('input[name="object"]').on('focus', function() {
        if ($(this).val() === "0" ) {
            $('#J_supplier_list').prop('disabled', true);
        } else {
            $('#J_supplier_list').prop('disabled', false);
        }
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('show');
    })
    //生成优惠券
    $('#J_create_coupon').on('click', function() {
    	$('.error-msg').removeClass('show');
    	$('#apxModalAdminCreate').modal('hide');
    	var name = $('#coupon_name').val().trim();
    	var price = $('#coupon_price').val().trim() - 0;
    	var total = $('#coupon_total').val().trim() - 0;
    	var startTime = $('#coupon_start_time').val();
    	var endTime = $('#coupon_end_time').val();
    	if ($('input[name="upper_limit"]:checked').val() == 0) {
    		var receiveLimit = 0;
    	} else {
    		var receiveLimit = $('#coupon_limit_receive').val().trim();
    		if (!(0 < receiveLimit && receiveLimit <= 9999)) {
    			errShow($('#coupon_limit_receive'), '0<单人持有上限<=9999')
    			return;
    		}
    	}
    	if (name === '') {
    		errShow($('#coupon_name'), '不能为空');
    		return;
    	}
    	if (!(0 < price && price <= 9999999)) {
    		errShow($('#coupon_price'), '请输入正确的面额(0<面额<=9999999)');
    		return;
    	}
    	if (!(0 < total)) {
    		errShow($('#coupon_total'), '请输入正确的发行量(0<发行量)');
    		return;
    	}
		var priceLimit = $('#coupon_limit_price').val().trim();
		if (!(0 < priceLimit && priceLimit <= 10000000)) {
			errShow($('#coupon_limit_price'), '0<金额限制<=10000000')
			return;
		}
    	if ($('input[name="object"]:checked').val() == 0) {
    		var id = 0;
    	} else {
    		var id = $('#J_supplier_list').val();
    		if (id == -1) {
    			errShow($('#J_supplier_list'), '请选择店铺');
    			return;
    		}
    	}
        if (priceLimit - 0 < price + 1) {
            errShow($('#coupon_limit_price'), '使用限额应大于等于优惠券面额+1');
            return;
        }
    	var data = {
    		name: name,
    		price: price,
    		total_limit: priceLimit,
    		receive_limit: receiveLimit,
    		total_quantity: total,
    		start_time: startTime,
    		end_time: endTime,
    		supply_user_id: id
    	}
    	$('#J_create_coupon').addClass('disabled');
    	requestUrl('/activity/coupon/create-coupon', 'POST', data, function(data) {
    		window.location.href = '/activity/coupon/index'
    	}, function(data) {
    		$('#J_create_coupon').removeClass('disabled');
    		$('#J_alert_content').html(data.data.errMsg);
    		$('#apxModalAdminAlert').modal('show');
    	})
    })
})
