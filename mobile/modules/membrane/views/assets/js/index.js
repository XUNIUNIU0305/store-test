$(function() {
	function success(data){
		if (data.level !== 2) {
			$('.J_apex_tab').removeClass('hidden')
		}
	};
	requestUrl("/index/get-user-status", 'GET','',success, function() {}, false);
})

//设置最小高度
$(function() {
	var minHeight = $('.container').height() - 0 + 50;
	$('#J_maskLayers').css('minHeight', minHeight);
})

$(function() {
	var g = {
		count: 0,
		addressId: undefined,
		price: 0,
		address: [],
		balance: 0,
		payment: 3,
		options: {},
		label: '',
		coefficient: '',
		id: undefined
	}

	// 验证购买权限
	function verifyPower() {
		requestUrl('/membrane/home/validate', 'GET', '', function(data) {
			
		}, function(data) {
			alert(data.data.errMsg)
			location.href = '/'
		})
	}
	verifyPower()

	//获取地址 
	function getAddress() {
		requestUrl('/membrane/home/address', 'GET', '', function(data) {
			g.address = data;
			$.each(data, function(i, v) {
				if (v.is_default === true) {
					$('.J_address_default').addClass('is');
					$('.J_address_name').text(v.consignee);
					$('.J_address_mobile').text(v.mobile);
					$('.J_address_detail').text(v.province.name + v.city.name + v.district.name + v.detail);
					g.addressId = v.id;
				}
			})
			var tpl = $('#J_tpl_address').html();
			$('.J_address_list').html(juicer(tpl, data))
		})
	}

	//获取余额 
	function getBalance() {
		requestUrl('/membrane/home/balance', 'GET', '', function(data) {
			g.balance = data.rmb;
			$('.J_account_blance').html(data.rmb.toFixed(2))
		})
	}

	// 获取产品
	var membrane ;
	function getProduct() {
		requestUrl('/membrane/home/product', 'GET', '', function(data) {
			membrane = data;
			// 初始化天御车膜价格
			var ty = data[0].params;
			$('#ty-coefficient-list-cont li').eq(0).data('price', ty[0].price).data('id', ty[0].id);
			$('#ty-coefficient-list-cont li').eq(1).data('price', ty[1].price).data('id', ty[1].id);
			$('#ty-coefficient-list-cont li').eq(2).data('price', ty[2].price).data('id', ty[2].id);
			$('#ty-coefficient-list-cont li').eq(3).data('price', ty[3].price).data('id', ty[3].id);
			$('#ty-coefficient-list-cont li').eq(4).data('price', ty[5].price).data('id', ty[5].id);
			$('#ty-coefficient-list-cont li').eq(5).data('price', ty[4].price).data('id', ty[4].id);

			// 初始化欧帕斯车膜价格
			var _apex = data[1].params;
			$.each(_apex, function(i, val) {
				$('#ops-choose-set-meal-box [data-base-price="' + val.id + '"]').text(val.price.toFixed(2))
			})

			var apex_item = membrane[1].params;
            apex.package = {
                1: {
                    1: {
                        price: apex_item[0].price,
                        id: apex_item[0].id
                    },
                    2: {
                        price: apex_item[1].price,
                        id: apex_item[1].id,
                    },
                    3: {
                        price: apex_item[2].price,
                        id: apex_item[2].id,
                    },
                    4: {
                        price: apex_item[3].price,
                        id: apex_item[3].id,
                    },
                    5: {
                        price: apex_item[25].price,
                        id: apex_item[25].id,
                    },
                    6: {
                        price: apex_item[4].price,
                        id: apex_item[4].id,
                    },
                },
                2: {
                    1: {
                        price: apex_item[5].price,
                        id: apex_item[5].id,
                    },
                    2: {
                        price: apex_item[6].price,
                        id: apex_item[6].id,
                    },
                    3: {
                        price: apex_item[7].price,
                        id: apex_item[7].id,
                    },
                    4: {
                        price: apex_item[8].price,
                        id: apex_item[8].id,
                    },
                    5: {
                        price: apex_item[26].price,
                        id: apex_item[26].id,
                    },
                    6: {
                        price: apex_item[9].price,
                        id: apex_item[9].id,
                    },
                },
                3: {
                    1: {
                        price: apex_item[10].price,
                        id: apex_item[10].id,
                    },
                    2: {
                        price: apex_item[11].price,
                        id: apex_item[11].id,
                    },
                    3: {
                        price: apex_item[12].price,
                        id: apex_item[12].id,
                    },
                    4: {
                        price: apex_item[13].price,
                        id: apex_item[13].id,
                    },
                    5: {
                        price: apex_item[27].price,
                        id: apex_item[27].id,
                    },
                    6: {
                        price: apex_item[14].price,
                        id: apex_item[14].id,
                    },
                },
                4: {
                    1: {
                        price: apex_item[15].price,
                        id: apex_item[15].id
                    },
                    2: {
                        price: apex_item[16].price,
                        id: apex_item[16].id,
                    },
                    3: {
                        price: apex_item[17].price,
                        id: apex_item[17].id,
                    },
                    4: {
                        price: apex_item[18].price,
                        id: apex_item[18].id,
                    },
                    5: {
                        price: apex_item[28].price,
                        id: apex_item[28].id,
                    },
                    6: {
                        price: apex_item[19].price,
                        id: apex_item[19].id,
                    },
                },
                5: {
                    1: {
                        price: apex_item[20].price,
                        id: apex_item[20].id
                    },
                    2: {
                        price: apex_item[21].price,
                        id: apex_item[21].id,
                    },
                    3: {
                        price: apex_item[22].price,
                        id: apex_item[22].id
                    },
                    4: {
                        price: apex_item[23].price,
                        id: apex_item[23].id,
                    },
                    5: {
                        price: apex_item[29].price,
                        id: apex_item[29].id,
                    },
                    6: {
                        price: apex_item[24].price,
                        id: apex_item[24].id,
                    },
                }
			}
		})	
	}

	//初始化页面
	getBalance()
	getAddress()
	getProduct()


	// 选择地址
	$('#J-address-selected').on('click', '.J_add_address', function() {
		$('#J-address-selected').addClass('hidden');
	})
	$('#J_select_address').on('click', function() {
		$('#J-address-selected').removeClass('hidden');
	})
	$('#J-address-selected').on('click', '.J_address_item', function() {
		var id = $(this).data('id');
		$.each(g.address, function(i, v) {
			if (v.id == id) {
				$('.J_address_name').text(v.consignee);
				$('.J_address_mobile').text(v.mobile);
				$('.J_address_detail').text(v.province.name + v.city.name + v.district.name + v.detail);
				g.addressId = v.id;
				if (v.is_default === true) {
					$('.J_address_default').addClass('is');
				} else {
					$('.J_address_default').removeClass('is');
				}
			}
		})
		$('#J-address-selected').addClass('hidden');
	})


	// 数据交互
	// 天御车膜
	var tianyu = {
		package: {
            '1': [
                {
                    label: 1,
                    value: 1
                },
                {
                    label: 2,
                    value: 2
                },
                {
                    label: 3,
                    value: 2
                },
                {
                    label: 4,
                    value: 2
                },
                {
                    label: 5,
                    value: 2
                },
                {
                    label: 6,
                    value: 2
                }
            ],
            '2': [
                {
                    label: 1,
                    value: 1
                },
                {
                    label: 2,
                    value: 3
                },
                {
                    label: 3,
                    value: 3
                },
                {
                    label: 4,
                    value: 3
                },
                {
                    label: 5,
                    value: 3
                },
                {
                    label: 6,
                    value: 3
                }
            ],
            '3': [
                {
                    label: 1,
                    value: 1
                },
                {
                    label: 2,
                    value: 4
                },
                {
                    label: 3,
                    value: 4
                },
                {
                    label: 4,
                    value: 4
                },
                {
                    label: 5,
                    value: 4
                },
                {
                    label: 6,
                    value: 4
                }
            ]
		},
		countPrice: function() {
			// 计算总价
			var type = $('#ty-choose-set-meal-box .c-coefficient-pic-active').parents('.stall-parameters').data('package');
			var price = $('#ty-coefficient-list-cont li[class*="active"]').data('price');
			var num = $('#ty-purchase-quantity').data('val');
			$('#J_ty_num').text(num);
			if (!type) {
				$('#J_ty_price').text('0.00');
				return
			}
			$('#J_ty_price').text((price * num).toFixed(2));
		},
		init: function() {
			// 绑定选择套餐事件
			$('#ty-choose-set-meal-box').on('click', '.stall-parameters', function() {
				$(this).find('.c-coefficient-pic').addClass('c-coefficient-pic-active');
				$(this).find('.stall-parameters-cont').show();
				$(this).siblings('.stall-parameters').find('.c-coefficient-pic').removeClass('c-coefficient-pic-active');
				$(this).siblings('.stall-parameters').find('.stall-parameters-cont').hide();
				var package = $(this).data('package');
				if (package == '4') {
					$("#custom-combination-cont").show();
				}
			})
			// 绑定选择自定义套餐
			$('#custom-combination-list').on('click', 'li', function() {
				$('#gate-gear-cont li').removeClass('active');
				var label = $(this).data('label');
				var name = {
					1: '前档',
					2: '后档',
					3: '左后档',
					4: '左前档',
					5: '右前档',
					6: '右后档',
				}
				var value = $(this).data('value');
				if (label !== 1) {
					$('#gate-gear-cont').show().find('.J_place_name').text(name[label]);
					$('#gate-gear-cont').data('option', label);
					if (value) {
						$('#gate-gear-cont').find('li[data-id="' + value +'"]').addClass('active')
					}
				}
			})
			// 绑定选择自定义套餐位置
			$('#gate-parameter-list-1').on('click', 'li', function() {
				var id = $(this).data('id');
				var name = $(this).text();
				var label = $('#gate-gear-cont').data('option');
				$('#custom-combination-list li[data-label="' + label + '"]').data('value', id).find('span').text(name);
				$('#gate-gear-cont').hide();
			})
			// 关闭位置弹窗
			$('#custom').on('click', function() {
				$('#custom-combination-cont').hide();
			})
			// 确定及关闭套餐选择
			$('#ty-set-meal-confirmation').on('click', function() {
				$('#ty-choose-set-meal-box').hide();
				var name = $('#ty-choose-set-meal-box .c-coefficient-pic-active').data('name');
				var type = $('#ty-coefficient-list-cont .active').text();
				if (!name) {
					name = '未选择'
				}
				$('#ty-coefficient .parameter-sele-txt').text('套餐/系数：' + name + '/' + type);
				tianyu.countPrice()
			})
			// 减少天御数量
			$('#ty-del-number').on('click', function() {
				var val = $('#ty-purchase-quantity').data('val');
				val--;
				if (val === 1) {
					$('#ty-del-number').addClass('btn-disabled');
				}
				if (val >= 1) {
					$('#ty-purchase-quantity').text(val).data('val', val);
				}
				tianyu.countPrice()
			})
			// 增加天御数量
			$('#ty-add-number').on('click', function() {
				var val = $('#ty-purchase-quantity').data('val');
				val++;
				$('#ty-del-number').removeClass('btn-disabled');
				$('#ty-purchase-quantity').text(val).data('val', val);
				tianyu.countPrice()
			})

			// 天御售价参考弹窗
			$('#ty-price-reference-cont').on('click', '.c-coefficient', function() {
				var show = $(this).data('show');
				if (show) {
					$(this).find('.c-coefficient-pic').removeClass('c-coefficient-pic-active');
					$(this).find('.c-coefficient-cont').hide();
					$(this).data('show', false);
				} else {
					$(this).find('.c-coefficient-pic').addClass('c-coefficient-pic-active');
					$(this).find('.c-coefficient-cont').show();
					$(this).data('show', true);
				}
			})

			// 选择收货地址
			$('.ty-receiving-addr').on('click', function() {
				$('#J-address-selected').removeClass('hidden')
			})
			
			//支付方式选择
			$(".payment-method-2").on("click",function(){
				var $this = $(this);
				var payment = $(this).data('payment');
				g.payment = payment;
				$('.payment-method-2[data-payment="' + payment + '"]').find('.payment-method-sele').addClass('active');
				$('.payment-method-2[data-payment="' + payment + '"]').siblings('.payment-method-2').find('.payment-method-sele').removeClass('active')
			});

			// 系数弹窗
			$('.J_show_reference').on('click', function() {
				$('#J_modal_type').show();
			})
			$('#J_type_close').on('click', function() {
				$('#J_modal_type').hide();
			})

		}
	}
	tianyu.init()

	// 欧帕斯车膜购买
	var apex = {
		package: {},
		attr: {
            1: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 6
                },
                {
                    label: 3,
                    value: 6
                },
                {
                    label: 4,
                    value: 6
                },
                {
                    label: 5,
                    value: 6
                },
                {
                    label: 6,
                    value: 6
                }
            ],
            2: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 7
                },
                {
                    label: 3,
                    value: 7
                },
                {
                    label: 4,
                    value: 7
                },
                {
                    label: 5,
                    value: 7
                },
                {
                    label: 6,
                    value: 7
                }
            ],
            3: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 8
                },
                {
                    label: 3,
                    value: 8
                },
                {
                    label: 4,
                    value: 8
                },
                {
                    label: 5,
                    value: 8
                },
                {
                    label: 6,
                    value: 8
                }
            ],
            4: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 9
                },
                {
                    label: 3,
                    value: 9
                },
                {
                    label: 4,
                    value: 9
                },
                {
                    label: 5,
                    value: 9
                },
                {
                    label: 6,
                    value: 9
                }
            ],
            5: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 10
                },
                {
                    label: 3,
                    value: 10
                },
                {
                    label: 4,
                    value: 10
                },
                {
                    label: 5,
                    value: 10
                },
                {
                    label: 6,
                    value: 10
                }
            ]
        },
		countPrice: function() {
			// 计算欧帕斯价格  
            var package = apex.package;
			var type = $('#ops-choose-set-meal-box .c-coefficient-pic-active').parents('.stall-parameters').data('type');
			var c_type = $('#ops-coefficient-list-cont li[class*="active"]').data('type');
			var num = $('#ops-purchase-quantity').data('val');
			$('#J_apex_num').text(num)
			if (!type) {
				$('#J_apex_price').text('0.00')
				return
			}
			var price = package[type][c_type].price;
			$('#J_apex_price').text((price * num).toFixed(2));
		},
		init: function() {
			// 绑定选择套餐
			$('#ops-choose-set-meal-box').on('click', '.stall-parameters', function() {
				$(this).find('.c-coefficient-pic').addClass('c-coefficient-pic-active');
				$(this).find('.stall-parameters-cont').show();
				$(this).siblings('.stall-parameters').find('.c-coefficient-pic').removeClass('c-coefficient-pic-active');
				$(this).siblings('.stall-parameters').find('.stall-parameters-cont').hide();
			})
			// 确定选择
			$('#ops-set-meal-confirmation').on('click', function() {
				$('#ops-choose-set-meal-box').hide();
				var name = $('#ops-choose-set-meal-box .c-coefficient-pic-active').data('name');
				var type = $('#ops-coefficient-list-cont .active').text();
				if (!name) {
					name = '未选择'
				}
				$('#ops-coefficient .parameter-sele-txt').text('套餐/系数：' + name + '/' + type);
				apex.countPrice()
			})
			// 减少欧帕斯数量
			$('#ops-del-number').on('click', function() {
				var val = $('#ops-purchase-quantity').data('val');
				val--;
				if (val === 1) {
					$('#ops-del-number').addClass('btn-disabled');
				}
				if (val >= 1) {
					$('#ops-purchase-quantity').text(val).data('val', val);
				}
				apex.countPrice()
			})
			// 增加欧帕斯数量
			$('#ops-add-number').on('click', function() {
				var val = $('#ops-purchase-quantity').data('val');
				val++;
				$('#ops-del-number').removeClass('btn-disabled');
				$('#ops-purchase-quantity').text(val).data('val', val);
				apex.countPrice()
			})

			// 天御售价参考弹窗
			$('#ops-price-reference-cont').on('click', '.c-coefficient', function() {
				var show = $(this).data('show');
				if (show) {
					$(this).find('.c-coefficient-pic').removeClass('c-coefficient-pic-active');
					$(this).find('.c-coefficient-cont').hide();
					$(this).data('show', false);
				} else {
					$(this).find('.c-coefficient-pic').addClass('c-coefficient-pic-active');
					$(this).find('.c-coefficient-cont').show();
					$(this).data('show', true);
				}
			})

			// 欧帕斯详情
			$('#J_show_apex_detail').on('click', function() {
				$('.apex-product-detail-modal').removeClass('hidden')
			})
		}
	}
	apex.init()



	// 支付
	$('.J_sure_pay_money').on('click', function() {
		var type = $('#myTab li[class*="active"]').data('type');
		var items = [];
		if (type === 1) {
			// 支付天御车膜
			var package = $('#ty-choose-set-meal-box .c-coefficient-pic-active').parents('.stall-parameters').data('package');
			var id = $('#ty-coefficient-list-cont li[class*="active"]').data('id');
			var remarks = $('#J_ty_remarks').val();

			if (!package) {
				alert('请选择套餐')
				return
			}
			var attributes = [];
			if (package !== 4) {
				attributes = tianyu.package[package]
			} else {
				$.each($('#custom-combination-list li'), function(i, val) {
					var name = $(this).data('name');
                    var label = $(this).data('label');
                    var val = $(this).data('value');
                    if (!val) {
                        alert('请选择' + name)
                        return false
                    }
                    attributes.push({
                        label: label,
                        value: val
                    })
				})
			}
			if (attributes.length < 6) {
                return
			}
			var num = $('#ty-purchase-quantity').data('val');
			for (var i = 0; i < num; i++) {
                items.push({
                    id: id,
					attributes: attributes,
					remark: remarks
                })                
            }
		} else {
			// 购买欧帕斯
			var type = $('#ops-choose-set-meal-box .c-coefficient-pic-active').parents('.stall-parameters').data('type');
			var c_type = $('#ops-coefficient-list-cont li[class*="active"]').data('type');
			var num = $('#ops-purchase-quantity').data('val');
			if (!type) {
				alert('请选择套餐！')
				return
			}
            var id = apex.package[type][c_type].id;
            var attributes = apex.attr[type];
			var remarks = $('#J_apex_remark').val();
            for (var i = 0; i < num; i++) {
                items.push({
                    id: id,
                    remark: remarks,
                    attributes: attributes
                })                
            }
		}
		var balance = $('.J_account_blance').text() - 0;
		if (g.payment == 1) {
			if (type === 1) {
				var price = $('#J_ty_price').text() - 0;
				if (price > balance) {
					alert('余额不足，请选择其他支付方式！')
					return
				}
			} else {
				var price = $('#J_apex_price').text() - 0;
				if (price > balance) {
					alert('余额不足，请选择其他支付方式！')
					return
				}
			}
		}
		var data = {
			way: g.payment,
			address: g.addressId,
			items: items
		}
		$('.J_sure_pay_money').addClass('diabled')
		requestUrl('/membrane/home/order', 'POST', data, function(data) {
			window.location.href = data.url;
		}, function(data) {
			alert(data.data.errMsg)
			$('.J_sure_pay_money').removeClass('diabled')
		})
	})
})



