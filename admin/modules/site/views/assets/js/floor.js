$(function() {
	//获取商品列表
	var tpl_product = $('#J_tpl_product').html();
	var compiled_list = juicer(tpl_product);
	function getProductList() {
		var id = url('?id');
		function listCB(data) {
			var html = compiled_list.render(data);
			$('#J_product_list').html(html);
		}
		requestUrl('/site/floorgoods/get-floor-goods', 'GET', {fid: id}, listCB);
	}
	getProductList();
	//添加商品
	var newCount = 0;
	$('#J_add_product').on('click', function(e) {
		e.preventDefault();
		newCount++;
		var newPor = '<div class="row list new J_product">\
                        <div class="col-xs-2 J_product_img">\
                            <label class="img-upload-box" for="upload_img_' + newCount + '">\
                                <input type="file" id="upload_img_' + newCount + '">\
                                <img src="">\
                            </label>\
                        </div>\
                        <div class="col-xs-2 form-horizontal">\
                            <div class="form-group form-group-sm J_product_name">\
                                <label for="prod_name" class="col-xs-12 control-label">商品名称：</label>\
                                <div class="col-xs-9">\
                                    <input type="text" maxlength="9" class="form-control" >\
                                </div>\
                            </div>\
                        </div>\
                        <div class="col-xs-3 form-horizontal">\
                            <div class="form-group form-group-sm J_product_point_one">\
                                <label for="sell_point_1" class="col-xs-6 control-label">商品卖点1：</label>\
                                <div class="col-xs-6">\
                                    <input type="text" maxlength="10" class="form-control">\
                                </div>\
                            </div>\
                            <div class="form-group form-group-sm J_product_point_two">\
                                <label for="sell_point_2" class="col-xs-6 control-label">商品卖点2：</label>\
                                <div class="col-xs-6">\
                                    <input type="text" maxlength="10" class="form-control" >\
                                </div>\
                            </div>\
                        </div>\
                        <div class="col-xs-3 form-horizontal">\
                            <div class="form-group form-group-sm J_product_id">\
                                <label for="sell_id_1" class="col-xs-6 control-label">商品id：</label>\
                                <div class="col-xs-6">\
                                    <input type="text" class="form-control" >\
                                </div>\
                            </div>\
                            <div class="form-group form-group-sm J_product_sort">\
                                <label for="sell_id_2" class="col-xs-6 control-label">排序id：</label>\
                                <div class="col-xs-6">\
                                    <input type="text" class="form-control" >\
                                </div>\
                            </div>\
                            <div class="form-group form-group-sm J_product_price">\
                                <label for="sell_id_2" class="col-xs-6 control-label">市场指导价：</label>\
                                <div class="col-xs-6">\
                                    <input type="text" class="form-control" >\
                                </div>\
                            </div>\
                        </div>\
                        <div class="col-xs-2">\
                            <a href="#" class="btn btn-warning btn-block btn-sm J_sure_new">确认添加</a>\
                        </div>\
                    </div>'
        $('#J_product_box .J_new_product').prepend(newPor);
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
	//上传图片
	$('#J_product_box').on('change', 'input[type="file"]', function() {
		var $this = $(this);
    	var imgName = $(this).val();
    	var suffix = getSuffix(imgName);
    	//上传成功回调处理
    	function succesCB(data) {
    		if (data.status == 200) {
    			$this.siblings('img').attr({'src': data.data.url, 'data-filename': data.data.filename});
    		} else {
    			$('#J_alert_content').html(data.data.errMsg);
    			$('#apxModalAdminAlert').modal('show');
    		}
    	}
    	//请求OSS回调
    	function imgCB(data) {
    		var formData = setUpParam($this, data);
    		uploadImg(data, formData, succesCB);
    	}
    	requestUrl('/site/carousel/get-oss-permission', 'GET', {file_suffix: suffix}, imgCB)
	})
    //获取修改或添加数据
    function getProData($this) {
        var id = $this.data('id');
        if (id == undefined) id = '';
        var fid = url('?id');
        var good_name = $this.find('.J_product_name input').val().trim();
        var good_url = $this.find('.J_product_img img').attr('src');
        var file_name = $this.find('.J_product_img img').data('filename');
        var sale_one = $this.find('.J_product_point_one input').val().trim();
        var sale_two = $this.find('.J_product_point_two input').val().trim();
        var good_id = $this.find('.J_product_id input').val().trim();
        var sort = $this.find('.J_product_sort input').val().trim();
        var price = $this.find('.J_product_price input').val().trim();
        if (good_name == '') {
            $('#J_alert_content').html('商品名称不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (good_url== '') {
            $('#J_alert_content').html('请上传图片！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (sale_one == '' || sale_two == '') {
            $('#J_alert_content').html('商品卖点不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (good_id == '') {
            $('#J_alert_content').html('商品ID不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (sort == '') {
            $('#J_alert_content').html('排序ID不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if ((good_id - 0) != (good_id - 0) || (good_id - 0) < 1) {
            $('#J_alert_content').html('商品ID必须为正整数！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if ((sort - 0) != (sort - 0) || (sort - 0) < 1) {
            $('#J_alert_content').html('排序ID必须为正整数！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (price - 0 != price) {
            $('#J_alert_content').html('请输入正确格式的指导价格！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        var data = {
            id: id,
            fid: fid,
            good_name: good_name,
            good_url: good_url,
            file_name: file_name,
            sale_one: sale_one,
            sale_two: sale_two,
            good_id: good_id,
            sort: sort,
            guide_price: price
        }
        return data;
    }
    //确认添加
    $('#J_product_box').on('click', '.J_sure_new', function(e) {
        e.preventDefault();
        var $this = $(this).parents('.J_product');
        var data = getProData($this);
        if (data == undefined) return;
        function newCB(data) {
           $('#J_alert_content').html('成功！');
            $('#apxModalAdminAlert').modal('show');
            $this.remove();
            getProductList(); 
        }
        delete data.id;
        requestUrl('/site/floorgoods/insert-floor-good', 'POST', data, newCB);
    })
	//确认修改
	$('#J_product_box').on('click', '.J_sure_add', function(e) {
		e.preventDefault();
        var $this = $(this).parents('.J_product');
		var data = getProData($this);
        if (data == undefined) return;
		function addCB(data) {
			$('#J_alert_content').html('成功！');
    		$('#apxModalAdminAlert').modal('show');
    		getProductList();
		}
		requestUrl('/site/floorgoods/update-floor-good', 'POST', data, addCB);
	})
	//修改商品
	$('#J_product_list').on('click', '.J_product_edit', function() {
		var $this = $(this).parents('.J_product');
		var url =  $this.find('.J_product_img img').attr('src');
		var filename =  $this.find('.J_product_img img').data('filename');
		var good_name =  $this.find('.J_product_name p').text();
		var point_one =  $this.find('.J_product_point_one p').text();
		var point_two =  $this.find('.J_product_point_two p').text();
		var product_id = $this.find('.J_product_id p').text();
        var sort = $this.find('.J_product_sort p').text();
		var price = $this.find('.J_product_price p').text();
		$this.find('.J_product_img').html('<label class="img-upload-box media-object" for="upload_img_' + sort + '">\
	                                            <input type="file" id="upload_img_' + sort + '">\
	                                            <img data-filename="' + filename + '" src="' + url + '">\
	                                        </label>');
		$this.find('.J_product_name div').html('<input type="text" class="form-control" maxlength="9" value="' + good_name + '">');
		$this.find('.J_product_point_one div').html('<input type="text" class="form-control" maxlength="10" value="' + point_one + '">');
		$this.find('.J_product_point_two div').html('<input type="text" class="form-control" maxlength="10" value="' + point_two + '">');
		$this.find('.J_product_id div').html('<input type="text" class="form-control" value="' + product_id + '">');
        $this.find('.J_product_sort div').html('<input type="text" class="form-control" value="' + sort + '">');
		$this.find('.J_product_price div').html('<input type="text" class="form-control" value="' + price + '">');
		$this.find('.J_product_btn').html('<div class="btn btn-warning J_sure_add">确定</div>\
                                            <div class="btn btn-danger J_product_del">删除</div>');
	})
    commonConfrim()
    //删除楼层商品
    $('#J_product_list').on('click', '.J_product_del', function() {
        $('#apxModalAdminConfrim').on('shown.bs.modal', function(e) {
            var $this = $(e.relatedTarget);
            $('#J_common_sure').off().on('click', function() {
                var id = $this.parents('.J_product').data('id');
                function delCB(data) {
                    $('#apxModalAdminConfrim').modal('hide');
                    $this.parents('.J_product').remove();
                    $('#J_alert_content').html('删除成功！');
                    $('#apxModalAdminAlert').modal('show');
                    getProductList();
                }
                requestUrl('/site/floorgoods/delete-floor-good', 'POST', {id: id}, delCB);
            })
        })
    })















})