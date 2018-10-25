$(function() {
	//获取API地址
	var api = ''
	requestUrl('/api-hostname', 'GET', '', function(data) {
		api = data.hostname;
		//获取汽车品牌
		requestUrl('/quality/price/get-car-brand', 'GET', '', function(data) {
			var options = '<option value="-1">请选择</option>';
			for (var i = 0; i < data.length; i++) {
				options += '<option value="' + data[i].id + '">'+ data[i].name + '</option>'
			}
			$('#J_brand_list').html(options);
		})
		//获取汽车类型
		$('#J_brand_list').on('change', function() {
			var id = $(this).val();
			$('#J_type_list').html('<option value="-1">请选择</option>');
			if (id == -1) return;
			requestUrl('/quality/price/get-car-type', 'GET', {brand_id: id}, function(data) {
				var options = '<option value="-1">请选择</option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i].id + '">'+ data[i].name + '</option>'
				}
				$('#J_type_list').html(options);
			})
		})
		/*//获取施工位置
		requestUrl( api + '/quality/place', 'GET', '', function(data) {
			for (var i = 0; i < $('.package').length; i++) {
				$('.package').eq(i).data('place', data[0].id)
			}
		})
		//获取报价列表
		var tpl = $('#J_tpl_list').html();
		function star(data) {
			if (data == '') return '';
			var star = '';
			for (var i = 0; i < data; i++) {
				star += '<i class="glyphicon glyphicon-star-empty"></i>'
			}
			return star
		}
		juicer.register('star_build', star);
		function getPriceList(package, type) {
			var data = {
				package_id: package,
				type_id: type
			}
			requestUrl(api + '/quality/get-price-list', 'GET', data, function(data) {
				var html = juicer(tpl, data);
				$('#J_price_list').html(html);

				//加载全档属性数据信息
				for (var i=0;i<data.length;i++){
					//修改名称
					$(".J_package_name:eq("+i+")").html(data[i].material.name);
					var attr=data[i].material.attribute;
					var html="";
					for(var j=0;j<attr.length;j++){
                        html += '<li>' + attr[j].name + '<span class="text-warning"> ' + attr[j].value + '</span></li>';
                    }
                    $(".J_package_attr:eq("+i+")").html(html);
				}


			})
		}
		//获取车型报价
		function getCarPrice(package, place, type) {
			var data = {
				package_id: package,
				place_id: place,
				type_id: type
			}
			requestUrl(api + '/quality/get-price', 'GET', data, function(data) {
				$('.J_package_price').html('￥' + data.price);

				/*
				/*
				var html = '';
				for (var i = 0; i < data.material.attribute.length; i++) {
					html += '<li>' + data.material.attribute[i].name + '<span class="text-warning">' + data.material.attribute[i].value + '</span></li>';
				}
				$('.J_package_name').html(data.material.name)
				$('.J_package_attr').html(html);

			})
		}*/

        //获取车型报价
        function getCarFactor(brand_id, type_id,package) {
            var data = {
                brand_id: brand_id,
                type_id: type_id,
                package: package
            }
            requestUrl('/quality/price/get-car-factor', 'GET', data, function(data) {
                $('.J_package_price').html('￥' + data.price);
            })
        }
		$('#J_type_list').on('change', function() {
			var id = $('#J_brand_list').val();
			var type = $('#J_type_list').val();
			var package = $('.package[class*="active"]').data('id');
			if (id != -1 && type != -1) {
                getCarFactor(id, type,package);
			}
		})
		$('.package').on('click', function() {
			var $this = $(this);
			$this.addClass('active').parents('li').siblings('li').find('.package').not($this).removeClass('active');
			var id = $('#J_brand_list').val();
			var type = $('#J_type_list').val();
			var package = $('.package[class*="active"]').data('id');
			if (id != -1 && type != -1) {
                getCarFactor(id, type,package);
			}
			var width = ['70%', '60%', '40%', '100%', '90%'];
			$('.progress-inner').css('width', '0');
			for (var i = 0; i < 5; i++) {
				$('.progress-inner').eq(i).stop(true).animate({
					width: width[i]
				}, 1000)
			}
		})
	})


})