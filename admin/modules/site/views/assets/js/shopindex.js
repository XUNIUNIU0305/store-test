$(function () {
        //获取数据加载下拉框店铺列表
        function supplylist(){
            function supplyCB(data) {
                var options = '<option value="-1" data-id = "-1"> 请选择 </option>';
                for (var i = 0, len = data.codes.length; i < len; i++) {
                    options += '<option value="' + i + '" data-id = "'+data.codes[i].id+'">' + data.codes[i].brand_name + '(ID：' + data.codes[i].id + ')' +'</option>';
                }
                $('#J_supply').html(options);
                $('.selectpicker').selectpicker('refresh');
                $('.selectpicker').selectpicker('show');
            }
            requestUrl('/site/supply/get-supply-list', 'GET', {current_page: 1, page_size: 1000}, supplyCB);
        }
        supplylist();
        //图片上传
        $('#apxModalAdminShopImgs').on('change','input[type="file"]',function () {
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

        //获取图片列表
        var id = -2;
        $('.pull-right').on('click','li',function () {
            var index = $(this).data('original-index');
            id = $("#J_supply option[value='"+(index-1)+"']").data("id");
            if (!id) {
                return
            }
            if(id == -1){
                id = -2;
                $('#J_shop_list').html('<div class="col-xs-12" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="0" data-size="1200*380">\
                    <img src="" data-filename="" data-url=""/>\
                    </div>\
                    <div class="col-xs-6" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="1" data-size="424*170">\
                    <img src="" data-filename="" data-url=""/>\
                    </div>\
                    <div class="col-xs-6" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="1" data-size="424*170">\
                    <img src="" data-filename="" data-url=""/>\
                    </div>\
                    <div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2" data-size="370*232">\
                    <img src="" data-filename="" data-url=""/>\
                    </div>\
                    <div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2" data-size="370*232">\
                    <img src="" data-filename="" data-url=""/>\
                    </div>\
                    <div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2" data-size="370*232">\
                    <img src="" data-filename="" data-url=""/>\
                    </div>');
                return
            };
            function selectIMG(data){
                var top = data.top;
                var small = data.small;
                var sub = data.sub;
                var html = '';
                if(top.id == ""){
                    html +='<div class="col-xs-12" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="0" data-sort="0" data-size="1200*380">\
                             <img src="" data-filename="" data-url=""/>\
                             </div>'
                }else{
                    html +='<div class="col-xs-12" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="0" data-sort="0" data-size="1200*380">\
                            <img src="'+top.image_path+'" data-filename="'+top.file_name+'" data-url="'+top.image_url+'" data-id="'+top.id+'"/>\
                            </div>'
                }
                for(var i in small){
                    if(small[i].id == ""){
                        html +='<div class="col-xs-6" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="1" data-sort="'+(Number(i)+1)+'" data-size="424*170">\
                                <img src="" data-filename="" data-url=""/>\
                                </div>'
                    }else{
                        html +='<div class="col-xs-6" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="1" data-sort="'+(Number(i)+1)+'" data-size="424*170">\
                                <img src="'+small[i].image_path+'" data-filename="'+small[i].file_name+'" data-url="'+small[i].image_url+'" data-id="'+small[i].id+'"/>\
                                </div>'
                    }
                }
                for(var i in sub){
                    if(sub[i].id == ""){
                        html +='<div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2" data-sort="'+(Number(i)+3)+'" data-size="370*232">\
                                <img src="" data-filename="" data-url=""/>\
                                </div>'
                    }else{
                        html +='<div class="col-xs-4" data-toggle="modal" data-target="#apxModalAdminShopImg" data-type="2" data-sort="'+(Number(i)+3)+'" data-size="370*232">\
                                <img src="'+sub[i].image_path+'" data-filename="'+sub[i].file_name+'" data-url="'+sub[i].image_url+'" data-id="'+sub[i].id+'"/>\
                                </div>'
                    }
                }
                $('#J_shop_list').html(html)
            }
            requestUrl('/site/shopindex/list', 'GET', {supply_user_id:id}, selectIMG);
        })
        //点击弹出框
        var $thistype ;
        $('.apx-shop-upload-box').on('click','div',function () {
            if(id == -2){
                $('#J_alert_content').html('请选选择门店！');
                $('#apxModalAdminAlert').modal('show');
                return;
            }
            var src = $(this).find('img').attr('src');
            var filename =$(this).find('img').attr('data-filename');
            var url =$(this).find('img').attr('data-url');
            var sid =$(this).find('img').attr('data-id');
            var size = $(this).attr('data-size');
            if(src != "" && filename != "" && url != ""){
                $('#shop_img img').attr({'src':src,'data-filename':filename,'data-id':sid});
                $('#shop_inp input').val(url);
                $('.btn-block').attr('data-update','update');
                $('.btn-block').text("修改");
            }else{
                $('#shop_img img').attr({'src':'','data-filename':'','data-id':''});
                $('#shop_inp input').val("");
                $('.btn-block').removeAttr('data-update');
                $('.btn-block').text("提交");
            }
            $thistype = $(this);
            $('.text-danger').html("（图片尺寸要求："+size+"）");
            $('#apxModalAdminShopImgs').modal('show');
        })
        //修改新增
        $('.btn-block').on('click',function () {
            var $this = $(this).parents("#apxModalAdminShopImgs");
            var type = $thistype.data("type");
            var sort = $thistype.data('sort');
            var data = getselectData($this,id,type,sort);
            if(data == undefined){return}
            function  getIMG(data) {
                    var src = $this.find('#shop_img img').attr('src');
                    var filename = $this.find('#shop_img img').attr('data-filename');
                    var url = $this.find('#shop_inp input').val().trim();
                    $('#apxModalAdminShopImgs').modal('hide');
                    if(data.id != undefined){
                        $($thistype).find('img').attr({'src':src,'data-filename':filename,'data-url':url,'data-id':data.id});
                    }else{
                        $($thistype).find('img').attr({'src':src,'data-filename':filename,'data-url':url});
                    }
                    $('#shop_inp input').removeAttr('update');
                    $('.btn-block').text("提交");
                    $('#J_alert_content').html("成功");
                    $('#apxModalAdminAlert').modal("show");
                    $this.find('#shop_img img').attr('src','');
                    $this.find('#shop_inp input').text('');
                    $this.find('#shop_img img').attr('data-filename','');
            }
            if($(this).attr('data-update') == "update"){
                requestUrl('/site/shopindex/edit', 'POST', data, getIMG);
            }else {
                requestUrl('/site/shopindex/create', 'POST', data, getIMG);
            }
        })
        //判断参数
        function getselectData($this,id,type,sort) {
            var filename = $this.find('#shop_img img').attr('data-filename');
            var sid = $this.find('#shop_img img').attr('data-id');
            var url = $this.find('#shop_inp input').val().trim();
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
            if (id == ''|| id ==undefined || id == -1) {
                $('#J_alert_content').html('请选选择门店！');
                $('#apxModalAdminAlert').modal('show');
                return
            }
            if (type == undefined) {
                $('#J_alert_content').html('操作错误！');
                $('#apxModalAdminAlert').modal('show');
                return
            }
            var data = {
                supply_user_id: id,
                image_url : url,
                file_name: filename,
                type: type,
                sort:sort,
                id:sid
            }
            return data;
        }
        $(".btn-default").on("click",function () {
            if(id == -2) {
                $('#apxModalAdminShopImgs').modal('hide');
            }
        })
})

// 手机端品牌页
$(function() {
    var mShop = {
        shopId: null,
        banner: [],
        selectedPro: [],
        shopPro: [],
        bannerTpl: $('#J_tpl_banner').html(),
        getImgList: function(params) {
            // 获取主图及轮播图
            requestUrl('/site/wap-shopindex/list', 'GET', params, function(data) {
                var big = data.big;
                if (big.id) {
                    $('#J_main_img').attr('src', big.image_path)
                } else {
                    $('#J_main_img').attr('src', "/images/admin/shop/upload.png")
                }
                var carousel = data.carousel;
                mShop.banner = carousel;
                $('#J_banner_list').html(juicer(mShop.bannerTpl, carousel))
            })
        },
        getSelectPro: function(params, type) {
            // 获取甄选商品
            requestUrl('/site/wap-shopindex/list-product', 'GET', params, function(data) {
                mShop.selectedPro = data;
                var spans = '';
                for (var i = 0; i < data.length; i++) {
                    if (i === 0) {
                        spans += '<span class="active" data-index="' + i + '">产品' + (i + 1) + '</span>'
                    } else {
                        spans += '<span data-index="' + i + '">产品' + (i + 1) + '</span>'
                    }
                }
                $('.J_selected_pro_list').html(spans)
                $('#J_selected_pro_container').html(juicer($('#J_tpl_selected_pro').html(), data))
                if (type === 'new') {
                    $('.J_selected_pro_list span:last').click()
                }
            })
        },
        queryPro: function(params) {
            // 筛选商品
            requestUrl('/site/wap-shopindex/search-product', 'GET', params, function(data) {
                $('#J_all_pro_box').html(juicer($('#J_tpl_allpro').html(), data))
            })
        },
        getAllPro: function(params) {
            // 获取店铺所有商品
            requestUrl('/site/wap-shopindex/shop-products', 'GET', params, function(data) {
                mShop.shopPro = data;
                $('#J_all_pro_box').html(juicer($('#J_tpl_allpro').html(), data))
            })
        },
        createImg: function(params) {
            // 新增图片
            requestUrl('/site/wap-shopindex/create', 'POST', params, function(data) {
                mShop.getImgList({
                    supply_user_id: params.supply_user_id
                })
            })
        },
        editImg: function(params) {
            // 编辑轮播
            requestUrl('/site/wap-shopindex/edit', 'POST', params, function(data) {
                mShop.getImgList({
                    supply_user_id: mShop.shopId
                })
            })
        },
        delBanner: function(params) {
            // 删除轮播
            requestUrl('/site/wap-shopindex/delete', 'POST', params, function(data) {
                mShop.getImgList({
                    supply_user_id: mShop.shopId
                })
            })
        },
        addSelectedPro: function(params) {
            // 新增甄选商品
            requestUrl('/site/wap-shopindex/create-product', 'POST', params, function(data) {
                mShop.getSelectPro({
                    supply_user_id: mShop.shopId
                }, 'new')
            })  
        },
        editSelectedPro: function(params) {
            // 修改甄选商品
            requestUrl('/site/wap-shopindex/edit-product', 'POST', params, function(data) {
                mShop.getSelectPro({
                    supply_user_id: mShop.shopId
                })
                $('#J_alert_content').html('保存成功！');
                $('#apxModalAdminAlert').modal("show");
            })  
        },
        delSelectedPro: function(params) {
            // 删除甄选商品
            requestUrl('/site/wap-shopindex/delete-product', 'POST', params, function(data) {
                mShop.getSelectPro({
                    supply_user_id: mShop.shopId
                })
            })
        },
        addBannerHtml: function(num) {
            if (num === 5) {
                $('#J_alert_content').html('轮播已超出最大数量！');
                $('#apxModalAdminAlert').modal("show");
                return
            }
            var html = '<div class="row list new banner-item">\
                            <div class="col-xs-3">\
                                <input type="text" maxlength="2" class="form-control banner-item-sort">\
                            </div>\
                            <div class="col-xs-3 edit">\
                                <label class="img-upload-box" for="upload_file_' + num + '">\
                                    <input type="file" class="upload_img_input" id="upload_file_' + num + '">\
                                    <img alt=""/>\
                                </label>\
                            </div>\
                            <div class="col-xs-3">\
                                <input type="text" class="form-control banner-item-link" value="">\
                            </div>\
                            <div class="col-xs-3">\
                                <a href="#" class="btn btn-warning btn-sm J_del_banner_btn">删除</a>\
                                <a href="#" class="btn btn-danger btn-sm J_sure_add_banner">确定</a>\
                            </div>\
                        </div>'
            $('#J_banner_list').prepend(html)
        },
        upload: function(dom, success) {
            var $this = dom;
            var imgName = $this.val();
            if(imgName == ""){
                return;
            }
            var suffix = ossUpload.getSuffix(imgName);
            requestUrl('/site/carousel/get-oss-permission','GET',{file_suffix:suffix},function (data) {
                var formData = ossUpload.setUpParam($this, data);
                ossUpload.uploadImg(data,formData,function(data) {
                    if(data.status == 200){
                        typeof success === 'function' && success(data)
                    }else {
                        $('#J_alert_content').html(data.data.errMsg);
                        $('#apxModalAdminAlert').modal("show");
                    }
                });
            })
        },
        showError: function(msg) {
            $('#J_alert_content').html(msg);
            $('#apxModalAdminAlert').modal("show");
        },
        init: function() {
            // 店铺选择
            $('select.J_shop_list_select').on('change', function() {
                var i = $(this).val();
                var id = $(this).find('option[value="' + i + '"]').data('id')
                if (id !== -1) {
                    $('#shop-edit-content').removeClass('hidden')
                } else {
                    $('#shop-edit-content').addClass('hidden')
                    return
                }
                mShop.shopId = id;
                mShop.getImgList({
                    supply_user_id: id
                })
                mShop.getSelectPro({
                    supply_user_id: id
                })
                mShop.getAllPro({
                    supply_user_id: id
                })
            })
            // 上传主图
            $('#upload-main-file').on('change', function() {
                var $this = $(this);
                mShop.upload($(this), function(data) {
                    $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
                    mShop.createImg({
                        supply_user_id: mShop.shopId,
                        file_name: data.data.filename,
                        image_url: 'http://www.9daye.com.cn',
                        type: 3,
                        sort: 1
                    })
                })
            })
            // 添加轮播按钮事件
            $('#J_add_newbanner_btn').on('click', function() {
                var num = $('#J_banner_list .banner-item').length;
                mShop.addBannerHtml(num)
            })
            // 上传轮播图
            $('#J_banner_list').on('change', 'input.upload_img_input', function() {
                var $this = $(this);
                mShop.upload($this, function(data) {
                    $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
                })
            })
            // 添加完整轮播
            $('#J_banner_list').on('click', '.J_sure_add_banner', function() {
                var $parent = $(this).parents('.banner-item');
                var sort = $parent.find('.banner-item-sort').val();
                var img = $parent.find('img').data('filename');
                var link = $parent.find('.banner-item-link').val().trim();
                if (sort === '') {
                    mShop.showError('序号不能为空')
                    return
                }
                if (!img) {
                    mShop.showError('图片不能为空')
                    return
                }
                if (link === '') {
                    mShop.showError('链接不能为空')
                    return
                }
                mShop.createImg({
                    supply_user_id: mShop.shopId,
                    type: 4,
                    image_url: link,
                    file_name: img,
                    sort: sort
                })
            })
            // 编辑轮播html
            $('#J_banner_list').on('click', '.J_edit_banner_btn', function() {
                var $parent = $(this).parents('.banner-item');
                var i = $parent.data('index');
                var data = mShop.banner[i];
                var html = '<div class="col-xs-3">\
                                <input type="text" maxlength="2" class="form-control banner-item-sort" value="' + data.sort + '">\
                            </div>\
                            <div class="col-xs-3 edit">\
                                <label class="img-upload-box" for="upload_file_' + data.id + '">\
                                    <input type="file" class="upload_img_input" id="upload_file_' + data.id + '">\
                                    <img src="' + data.image_path + '" data-filename="' + data.file_name + '" alt=""/>\
                                </label>\
                            </div>\
                            <div class="col-xs-3">\
                                <input type="text" class="form-control banner-item-link" value="' + data.image_url + '">\
                            </div>\
                            <div class="col-xs-3">\
                                <a href="#" class="btn btn-warning btn-sm J_del_banner_btn">删除</a>\
                                <a href="#" class="btn btn-danger btn-sm J_sure_edit_banner">确定</a>\
                            </div>'
                $parent.html(html).addClass('edit')
            })
            // 编辑轮播请求
            $('#J_banner_list').on('click', '.J_sure_edit_banner', function() {
                var $parent = $(this).parents('.banner-item');
                var sort = $parent.find('.banner-item-sort').val();
                var img = $parent.find('img').data('filename');
                var link = $parent.find('.banner-item-link').val();
                var id = $parent.data('id');
                mShop.editImg({
                    id: id,
                    supply_user_id: mShop.shopId,
                    image_url: link,
                    file_name: img,
                    sort: sort
                })
            })
            // 删除轮播
            $('#J_banner_list').on('click', '.J_del_banner_btn', function() {
                var $parent = $(this).parents('.banner-item');
                var id = $parent.data('id');
                if (id) {
                    var yes = confirm('确定删除此轮播？');
                    if (!yes) return;
                    mShop.delBanner({
                        id: id
                    })
                } else {
                    $parent.remove()
                }
            })
            $('[data-target="#J_add_newpro_modal"]').on('click', function() {
                var len = $('.J_selected_pro_list span').length
                if (len > 3) {
                    mShop.showError('甄选商品最多4个！')
                    return false
                }
            })
            // 新增时展示店铺商品
            $('#J_add_newpro_modal').on('show.bs.modal', function(e) {
                $('#J_query_pro_input').val('');
                if (mShop.shopPro.length !== 0) {
                    $('#J_all_pro_box').html(juicer($('#J_tpl_allpro').html(), mShop.shopPro))
                } else {
                    mShop.getAllPro({
                        supply_user_id: mShop.shopId
                    })
                }
            })
            // 选择商品
            $('#J_all_pro_box').on('click', 'tr', function() {
                $(this).find('.checkbox').addClass('active')
                $(this).siblings().find('.checkbox').removeClass('active')
            })
            // 确定选择
            $('#J_select_pro_btn').on('click', function() {
                var id = $('tr .checkbox[class*="active"]').parents('tr').data('id');
                $('#J_add_newpro_modal').modal('hide');
                mShop.addSelectedPro({
                    supply_user_id: mShop.shopId,
                    product_id: id
                })
                // var len = $('.J_selected_pro_list span').length;
                // $('#J_selected_pro_container ul').addClass('hidden');
                // $('.J_selected_pro_list span').removeClass('active');
                // $('#J_selected_pro_container').append(juicer($('#J_tpl_selected_pro').html(), mShop.shopPro[index]))
                // $('.J_selected_pro_list').append('<span class="active">产品' + (len - 0 + 1 ) + '</span>')
            })
            // 编辑甄选商品
            $('#J_selected_pro_container').on('click', '.J_sure_create_selected_item_btn', function() {
                var $parent = $(this).parents('.selected-items');
                var id = $parent.data('id');
                var img = $parent.find('.J_show_img').attr('data-filename');
                var title = $parent.find('.J_show_title').val().trim();
                var description = $parent.find('.J_show_description').val().trim();
                if (!img) {
                    mShop.showError('图片不能为空')
                    return
                }
                if (title === '') {
                    mShop.showError('标题不能为空')
                    return
                }
                if (description === '') {
                    mShop.showError('卖点不能为空')
                    return
                }
                mShop.editSelectedPro({
                    id: id,
                    show_title: title,
                    show_message: description,
                    file_name: img
                })
            })
            // 编辑甄选商品图片
            $('#J_selected_pro_container').on('change', '.J_show_img_input', function() {
                var $this = $(this);
                mShop.upload($(this), function(data) {
                    $this.siblings('img').attr({'src':data.data.url,'data-filename':data.data.filename});
                })                
            })
            // 切换产品
            $('.J_selected_pro_list').on('click', 'span', function() {
                var i = $(this).data('index');
                $(this).addClass('active').siblings().removeClass('active');
                $('#J_selected_pro_container .selected-items').addClass('hidden').eq(i).removeClass('hidden')
            })
            // 删除甄选商品
            $('#J_selected_pro_container').on('click', '.J_sure_del_selected_item_btn', function() {
                var id = $(this).data('id');
                var yes = confirm('确定删除此产品？');
                if (!yes) return;
                mShop.delSelectedPro({
                    recommend_product_id: id
                })
            })
            // 查询商品
            $('#J_query_pro_btn').on('click', function() {
                var val = $('#J_query_pro_input').val();
                mShop.queryPro({
                    supply_user_id: mShop.shopId,
                    condition: val
                })
            })
            // 回车查询
            $('#J_query_pro_input').on('keydown', function(e) {
                if (e.keyCode === 13) {
                    $('#J_query_pro_btn').click()
                }
            })
        }
    }
    mShop.init()
})