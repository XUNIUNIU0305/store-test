$(function(){ 
    g = {
        prames: {order_number:url("?order_number")},
        order: url('?order_number'),
        brands: {},
        types: [],
        brand_id: undefined,
        type_id: undefined,
        brand_img: undefined,
        brand_name: undefined,
        status: 1
    } 

    // 获取订单详情
    function getOrderInfo() {
        requestUrl('/customization/order/one', 'GET', g.prames, function(data) {
            // 订单基本信息
            $('#J_info_img').attr('src', data.product.image);
            $('#J_info_title').html(data.product.title);
            $('#J_pay_time').html(data.pay_time);
            var attr = '';
            $.each(data.product.attributes, function(i, v) {
                attr += v.attribute + "：" + v.option + "；"
            })
            $('#J_info_attr').html(attr);
            $('#J_info_price').html('￥' + data.product.total_fee.toFixed(2));
            for (var i = 0; i < data.notes.length; i++) {
                if (data.notes[i].type == 2) {
                    $('#J_supply_remark').html(data.notes[i].text);
                    break
                }
            }
            // 已上传订单信息
            if (data.status != 1) {
                g.brand_id = data.brand_id;
                g.type_id = data.type_id;
                $('.J_brand_name').html(data.brand);
                $('.J_type_name').html(data.type);
                g.brand_name = data.brand;

                for (var i = 0; i < data.notes.length; i++) {
                    if (data.notes[i].type == 1) {
                        $('#J_remark').html(data.notes[i].text);
                        break
                    }
                }
                var imgs = '';
                for (var i = 0; i < data.pics.length; i++) {
                    imgs += `<label class="img-upload-box">
                                <div class="close">X</div>
                                <img class="upload" src="` + data.host + '/' + data.pics[i].filename + `" data-filename="` + data.pics[i].filename + `">
                            </label>`;
                }
                $('#J_img_box').prepend(imgs);
                if (data.status !== 2) {
                    // 限制所有修改
                    $('#J_select_brand').off();
                    $('#J_select_type').off();
                    $('#J_img_box').off();
                    $('#J_remark').attr('disabled', 'disabled');
                    $('#upload_mobile_pic').parent('.img-upload-box').addClass('hidden');
                }
            }
            // 未处理状态
            if (data.status == 2) {
                g.status = 2;
                $('#J_submit').html('修改');
            }
            // 生产中
            if (data.status == 3) {
                $('#J_submit').addClass('hidden');
                $('#J_back').removeClass('hidden');
                $('.tip-title').addClass('masking').find('p').text('生产中');
            }
            // 已发货
            if (data.status == 4) {
                $('#J_submit').addClass('hidden');
                $('#J_back').removeClass('hidden');
                $('.tip-title').addClass('transiting').find('p').text('已发货');
                $('.supply-remark').removeClass('hidden');
                $('.express-info').removeClass('hidden');
                $('#J_express_no').html(data.express_number);
                $('#J_express_name').html(data.express_name);
            }
            // 已拒绝
            if (data.status == 5) {
                $('#J_submit').addClass('hidden');
                $('#J_back').removeClass('hidden');
                $('.tip-title').addClass('reject').find('p').text('已拒绝');
            }
            // 已取消
            if (data.status == 6) {
                $('#J_submit').addClass('hidden');
                $('#J_back').removeClass('hidden');
                $('.tip-title').addClass('reject').find('p').text('已取消');
            }
        })
    }
    getOrderInfo()

    // 获取汽车品牌
    function getBrand() {
        requestUrl('/customization/order/brand', 'GET', '', function(data) {
            g.brands = data;
            var lis = '',
                brands = '';
            $.each(data, function(i, v) {
                lis += '<li data-id="' + i +'"><span>' + i + '</span></li>';
                brands += '<li class="title" id="' + i + '"><span>' + i + '</span></li>'
                $.each(v, function(index, val) {
                    brands += '<li data-id="' + val.id + '" data-img="' + val.url + '" data-name="' + val.name + '"><span class="img"><img src="' + val.url + '" alt="" /></span><span class="brand-name">' + val.name + '</span></li>';
                })
            })
            $('#J_car_list').html(lis);
            $('#J_car_brand').html(brands);
            $('#J_car_list').height($('#J_car_brand').height());
        })
    }

    // 获取汽车型号
    function getType(id) {
        requestUrl('/customization/order/types', 'GET', {id: id}, function(data) {
            var lis = '<li class="type-title"><span class="img"><img src="' + data.brand.url  + '" alt=""></span><span class="brand-name">' + g.brand_name + '</span></li>';
            $.each(data.types, function(index, val) {
                lis += '<li data-id="' + val.id + '" data-name="' + val.name + '"><span>' + val.name + '</span></li>'
            })
            $('#J_car_type').html(lis);
        })
    }

    // 选择品牌
    $('#J_car_list').on('click', 'li', function() {
        var id = $(this).data('id');
        var dv = $('#J_car_brand')[0];
        $(dv).css('-webkit-overflow-scrolling','auto'); 
        dv.scrollTop = $('#' + id)[0].offsetTop - dv.offsetTop;
        $(dv).css('-webkit-overflow-scrolling','touch');
        $(this).addClass('active').siblings('li').removeClass('active');
    })
    $('#J_select_brand').on('click', function() {
        if (Object.keys(g.brands).length < 1) {
            getBrand()
        }
        $('#mask_select_brand').addClass('in');
        $('body').css({'overflowY': 'hidden', 'position': 'fixed'});
    })

    $('#J_car_brand').on('click', 'li', function() {
        if ($(this).hasClass('title')) return;
        var id = $(this).data('id');
        var name = $(this).data('name');
        g.brand_id = id;
        g.brand_name = name;
        g.brand_img = $(this).data('img');
        $('.J_brand_name').html(name);
        $('#mask_select_brand').removeClass('in');
        $('body').css({'overflowY': 'auto', 'position': 'static'});
        // 联动
        g.type_id = undefined;
        $('.J_type_name').html('请选择')
    })
    $('#mask_select_brand .mask-bg').on('click', function(e) {
        $('#mask_select_brand').removeClass('in');
        $('body').css({'overflowY': 'auto', 'position': 'static'});
    })

    // 选择车型
    $('#J_select_type').on('click', function() {
        if (g.brand_id == undefined) {
            alert('请先选择品牌！')
            return
        }
        if (g.types.length < 1) {
            getType(g.brand_id)
        }
        $('#mask_select_type').addClass('in');
        $('body').css({'overflowY': 'hidden', 'position': 'fixed'});
    })
    $('#J_car_type').on('click', 'li', function() {
        if ($(this).hasClass('type-title')) return;
        var id = $(this).data('id');
        g.type_id = id;
        var name = $(this).data('name');
        $('.J_type_name').html(name);
        $('#mask_select_type').removeClass('in');
        $('body').css({'overflowY': 'auto', 'position': 'static'});
    })
    $('#mask_select_type .mask-bg').on('click', function(e) {
        $('#mask_select_type').removeClass('in');
        $('body').css({'overflowY': 'auto', 'position': 'static'});
    })

    //图片上传
    $('#upload_mobile_pic').on('change', function(){
        if ($('#J_img_box .img-upload-box').length > 8) {
            alert('最多上传8张图片！')
            return
        }
        var $this = $(this);
        var imgName = $(this).val();
        if (imgName === '') {
            return false
        }

        mobileUploadUtils($this, function(data) {
            if(data.status == 200){
                var label = `<label class="img-upload-box">
                                <div class="close">X</div>
                                <img class="upload" src="` + data.data.url + `" data-filename="` + data.data.filename + `">
                            </label>`;
                $("#J_img_box").prepend(label);
            }else{
                alert(data.data.errMsg);
            }
            $('#upload_mobile_pic').val('');
        })
        
    })
    // 删除图片
    $('#J_img_box').on('click', '.close', function() {
        var yes = confirm('确定删除该图片？')
        if (yes) {
            $(this).parents('.img-upload-box').remove()
        }
    })

    // 提交信息
    $('#J_submit').on('click', function() {
        if (g.brand_id == undefined) {
            alert('请选择汽车品牌！')
            return
        }
        if (g.type_id == undefined) {
            alert('请选择汽车型号！')
            return
        }
        var imgs = [];
        for (var i = 0; i < $('img.upload').length; i++) {
            imgs.push($('img.upload').eq(i).data('filename'))
        }
        if (imgs.length < 1) {
            alert('至少上传一张图片！')
            return
        }
        var _data = {
            brand_id: g.brand_id,
            type_id: g.type_id,
            order_number: g.order,
            images: imgs,
            note: $('#J_remark').val()
        }
        requestUrl('/customization/order/upload', 'POST', _data, function(data) {
            window.location.href = '/customization/order?status=2'
        })
    })

});