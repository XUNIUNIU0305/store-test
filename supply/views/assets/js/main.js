$(function() {
    //获取API域名
    requestUrl('/api-hostname', 'GET', '', function(data) {
        $('#modalSupShopInfo').data('src', data.hostname);
        //获取省级信息
        getMsg(data.hostname + '/district/province','',ProvinceFn);
    })

    //获取左侧菜单栏
    function getMenuList() {
        function menuCB(data) {
            function classBuild(data) {
                if (location.pathname === data) {
                    return 'actived'
                } else {
                    return ''
                }
            }
            var tpl_menu = $('#J_tpl_menu').html();
            juicer.register('class_build', classBuild);
            var result = juicer(tpl_menu, data);
            $('.apx-seller-aside').removeClass('loading').append(result);
        }
        requestUrl('/main/menu', 'GET', '', menuCB)
    }
    if ($('.apx-seller-aside').length > 0) getMenuList();
    //获取账户信息
    var accountMsg = {};
    function getAccountMsg() {
        requestUrl('/profile/get-profile', 'GET', '', function(data) {
            accountMsg = data;
            $('.J_brand_name').html(data.brand_name);
            $('.J_company_name').html(data.company_name);
            if (data.address != '') $('.J_address').html('退换货地址：' + data.address);
            if (data.header_img != '') $('.J_header_img').attr('src', data.header_img);
        })
    }
    getAccountMsg()

    $('#modalSupShopInfo').on('show.bs.modal', function() {
        $('#modalSupShopInfo #brand_name').val(accountMsg.brand_name);
        $('#modalSupShopInfo #co_name').val(accountMsg.company_name);
        if (accountMsg.mobile != 0) $('#modalSupShopInfo #mobile').val(accountMsg.mobile);
        $('#modalSupShopInfo #tel_prefex').val(accountMsg.area_code);
        $('#modalSupShopInfo #tel_tail').val(accountMsg.telephone);
        $('#modalSupShopInfo #address_detail').val(accountMsg.address);
        $('#modalSupShopInfo #real_name').val(accountMsg.real_name);
        $('#modalSupShopInfo .error-msg').addClass('hide');
        if (accountMsg.province_id != 0) {
            $('#address_province').val(accountMsg.province_id);
            if (accountMsg.city_id != 0) {
                getMsg($('#modalSupShopInfo').data('src') + '/district/city', {province: accountMsg.province_id}, CityFn, false);
                $('#address_city').val(accountMsg.city_id);
                getMsg($('#modalSupShopInfo').data('src') + '/district/district', {province: accountMsg.province_id, city: accountMsg.city_id}, DistrictFn, false);
                $('#address_district').val(accountMsg.district_id);
            } else {
                $('#address_city').parent('div').addClass('hidden');
                getMsg($('#modalSupShopInfo').data('src') + '/district/district', {province: accountMsg.province_id, city: 0}, DistrictFn, false);
                $('#address_district').val(accountMsg.district_id);
            }
        }
        if (accountMsg.header_img != '') {
            $('#modalSupShopInfo .J_header_img_up').removeClass('hidden');
            $('#modalSupShopInfo .J_header_img_up').attr('src', accountMsg.header_img);
        }
    })
    //获取地址信息
    function getMsg(address, data, fn, async) {
        function msgCB(data) {
            var result = '<option value="-1">请选择</option>';
            for (var i = 0; i < data.length; i++) {
                result += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            if (data.length == 0) {
                result = ''
            }
            if (typeof(fn) == 'function') fn(result);
        }
        requestUrl(address, 'GET', data, msgCB, '', async)
    }
    
    //省级下拉函数
    function ProvinceFn(data) {
        $('#address_province').html(data);
        //省市联动
        $('#address_province').off('.pro').on('change.pro', function() {
            reset($('#address_city'));
            reset($('#address_district'));
            $('#address_city').parent('div').removeClass('hidden');
            $('#address_district').parent('div').removeClass('hidden');
            if ($(this).val() == -1) return;
            $('#address_city').val('-1');
            $('#address_district').val('-1');
            var _data = {province: $(this).val()}
            getMsg($('#modalSupShopInfo').data('src') + '/district/city', _data, CityFn)
        })
    }
    //市级下拉函数
    function CityFn(data) {
        $('#address_city').html(data);
        if (data == '') {
            $('#address_city').parent('div').addClass('hidden');
            $('#address_city').val('-1');
            var _data = {
                province: $('#address_province').val(),
                city: 0
            }
            getMsg($('#modalSupShopInfo').data('src') + '/district/district', _data, DistrictFn)
            return
        }
        //市区联动
        $('#address_city').off('.cit').on('change.cit', function() {
            $('#address_district').parent('div').removeClass('hidden');
            $('#address_district').val('-1');
            if ($(this).val() == -1) return;
            var _data = {province: $('#address_province').val(),city: $('#address_city').val()}
            getMsg($('#modalSupShopInfo').data('src') + '/district/district', _data, DistrictFn)
        })
    }
    //区级菜单函数
    function DistrictFn(data) {
        $('#address_district').html(data);
        if (data == '') {
            $('#address_district').parent('div').addClass('hidden');
            $('#address_district').val('-1');
            return
        }
    }
    //重置下拉菜单
    function reset(item) {
        item.html('<option value="-1">请选择</option>');
    }

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
    $('#shop_info_avatar').on('change', function(e) {
        var imgName = $(this).val();
        var $this = $(this);
        var suffix = getSuffix(imgName);
        if (suffix == '') return;
        //上传成功回调处理
        function succesCB(data) {
            if (data.status == 200) {
                $('.error-msg').addClass('hide');
                $('#modalSupShopInfo .J_header_img_up').removeClass('hidden');
                $this.siblings('img').attr({'src': data.data.url, 'data-filename': data.data.filename});
            } else {
                $('.error_msg').html(data.data.errMsg);
                $('.error-msg').removeClass('hide');
            }
        }
        //请求OSS回调
        requestUrl('/release/permission', 'GET', {file_suffix: suffix}, function(data) {
            var formData = setUpParam($this, data);
            uploadImg(data, formData, succesCB);
        })
    })
    //提交
    $('#J_sure_edit').on('click', function() {
        var brand_name = $('#brand_name').val().trim();
        var co_name = $('#co_name').val().trim();
        var mobile = $('#mobile').val().trim();
        var area_code = $('#tel_prefex').val().trim();
        var tel_tail = $('#tel_tail').val().trim();
        var address_detail = $('#address_detail').val().trim();
        var province = $('#address_province').val();
        var city = $('#address_city').val();
        var district = $('#address_district').val();
        var img = $('.J_header_img_up').attr('data-filename');
        var real_name = $('#real_name').val().trim();
        if (brand_name == '' || co_name == '' || mobile == '' || area_code == '' || address_detail == '' || real_name == '') {
            $('.error_msg').html('请把信息填写完整！');
            $('.error-msg').removeClass('hide');
            return
        }
        if (province == -1 || district == -1) {
            $('.error_msg').html('请选择地址！');
            $('.error-msg').removeClass('hide');
            return
        }
        var resultMB = mobile.search(/0?(1)[0-9]{10}/);
        if (resultMB == -1) {
            $('.error_msg').html('手机号格式错误！');
            $('.error-msg').removeClass('hide');
            return
        }
        if (area_code.length < 3 || tel_tail.length < 7) {
            $('.error_msg').html('固话格式错误！');
            $('.error-msg').removeClass('hide');
            return
        }
        var resultAR = area_code.search(/[^0-9]/g);
        if (resultAR != -1) {
            $('.error_msg').html('固话格式错误！');
            $('.error-msg').removeClass('hide');
            return
        }
        var resultTT = tel_tail.search(/[^0-9]/g);
        if (resultTT != -1) {
            $('.error_msg').html('固话格式错误！');
            $('.error-msg').removeClass('hide');
            return
        }
        if ($('#address_city').parent('div').hasClass('hidden')) city = 0;
        if ($('#address_district').parent('div').hasClass('hidden')) district = 0;
        var data = {
            brand_name: brand_name,
            company_name: co_name,
            mobile: mobile,
            area_code: area_code,
            telephone: tel_tail,
            province: province,
            city: city,
            district: district,
            address: address_detail,
            header_img: img || '',
            real_name: real_name
        }
        requestUrl('/profile/save-profile', 'POST', data, function(data) {
            $('#modalSupShopInfo').modal('hide');
            getAccountMsg();
        })
    })
})
