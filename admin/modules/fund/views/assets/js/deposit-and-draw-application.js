$(function() {
    // kindeditor 
	window.KindEditor && KindEditor.ready(function(K) {
	    window.editor = K.create('#apx_editor',{
	        items: [
	            'source', 'preview', '|', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright',
	            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
	            'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
	            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
	            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
	            'flash', 'emoticons', 'link'
	        ]
        });
        overrideImgUpdate();
        appendFullScreenFn();
    });

    var info = {}

    $('#J_search_btn').on('click', function() {
        var val = $('#J_search_input').val();
        getUserInfo(val);
    })
    $('#J_search_input').on('blur', function() {
        var val = $('#J_search_input').val();
        if (val == '') {
            return
        }
        getUserInfo(val);
    })

    $('#J_select_type').on('change', function() {
        var val = $(this).val();
        info.type = val;
    })

    function getUserInfo(no) {
        // 清空表单
        $('#J_money_input').val('');
        $('textarea').val('');
        window.editor.html('');
        var _data = {
            account: no
        }
        info.account = no;
        requestUrl('/fund/deposit-and-draw-application/user-info', 'GET', _data, function(data) {
            $('.J_user_account').html(data.account);
            $('.J_user_mobile').html(data.mobile);
            $('.J_user_name').html(data.name);
            $('.J_user_role').html(data.role);
            $('.J_user_blance').html(data.rmb.toFixed(2));
            $('.J_user_area').html(data.area);
            var type = '未知';
            if (data.type == 1) {
                type = 'CUSTOM';
            } else if (data.type == 2) {
                type = 'BUSINESS'
            }
            $('.J_user_type').html(type);
            var status = '未知';
            if (data.status === 0) {
                status = '正常';
                $('.J_handle_remark').removeClass('hidden');
            } else if (data.status === 1) {
                status = '封停/删除';
                $('.J_handle_remark').addClass('hidden');
            } else if (data.status === 2) {
                status = '未注册';
                $('.J_handle_remark').addClass('hidden');
            }
            $('.J_user_status').html(status);
            $('.J_user_info').removeClass('hidden');
        })
    }

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
    
    $('#J_editor_img_upload').on('change', function() {
        var _this = this;
        apex.uploadImg(this.files[0], {
            loaded: function(data) {
                KindEditor.appendHtml('#apx_editor', '<div><img class="img-responsive" src="' + data.url + '"></div>');
            },
            error: function () {
                alert('图片格式有误')
            }
        })
    })
    $('#J_create_btn').on('click', function() {
        info.amount = $('#J_money_input').val();
        info.brief = $('#J_remark_input').val();
        info.detail = window.editor.html();
        if (info.amount < 0.01 || info.amount > 100000) {
            alert('金额错误！')
            return
        }
        var _data = {
            account: info.account,
            operate_type: info.type,
            amount: info.amount,
            operate_brief: info.brief,
            operate_detail: info.detail
        }
        _this = $(this);
        _this.addClass('disabled');
        requestUrl('/fund/deposit-and-draw-application/create', 'POST', _data, function(data) {
            location.href = '/fund/deposit-and-draw-list';
        }, function(data) {
            _this.removeClass('disabled');
            alert(data.data.errMsg);
        })
    })
    $('#apxModalAdminAlertEnterDepartment').on('show.bs.modal', function() {
        var remark = $('#J_remark_input').val();
        var detail = window.editor.html();
        $('.J_brief').html(remark);
        $('.J_detail').html(detail);
        if (remark == '') {
            alert('请填写操作原因简要！')
            return false
        }
        if (detail == '') {
            alert('请填写操作原因详情！')
            return false
        }
        var type = $('#J_select_type').val();
        if (type == -1) {
            alert('请选择操作类型！')
            return false
        }
        var money = $('#J_money_input').val();
        if (money < 0.01 || money > 100000) {
            alert('金额错误！')
            return false
        }
        if (type == 1) {
            var typeT = '入账'
        } else if (type == 2) {
            var typeT = '出账'
        }
        $('.J_handle_type').text(typeT);
        $('.J_handle_money').text(money);
   })
})