$(function(){
    $('.account-header').removeClass('hidden');
    // address page
    $(document).on('click', '.J_address_delete_btn', function (e) {
        $(this).parents('.J_box').find('.del-confirm').removeClass('hidden');
    })
    // 取消
    $(document).on('click', '.J_address_cancel_btn', function (e) {
        $(this).parents('.del-confirm').addClass('hidden');
    })

    var tpl_address = $('#J_tpl_address').html();
    var compiled_tpl = juicer(tpl_address);

    var items = [], flag = true;
    function getList() {
        function listCB(data) {
            items = data;
            var list = compiled_tpl.render(data);
            $('#J_address_box').html(list);

            //设置默认地址
            $('.J_set_default').on('click', function() {
                var $this = $(this);
                var _adata = {
                    id: $this.parents('.J_address').data('id')
                }
                function defaultCB(data) {
                    getList();
                    if (window.opener != null) window.opener.location.reload();
                }
                requestUrl('/account/address/default', 'POST', _adata, defaultCB)
            })
            //删除地址
            $('.J_address').on('click', '.J_adress_delete-confirm', function() {
                var $this = $(this);
                var _bdata = {
                    id: $this.parents('.J_address').data('id')
                }
                function delAddressCB(data) {
                    $this.parents('.J_box').remove();
                    if (window.opener != null) window.opener.location.reload();
                }
                requestUrl('/account/address/remove', 'POST', _bdata, delAddressCB)
            })
            //修改地址
            $('.J_alter').on('click', function() {
                $('.J_address_city').removeClass('hidden').find('ul').html('');
                $('.J_address_district').removeClass('hidden').find('ul').html('');
                flag = false;
                $('#collapseAddress').addClass('in');
                $('h5[class*="apx-edit-address-subtitle"]').html('修改收货地址');
                scrollToAddAddressForm();
                $('.J_add_address').data('id', $(this).parents('.J_address').data('id'));
                var id = $(this).parents('.J_box').data('id');
                $('#J_username').val(items[id].consignee);
                $('#J_detail').val(items[id].detail);
                $('#J_mobile').val(items[id].mobile);
                $('#J_code').val(items[id].postal_code);
                $('.J_address_province span').html(items[id].province.name);
                $('.J_address_province').attr('tabindex', items[id].province.id);
                if (items[id].city.name != '') {
                    $('.J_address_city span').html(items[id].city.name);
                    $('.J_address_city').attr('tabindex', items[id].city.id);
                    var _data = {
                        province: items[id].province.id
                    }
                    getMsg($('#collapseAddress').data('src') + '/district/city', _data, CityFn);
                } else {
                    $('.J_address_city').addClass('hidden');
                    $('.J_address_city').attr('tabindex', 0);
                }
                if (items[id].district.name != '') {
                    $('.J_address_district').attr('tabindex', items[id].district.id);
                    $('.J_address_district span').html(items[id].district.name);
                    var _data = {
                        province: items[id].province.id,
                        city: items[id].city.id
                    }
                    getMsg($('#collapseAddress').data('src') + '/district/district', _data, DistrictFn);
                } else {
                    $('.J_address_district').addClass('hidden');
                    $('.J_address_district').attr('tabindex', 0);
                }
            })
        }
        requestUrl('/account/address/list', 'GET', '', listCB);
    }
    getList()

    // 省市区下拉菜单同步
    $('.apx-edit-address-form').on('click', '.dropdown .dropdown-menu a', function (e) { 
        e.preventDefault();
        e.stopPropagation();
        $(this).parents('.dropdown').removeClass('open');
        $(this).parents('.dropdown').find('.btn > span').text($(this).text());
        $(this).parents('.dropdown').attr('tabindex', $(this).attr('tabindex'));
    })

    //省级下拉菜单函数
    function ProvinceFn(result) {
        $('.J_address_province ul').html(result);
        //绑定省市联动
        $('.J_address_province .dropdown-menu a').on('click', function() {
            $('.J_address_city').removeClass('hidden').find('ul').html('');
            $('.J_address_district').removeClass('hidden').find('ul').html('');
            $('.J_address_city').find('.btn > span').text('市');
            $('.J_address_district').find('.btn > span').text('区');
            $('.J_address_city').attr('tabindex', -1);
            $('.J_address_district').attr('tabindex', -1);
            var _data = {province: $(this).attr('tabindex')};
            getMsg($('#collapseAddress').data('src') + '/district/city', _data, CityFn);
        })
    }
    //市级下拉菜单函数
    function CityFn(city) {
        $('.J_address_city ul').html(city);
        if (city == '') {
            $('.J_address_city').addClass('hidden');
            $('.J_address_city').attr('tabindex', -1);
            var _data = {
            province: $('.J_address_province').attr('tabindex'),
            city: 0
            }
            getMsg($('#collapseAddress').data('src') + '/district/district', _data, DistrictFn);
            return
        }
        //绑定市区联动
        $('.J_address_city .dropdown-menu a').on('click', function() {
            $('.J_address_district').removeClass('hidden').find('ul').html('');
            $('.J_address_district').find('.btn > span').text('区');
            $('.J_address_district').attr('tabindex', -1);
            var _data = {
                province: $('.J_address_province').attr('tabindex'),
                city: $(this).attr('tabindex')
            }
            getMsg($('#collapseAddress').data('src') + '/district/district', _data, DistrictFn);
        })
    }
    //区级下拉菜单函数
    function DistrictFn(district) {
        $('.J_address_district ul').html(district);
        if (district == '') {
            $('.J_address_district').addClass('hidden');
            $('.J_address_district').attr('tabindex', -1);
            return
        }
    }

    //初始化地址
    function init() {
        //获取省市区API域名
        function initCB(data) {
            $('#collapseAddress').data('src', data.hostname);
            //获取省级信息
            var address = '';
            address = data.hostname + '/district/province';
            getMsg(address,'',ProvinceFn)
        }
        requestUrl('/api-hostname', 'GET', '', initCB);
    }

    //获取信息
    function getMsg(address, data, fn) {
        function msgCB(data) {
            var result = '';
            for (var i = 0; i < data.length; i++) {
                result += '<li role="presentation"><a role="menuitem" tabindex="' + data[i].id + '" href="javaScript:;">' + data[i].name + '</a></li>'
            }
            if (typeof(fn) == 'function') fn(result);
        }
        requestUrl(address, 'GET', data, msgCB)
    }

    init()

    //限制邮编输入
    $('.J_only_number').on('keyup', function() {
        var number = $(this).val().replace(/\D/g,'');
        $(this).val(number);
    })
    //限制手机号
    $('#J_mobile').on('keyup', function() {
       var number = $(this).val().replace(/\D|^[2-9]/g,'') - 0;
        $(this).val(number);
        if (number < 1) {
            $(this).val('');
        }
    })

    // 滚动屏幕到表单
    function scrollToAddAddressForm() {
        $('html, body').animate({
            scrollTop: $('.apx-edit-address-wrap').offset().top
        }, 400)
    }
    // $('.J_address_add').click(scrollToAddAddressForm);
    // 滚动屏幕到收货地址
    function scrollToAddress() {
        $('html, body').animate({
            scrollTop: $('.apx-edit-address-wrap').eq(0).offset().top
        }, 400)
    }

    //添加收货地址
    $('.J_add_address').on('click', function() {
        if (flag) {
            setAddress('/account/address/add', '');
        } else {
            setAddress('/account/address/edit', $('.J_add_address').data('id'));
        }
    })
    //添加或修改地址
    function setAddress(url, _id) {
        var province = $('.J_address_province').attr('tabindex');
        var city = $('.J_address_city').attr('tabindex');
        var district = $('.J_address_district').attr('tabindex');
        if ($('.J_address_district').hasClass('hidden')) {
            district = 0
        }
        if ($('.J_address_city').hasClass('hidden')) {
            city = 0
        }
        if (province == undefined || province == -1) {alert('未选择省份'); return};
        if (city == undefined || city == -1) {alert('未选择城市'); return};
        if (district == undefined || district == -1) {alert('未选择区'); return};
        var data = {
            consignee: $('#J_username').val(),
            province:  province,
            city: city,
            district: district,
            detail: $('#J_detail').val(),
            mobile: $('#J_mobile').val(),
            postal_code: $('#J_code').val(),
            id: _id
        }
        if (data.consignee.trim() == '') {
            alert('收货人不能为空！')
            return
        }
        if (data.detail.trim() == '') {
            alert('请填写详细地址！')
            return
        }
        if (data.mobile == '') {
            alert('手机号码不能为空！')
            return
        }
        if (data.postal_code == '') {
            alert('邮政编码不能为空！')
            return
        }
        function editCB(data) {
            $('#collapseAddress').removeClass('in');
            $('h5[class*="apx-edit-address-subtitle"]').html('新增收货地址');
            if (window.opener != null) window.opener.location.reload();
            scrollToAddress();
            $('#J_username').val('');
            $('#J_detail').val('');
            $('#J_mobile').val('');
            $('#J_code').val('');
            $('.J_address_city').removeClass('hidden').find('ul').html('');
            $('.J_address_district').removeClass('hidden').find('ul').html('');
            $('.J_address_province').find('.btn > span').text('省');
            $('.J_address_city').find('.btn > span').text('市');
            $('.J_address_district').find('.btn > span').text('区');
            $('.J_address_province').attr('tabindex', -1);
            $('.J_address_city').attr('tabindex', -1);
            $('.J_address_district').attr('tabindex', -1);
            flag = true;
            getList();
        }
        requestUrl(url, 'POST', data, editCB);
    }
    
});