$(function () {
	//兼容没有trim函数的浏览器
	if (!String.prototype.trim) {
		String.prototype.trim = function () {
			return this.replace(/(^[\s\n\t]+|[\s\n\t]+$)/g, "");
		}
	};

	//获取该商品的已存在属性
	function getProductList(id) {
		var _data;
		function listCB(data) {
			_data = data;
		}
		requestUrl('/price/current-sku', 'GET', {product_id: id}, listCB, '', false);
		return _data;
	}

	//清空表单
	function clearForm() {
		setTimeout(function () {
			$('#J_attr_form')[0].reset();
			$('.sort-container').html(`<div class="col-xs-3">
			<input type="text" class="form-control" placeholder="输入分类名称">
	</div>`)
		}, 500)
	}

	$('#J_toggle').on('click', function () {
		clearForm()
	})

	//获取属性内容
	function getAttr() {
		if ($('#J_attr_form input:eq(0)').val().trim() == '') {
			return false
		}
		var attr = [];
		$('#J_attr_form input').each(function () {
			var str = $(this).val().trim();
			if (str != '') {
				attr.push(str)
			}
		})
		return attr
	}

	//生成单个属性
	function creatAttr(attr) {
		var len = $('.J_attr_box').length;
		var data = {
			id: len,
			attrs: attr
		}
		var tpl = $('#J_tpl_attr').html();
		var result = juicer(tpl, data);
		$('#J_addAttr').append(result);
		$('#J_toggle').trigger('click');
	}

	//生成表格数据
	function getTableData() {
		var col = $('.J_attr_box').length;
		var row = 1;
		for (var i = 0; i < col; i++) {
			row *= $('.J_attr_box').eq(i).find('label').length
		}
		var allAttr = [];
		var colSum = [];
		var attrName = [];
		for (var i = 0; i < col; i++) {
			attrName[i] = $('.J_attr_box').eq(i).find('span').html();
			colSum[i] = $('.J_attr_box').eq(i).find('label').length;
			allAttr[i] = {};
		}
		var rowspan = row;
		for (var i = 0; i < col; i++) {
			var n = i;
			rowspan = rowspan / colSum[i];
			for (var j = 0; j < colSum[n]; j++) {
				var z = j * rowspan;
				allAttr[n][z] = $('.J_attr_box').eq(n).find('label').eq(j).html();
			}
		}
		var allAttrBase = $.extend(true, [], allAttr)
		allAttrBase.forEach(function (v, i) {
			for (var x in v) {
				rowspan = row;
				for (var j = 0; j < (i + 1); j++) {
					rowspan = rowspan / colSum[j];
				}
				var n = rowspan * colSum[i];
				var t = 1;
				for (var y = 0; y < i; y++) {
					t = t * colSum[y]
				}
				for (var z = 1; z < t; z++) {
					var o = x - 0 + z * n;
					allAttr[i][o] = v[x];
				}
			}
		})
		var all = [allAttr, col, row, colSum, attrName]
		return all;
	}

	//生成表格
	function getTable(allAttr, col, row, colSum, attrName) {
		var data = {
			allAttr: allAttr,
			col: col,
			row: row,
			colSum: colSum,
			attrName: attrName
		}
		$('#J_table_box').children('table').remove();
		var rowspan = function(data) {
			var arr = [];
			$.each(data[0], function(i, n) {
				arr.push(i)
			})
			if (arr.length == 1) {
				return data[1]
			}
			return arr[1]
		}
		var tpl = $('#J_tpl_table').html();
		juicer.register('rowspan_build', rowspan);
		var table = juicer(tpl, data);
		return table;
	}

	//添加属性
	$('#J_getAttr').click(function () {
		$('#J_table_box').css('display', 'block');
		$('button[type=submit]').removeAttr('disabled');
		var attr = getAttr();
		if (attr == false) {
			alert('类别不能为空！');
			return false;
		}
		if (attr.length < 2) {
			alert('至少需要一个分类名称！');
			return false;
		}
		creatAttr(attr);
		var allData = getTableData();
		var table = getTable(allData[0], allData[1], allData[2], allData[3], allData[4]);
		$('#J_table_box').append(table);
	})

	//删除属性
	$('#J_addAttr').on('click', '.J_delAttr', function () {
		$(this).parents('.J_attr_box').remove();
		if ($('.J_attr_box').length < 1) {
			$('#J_table_box').css('display', 'none');
			$('button[type=submit]').attr('disabled', 'true');
			$('#J_table_box table').remove();
		} else {
			var allData = getTableData();
			var table = getTable(allData[0], allData[1], allData[2], allData[3], allData[4]);
			$('#J_table_box').append(table);
		}
	})

	//获取attrs
	function getSkuAttrs() {
		var len = $('.J_attr_box').length;
		var attrs = [];
		for (var i = 0; i < len; i++) {
			attrs[i] = {};
			var name = $('.J_attr_box').eq(i).find('span').html();
			attrs[i][name] = [];
			for (var j = 0; j < $('.J_attr_box').eq(i).find('label').length; j++) {
				attrs[i][name].push($('.J_attr_box').eq(i).find('label').eq(j).html())
			}
		}
		return attrs;
	}

	//笛卡尔积
	function Cartesian(a, b, c) {
		var ret = [];
		for (var i = 0; i < a.length; i++) {
			for (var j = 0; j < b.length; j++) {
				ret.push(ft(a[i], b[j], c));
			}
		}
		return ret;
	}

	function ft(a, b, c) {
		return a + c + b;
	}
	//多个一起做笛卡尔积  
	function multiCartesian(data, sym) {
		var len = data.length;
		if (len == 0)
			return [];
		else if (len == 1)
			return data[0];
		else {
			var r = data[0];
			for (var i = 1; i < len; i++) {
				r = Cartesian(r, data[i], sym);
			}
			return r;
		}
	}

	//设置价格输入限制
	$('#J_table_box').on('keyup', '.J_price', function () {
		var number = $(this).val().replace(/[^0-9.]/g, '');
		$(this).val(number);
	})
	$('#J_table_box').on('blur', '.J_price', function () {
		var x = $(this).val().match(/\./g)
		if (x == null) {
			$(this).val($(this).val().replace(/^0/, ''))
		} else {
			if (x.length > 1) {
				alert('数字不合法！')
				$(this).val('')
				return
			} else {
				var i = $(this).val().indexOf('.')
				if (i != 1) {
					$(this).val($(this).val().replace(/^0/, ''))
				}
				var len = $(this).val().substring(i)
				if (len.length > 3) {
					alert('最多输入两位小数！')
					$(this).val('')
					return
				}
			}
		}

	})
	//设置数量输入限制
	$('#J_table_box').on('keyup', '.number-fill', function () {
		if ($(this).val().length > 1) {
			$(this).val($(this).val().replace(/[^0-9]/g, ''))
		}
		$(this).val($(this).val().replace(/[^0-9]/g, ''))
	})
	//设置内部ID输入限制
	$('#J_table_box').on('keyup', '.ownid', function () {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z*]/g, ''))
	})
	//设置条形码输入限制
	$('#J_table_box').on('keyup', '.barcode', function () {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z*]/g, ''))
	})

	//计算单价
	$('#J_table_box').on('keyup', '.cost-fill', function() {
		var $this = $(this);
		var price = $this.val().trim();
		if (price == '') {
			return
		}
		if (price.toString().charAt(price.toString().length - 1) == '.') {
			price = price + '0'
		}
		function salePriceCB(data) {
			$this.parents('td').siblings().find('.price-fill').val(data.price)
		}
		requestUrl('/price/sale-price', 'POST', {cost_price: price}, salePriceCB);
	})

	//获取商品成本价
	function getCostPrice() {
		var price = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			price[i] = $('#J_table_box table tr').eq(i + 1).find('.cost-fill').val()
		}
		return price;
	}

	//获取商品指导价
	function getGuidancePrice() {
		var price = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			price[i] = $('#J_table_box table tr').eq(i + 1).find('.guidance-fill').val()
		}
		return price;
	}

	// 获取商品原价
	function getOriginalPrice() {
		var price = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			price[i] = $('#J_table_box table tr').eq(i + 1).find('.original-fill').val() - 0
		}
		return price;
	}
	// 获取商品原指导价
	function getOriginalGuidancePrice() {
		var price = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			price[i] = $('#J_table_box table tr').eq(i + 1).find('.original-guidance-fill').val() - 0
		}
		return price;
	}
	//获取商品单价
	function getPrice() {
		var price = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			price[i] = $('#J_table_box table tr').eq(i + 1).find('.price-fill').val()
		}
		return price;
	}

	//获取商品数量
	function getNumber() {
		var number = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			number[i] = $('#J_table_box table tr').eq(i + 1).find('.number-fill').val()
		}
		return number;
	}

	//获取商品内部ID
	function getID() {
		var id = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			id[i] = $('#J_table_box table tr').eq(i + 1).find('.ownid').val()
		}
		return id;
	}

	//获取商品条形码
	function getBarcode() {
		var barcode = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.barcode').val()
		}
		return barcode;
	}

	//生成已存在商品数据
	function getOldData(proData) {
		var col = Object.keys(proData.attributes).length;
		var row = Object.keys(proData.sku).length;
		var allData = [],
			colSum = [],
			attrName = [];
		var idArray = Object.keys(proData.attributes),
			attrArray = [],
			sortArray = [];
		for (var i = 0; i < col; i++) {
			attrArray[i] = Object.keys(proData.attributes[idArray[i]]);
			sortArray[i] = Object.keys(proData.attributes[idArray[i]][attrArray[i][0]])
			attrName[i] = attrArray[i][0];
			colSum[i] = sortArray[i].length;
			allData[i] = {};
		}
		var rowspan = row;
		for (var i = 0; i < col; i++) {
			rowspan = rowspan / colSum[i];
			for (var j = 0; j < colSum[i]; j++) {
				var z = j * rowspan;
				allData[i][z] = proData.attributes[idArray[i]][attrArray[i][0]][sortArray[i][j]];
			}
		}
		var allDataBase = $.extend(true, [], allData)
		allDataBase.forEach(function (v, i) {
			for (var x in v) {
				rowspan = row;
				for (var j = 0; j < (i + 1); j++) {
					rowspan = rowspan / colSum[j];
				}
				var n = rowspan * colSum[i];
				var t = 1;
				for (var y = 0; y < i; y++) {
					t = t * colSum[y]
				}
				for (var z = 1; z < t; z++) {
					var o = x - 0 + z * n;
					allData[i][o] = v[x];
				}
			}
		})
		var all = [allData, col, row, colSum, attrName]
		return all;
	}

	//价格等内容填充
	function setContent(row, proData) {
		var content = [];
		$.each(proData.sku, function (i, v) {
			content.push(v)
		})
		for (var i = 0; i < row; i++) {
			$('.cost-fill').eq(i).val(content[i].cost_price);
			$('.guidance-fill').eq(i).val(content[i].guidance_price);
			$('.price-fill').eq(i).val(content[i].price);
			$('.number-fill').eq(i).val(content[i].stock);
			$('.ownid').eq(i).val(content[i].custom_id);
			$('.barcode').eq(i).val(content[i].bar_code);
			$('.original-fill').eq(i).val(content[i].original_price === 0 ? '' : content[i].original_price);
			$('.original-guidance-fill').eq(i).val(content[i].original_guidance_price === 0 ? '' : content[i].original_guidance_price);
		}
	}
	var proData = getProductList(url('?product_id'));
	if (proData.sku.length < 1) {
		var flag = true;
		//页面初始化状态
		$('#J_table_box').css('display', 'none');
		$('button[type=submit]').attr('disabled', 'true');

	} else {
		var flag = false;
		//页面初始化
		$('#J_addAttr').css('display', 'none');
		var all = getOldData(proData);
		var table = getTable(all[0], all[1], all[2], all[3], all[4]);
		$('#J_table_box').append(table);
		setContent(all[2], proData);
	}


	//获取分隔符
	function splitCB(data) {
		$('#J_table_box').data({
			'attrkey': data.key_value,
			'attrs': data.attrs
		})
	}
	requestUrl('/price/split', 'GET', '', splitCB);

	//发布
	$('button[type=submit]').on('click', function () {
		if ($('#J_table_box tr:eq(0) td').length < 5) {
			alert('没有发布内容！')
			return false;
		}
		var len = $('.price-fill').length;
		for (var i = 0; i < len; i++) {
			var price = $('.price-fill').eq(i).val() - 0;
			var number = $('.number-fill').eq(i).val();
			var cost_price = $('.cost-fill').eq(i).val() - 0;
			var guidance_price = $('.guidance-fill').eq(i).val() - 0;
			var original_price = $('.original-fill').eq(i).val().trim();
			var original_guidance_price = $('.original-guidance-fill').eq(i).val().trim();
			if (price == '' || number === '' || cost_price == '' || guidance_price == '') {
				alert('所有价格和数量必须填写完整！')
				return false;
			}
			if (price <= 0) {
				alert('单价不能为0！')
				return false;
			}
			if (number < 0 || number === '') {
				alert('数量应为正数！')
				return false;
			}
			// if (guidance_price <= price) {
			// 	alert('指导价格必须大于单价！')
			// 	return false;
			// }
			// if (guidance_price <= cost_price) {
			// 	alert('指导价格必须大于未含税价！')
			// 	return false;
			// }
			if (original_price !== '') { 
				if (original_price <= cost_price) { 
					alert('原价格必须大于单价！') 
					return false; 
				} 
			} 
			if (original_guidance_price !== '') { 
				if (original_guidance_price <= guidance_price) { 
					alert('原指导价格必须大于指导价！') 
					return false; 
				} 
			} 
		}
		if (flag) {
			var skuAttrs = getSkuAttrs();
			var dataAttrs = $('#J_table_box').data('attrs');
			var dataKey = $('#J_table_box').data('attrkey');
			var result = [];
			for (var i = 0; i < $('.J_attr_box').length; i++) {
				result[i] = [];
				for (var j = 0; j < $('.J_attr_box').eq(i).find('label').length; j++) {
					result[i].push(i + dataKey + j)
				}
			}
			var z = multiCartesian(result, dataAttrs);

			var proCost = getCostPrice();
			var proGuidance = getGuidancePrice();
			var proNum = getNumber();
			var proId = getID();
			var proBar = getBarcode();
			var proOriginal = getOriginalPrice();
			var proOriginalGuidance = getOriginalGuidancePrice();
			var cartesian = {};
			for (var i = 0; i < z.length; i++) {
				var skuId = z[i];
				var price = 'price';
				var stock = 'stock';
				var custom_id = 'custom_id';
				var bar_code = 'bar_code';
				var cost_price = 'cost_price';
				var guidance_price = 'guidance_price';
				var original_price = 'original_price';
				var original_guidance_price = 'original_guidance_price';
				cartesian[skuId] = {};
				cartesian[skuId][cost_price] = proCost[i];
				cartesian[skuId][guidance_price] = proGuidance[i];
				cartesian[skuId][stock] = proNum[i];
				cartesian[skuId][custom_id] = proId[i];
				cartesian[skuId][bar_code] = proBar[i];
				cartesian[skuId][original_price] = proOriginal[i];
				cartesian[skuId][original_guidance_price] = proOriginalGuidance[i];
			}
			var _data = {
				product_id: url('?product_id'),
				attrs: skuAttrs,
				cartesian: cartesian
			}
			function resetCB(data) {
				window.location.href = data.url
			}
			requestUrl('/price/sku', 'POST', _data, resetCB);
		} else {
			var skuData = {};
			var proCostPirce = getCostPrice();
			var proGuidancePirce = getGuidancePrice();
			var proNum = getNumber();
			var proId = getID();
			var proBar = getBarcode();
			var proOriginal = getOriginalPrice();
			var proOriginalGuidance = getOriginalGuidancePrice();
			var skuId = []
			$.each(proData.sku, function (i, v) {
				skuId.push(proData.sku[i].id)
			})
			for (var i = 0; i < Object.keys(proData.sku).length; i++) {
				skuData[skuId[i]] = {
					cost_price: proCostPirce[i],
					guidance_price: proGuidancePirce[i],
					stock: proNum[i],
					custom_id: proId[i],
					bar_code: proBar[i],
					original_price: proOriginal[i],
					original_guidance_price: proOriginalGuidance[i]
				}
			}
			var data = {
				product_id: url('?product_id'),
				sku: skuData
			}
			function submitCB(data) {
				window.location.href = data.url;
			}
			requestUrl('/price/modify-sku', 'POST', data, submitCB);
		}

	})

	// 初始化tooltip
	$('[data-toggle="tooltip"]').tooltip();

	//新增添加分类名称按钮
	$('.add-sort-name').on('click',function () {
		$('.sort-container').append(`<div class="col-xs-3">
		<input type="text" class="form-control" placeholder="输入分类名称">
</div>`);
	})
});