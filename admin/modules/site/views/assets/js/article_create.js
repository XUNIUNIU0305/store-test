$(function () {
    $('#apxModalPreview').on('show.bs.modal', function(e) {
        var title = $("#J_title_content").val();
        var titleH = "<h2 class='text-center'>" + title + "</h2>"
    	var html = editor.html();
    	$('#J_wechat_preview').html(titleH + html);
    });
    //实时去判断标题 
	$("#J_title_content").on("input propertychange",function(){  
	    var val = $(this).val();
	    $('#J_title_limit').html(20 - val.length);
	    $('#J_title_preview').html(val);
	})
	//上传图片
	$('#upload_img_input').on('change', function() {
		var filename = $(this).val();
		var $this = $(this);
		if (filename == '') {
			return
		}
		var suffix = ossUpload.getSuffix(filename);
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
    	requestUrl('/site/carousel/get-oss-permission', 'GET', {file_suffix: suffix}, function(data) {
    		var formData = ossUpload.setUpParam($this, data);
    		ossUpload.uploadImg(data, formData, succesCB);
    	})
	})
	//editor开始上传
	$('#J_editor_img_upload').on('change', function(e) {
		var $this = $(this);
		var filename = $this.val();
		if (filename == '') {
			return
		}
		var suffix = ossUpload.getSuffix(filename);
		//上传成功回调处理
    	function succesCB(data) {
    		if (data.status == 200) {
                KindEditor.appendHtml('#apx_editor', '<img src="' + data.data.url + '">');
    		} else {
    			$('#J_alert_content').html(data.data.errMsg);
    			$('#apxModalAdminAlert').modal('show');
    		}
    	}
    	//请求OSS回调
    	requestUrl('/site/carousel/get-oss-permission', 'GET', {file_suffix: suffix}, function(data) {
    		var formData = ossUpload.setUpParam($this, data);
    		ossUpload.uploadImg(data, formData, succesCB);
    	})
	})
	function overrideImgUpdate() {
        $('[data-name="image"]').on('click', function(e) {
            e.stopPropagation();
            $('#J_editor_img_upload').click();
        })
    }
    function appendFullScreenFn() {
        $('[data-name="fullscreen"]').on('click', function(e) {
            setTimeout(function() {
                overrideImgUpdate();
                appendFullScreenFn();
            }, 100)
        })
    }
    overrideImgUpdate();
    appendFullScreenFn();
    //发布
    $('#J_sure_submit').on('click', function() {
    	$('#apxModalAdminDel').modal('hide');
    	var title = $('#J_title_content').val();
    	var filename = $('#upload_img_input').siblings('img').data('filename');
    	var content = editor.html();
    	if (title == '') {
    		$('#J_alert_content').html('标题不能为空！');
    		$('#apxModalAdminAlert').modal('show');
    		return;
    	}
    	if (filename == undefined) {
    		$('#J_alert_content').html('图片不能为空！');
    		$('#apxModalAdminAlert').modal('show');
    		return;
    	}
    	if (content == '') {
    		$('#J_alert_content').html('正文不能为空！');
    		$('#apxModalAdminAlert').modal('show');
    		return;
    	}
    	var data = {
    		title: title,
    		file_name: filename,
    		content: content
    	}
    	requestUrl('/site/article/insert', 'POST', data, function(data) {
    		$('#J_alert_content').html('发布成功！');
    		$('#apxModalAdminAlert').modal('show');
    		setTimeout(function() {
    			window.location.href = '/site/article/index';
    		}, 1000)
    	})
    })
})