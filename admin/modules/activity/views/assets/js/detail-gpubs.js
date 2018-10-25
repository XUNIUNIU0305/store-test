$(function () {

    var _product_id = url('?gpubsProductId');

    $('#to_list').on('click', function () {
        location.href = '/activity/gpubs/index'
    })

    function init(id) {

        requestUrl(
            '/activity/gpubs/detail',
            'GET',
            {
                gpubsProductId: id
            },
            function (res) {
                $('.pro-title').text(res.title)
                var pro_sort =[]
                for (let i = 0; i< res.full_category.length; i++) {
                    pro_sort.push(res.full_category[i].name)
                }
                $('.pro-sort').text(pro_sort.join('>'))
                $('.pro-img').attr('src', res.image_path)
                $('.lifecycle_per_group').text(res.gpubs.lifecycle_per_group)
                // 老的成团规则 $('.min_quantity_per_group').text(res.gpubs.min_quantity_per_group)
                $('.max_launch_per_user').text(res.gpubs.max_launch_per_user)
                $('.J_start_time').text(res.gpubs.activity_start_datetime)
                $('.J_end_time').text(res.gpubs.activity_end_datetime)
                // 拼购类型
                var groupType = ''
                if(res.gpubs.gpubs_type == 1){
                	groupType = '自提拼购'
                }else if(res.gpubs.gpubs_type == 2){
                	groupType = '送货拼购'
                }
                $('#groupType').text(groupType)
                // 微信分享内容
                $('#description').text(res.gpubs.description)
                $('#share_title').text(res.gpubs.share_title)
                $('#share_subtitle').text(res.gpubs.share_subtitle)
                $('#filename').attr('src',res.gpubs.filename)
                // 新成团规则
                if(res.gpubs.gpubs_rule_type == 1){
                	$('.min_quantity_per_group').text(res.gpubs.min_member_per_group + ' 人成团')
                }else if(res.gpubs.gpubs_rule_type == 2){
                	$('.min_quantity_per_group').text(res.gpubs.min_quantity_per_group + ' 件成团')
                }else if(res.gpubs.gpubs_rule_type == 3){
                	$('.min_quantity_per_group').text(res.gpubs.min_member_per_group + ' 人 + ' + res.gpubs.min_quantity_per_member_of_group + ' 件成团')
                }
                initPro(res)
            }
        )
    }
    init(_product_id)
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
    // 价格填充
    function setOldPrice(row, proData) {
		var content = [];
		$.each(proData.sku, function (i, v) {
			content.push(v)
		})
		for (var i = 0; i < row; i++) {
			$('.old-price').eq(i).html(content[i].original_price + ' 元').attr('data-id', content[i].original_price)
		}
    }

    //拼购价格
    function setGpPrice(row, proData) {
		var content = [];
		$.each(proData.sku, function (i, v) {
			content.push(v)
		})
		for (var i = 0; i < row; i++) {
			$('.price').eq(i).html(content[i].price + ' 元').attr('data-id', content[i].price)
		}
    }
    //拼购数量
    function setContent(row, proData) {
		var content = [];
		$.each(proData.sku, function (i, v) {
			content.push(v)
		})
		for (var i = 0; i < row; i++) {
			$('.stock').eq(i).html(content[i].stock + ' 件').attr('data-id', content[i].stock)
		}
    }


    var proData,all,table
    function initPro(res) {
        proData = res
        all = getOldData(proData);
        table = getTable(all[0], all[1], all[2], all[3], all[4]);
        $('#J_table_box').append(table);
        setContent(all[2], proData);
        setOldPrice(all[2], proData);
        setGpPrice(all[2], proData);
    }

})
