$(function(){
	function total_price(data) {
		var price = 0;
		$.each(data, function(i, val) {
			price += val.price * val.count
		})
		return price.toFixed(2)
	}
	function ju_price(data) {
		return parseFloat(data).toFixed(2)
	}
	juicer.register('total_price', total_price);
	juicer.register('ju_price', ju_price);

	// 获取账号等级
     ;(!function getUserInfo() {
         requestUrl('/index/userinfo', 'GET', '', function(data) {
             if (data.level == 4) {
                 $('#J_person_pay').removeClass('hidden')
             }
         })
     }());

	// 省市区数据交互
	var _profile = {};//用户资料信息
	var areaHandle = {
		hostname: '',
		reset: function (item) {
			item.html('<option value="-1">请选择</option>');
			this.refresh();
		},
		refresh: function() {
			$('.selectpicker').selectpicker('refresh');
			$('.selectpicker').selectpicker('show');
		},
		getMsg: function(url, data, fn) {
			requestUrl(url, 'GET', data, function(data) {
				var result = '<option value="-1">请选择</option>';
				for (var i = 0; i < data.length; i++) {
					result += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
				}
				if (data.length == 0) {
					result = ''
				}
				if (typeof (fn) == 'function') fn(result);
			})
		},
		provinceFn: function(result) {
			$('select[class*=J_province]').html(result);
			areaHandle.refresh()

			//省市联动
			$('select[class*=J_province]').off('.pro').on('change.pro', function () {
				areaHandle.reset($('select[class*=J_city]'));
				areaHandle.reset($('select[class*=J_district]'));
				$('.address .col-xs-3').eq(1).removeClass('hidden');
				$('.address .col-xs-3').eq(2).removeClass('hidden');
				if ($(this).val() == -1) return;
				$('select[class*=J_city]').val('-1');
				$('select[class*=J_district]').val('-1');
				var _data = { province: $(this).val() }
				areaHandle.getMsg(areaHandle.hostname + '/district/city', _data, areaHandle.cityFn)
			})

			if (parseInt(_profile.provinceId) > 0) {
				$('select[class*=J_province]').val(_profile.provinceId).trigger("change.pro");
			}
		},
		cityFn: function(data) {
			$('select[class*=J_city]').html(data);
			areaHandle.refresh();
			if (data == '') {
				$('.address .col-xs-3').eq(1).addClass('hidden');
				$('select[class*=J_city]').val(null);
				var _data = {
					province: $('select[class*=J_province]').val(),
					city: 0
				}
				areaHandle.getMsg(areaHandle.hostname + '/district/district', _data, areaHandle.districtFn)
				return
			}
			//市区联动
			$('select[class*=J_city]').off('.cit').on('change.cit', function () {
				areaHandle.reset($('select[class*=J_district]'));
				$('.address .col-xs-3').eq(2).removeClass('hidden');
				$('select[class*=J_district]').val('-1');

				if ($(this).val() == -1) return;
				var _data = { province: $('select[class*=J_province]').val(), city: $('select[class*=J_city]').val() }
				areaHandle.getMsg(areaHandle.hostname + '/district/district', _data, areaHandle.districtFn)
			})


			if (parseInt(_profile.cityId) > 0) {
				$('select[class*=J_city]').val(_profile.cityId).trigger("change.cit");
			}
		},
		districtFn: function (data) {
			$('select[class*=J_district]').html(data);
			areaHandle.refresh();

			if (data == '') {
				areaHandle.reset($('select[class*=J_district]'));
				$('.address .col-xs-3').eq(2).addClass('hidden');
				$('select[class*=J_district]').val(null);
				return
			}

			if (parseInt(_profile.districtId) > 0) {
				$('select[class*=J_district]').val(_profile.districtId);
				areaHandle.refresh();
			}
		},
		init: function() {
			var _this = this;
			//获取省市区API域名
			requestUrl('/api-hostname', 'GET', '', function(data) {
				_this.hostname = data.hostname;
				//获取省级信息
				var address = '';
				address = data.hostname + '/district/province';
				_this.getMsg(address, '', _this.provinceFn);
			});
		}
	}

	// 获取收货地址
	var getAddress = {
		tpl: $("#J_address_tpl").html(),
		default: [],
		tmp: [],
		getItems: function() {
			var $this = this;
			requestUrl('/account/address/list', 'GET', '', function(data) {
				$this.tmp = data;
				var _default = {};
				$.each(data, function(i, val) {
					if (val.is_default) {
						$this.tmp.unshift($this.tmp.splice(i ,i + 1)[0])
						return false
					}
				})
				$('#J_address_list').html(juicer($this.tpl, data));
				// 显示地址
				if ($this.tmp.length > 0) {
					var address = $this.tmp[0];
					var _html = address.province.name + ' ' + address.city.name + ' ' + address.district.name + ' ' + address.detail + ' 收货人：' + address.consignee + ' ' + address.mobile;
					$('.J_end_address').html(_html);
				}
			})
		},
		handle: function() {
			var $this = this;
			$('.J_load_all').on('click', function() {
				$('#J_address_list').removeClass('limit-height');
				$(this).parents('.col-xs-12').addClass('hidden');
			})
			// 切换选中
			$('#J_address_list').on('click', '.apx-settle-address', function() {
				$('#J_address_list .apx-settle-address').removeClass('active');
				$(this).addClass('active');
				var id = $(this).data('id');
				// 显示地址
				$.each($this.tmp, function(i, val) {
					if (val.id == id) {
						var address = val;
						var _html = address.province.name + ' ' + address.city.name + ' ' + address.district.name + ' ' + address.detail + ' 收货人：' + address.consignee + ' ' + address.mobile;
						$('.J_end_address').html(_html);
					}
				})
			})
			// 修改地址
			$('#J_address_list').on('click', '.J_edit_btn', function() {
				var _tmp = $this.tmp[$(this).data('id')];
				$('#modal_name').val(_tmp.consignee);
				$('#modal_address').val(_tmp.detail);
				$('#modal_mobile').val(_tmp.mobile);
				$('#modal_zipcode').val(_tmp.postal_code);
				_profile.provinceId = _tmp.province.id;
				_profile.cityId = _tmp.city.id;
				_profile.districtId = _tmp.district.id;
				areaHandle.init();
				$('#modal-settle-address-info').data('id', _tmp.id);
				$('#modal_title').html('修改收货人信息');
				$('#modal-settle-address-info').modal('show');
				$('#J_reserve_btn').off().on('click', function() {
					var id = $('#modal-settle-address-info').data('id');
					var name = $('#modal_name').val();
					var detail = $('#modal_address').val();
					var mobile = $('#modal_mobile').val();
					var postal_code = $('#modal_zipcode').val();
					var province = $('select[class*=J_province]').val();
					var city = $('select[class*=J_city]').val();
					var district = $('select[class*=J_district]').val();
					var data = {
						consignee: name,
						province: province,
						city: city,
						district: district,
						detail: detail,
						mobile: mobile,
						postal_code: postal_code,
						id: id
					}
					if (name == '') {
						alert('收货人不能为空！')
						return
					}
					if (province == -1 || city == -1 || district == -1) {
						alert("请选择门店所属区域！");
						return
					}
					if (detail == '') {
						alert('请填写详细地址！')
						return
					}
					if (mobile.search(/0?(1)[0-9]{10}/) == -1) {
						alert('手机号格式错误！')
						return
					}
					if (postal_code == '') {
						alert('邮政编码不能为空！')
						return
					}
					$('#modal-settle-address-info').modal('hide');
					requestUrl('/account/address/edit', 'POST', data, function(data) {
						$this.getItems()
					})
				})
			})
			// 新增地址
			$('#J_add_btn').on('click', function() {
				$('#modal-settle-address-info input').val('');
				areaHandle.reset($('select[class*=J_province]'))
				areaHandle.reset($('select[class*=J_city]'))
				areaHandle.reset($('select[class*=J_district]'));
				_profile = {};
				areaHandle.init();
				$('#modal-settle-address-info').modal('show');
				$('#J_reserve_btn').off().on('click', function () {
					var id = $('#modal-settle-address-info').data('id');
					var name = $('#modal_name').val();
					var detail = $('#modal_address').val();
					var mobile = $('#modal_mobile').val();
					var postal_code = $('#modal_zipcode').val();
					var province = $('select[class*=J_province]').val();
					var city = $('select[class*=J_city]').val();
					var district = $('select[class*=J_district]').val();
					var data = {
						consignee: name,
						province: province,
						city: city,
						district: district,
						detail: detail,
						mobile: mobile,
						postal_code: postal_code,
						id: id
					}
					if (name == '') {
						alert('收货人不能为空！')
						return
					}
					if (province == -1 || city == -1 || district == -1) {
						alert("请选择门店所属区域！");
						return
					}
					if (detail == '') {
						alert('请填写详细地址！')
						return
					}
					if (mobile.search(/0?(1)[0-9]{10}/) == -1) {
						alert('手机号格式错误！')
						return
					}
					if (postal_code == '') {
						alert('邮政编码不能为空！')
						return
					}
					$('#modal-settle-address-info').modal('hide');
					requestUrl('/account/address/add', 'POST', data, function (data) {
						$this.getItems()
					})
				})
			})
		},
		init: function() {
			this.getItems();
			this.handle();
		}
	}
	getAddress.init()

	//获取支付方式列表
	function getPay() {
		function payCB(data) {
			if ($('.J_now_balance').length > 0) getBalance();
		}
		requestUrl('/confirm-order/payment', 'GET', '', payCB);
	}
	// getPay();
	// 切换支付方式
	$('.J_paymemt').on('click', function() {
		var name = $(this).data('name');
		$('#J_payment').html(name)
	})
	//显示余额
	function getBalance() {
		function balanceCB(data) {
			$('#J_balance').html(data.rmb.toFixed(2))
		}
		requestUrl('/account/index/balance', 'GET', '', balanceCB)
	}
	getBalance()
	//获取订单信息
	function getOrder() {
		var data = {q: url('?q')};
		function orderCB(data) {
			var tpl_order = $('#J_order_tpl').html();
			var result = juicer(tpl_order, data);
			$('#J_order_box').html(result);
			$('.selectpicker').selectpicker('refresh');
			$('.selectpicker').selectpicker('show');
			// 计算商品总数
			var _count = 0, _price = 0;
			$.each(data, function(i, val) {
				$.each(val.items, function(j, value) {
					$.each(value, function(i, v) {
						_count += parseFloat(v.count)
						_price += v.count * v.price
					})
				})
			})
			$('.J_total_count').html(_count);
			$('.J_total_price').html('¥' + (_price - _coupon.reduction).toFixed(2)).data('price', (_price - _coupon.reduction).toFixed(2));
			$('.J_orders_price').html('¥' + _price.toFixed(2));
			// 根据是否订制及是否已优惠来判断能否使用优惠券
			if (_coupon.reduction > 0) {
				$.each($('.coupon-select'), function (i, val) {
					var type = $(val).data('type').toString();
					if (type === '0') {
						$(this).find('.selectpicker').prop('disabled', true);
						$(this).find('.selectpicker').selectpicker('refresh');
					}
				})
			}
			_coupon.init()
		}
		requestUrl('/confirm-order/list', 'GET', data, orderCB)
	}

	//获取优惠券信息
	var _coupon = {
		coupon: {},
		is_use: {},
		enable: {},
		renderData: {},
		reduction: 0,
		tpl: $('#J_coupon_tpl').html(),
		getCoupon: function(q) {
			var _this = this;
			var _data = {
				q: q
			}
			requestUrl('/confirm-order/get-tickets', 'GET', _data, function (data) {
				$.each(data.valid, function(i, val) {
					_this.coupon[val.id] = val;
				})
				if (data.valid.length < 1) {
					return
				}
				var html = juicer(_this.tpl, data);
				$('#J_coupon_list').html(html);
				requestUrl('/confirm-order/get-suitable-tickets', 'GET', _data, function(data) {
					_this.enable = data;
					if (data.length < 1) {
						return
					}
					$.each($('.coupon-select'), function(i, val) {
						var type = $(val).data('type').toString();
						var supplier = $(val).data('supplier');
						var sku = $(val).data('sku');
						var _tmp = _this.enable[supplier][type];
						var _html = '<option value="-1">不使用</option>';
						var _arr = {};
						if (type === '0') {
							$.each(_tmp, function(i, val) {
								_arr[val] = val;
								_html += '<option value="' + val + '">' + _this.coupon[val].name + '</option>'
							})
							_this.renderData[i] = _arr;
							$(val).find('select').html(_html).data('index', i)
						} else {
							$.each(_tmp[sku], function (i, val) {
								_arr[val] = val;
								_html += '<option value="' + val + '">' + _this.coupon[val].name + '</option>'
							})
							_this.renderData[i] = _arr;
							$(val).find('select').html(_html).data('index', i)
						}
						$('.selectpicker').selectpicker('refresh');
						$('.selectpicker').selectpicker('show');
					})
				})
			})
		},
		handle: function() {
			var _this = this;
			$('#J_order_box').on('changed.bs.select', '.selectpicker', function () {
				var _val = $(this).val();
				if (_val !== '-1') {
					var _selected = $(this).parents('.coupon-select').data('selected');
					if (_selected !== undefined) {
						delete _this.is_use[_selected]
					}
					$(this).parents('.coupon-select').data('selected', _val)
					_this.is_use[_val] = _val;
					var _data = _this.coupon[_val];
					var _html = _data.brand_name + '满' + _data.limit_price + '可减' + _data.price + '元<br>' + '使用时间：' + _data.start_time + '-' + _data.end_time;
					$(this).parents('.coupon-select').siblings('.coupon-info').find('p').html(_html);
				} else {
					var _selected = $(this).parents('.coupon-select').data('selected');
					if (_selected !== undefined) {
						delete _this.is_use[_selected]
					}
					$(this).parents('.coupon-select').siblings('.coupon-info').find('p').html('');
				}
				// 计算优惠券金额
				var _couponPrice = 0;
				$.each(_this.is_use, function(i, val) {
					_couponPrice += parseFloat(_this.coupon[val].price)
				})
				$('.J_coupon_price').html('-￥' + (_couponPrice + parseFloat(_this.reduction)).toFixed(2));
				$('.J_total_price').html(($('.J_total_price').data('price') - _couponPrice).toFixed(2));
			})
			$('#J_order_box').on('show.bs.select', '.selectpicker', function () {
				var i = $(this).data('index');
				var _val = $(this).val();
				var _arr = [];
				$.each(_this.renderData[i], function(i, val) {
					if (!_this.is_use[i]) {
						_arr.push(val)
					}
				})
				if (_val != -1) {
					_arr.push(_val)
				}
				var _html = '<option value="-1">不使用</option>';
				$.each(_arr, function(i, val) {
					_html += '<option value="' + val + '">' + _this.coupon[val].name + '</option>'
				})
				if (_arr.length < 1) {
					_html = '<option value="-1">暂无</option>';
				}
				$(this).parents('.coupon-select').find('select').html(_html);
				$(this).parents('.coupon-select').find('select').val(_val);
				$('.selectpicker').selectpicker('refresh');
				$('.selectpicker').selectpicker('show');
			})
		},
		init: function() {
			this.getCoupon(url('?q'));
			this.handle();
		}
	}
	// 获取满减
	function getReduction() {
		requestUrl('/confirm-order/get-reduction', 'GET', {
		    q: url('?q')
		}, function (data) {
		    if (data.reduce_rmb > 0) {
		        _coupon.reduction = data.reduce_rmb;
		        $('.J_coupon_price').html('-￥' + parseFloat(_coupon.reduction).toFixed(2));
		    }
		    getOrder()
		})
	}
	getReduction()

    // affix bar for cart page
    function initCartBarAffix() {
        $('.J_cart_affix_bar').affix({
            offset: {
                top: ($('.J_cart_affix_bar').offset().top - $(window).innerHeight() + $('.J_cart_affix_bar').innerHeight())
            }
        })
    }
    function destroyCartBarAffix() {
        // destroy affix manually
        $('.J_cart_affix_bar').removeClass('affix-top').removeData();
        $(window).off('.affix');
    }
    // init the affix bar
    $(window).on('scroll', function() {
    	destroyCartBarAffix();
    	initCartBarAffix();
    })

    var bankPay = {
		requestString: function(str) {
		    var params = {};
			var search = str.slice(1);
		    var arr = search.split("&");
		    for (var i = 0; i < arr.length; i++) {
		        var ar = arr[i].split("=");
		        params[ar[0]] = unescape(ar[1])
		    }
		    return params;
		},
		submit: function(url, params) {
			var form = '<form action="' + url + '" method="post" target="_blank">'
			$.each(params, function(i, val) {
				form += '<input type="hidden" name="' + i + '" value="' + val + '" />'
			})
			form += '</form>';
			var dom = $(form);
			dom.appendTo('body').submit().remove();
		}
	}

	//提交订单
	$('#J_submit').on('click', function() {
		var id = $('.apx-settle-address[class*="active"]').data('id');
		var payment = $('label.J_paymemt[class*="active"]').data('payment');
		var _balance = $('.J_now_balance').text() - 0;
		var _price = $('.J_total_price').text() - 0;
		var remark = {};
		for (var i = 0; i < $('.J_remark').length; i++) {
			remark[$('.J_remark').eq(i).parents('.J_single_order').data('skuid')] = $('.J_remark').eq(i).val().trim();
		}
		if (id == undefined) {
			alert('请选择收货地址')
			return
		}
		$('#J_submit').addClass('disable');
		$('#J_submit').text('订单提交中...');
		var data = {
			q: url('?q'),
			address: id,
			payment: payment,
			items: {}
		}
		$.each($('.J_items_box'), function (i, val) {
			var supplier = $(val).data('supplier');
			data.items[supplier] = {};
			data.items[supplier]['0'] = {};
			data.items[supplier]['1'] = {};
		})
		$.each($('.J_items_box'), function(i, val) {
			var supplier = $(val).data('supplier');
			if ($(val).data('type').toString() === '0') {
				var ticket_id = $(val).parents(".content").find('select').val();
				if (ticket_id == -1) {
					data.items[supplier]['0']['ticket'] = '';
				} else {
					data.items[supplier]['0']['ticket'] = ticket_id;
				}
				$.each($(val).find('.item'), function(i, value) {
					var _id = $(value).data('sku').toString();
					data.items[supplier]['0'][_id] = $(value).find('.J_remark_text').val();
				})
			} else {
				var _sku = $(val).data('sku');
				var ticket_id = $(val).parents(".content").find('select').val();
				if (ticket_id == -1) {
					ticket_id = ''
				}
				if (!data.items[supplier]['1'][_sku]) {
					data.items[supplier]['1'][_sku] = [{
						comment: $(val).find('.J_remark_text').val(),
						ticket: ticket_id
					}];
				} else {
					data.items[supplier]['1'][_sku].push({
						comment: $(val).find('.J_remark_text').val(),
						ticket: ticket_id
					})
				}
			}
		})
		requestUrl('/confirm-order/confirm', 'POST', data, function(data) {
			if (payment == '4' || payment == '5') {
				var i = data.url.indexOf('?');
				var str = data.url.substring(i);
				var url = data.url.substring(0, i);
				var params = bankPay.requestString(str);
				$('#apxModalPass').modal({
					keyboard: false,
					backdrop: 'static',
					modal: 'show'
				}).one('hidden.bs.modal', function () {
					window.location.href = '/account'
				})
				$('#J_open_window').one('click', function () {
					bankPay.submit(url, params);
					$('#J_bank_success').addClass('hidden').siblings('p').removeClass('hidden');
					$('#J_open_window').addClass('hidden').siblings('button').removeClass('hidden');
				})
				return
			}
			if (data.url) {
				window.location.href = data.url;
			} else {
				var yes = confirm(data.error);
				window.location.href = '/cart'
				return;
			}
		}, function(data) {
			$('#J_submit').removeClass('disable');
			$('#J_submit').text('提交订单');
			alert(data.data.errMsg);
		})
	})




})
