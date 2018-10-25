$(function() {
    var imgUrl = [],
        nub = 0,
        limit;
    /**
     * 获取商品属性
     * @param  {[number]} p_id [category id]
     */
    function getProductAttr(p_id) {
        if (!p_id) {
            alert('错误的URL，请重新选择分类！');
            location.href = location.host + '/release';
            return;
        }
        var data = {
           'category_id': p_id 
        }
        function attrCB(data) {
            var tpl = $('#J_tpl_list').html();
            var result = juicer(tpl, data);
            $('.J_prod_attr_form_box').append(result);
            if (url('?product_id')) {
                getPorInfo(url('?product_id'));
            }
        }
        requestUrl('/release/attribute', 'GET', data, attrCB)
    }

    if ($('.apx-seller-form-wrap').length > 0) getProductAttr(url('?category'));
    if (url('?product_id')) {
        $('#J_publish').addClass('hidden');
        $('#J_edit_pro').removeClass('hidden');
        $('#J_edit_category').addClass('disabled');
    }
    var data_keyword; 
    //获取商品主体信息
    function getPorInfo(id) {
        requestUrl('/release/info', 'GET', {product_id: id}, function(data) {
            $('#title').val(data.title);
            $('#spotlight').val(data.description);
            $('[name=locale][value="' + data.purchase_location + '"]').prop('checked', true); 
            $('[name=checklist][value="' + data.invoice + '"]').prop('checked', true); 
            $('[name=ensurance][value="' + data.warranty + '"]').prop('checked', true); 
            $('[name=order][value="' + data.customization + '"]').prop('checked', true);
            $('[name=userRole][value="' + data.customer_limit + '"]').prop('checked', true);
            $('[type="radio"]').prop('disabled', true);
            $('.J_input_count').keydown();
            editor.html(data.detail.toString());
            mobileEditor.html(data.mobile_detail.toString());
            $.each(data.spu, function(index, val) {
                if ($('select.form-control[data-id="' + index + '"]').find('option[value="' + val + '"]').length > 0) {
                    $('select.form-control[data-id="' + index + '"]').val(val);
                }
            });
            $.each(data.big_images, function(index, val) {
                var html = `<div class="pic-box-item" data-id="` + index + `" data-filename="` + val.name + `">
                                <img src="` + val.path + `" alt="" class="img-responsive J_img_show">
                                <span class="close">×</span>
                            </div>`;
                $('#J_imgs_box').append(html);
            })

            //-------商品关键字--------
            $('.goods-key-words').show();
            var key_list =  $('.key-ipt-list').find('input');
            $('.key-ipt-list').find('input').val('');
            buildTag(2);
            var span_text = $('.key-ipt-list').find('span');
            data_keyword = data.keyword;
            span_text.each(function(index){
                $(this).text(data.keyword[index]);
                if ( index >= data.keyword.length) {
                    $(this).text('');
                }
            });
            key_list.each(function(index) {
                $(this).val(data.keyword[index]); 
                if( index >= data.keyword.length){
                    $(this).val('');
                }
            });
            
        })
    }

    
    $('.btn-contain').on('click.modify','.J_modify_btn',function() {
        buildTag(1);
        var input_list = $('.key-ipt-list').find('input');
        input_list.each(function(index){
            $(this).val(data_keyword[index]);
            if ( index >= data_keyword.length) {
                $(this).val('');
            }
        });
    });   
    var inputArr = [];
    //控制input
    function buildTag(type) {
        if ( type === 1) {
            var sure_btn = '<span class="button key-words-btn J_sure_btn" data-toggle="modal" data-target="">确定</span>';
            $('.btn-contain').html(sure_btn);
            $('.input-detail').html('<input type="text">');
            $('.J_modify_btn').remove();
        } else {
            var modify_btn = '<span class="button key-words-btn J_modify_btn">修改</span>';
            $('.btn-contain').html(modify_btn);
            $('.input-detail').html('<span></span>');
            var span_text = $('.key-ipt-list').find('span');
            span_text.each(function(index){
                $(this).text(inputArr[index]);
                if ( index >= inputArr.length) {
                    $(this).text('');
                }
            });
            $('.J_sure_btn').remove();
        }
    }

    $('.btn-contain').on('click.sure','.J_sure_btn',function() {
        $(".key-ipt-list input").each(function() {
            if($.trim($(this).val()) != '' ) {
                inputArr.push($(this).val().trim());
                }
            });
        if(inputArr.length <= 0){
            alert("至少添加一个关键字！");
            $(".input-confirm-btn").attr("data-target","");
            return;
        }
        $(".key-words-btn").attr("data-target","#apxModalAdminAlert");
    });
    //提交修改信息
     $('.btn-danger').click(function() {
        var id_str = url("?product_id");
        var data = {
            product_id:id_str,
            keyword:inputArr
        };
        requestUrl("release/add-keyword",'POST',data,function(){
            buildTag(2);
            inputArr = [];
            getPorInfo(url("?product_id"));
        }); 
    });

    ////////////////////////////////////
    // count the letter in input area //
    ////////////////////////////////////
    $('.J_input_count').on('keydown', function(e) {
        var _this = this;
        setTimeout(function() {
            $('[data-count-from="' + $(_this).attr('id') + '"]').text($(_this).attr('maxLength') - $(_this).val().length);
        }, 0);
    })

    //获取类目
    function getCategory(id) {
        requestUrl('/release/full-category', 'GET', {category_id: id}, function(data) {
            var html = '';
            $.each(data, function(index, val) {
                html += '<span>' + val.name + '</span>'
                if (index < data.length - 1) {
                    html += ' > '
                }
            })
            $('#J_category_box').html(html);
        })
    }
    getCategory(url('?category'));

    //获取上传限制
    function get_upload_limit() {
        function limitCB(data) {
            limit = data;
            //初始化页面
            $('#title').attr({'maxlength': limit.title_max_length});
            $('#spotlight').attr({'maxlength': limit.description_max_length});
            $('.J_pro_title').html(limit.title_max_length);
            $('.J_pro_trait').html(limit.description_max_length);
            $('#J_text_label li:eq(0) span').html(limit.img_max_length/1024/1024);
            $('#J_text_label li:eq(1) span').html(limit.img_max_count);
        }
        function errCB(data) {
            alert('页面初始化失败！请重新刷新页面！');
        }
        requestUrl('/release/limit', 'GET', '', limitCB, errCB)
    }
    if ($('input[type="file"]').length > 0) get_upload_limit();
    

    //判断上传文件是否符合限制
    function judge_img($target, limit) {
        var img_size = $target[0].files[0].size;
        var img_name = $target.val();
        var suffix = ossUpload.getSuffix(img_name);
        var _result = limit.img_suffix.indexOf(suffix);
        if (img_size > limit.img_max_length || img_size < limit.img_min_length) {
            return false;
        } else if (_result == -1) {
            return false;
        } else {
            return true;
        }
    }
 
    //重置上传文件
    function reset_input_file($target) {
        $target.val('');
        $target[0].files[0] = '';
    }

    //开始上传
    $('#J_img_upload').on('change', function(e) {
        var result = confirm('确认上传！');
        // 取消返回
        if (!result) {
            reset_input_file($('#J_img_upload'));
            return;
        }
        // 大于等于5张返回
        if ($('#J_imgs_box img').length >= 5) {
            alert('您最多只能上传5张图片！');
            return;
        }
        // 不符合要求返回
        if (!judge_img($('#J_img_upload'), limit)) {
            alert("您上传的图片大小不符合要求或者格式不正确！");
            reset_input_file($('#J_img_upload'));
            return;
        }

        var img_name = $('#J_img_upload').val();
        var suffix = ossUpload.getSuffix(img_name);
        //回调处理
        function mainImgCB(data) {
            if (data.status == 200) {
                var html = `<div class="pic-box-item" data-id="` + nub + `" data-filename="` + data.data.filename + `">
                                <img src="` + data.data.url + `" alt="" class="img-responsive J_img_show">
                                <span class="close">×</span>
                            </div>`;
                $('#J_imgs_box').append(html);
                nub++;
            } else {
                alert('上传失败！');
            }
            reset_input_file($('#J_img_upload'));
        }
        //请求OSS回调参数
        var data = { file_suffix: suffix };
        function paramCB(data) {
            var formData = ossUpload.setUpParam($('#J_img_upload'), data);
            //上传
            ossUpload.uploadImg(data, formData, mainImgCB);
        }
        requestUrl('/release/permission', 'GET', data, paramCB)
    })

    //删除图片
    $('#J_imgs_box').on('click', '.close', function() {
        var yes = confirm('确定要删除该图片?');
        if (!yes) return;
        $(this).parent('.pic-box-item').remove();
    })

    //editor开始上传
    $('#J_editor_img_upload').on('change', function(e) {
        // 不符合要求返回
        if (!judge_img($('#J_editor_img_upload'), limit)) {
            alert("您上传的图片大小不符合要求或者格式不正确！");
            reset_input_file($('#J_editor_img_upload'));
            return;
        }
        var img_name = $('#J_editor_img_upload').val();
        var target = $('#J_editor_img_upload').attr('data-target');
        var suffix = ossUpload.getSuffix(img_name);
        //回调处理
        function editorCB(data) {
             if (data.status == 200) {
                KindEditor.appendHtml(target, '<div><img class="img-responsive" src="' + data.data.url + '"></div>');
                // var img = $('<img class="img-responsive" src="' + data.data.url + '">');
                // KindEditor.ready(function(K) {
                //     var editor = K.editor({
                //         allowFileManager: true
                //     });
                //     editor.loadPlugin('image', function() {
                //         editor.plugin.imageDialog({
                //             imageUrl: data.data.url,
                //             showLocal : false,
                //             clickFn: function(url, title, width, height, border, align) {
                //                 data.data.url;
                //                 editor.hideDialog();
                //             }
                //         });
                //     });
                // });
            } else {
                alert('上传失败！');
            }
        }
        //请求OSS回调参数
        var data = { file_suffix: suffix };
        function paramCB(data) {
            var formData = ossUpload.setUpParam($('#J_editor_img_upload'), data);
            ossUpload.uploadImg(data, formData, editorCB);
        }
        requestUrl('/release/permission', 'GET', data, paramCB);
    })

    function overrideImgUpdate() {
        $('[data-name="image"]').on('click', function(e) {
            e.stopPropagation();
            $('#J_editor_img_upload').attr('data-target', '#' + $(this).parents('.tab-pane').children('textarea').attr('id'));
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

    //获取发布内容
    function getContent() {
        var content = {};
        content.id = url('?category');
        content.title = $('#title').val();
        content.spotlight = $('#spotlight').val();
        content.locale = $("input:radio[name='locale']:checked").val();
        content.checklist = $("input:radio[name='checklist']:checked").val();
        content.ensurance = $("input:radio[name='ensurance']:checked").val();
        content.order = $("input:radio[name='order']:checked").val();
        content.customer_limit = $("input:radio[name=userRole]:checked").val();
        content.attr = {};
        var len = $('.J_prod_attr_form_box select.form-control').length;
        for (var i = 0; i < len; i++) {
            if ($('.J_prod_attr_form_box select.form-control').eq(i).val() == '') {
                return false
            }
            content.attr[$('.J_prod_attr_form_box select.form-control').eq(i).data('id')] = $('select.form-control').eq(i).val();
        }
        var imgsUrl = [];
        $('#J_imgs_box .pic-box-item').each(function() {
            imgsUrl.push($(this).data('filename'))
        })
        content.url = imgsUrl;
        content.editor = editor.html();
        content.mobileEditor = mobileEditor.html();
        return content;
    }

    //验证表单
    function verifyForm(content) {
        if (content == false) {
            alert('属性未选择完毕！') 
            return
        }
        if (content.title == '') {
            alert('标题不能为空！')
            return
        }
        if (content.spotlight == '') {
            alert('商品卖点不能为空！')
            return
        }
        if (content.locale == null) {
            alert('请选择采购地！')
            return
        }
        if (content.checklist == null) {
            alert('请选择是否有发票！')
            return
        }
        if (content.ensurance == null) {
            alert('请选择是否保修！')
            return
        }
        if (content.order == null) {
            alert('请选择是否定制！')
            return
        }
        if (content.customer_limit == null) {
            alert('请选择可购买用户！')
            return
        }
        if (content.url.length < 4) {
            alert('您至少要上传4张图片！')
            return
        }
        if (content.editor == '') {
            alert('电脑端商品描述不能为空！')
            return
        }
        return true
    }
    //提交表单
    $('#J_publish').on('click',function() {
        var content = getContent();
        var yes = verifyForm(content);
        if (yes !== true) return;
        var data = {
            category_id: content.id,
            title: content.title,
            description: content.spotlight,
            purchase_location: content.locale,
            invoice: content.checklist,
            warranty: content.ensurance,
            attribute: content.attr,
            image: content.url,
            detail: content.editor,
            mobile_detail: content.mobileEditor,
            customization: content.order,
            customer_limit: content.customer_limit
        }
        function submitCB(data) {
            window.location.href = data.url
        }
        requestUrl('/release/product', 'POST', data, submitCB)
    })
    //提交修改
    $('#J_edit_pro').on('click', function() {
        var content = getContent();
        var yes = verifyForm(content);
        if (yes !== true) return;
        var data = {
            product_id: url('?product_id'),
            attribute: content.attr,
            title: content.title,
            description: content.spotlight,
            image: content.url,
            mobile_detail: content.mobileEditor,
            detail: content.editor
        }
        requestUrl('/release/modify-product', 'POST', data, function(data) {
            window.location.href = '/product'
        });
    });

    //添加商品关键字
    // var inputArr = [];
    // $(".key-words-btn").on("click",function(){
    //     if($(".key-ipt-list input").val() == ''){
    //         alert("至少添加一个关键字！");
    //         return;
    //     }
    //     $(".key-ipt-list input").each(function() {
    //         if($.trim($(this).val()) != '' ) {
    //             inputArr.push($(this).val().trim());
    //         }
    //     });
    //     var product_id = url("?product_id");
    //     var data = {
    //         keyword: inputArr,
    //         product_id: product_id
    //     };
    //     requestUrl('/release/add-keyword','POST',data,function(){
    //         inputArr = [];
    //         alert("修改成功！");
    //     });
    // });

})