// 展示交互
$(function() {
	//展示弹窗
	function queryInfo(_ele, _obj){
		_ele.on("click",function(){
			_obj.show();
		});
	}

	//关闭弹窗
	function closePage(_ele, _obj){
		_ele.on("click",function(){
			_obj.hide();
		});
	}

	function detailedPrice(_pics, _conts, _flag){
		_pics.on("click",function(){
			var $this = $(this);
			var $index = $this.data("id");
			if(!$this.data("flag")){
				_pics.removeClass("c-coefficient-pic-active");
				$this.addClass("c-coefficient-pic-active");
				_conts.hide();
				_conts.eq($index-1).show(); 
				_flag && $("#gate-gear-cont").show();
				$("#custom-combination").removeClass("c-coefficient-pic-active");
				$this.data("flag","true");
			}else{
				$this.removeClass("c-coefficient-pic-active");
				_conts.eq($index-1).hide();
				$this.data("flag","false");
			}
		});
	}

	//选择系数
	function seleCoefficient(_lis){
		_lis.on("click",function(){
			$(this).addClass("active").siblings().removeClass("active");
		});
	}

	// 自定义组合车档选择
	function carGearSele(_list, _close){
		var $gateLis = _list.children("li");
		$gateLis.on("click",function(){
			$(this).addClass("active").siblings().removeClass("active");
		});
		
		_close.on("click",function(){
			var $txt = _list.find("li.active").text();
			$("#gate-gear-cont").hide();
			var $index = $(this).data("id");
			$("#custom-combination-txt-" + $index).text($txt);
		});
	}

	//计算数量
	function calculation(_ele, _obj){
		_ele.on("click",function(){
			var $txt = parseInt(_obj.text());
			if(_ele.data("val") === 1){
				var result = $txt - 1;
				result >= 1 ? _obj.text(result) : _obj.text("1").prev().addClass("btn-disabled");
				result >= 1 ? $("#total").text(result) : $("#total").text("1");
			}else{
				var result = $txt + 1;
				_ele.prev().prev().removeClass("btn-disabled");
				_obj.text(result);
				$("#total").text(result);
			}
		});
	}

	$(function(){
		// tab页切换
		$("#myTab>li").on("click",function(){
			var $index = $(this).index();
			$(this).addClass('active').siblings().removeClass('active');
			$("#myTabContent>div").eq($index).addClass('active').siblings().removeClass('active');
		});

		//显示套餐/系数
		queryInfo($("#ty-coefficient"), $("#ty-choose-set-meal-box"));
		queryInfo($("#ops-coefficient"), $("#ops-choose-set-meal-box"));

		//选择套餐/系数
		seleCoefficient($("#ty-coefficient-list-cont>li"));
		seleCoefficient($("#ops-coefficient-list-cont>li"));

		//显示售价参考
		queryInfo($("#ty-price-reference"), $("#ty-price-reference-cont"));
		queryInfo($("#ops-price-reference"), $("#ops-price-reference-cont"));

		//选择售价参考系数并退出
		var $tyList = $("#ty-price-reference-cont").find(".ty-price-close");
		var $opsList = $("#ops-price-reference-cont").find(".apex-price-close");
		$tyList.on("click",function(){
			$("#ty-price-reference-cont").hide();
		});
		$opsList.on("click",function(){
			$("#ops-price-reference-cont").hide();
		});

	});
})