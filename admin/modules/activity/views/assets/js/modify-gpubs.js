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
                file_name = res.gpubs.filename.substring(res.image_path.indexOf('.com/') + 4,res.image_path.length)
                $('#description').text(res.gpubs.description)
                $('#share_title').val(res.gpubs.share_title)
                $('#share_subtitle').val(res.gpubs.share_subtitle)
                $('#imgShow').attr('src',res.gpubs.filename)
                $('#filename').attr('value',file_name)
                $('#infoShow').fadeOut(0)
                $('#imgShow').fadeIn(0)
                // 新成团规则
                if(res.gpubs.gpubs_rule_type == 1){
                    $('.min_quantity_per_group').html(res.gpubs.min_member_per_group + ' 人成团')
                    $('.min_quantity_per_group').attr('value',1)
                }else if(res.gpubs.gpubs_rule_type == 2){
                    $('.min_quantity_per_group').html(res.gpubs.min_quantity_per_group + ' 件成团')
                    $('.min_quantity_per_group').attr('value',2)
                }else if(res.gpubs.gpubs_rule_type == 3){
                    $('.min_quantity_per_group').html(res.gpubs.min_member_per_group + ' 人 + <span id="minqu">' + res.gpubs.min_quantity_per_member_of_group + '</span> 件成团')
                    $('.min_quantity_per_group').attr('value',3)
                }
                initPro(res)

                $('#J_table_box').on('blur','.J_count' ,function () {
                    $(this).val($(this).val().replace(/[^0-9]/g, ''))
                })
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
			$('.J_price').eq(i).html(content[i].price).attr('data-id', content[i].price)
		}
    }
    //拼购数量
    function setContent(row, proData) {
		var content = [];
		$.each(proData.sku, function (i, v) {
			content.push(v)
		})
		for (var i = 0; i < row; i++) {
			$('.stock').eq(i).find('input').val(content[i].stock ).attr('data-id', content[i].stock)
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

     //获取拼购价格
     function getOldprice() {
        var barcode = [];
        var len = $('#J_table_box table tr').length;
        for (var i = 0; i < len - 1; i++) {
            barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.old-price').attr('data-id')
        }
        return barcode;
    }

    //获取拼购价格
    function getJprice() {
		var barcode = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.J_price').text()-0
		}
		return barcode;
    }
    //获取拼购总量
    function getJcounts() {
		var barcode = [];
		var len = $('#J_table_box table tr').length;
		for (var i = 0; i < len - 1; i++) {
			barcode[i] = $('#J_table_box table tr').eq(i + 1).find('.J_count').val()
		}
		return barcode;
    }


    $('#modify-btn').on('click', function () {
        var len = $('.J_count').length;
		for (var i = 0; i < len; i++) {
            var number = $('.J_count').eq(i).val() - 0;
			if (number === '') {
                console.log(number)
				alert('拼购数量必须填写完整！')
				return false;
			}
			if (number < 0 || number === '') {
				alert('拼购数量应为正数！')
				return false;
			}
            if($('.min_quantity_per_group').attr('value') == '3'){
                if (number < $('#minqu').html() && number != 0){
                    alert('拼购商品数量必须大于每人最少购买数量！')
                    return false;
                }
            }
        }

        var skuData = {};
        var proJprice = getJprice();
        var proJcounts = getJcounts();
        var proOldprice = getOldprice();
        var skuId = []
        $.each(proData.sku, function (i, v) {
            skuId.push(proData.sku[i].id)
        })
        for (var i = 0; i < Object.keys(proData.sku).length; i++) {
            skuData[skuId[i]] = {
                original_price: proOldprice[i],
                price: proJprice[i],
                stock: proJcounts[i]
            }
        }

        var  _gpubsProductId = url('?gpubsProductId'),
            min_quantity_per_group = $('.min_quantity_per_group').text()-0;
        var data = {
            gpubsProductId: _gpubsProductId,
            // min_quantity_per_group: min_quantity_per_group,
            sku: skuData,
            share_title: $('#share_title').val(),
            share_subtitle: $('#share_subtitle').val(),
            filename: file_name
        }
       modifyMsg(data)
    })


    function modifyMsg(data) {
        requestUrl('/activity/gpubs/update','POST',data, function () {
            location.href = '/activity/gpubs/index'
        })
    }



    $('#total-counts').blur(function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ''))
    })

    $('#set_total_btn').on('click', function () {
        $('.J_count').val($('#total-counts').val())
    })

    // 获取上传文件后缀
    function getSuffix(filename) {
        var pos = filename.lastIndexOf('.');
        var suffix = '';
        if (pos != -1) {
            suffix = filename.substring(pos + 1)
        }
        return suffix;
    }

    // 图片上传
    var file,imgUrl;
    var file_name = '';
    $('#filename').change(function(){
        var suffix = getSuffix($(this).val()).toLowerCase();
        if(suffix!="jpg"&&suffix!="gif"&&suffix!="png"){
            if(suffix == '' || suffix == null || suffix == undefined){
                return false;
            }else{
                $('#apxModalAdminAlert').modal('show')
                $('#J_alert_content').text('图片格式错误,请选择JPG,PNG,GIF图片上传')
                return false;
            }
        }else{
            requestUrl(
                '/site/carousel/get-oss-permission', 
                'GET', 
                {file_suffix: suffix},
                function(data){
                    file_name = data.key[0]
                    file = document.getElementById("filename");
                    imgUrl = window.URL.createObjectURL(file.files[0]);
                    $('#imgShow').attr('src',imgUrl);
                    $('#infoShow').fadeOut(0);
                    $('#imgShow').fadeIn(0);
                    var formFile = new FormData();
                    formFile.append("OSSAccessKeyId",data.OSSAccessKeyId)
                    formFile.append("policy",data.policy)
                    formFile.append("Signature",data.signature)
                    formFile.append("key",file_name)
                    formFile.append("callback",data.callback)
                    formFile.append("file",document.getElementById("filename").files[0])
                    $.ajax({
                        url:data.host,
                        method:'POST',
                        data:formFile,
                        processData: false,
                        contentType: false,
                        success:function(data){
                            $('#apxModalAdminAlert').modal('show')
                            $('#J_alert_content').text('上传成功')
                        },
                        error:function(){
                            $('#apxModalAdminAlert').modal('show')
                            $('#J_alert_content').text('上传失败')
                        }
                    })
                }
            )
        }
    })
})
