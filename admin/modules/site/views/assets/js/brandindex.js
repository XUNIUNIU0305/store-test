$(function () {
    var scrolls = [];
    $('.iscroll_container').each(function () {
        scrolls.push(new IScroll(this, {
            mouseWheel: true,
            scrollbars: true,
            scrollbars: 'custom'
        }))
    })
    // refresh api
    // scrolls.forEach(function (scroll) {
    //     scroll.refresh();
    // })
    function refreshScroll() {
        setTimeout(function() {
            scrolls.forEach(function (scroll) {
                scroll.refresh();
            })
        }, 1000)
    }
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        refreshScroll()
    });

    //轮播数据加载
    var tpl_list = $('#J_tpl_list').html();
    var compiled_tpl = juicer(tpl_list);
    //获取数据加载列表
    function getList() {
        function listCB(data) {
            var html = compiled_tpl.render(data);
            $('#J_carousel_box').html(html);
        }
        requestUrl('/site/brandindex/list-header-adv', 'GET', {current_page: 1, page_size: 99}, listCB);
    }
    getList();


    $('#J_carousel_box').on('change','input[type="file"]',function () {
        var $this = $(this);
        var imgName = $(this).val();
        if(imgName == ""){
            return;
        }
        var suffix = ossUpload.getSuffix(imgName);
        function succesCB(data){
            if(data.status == 200){
                $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
            }else {
                $('#J_alert_content').html(data.data.errMsg);
                $('#apxModalAdminAlert').modal("show");
            }
        }
        requestUrl('/site/carousel/get-oss-permission','GET',{file_suffix:suffix},function (data) {
            var formData = ossUpload.setUpParam($this, data);
            ossUpload.uploadImg(data,formData,succesCB);
        })
    })
    //添加广告轮播
    var coun = 0;
    $('#J_add_carousel').on('click', function() {
        coun++;
        var html = '<div class="row list new J_carousel">\
                        <div class="col-xs-3 J_carousel_no">\
                            <input type="text" class="form-control" maxlength="2">\
                        </div>\
                        <div class="col-xs-3 edit J_carousel_img">\
                            <label class="img-upload-box" for="new_upload_img_' + coun + '">\
                                <input type="file" id="new_upload_img_' + coun + '">\
                                <img src="" alt="" />\
                            </label>\
                        </div>\
                        <div class="col-xs-3 J_carousel_url">\
                            <input type="text" class="form-control" value=""  id="contol-url" >\
                        </div>\
                        <div class="col-xs-3 J_carousel_btn">\
                            <a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-warning btn-sm  J_dele_btn" data-no="brand_delarr">删除</a>\
                            <a href="#" class="btn btn-danger btn-sm J_sure_new">确定</a>\
                        </div>\
                    </div>'
        $('#J_carousel_box').prepend(html);
    })
    //修改广告轮播
    $('#J_carousel_box').on('click.edit', '.J_edit_btn', function(e) {
        var $this = $(this).parents('.J_carousel');
        var no = $this.find('.J_carousel_no').text();
        var src = $this.find('.J_carousel_img img').attr('src');
        var filename = $this.find('.J_carousel_img img').data('filename');
        var url = $this.find('.J_carousel_url').text();
        $this.addClass('edit');
        var no_html = '<input type="text" class="form-control" maxlength="2" value="' + no + '">'
        $this.find('.J_carousel_no').html(no_html);
        var src_html = '<label class="img-upload-box" for="upload_img_' + no + '">\
                            <input type="file" id="upload_img_' + no + '">\
                            <img class="img-responsive" data-filename="' + filename + '" src="' + src + '">\
                        </label>'
        $this.find('.J_carousel_img').html(src_html);
        var url_html = '<input type="text" class="form-control" value="' + url + '"  id="contol-url" >';
        $this.find('.J_carousel_url').html(url_html);
        var btn_html = '<a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-warning btn-sm J_dele_btn" data-no="brand_delarr">删除</a>\
                        <a href="#" class="btn btn-danger btn-sm J_sure_btn">确定</a>'
        $this.find('.J_carousel_btn').html(btn_html);
        e.preventDefault();
    })
    //获取轮播上传数据
    function getBrandData($this) {
        var id = $this.data('id');
        if (id == undefined) id = '';
        var no = $this.find('.J_carousel_no input').val().trim();
        var src = $this.find('.J_carousel_img img').attr('src');
        var filename = $this.find('.J_carousel_img img').data('filename');
        var url = $this.find('.J_carousel_url input').val().trim();
        if (no == '') {
            $('#J_alert_content').html('序号不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (filename == '' || filename==undefined) {
            $('#J_alert_content').html('图片不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (url == '') {
            $('#J_alert_content').html('链接不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if ((no - 0) != (no - 0) || (no - 0) < 1) {
            $('#J_alert_content').html('序号必须为正整数！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        var data = {
            id: id,
            header_sort : no,
            file_name: filename,
            url: url
        }
        return data;
    }
    //确定添加
    $('#J_carousel_box').on('click', '.J_sure_new', function(e) {
        e.preventDefault();
        var $this = $(this).parents('.J_carousel');
        var data = getBrandData($this);
        if (data == undefined) return;
        function sureCB(data) {
            $this.removeClass('new');
            $('#J_alert_content').html('成功！');
            $('#apxModalAdminAlert').modal('show');
            getList();
        }
        requestUrl('/site/brandindex/create-header-adv', 'POST', data, sureCB);
    })
    //确定修改
    $('#J_carousel_box').on('click.sure', '.J_sure_btn', function(e) {
        var $this = $(this).parents('.J_carousel');
        var data = getBrandData($this);
        if (data == undefined) return;
        function sureCB(data) {
            $this.removeClass('new');
            $('#J_alert_content').html('成功！');
            $('#apxModalAdminAlert').modal('show');
            getList();
        }
        requestUrl('/site/brandindex/edit-header-adv', 'POST', data, sureCB);
        e.preventDefault();
    })
    //删除
    $('#apxModalAdminAdvertising').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        if($this.attr('data-no')=='brand') {
            if ($this.parents('.J_brand').hasClass('brand_new')) {
                $('#J_common_sure').off().on('click.dele', function () {
                    $this.parents('.J_brand').remove();
                    $('#apxModalAdminAdvertising').modal('hide');
                })
            } else {
                var arr = new Array();
                $('#J_common_sure').off().on('click.del', function () {
                    var id = $this.parents('.J_brand').data('id');
                    arr[0] = id;
                    function delCB(data) {
                        $this.parents('.J_brand').remove();
                        $('#apxModalAdminAdvertising').modal('hide');
                        $('#J_alert_content').html('成功！');
                        $('#apxModalAdminAlert').modal('show');
                    }
                    requestUrl('/site/brandindex/remove-brand', 'POST', {id: arr}, delCB);
                })
            }
        }else if($this.attr("data-no") == "brand_delete") {
            if ($this.parents('#J_brand_li').hasClass('news')) {
                $('#J_common_sure').off().on('click.dele', function () {
                    $this.parents('#J_brand_li').remove();
                    $('.J_add_btn').attr("data-add", "add");
                    $('.J_add_btn').text("添加")
                    $('.J_update_btn').removeClass("disabled");
                    $('.J_delete_btn').removeClass("disabled");
                    $('.J_update_btn').removeAttr("disabled")
                    $('.J_delete_btn').removeAttr("disabled")
                    $('#apxModalAdminAdvertising').modal('hide');
                })
            } else {
                var arr = new Array();
                $('#J_common_sure').off().on('click.del', function () {
                    var id = $this.parents('.brand-item').data('id');
                    arr[0] = id;
                    function delCB(data) {
                        $this.parents('#J_brand_li').remove();
                        $('.J_add_btn').attr("data-add", "add");
                        $('.J_add_btn').text("添加")
                        $('.J_update_btn').attr("data-update", "update");
                        $('.J_update_btn').text("修改")
                        $('.J_delete_btn').attr("data-delete", "delete");
                        $('.J_delete_btn').text("删除")
                        $('.J_update_btn').removeClass("disabled");
                        $('.J_delete_btn').removeClass("disabled");
                        $('.J_update_btn').removeAttr("disabled")
                        $('.J_delete_btn').removeAttr("disabled")
                        $('.J_add_btn').removeClass("disabled");
                        $('.J_add_btn').removeAttr("disabled")
                        $('#apxModalAdminAdvertising').modal('hide');
                        $('#J_alert_content').html('成功！');
                        $('#apxModalAdminAlert').modal('show');
                        getbrandsList(1, 10);
                    }
                    requestUrl('/site/brandindex/remove-brand', 'POST', {id: arr}, delCB);
                })
            }
        }else if($this.attr("data-no")=="brand_delarr"){
            if ($this.parents('.J_carousel').hasClass('new')) {
                $('#J_common_sure').off().on('click.dele', function() {
                    $this.parents('.J_carousel').remove();
                    $('#apxModalAdminAdvertising').modal('hide');
                })
            } else {
                $('#J_common_sure').off().on('click.del', function() {
                    var id = $this.parents('.J_carousel').data('id');
                    function delCB(data) {
                        $this.parents('.J_carousel').remove();
                        $('#apxModalAdminAdvertising').modal('hide');
                        $('#J_alert_content').html('成功！');
                        $('#apxModalAdminAlert').modal('show');
                    }
                    requestUrl('/site/brandindex/remove-header-adv', 'POST',{id: id}, delCB);
                })
            }
        }else{
            var arr = new Array();
            $(".edit").each(function(index,data){
                if(index == undefined) return;
                arr[index] = $(this).data('id');
            });
            $('#J_common_sure').off().on('click.del', function () {
                function delCB(data) {
                    $this.parents('#J_brand_li').remove();
                    $('#apxModalAdminAdvertising').modal('hide');
                    $('#J_alert_content').html('成功！');
                    $('#apxModalAdminAlert').modal('show');
                    getbrandsList(1, 10);
                }
                requestUrl('/site/brandindex/remove-brand', 'POST', {id: arr}, delCB);
            })
        }
    })


    //品牌特辑

    //轮播数据加载
    var brand_list = $('#J_brand_list').html();
    var brand_list_tpls = juicer(brand_list);
    //获取数据加载列表
    function getbrandList() {
        function listbrandCB(datas) {
            var html = brand_list_tpls.render(datas);
            $('#J_brand_box').html(html);
        }
        requestUrl('/site/brandindex/list-brand-album', 'GET', {current_page: 1, page_size: 99}, listbrandCB);
    }
    getbrandList();
    var coms = 0;
    //添加品牌
    $('#J_add_brand').on('click', function() {
        coms++;

        var html =  '<div class="col-xs-6 J_brand brand_new">\
                        <div class="admin-edit-brands-panel">\
                            <div class="content with-footer">\
                                <div class="row">\
                                <div class="col-xs-7"  id="J_brand_img_bg">\
                                <label class="img-upload-box media-object" for="upload_img_' + coms + '">\
                                    <input type="file" id="upload_img_' + coms + '" data-filename="">\
                                    <img src="">\
                                </label>\
                                <small class="high-lighted">背景图（576*330px）</small>\
                    </div>\
                    <div class="col-xs-5"  id="J_brand_img_logo">\
                        <label class="img-upload-box media-object" for="upload_img_' + (coms+1) + '">\
                        <input type="file" id="upload_img_' + (coms+1) + '" data-filename="">\
                        <img src="">\
                        </label>\
                        <small class="high-lighted">LOGO（140*60px）</small>\
                    </div>\
                    </div>\
                    <div class="form-group form-group-sm">\
                        <label for="" class="pull-left">序号：</label>\
                            <div  id="J_brand_no">\
                                <input type="text" class="form-control" id="contol-no" maxlength="7">\
                            </div>\
                        </div>\
                        <div class="form-group form-group-sm">\
                        <label for="" class="pull-left">链接：</label>\
                            <div id="J_brand_url">\
                                <input type="text" class="form-control" id="contol-url">\
                            </div>\
                        </div>\
                        <div class="form-group form-group-sm">\
                            <label for="" class="pull-left">标题：</label>\
                            <div  id="J_brand_title">\
                                <input type="text" class="form-control" maxlength="10">\
                            </div>\
                        </div>\
                        <div class="form-group form-group-sm">\
                        <label for="" class="pull-left">说明：</label>\
                            <div  id="J_brand_explain">\
                                <input type="text" class="form-control" maxlength="50">\
                            </div>\
                        </div>\
                        </div>\
                        <div class="footer">\
                        <div class="text-right operation">\
                            <a href="#" class="btn btn-warning J_sure_new">确定</a>\
                            <a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-danger J_brand" data-no="brand">删除</a>\
                        </div>\
                        </div>\
                        </div>\
                        </div>'
        $('#J_brand_box').prepend(html);
    })
    //修改品牌
    $('#J_brand_box').on('click', '.J_brand_but', function(e) {
        var $this = $(this).parents('.J_brand');
        var img_bg = $this.find('#J_brand_img_bg img').attr('src');
        var img_bg_filename = $this.find('#J_brand_img_bg img').data('filename');
        var img_logo = $this.find('#J_brand_img_logo img').attr('src');
        var img_logo_filename = $this.find('#J_brand_img_logo img').data('filename');
        var no = $this.find('#J_brand_no p').text();
        var url = $this.find('#J_brand_url p').text();
        var title = $this.find('#J_brand_title p').text();
        var explain = $this.find('#J_brand_explain p').text();
        var img_bg_html = '<label class="img-upload-box media-object" for="upload_img_'+no+'">\
                                <input type="file" id="upload_img_'+no+'">\
                                <img src="'+img_bg+'" data-filename="' + img_bg_filename + '">\
                            </label>\
                            <small class="high-lighted">背景图（576*330px）</small>'
        $this.find('#J_brand_img_bg').html(img_bg_html);
        var img_logo_html = '<label class="img-upload-box media-object" for="upload_img_'+(no+1)+'">\
                                <input type="file" id="upload_img_'+(no+1)+'">\
                                <img src="'+img_logo+'" data-filename="' + img_logo_filename + '">\
                            </label>\
                            <small class="high-lighted">背景图（140*60px）</small>'
        $this.find('#J_brand_img_logo').html(img_logo_html);
        var no_html = '<input type="text" class="form-control" value="'+no+'" id="contol-no" maxlength="7">'
        $this.find('#J_brand_no').html(no_html);
        var url_html = '<input type="text" class="form-control" value="'+url+'" id="contol-url">'
        $this.find('#J_brand_url').html(url_html);
        var title_html = '<input type="text" class="form-control" value="'+title+'" maxlength="10">'
        $this.find('#J_brand_title').html(title_html);
        var explain_html = '<input type="text" class="form-control" value="'+explain+'" maxlength="50">'
        $this.find('#J_brand_explain').html(explain_html);
        var but_html = '<a href="#" class="btn btn-warning J_sure_btn">确定</a>\
                        <a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-danger">删除</a>'
        $this.find('#J_brand_but').html(but_html);
        e.preventDefault();
    })
    // 品牌上传
    $('#J_brand_box').on('change','input[type="file"]',function () {
        var $this = $(this);
        var imgName = $(this).val();
        if(imgName == ""){
            return;
        }
        var suffix = ossUpload.getSuffix(imgName);
        function succesCB(data){
            if(data.status == 200){
                $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
            }else {
                $('#J_alert_content').html(data.data.errMsg);
                $('#apxModalAdminAlert').modal("show");
            }
        }
        requestUrl('/site/carousel/get-oss-permission','GET',{file_suffix:suffix},function (data) {
            var formData = ossUpload.setUpParam($this, data);
            ossUpload.uploadImg(data,formData,succesCB);
        })
    })
    //获取品牌上传数据
    function getBrandBJData($this) {
        var id = $this.data('id');
        if (id == undefined) id = '';
        var img_bg_filename = $this.find('#J_brand_img_bg img').attr('data-filename');
        var img_logo_filename = $this.find('#J_brand_img_logo img').attr('data-filename');
        var url = $this.find('#J_brand_url input').val().trim();
        var no = $this.find('#J_brand_no input').val().trim();
        var title = $this.find('#J_brand_title input').val().trim();
        var explain = $this.find('#J_brand_explain input').val().trim();

        if(img_bg_filename == ''||img_bg_filename == undefined){
            $('#J_alert_content').html('背景图片不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (img_logo_filename == ''||img_logo_filename == undefined) {
            $('#J_alert_content').html('logo图片不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (no == '') {
            $('#J_alert_content').html('序号不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (url == '') {
            $('#J_alert_content').html('链接不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (title == '') {
            $('#J_alert_content').html('标题不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if (explain == '') {
            $('#J_alert_content').html('说明不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        if ((Number(no) - 0) != (Number(no) - 0) || (Number(no) - 0) < 1) {
            $('#J_alert_content').html('序号必须为正整数！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        var data = {
            id: id,
            logo : img_logo_filename,
            background : img_bg_filename,
            url: url,
            album_sort: no,
            title: title,
            introduction:explain
        }
        return data;
    }
    //品牌确认添加
    $('#J_brand_box').on('click', '.J_sure_new', function(e) {
        e.preventDefault();
        var $this = $(this).parents('.J_brand');
        var data = getBrandBJData($this);
        if (data == undefined) return;
        function sureCB(data) {
            $this.removeClass('new');
            $('#J_alert_content').html('成功！');
            $('#apxModalAdminAlert').modal('show');
            getbrandList();
        }
        requestUrl('/site/brandindex/create-brand-album', 'POST', data, sureCB);
    })
    //品牌确定修改
    $('#J_brand_box').on('click.sure', '.J_sure_btn', function(e) {
        var $this = $(this).parents('.J_brand');
        var datas = getBrandBJData($this);
        if (datas == undefined) return;
        function sureCB(data) {
            $this.removeClass('new');
            $('#J_alert_content').html('成功！');
            $('#apxModalAdminAlert').modal('show');
            var img_bg_src = $this.find('#J_brand_img_bg img').attr('src');
            var img_logo_src = $this.find('#J_brand_img_logo img').attr('src');
            var img_bg_html = '<img class="img-upload-box" src="'+img_bg_src+'" data-filename="'+datas.background+'">\
                        <small class="high-lighted">背景图（576*330px）</small>'
            $this.find('#J_brand_img_bg').html(img_bg_html);
            var img_logo_html = '<img  class="img-upload-box" src="'+img_logo_src+'" data-filename="'+datas.logo+'">\
                            <small class="high-lighted">LOGO（140*60px）</small>'
            $this.find('#J_brand_img_logo').html(img_logo_html);
            var no_html = ' <p class="form-control-static">'+datas.album_sort+'</p>'
            $this.find('#J_brand_no').html(no_html);
            var url_html = '<p class="form-control-static">'+datas.url+'</p>'
            $this.find('#J_brand_url').html(url_html);
            var title_html = '<p class="form-control-static">'+datas.title+'</p>'
            $this.find('#J_brand_title').html(title_html);
            var explain_html = '<p class="form-control-static">'+datas.introduction+'</p>'
            $this.find('#J_brand_explain').html(explain_html);
            var but_html = '<a href="#" class="btn btn-warning J_brand_but">修改</a>\
                <a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="btn btn-danger J_brand" data-no="brand">删除</a>'
            $this.find('#J_brand_but').html(but_html);

        }
        requestUrl('/site/brandindex/edit-brand-album', 'POST', datas, sureCB);
        e.preventDefault();
    })


    // 热销品牌
    //获取数据加载列表
    var Selling_list = $('#J_Selling_list').html();
    var Selling_list_tpls = juicer(Selling_list);
    function getSellingList() {
        function listSellingCB(datas) {
            var html = Selling_list_tpls.render(datas);
            $('#J_Selling_box').html(html);
        }
        requestUrl('/site/brandindex/list-big-small-adv', 'GET', {current_page: 1, page_size: 99}, listSellingCB);
    }
    getSellingList();

    //点击修改
    $('#J_Selling_box').on('click', '.J_Selling_but', function(e) {
        var position = $(this).parents('.J_Selling').data('id');
        var $this = $(this).parents('.J_Selling');
        if(position == 1){
            var img_min = $this.find('#J_Selling_img_min img').attr('src');
            var input_min = $this.find('#J_Selling_input_min p').text();
            var img_min_filename = $this.find('#J_Selling_img_min img').data('filename');;
            var img_min_html = '<label class="img-upload-box media-object" for="upload_img_1">\
                                 <input type="file" id="upload_img_1">\
                                 <img src="'+img_min+'" data-filename="'+img_min_filename+'">\
                                </label>'
            $this.find('#J_Selling_img_min').html(img_min_html);
            var input_min_html = '<strong>图片大小：</strong> 435*284px\
                              <div>链接：</div>\
                              <input type="text" class="form-control"  value="'+input_min+'"  id="contol-url" >\
                              <button class="btn btn-danger btn-block J_sure_btn">确认</button>'
            $this.find('#J_Selling_input_min').html(input_min_html);
        }
        if(position == 2){
            var img_max = $this.find('#J_Selling_img_max img').attr('src');
            var input_max = $this.find('#J_Selling_input_max p').text();
            var img_max_filename = $this.find('#J_Selling_img_max img').data('filename');
            var img_max_html = '<label class="img-upload-box media-object" for="upload_img_1">\
                                 <input type="file" id="upload_img_1">\
                                 <img src="'+img_max+'" data-filename="'+img_max_filename+'">\
                                </label>'
            $this.find('#J_Selling_img_max').html(img_max_html);
            var input_max_html = '<strong>图片大小：</strong> 1170*140px\
                                   <div>链接：</div>\
                                   <input type="text" class="form-control" value="'+input_max+'"  id="contol-url" >\
                                   <button class="btn btn-danger btn-block J_sure_btn">确认</button>'
            $this.find('#J_Selling_input_max').html(input_max_html);
        }
        e.preventDefault();
    })
    //确定修改
    $('#J_Selling_box').on('click.sure', '.J_sure_btn', function(e) {
        var $this = $(this).parents('.J_Selling');
        var position = $(this).parents('.J_Selling').data('id');
        var datas = getSellingData($this);
        if (datas == undefined) return;
        function sureCB(data) {
            $this.removeClass('new');
            $('#J_alert_content').html('成功！');
            $('#apxModalAdminAlert').modal('show');
            if(position == 1){
                var src = $this.find('#J_Selling_img_min img').attr('src');
                var img_min_html = '<img src="'+src+'" data-filename="'+datas.file_name+'" class="media-object">'
                $this.find('#J_Selling_img_min').html(img_min_html);
                var input_min_html = '<strong>图片大小：</strong> 435*284px\
                              <div>链接：</div>\
                               <p>'+datas.url+'</p>\
                              <button class="btn btn-danger btn-block J_Selling_but">修改</button>'
                $this.find('#J_Selling_input_min').html(input_min_html);
            }
            if(position == 2){
                var src = $this.find('#J_Selling_img_max img').attr('src');
                var img_max_html = '<img src="'+src+'" data-filename="'+datas.file_name+'" class="media-object">'
                $this.find('#J_Selling_img_max').html(img_max_html);
                var input_max_html = '<strong>图片大小：</strong> 1170*140px\
                                   <div>链接：</div>\
                                   <p> '+datas.url+'</p>\
                                   <button class="btn btn-danger btn-block J_Selling_but">修改</button>'
                $this.find('#J_Selling_input_max').html(input_max_html);
            }
        }
        requestUrl('/site/brandindex/edit-header-adv', 'POST', datas, sureCB);
        e.preventDefault();
    })
    //获取轮播上传数据
    function getSellingData($this) {
        var id = $this.data('id');
        if(id == 1){
            var src = $this.find('#J_Selling_img_min img').attr('src');
            var filename = $this.find('#J_Selling_img_min img').attr('data-filename');
            var url = $this.find('#J_Selling_input_min input').val().trim();
            if (filename == ''||filename==undefined) {
                $('#J_alert_content').html('图片不能为空！');
                $('#apxModalAdminAlert').modal('show');
                return
            }
            if (url == '') {
                $('#J_alert_content').html('链接不能为空！');
                $('#apxModalAdminAlert').modal('show');
                return
            }
            var data = {
                id: id,
                header_sort : 0,
                file_name: filename,
                url: url
            }
            return data;
        }
        if(id == 2){
            var src = $this.find('#J_Selling_img_max img').attr('src');
            var filename = $this.find('#J_Selling_img_max img').attr('data-filename');
            var url = $this.find('#J_Selling_input_max input').val().trim();
            if (filename == ''||filename==undefined) {
                $('#J_alert_content').html('图片不能为空！');
                $('#apxModalAdminAlert').modal('show');
                return
            }
            if (url == '') {
                $('#J_alert_content').html('链接不能为空！');
                $('#apxModalAdminAlert').modal('show');
                return
            }
            var data = {
                id: id,
                header_sort : 0,
                file_name: filename,
                url: url
            }
            return data;
        }
    }
    $('#J_Selling_box').on('change','input[type="file"]',function () {
        var $this = $(this);
        var imgName = $(this).val();
        if(imgName == ""){
            return;
        }
        var suffix = ossUpload.getSuffix(imgName);
        function succesCB(data){
            if(data.status == 200){
                $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
            }else {
                $('#J_alert_content').html(data.data.errMsg);
                $('#apxModalAdminAlert').modal("show");
            }
        }
        requestUrl('/site/carousel/get-oss-permission','GET',{file_suffix:suffix},function (data) {
            var formData = ossUpload.setUpParam($this, data);
            ossUpload.uploadImg(data,formData,succesCB);
        })
    })

    //图片上传
    $('#J_brands_boxImg').on('change','input[type="file"]',function () {
        var $this = $(this);
        var imgName = $(this).val();
        if(imgName == ""){
            return;
        }
        var suffix = ossUpload.getSuffix(imgName);
        function succesCB(data){
            if(data.status == 200){
                $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
            }else {
                $('#J_alert_content').html(data.data.errMsg);
                $('#apxModalAdminAlert').modal("show");
            }
        }
        requestUrl('/site/carousel/get-oss-permission','GET',{file_suffix:suffix},function (data) {
            var formData = ossUpload.setUpParam($this, data);
            ossUpload.uploadImg(data,formData,succesCB);
        })
    })

    //数据加载
    var brands_list = $('#J_brands_list').html();
    var brands_compiled_tpl = juicer(brands_list);
    //获取数据加载列表
    function getbrandsList(page, size) {
        function brandslistCB(data) {
            var html = brands_compiled_tpl.render(data.codes);
            $('.list-unstyled').html(html);
            refreshScroll();
            pagingBuilder.build($('#J_brand_page'),page,size,data.total_count);
            pagingBuilder.click($('#J_brand_page'),function(page){
                getbrandsList(page, size);
            })
        }
        requestUrl('/site/brandindex/list-hot-brand', 'GET', {current_page: page, page_size: size}, brandslistCB);
    }
    getbrandsList(1, 10);

    var brands = 100;
    //添加品牌
    $('.J_add_btn').on('click', function(e) {
        brands++;
        var data_add = $(this).attr("data-add");
        if(data_add == "add"){
            var html =  '<li class="news" id="J_brand_li"><div class="brand-item edit" style="border-color: #d43f3a;" data-edit="">\
                        <label class="switch-btn">\
                        <input type="checkbox" checked/>\
                        <div><div></div></div></label>\
                        <button class="cancel-btn"><a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="cancel-btn" data-no="brand_delete">删除</a></button>\
                        <label class="img-upload-box media-object" for="upload_img_' + brands + '" id="J_brands_img">\
                        <input type="file" id="upload_img_'+ brands + '">\
                        <img src="" data-filename=""></label>\
                        <div class="form-group form-group-sm" id="J_brands_no">\
                        <label for="" class="pull-left">序号：</label><div>\
                        <input type="text" class="form-control"  maxlength="7"></div></div>\
                        <div class="form-group form-group-sm" id="J_brands_url">\
                        <label for="" class="pull-left">链接：</label><div>\
                        <input type="text" class="form-control"  id="contol-url">\
                        </div></div></div></li>'
            $('.list-unstyled').prepend(html);
            $('.J_update_btn').addClass("disabled");
            $('.J_update_btn').attr("disabled","true");
            $('.J_delete_btn').addClass("disabled");
            $('.J_delete_btn').attr("disabled","true");
            $(this).attr("data-add","");
            $(this).text("确认");
        }else{
            var data = getBrandsData($(this));
            if (data == false) return;
            function getbrandsCB(data) {
                if(data.length == 0 ){
                    $('#J_alert_content').html('成功！');
                    $('#apxModalAdminAlert').modal('show');
                    $('.J_add_btn').attr("data-add","add");
                    $('.J_add_btn').text("添加")
                    $('.J_update_btn').removeClass("disabled");
                    $('.J_delete_btn').removeClass("disabled");
                    $('.J_update_btn').removeAttr("disabled")
                    $('.J_delete_btn').removeAttr("disabled")
                    getbrandsList(1,10);
                }
            }
            requestUrl('/site/brandindex/create-hot-brand', 'POST',data,getbrandsCB);
            e.preventDefault();
        }
    })

    function getBrandsData($this) {
        var id = $this.data('id');
        var filename,no,url;
        if (id != undefined&& id !=null && id !=""){
            filename = $($this).find('#J_brands_img img').attr('data-filename');
            no = $($this).find('#J_brands_no input').val().trim();
            url = $($this).find('#J_brands_url input').val().trim();;
        }else{
            id = '';
            no = $('#J_brands_no input').val().trim();
            filename = $('#J_brands_img img').attr('data-filename');
            url = $('#J_brands_url input').val().trim();
        }
        if (filename == ''||filename == undefined) {
            $('#J_alert_content').html('图片不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return false;
        }
        if (no == '') {
            $('#J_alert_content').html('序号不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return false;
        }
        if (url == '') {
            $('#J_alert_content').html('链接不能为空！');
            $('#apxModalAdminAlert').modal('show');
            return false;
        }
        if ((Number(no) - 0) != (Number(no) - 0) || (Number(no) - 0) < 1) {
            $('#J_alert_content').html('序号必须为正整数！');
            $('#apxModalAdminAlert').modal('show');
            return false;
        }
        var data = {
            id: id,
            hot_sort : no,
            file_name: filename,
            url: url
        }
        return data;
    }
    var brandcom = 100;
    $('.J_update_btn').on('click', function(e) {
        var data_update = $(this).attr("data-update");
        if(data_update == "update"){
            $(".edit").each(function(index,data){
                brandcom++;
                $(this).addClass('editupdata');
                var src =  $(this).find('img').attr('src');
                var filename = $(this).find('img').attr('data-filename');
                var status = $(this).find('.brand-item').attr('data-status');
                var htmlstatus = '';
                if(status == 1){
                    htmlstatus = '<input type="checkbox" checked></input>';
                }else{
                    htmlstatus = '<input type="checkbox" ></input>';
                }
                var no = $(this).find('.J_brands_no p').text();
                var url = $(this).find('.J_brands_url p').text();
                var html =  ' <label class="switch-btn">'+htmlstatus+'\
                        <div><div></div></div></label>\
                        <button class="cancel-btn"><a href="#" data-toggle="modal" data-target="#apxModalAdminAdvertising" class="cancel-btn" data-no="brand_delete">删除</a></button>\
                        <label class="img-upload-box media-object" for="upload_img_' + brandcom + '" id="J_brands_img">\
                        <input type="file" id="upload_img_'+ brandcom + '">\
                        <img src="'+src+'" data-filename="'+filename+'"></label>\
                        <div class="form-group form-group-sm" id="J_brands_no">\
                        <label for="" class="pull-left">序号：</label><div>\
                        <input type="text" class="form-control"  value="'+no+'" maxlength="7"></div></div>\
                        <div class="form-group form-group-sm" id="J_brands_url">\
                        <label for="" class="pull-left">链接：</label><div>\
                        <input type="text" class="form-control"  value="'+url+'" id="contol-url" >\
                        </div></div>'
                $(this).html(html);
            })
            if(brandcom <= 100){
                $('#J_alert_content').html('请点击选中后修改');
                $('#apxModalAdminAlert').modal('show');
                return;
            }
            $('.J_add_btn').addClass("disabled");
            $('.J_add_btn').attr("disabled","true");
            $('.J_delete_btn').addClass("disabled");
            $('.J_delete_btn').attr("disabled","true");
            $(this).attr("data-update","");
            $(this).text("确认修改");
        }else{
            var brandList = [];
            $(".editupdata").each(function(index,data){
                var data = getBrandsData($(this));
                if (data != false){
                    brandList.push(data);
                }else{
                    return false;
                }
            });
            if (brandList.length == 0 ){ return;}
            function getbrandsCB(data) {
                if(data.length == 0){
                    $(".editupdata").each(function(index,data){
                        $(this).removeClass('editupdata');
                    });
                    $('#J_alert_content').html('成功！');
                    $('#apxModalAdminAlert').modal('show');
                    $('.J_update_btn').attr("data-update","update");
                    $('.J_update_btn').text("修改")
                    $('.J_add_btn').removeClass("disabled");
                    $('.J_delete_btn').removeClass("disabled");
                    $('.J_add_btn').removeAttr("disabled")
                    $('.J_delete_btn').removeAttr("disabled");
                    brandcom = 100;
                    getbrandsList(1,10);
                }
            }
            requestUrl('/site/brandindex/edit-hot-brand', 'POST', {multi_data:brandList} , getbrandsCB);
            e.preventDefault();
        }
    });

    $('#J_brands_boxImg').on('change','#switch-checkbox',function (e) {
        var cid = $(this).parents('.brand-item').data('id');
        if($(this).find("input").get(0).checked){
            function getcheckedCB(data) {
                $('#J_alert_content').html('设置显示成功！');
                $('#apxModalAdminAlert').modal('show');
            }
            requestUrl('/site/brandindex/hot-brand-status', 'POST', {id:cid,status:1} , getcheckedCB);
            e.preventDefault();
        }else{
            function getcheckedCB(data) {
                $('#J_alert_content').html('设置隐藏成功！');
                $('#apxModalAdminAlert').modal('show');
            }
            requestUrl('/site/brandindex/hot-brand-status', 'POST', {id:cid,status:0} , getcheckedCB);
            e.preventDefault();
        }
    })
    
    $('#J_brands_boxImg').on('click','.brand-item',function () {
         var edit = $(this).attr("data-edit");
         if(edit == "edit"){
             $(this).removeClass("edit");
             $(this).attr("data-edit","");
         }else {
             $(this).addClass("edit");
             $(this).attr("data-edit","edit");
         }
    });
    $(".J_delete_btn").on('click',function () {
        var cont;
        $(".edit").each(function(index,data){
            cont = index;
        });
        if(cont == undefined){
            $('#J_alert_content').html('请点击选中后删除！');
            $('#apxModalAdminAlert').modal('show');
        }else {
            $('#apxModalAdminAdvertising').modal('show');
        }
    })
    $(".J_brand_box").on('keyup','#contol-no',function () {
        this.value = this.value.replace(/[^\d]/g, '');
    })
    $(".J_brand_box").on('keyup','#contol-url',function () {
        //this.value = this.value.replace((http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?,'');
    })
})