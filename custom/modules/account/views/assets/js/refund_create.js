$(function() {
	//获取退货商品信息
	requestUrl('/account/refund/get-order-item', 'GET', {order_code: url('?no'), item_id: url('?id')}, function(data) {
		$('.J_product_url').attr('href', '/product?id=' + data.product_id.id);
		$('.J_product_img').attr('src', data.image);
		$('.J_product_title').html(data.title);
		var attrs = ''
		for (var i = 0; i < data.attributes.length; i++) {
			attrs += '<li>' + data.attributes[i].attribute + '：' + data.attributes[i].option + '</li>'
		}
		$('.J_product_attr').html(attrs);
		$('.J_product_price').html((data.price - 0).toFixed(2));
		$('.J_total_price').html((data.price - 0).toFixed(2));
		//判断输入数量
	    $('.J_only_int').on('keyup', function() {
	    	var number = $(this).val().replace(/\D/g,'') - 0;
	    	$(this).val(number);
	    	if ($(this).val() > data.count) {
	    		$(this).val(data.count)
	    	}
	    	setTimeout(function() {
	    		$('.J_total_price').html(($('.J_only_int').val() * data.price).toFixed(2))
	    	}, 300)
	    })
	    //减号
	    $('.J_input_minus').on('click', function() {
	    	var nub = $('.J_only_int').val() - 0;
	    	if (nub > 1) {
		    	$('.J_only_int').val(nub - 1)
	    	}
	    	setTimeout(function() {
	    		$('.J_total_price').html(($('.J_only_int').val() * data.price).toFixed(2))
	    	}, 300)
	    })
	    //加号
	    $('.J_input_add').on('click', function() {
	    	var nub = $('.J_only_int').val() - 0;
	    	if (nub < data.count && nub < 999) {
		    	$('.J_only_int').val(nub + 1)
	    	}
	    	setTimeout(function() {
	    		$('.J_total_price').html(($('.J_only_int').val() * data.price).toFixed(2))
	    	}, 300)
	    })
	})

	//获取上传文件后缀
    function getSuffix(filename) {
        var pos = filename.lastIndexOf('.');
        var suffix = '';
        if (pos != -1) {
            suffix = filename.substring(pos + 1)
        }
        return suffix;
    }
    //配置上传参数
    function setUpParam($target ,data) {
    	var formData = new FormData();
    	$.each(data, function(i, n) {
    		formData.append(i, n)
    	})
    	formData.append('file', $target[0].files[0]);
    	return formData;
    }
    //取得上传回调
    function uploadImg(obj, formData, callback) {
    	$.ajax({
    		url: obj.host,
		    type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function(data) {
            callback(data);
        })
        .fail(function() {
            setTimeout(function() {
                upload_img(obj, formData);
            }, 1000)
        })
    }
    //上传
    $('#upload_pic').on('change', function(e) {
    	var imgName = $(this).val();
    	var $this = $(this);
    	if ($('#J_img_box .item').length > 8) {
    		alert('您最多上传9张照片！')
    		return
    	}
    	var yes = confirm('确定上传？');
    	if (!yes) {
    		$this.val('')
    		return;
    	}
    	var suffix = getSuffix(imgName);
        if (suffix == '') return;
    	//上传成功回调处理
    	function succesCB(data) {
    		if (data.status == 200) {
    			var img = '<div class="item">\
    							<img src="' + data.data.url + '" data-filename="' + data.data.filename + '">\
    							<span class="close">&times;</span>\
                            </div>'
    			$('#J_img_box').append(img);
    		} else {
    			$('#J_alert_content').html(data.data.errMsg);
    			$('#apxModalAdminAlert').modal('show');
    		}
            $('#upload_pic').val('');
    	}
    	//请求OSS回调
    	requestUrl('/account/profile/upload', 'GET', {file_suffix: suffix}, function (data) {
    		var formData = setUpParam($this, data);
    		uploadImg(data, formData, succesCB);
    	}, function(data) {
            $('#upload_pic').val('');
            alert('图片不符合规范，请确认后提交！');
        })
    })
    //删除图片
    $('#J_img_box').on('click', '.close', function() {
    	var yes = confirm('删除该图片？');
    	if (!yes) return;
    	$(this).parent('.item').remove();
    })
    //提交申请
    $('#J_submit_order').on('click', function() {
    	var no = url('?no');
    	var id = url('?id');
    	var count = $('.J_only_int').val();
    	var reason = $('#description').val().trim();
    	var img = [];
    	for (var i = 0; i < $('#J_img_box .item').length; i++) {
    		img.push($('#J_img_box .item').eq(i).find('img').data('filename'))
    	}
    	if (reason == '') {
    		alert('请填写退换货原因！')
    		return
    	}
    	if (img.length < 1) {
    		alert('至少上传1张图片！')
    		return
    	}
        var yes = confirm('确定要退换？');
        if (!yes) return;
    	var data = {
    		order_code: no,
    		item_id: id,
    		quantity: count,
    		reason: reason,
    		images: img
    	}
    	$(this).addClass('disabled');
    	requestUrl('/account/refund/create-request', 'POST', data, function(data) {
    		window.location.href = '/account/refund'
    	}, function(data) {
    		alert(data.data.errMsg);
    		$('#J_submit_order').removeClass('disabled');
    	})
    })
})