$(function() {

    // sync the content with option
    $("select").on("change", function() {
        var $cnt = $(this).siblings(".cnt");
        var val = $(this).val();
        var content = $(this).find('option[value="' + val + '"]').text();
        $cnt.text(content);
    });

    // loading
    function addLoading($dom) {
        $dom.addClass('data-loading');
        var html = '<div class="loading-box">\
                        <div class="loader-inner line-scale">\
                            <div></div>\
                            <div></div>\
                            <div></div>\
                            <div></div>\
                            <div></div>\
                        </div>\
                    </div>';
        $dom.append(html);
    }
    function removeLoading($dom) {
        $dom.removeClass('data-loading');
        $dom.find('.loader-inner').remove()
    }


    $('.nav-tabs span').on('click', function() {
        var type = $(this).data('type')
        $(this).addClass('active').siblings().removeClass('active')
        $('.query-container[data-type="' + type + '"]').removeClass('hidden').siblings('.query-container').addClass('hidden')
    })
    // 线束查询
    var query = {
        tpl: $('#J_tpl_list').html(),
        getCarBrand: function() {
            // 获取车系
            requestUrl('/temp/wire/get-car-brand-list', 'GET', '', function(data) {
                var ops = '<option value="-1">请选择车系</option>';
                $.each(data, function(i, val) {
                    ops += '<option value="' + val.value + '">' + val.context + '</option>'
                })
                $('#J_car_brand').html(ops)
            })
        },
        getCarType: function(params) {
            // 获取车型
            requestUrl('/temp/wire/get-car-type-list', 'GET', params, function(data) {
                var ops = '<option value="-1">请选择车型</option>';
                $.each(data.wire, function(i, val) {
                    ops += '<option value="' + val.id + '" data-remarks="' + val.remarks + '">' + val.model + '</option>'
                })
                $('#J_car_type').html(ops)
            })
        },
        getWireImg: function(params, dom) {
            // 获取线束图片
            addLoading(dom)
            requestUrl('/temp/wire/get-wire-image', 'GET', params, function(data) {
                removeLoading(dom)
                if (params.car_type_id) {
                    $('#wireRemark').html('备注：' + $('#J_car_type option:checked').attr('data-remarks'))
                    $('#J_type_wire_img').attr('src', data.url)
                    $('#J_query_other').data('id', data.wire_id)
                    $('#J_wire_name').text(data.wire_id)
                }
                if (params.wire_id) {
                    $('#J_wire_img').attr('src', data.url)
                }
            })
        },
        getWire: function() {
            // 获取线束列表
            requestUrl('/temp/wire/get-wire-list', 'GET', '', function(data) {
                var ops = '<option value="-1">请选择</option>';
                $.each(data, function(i, val) {
                    ops += '<option value="' + val.value + '">' + val.context + '</option>'
                })
                $('#J_wire_list').html(ops)
            })
        },
        getDetail: function(params) {
            // 获取线束车型详情
            addLoading($('#J_apply_type_list'))
            requestUrl('/temp/wire/get-wire-detail', 'GET', params, function(data) {
                removeLoading($('#J_apply_type_list'))
                $('#J_apply_type_list').html(juicer(query.tpl, data))
            })
        },
        init: function() {
            query.getCarBrand()
            query.getWire()
            // 绑定选择车系
            $('#J_car_brand').on('change', function() {
                $('.J_wire_box').addClass('hidden')
                var val = $(this).val();
                if (val !== '-1') {
                    $('.J_car_type_box').removeClass('hidden')
                    $('.J_car_type_box .cnt').text('请选择车型')
                    query.getCarType({
                        car_brand_id: val
                    })
                } else {
                    $('.J_car_type_box').addClass('hidden')
                }
            })
            // 绑定选择车型
            $('#J_car_type').on('change', function() {
                var val = $(this).val();
                if (val !== '-1') {
                    $('.J_wire_box').removeClass('hidden')
                    $('#J_type_name').text($('#J_car_type option[value="' + val + '"]').html())
                    query.getWireImg({
                        car_type_id: val
                    }, $('.J_wire_box'))
                } else {
                    $('.J_wire_box').addClass('hidden')
                }
            })
            // 绑定选择线束
            $('#J_wire_list').on('change', function() {
                var val = $(this).val();
                if (val !== '-1') {
                    $('[data-type="line"] .section').removeClass('hidden')
                    query.getWireImg({
                        wire_id: val
                    }, $('.J_wire_result'))
                    query.getDetail({
                        wire_id: val
                    })
                } else {
                    $('[data-type="line"] .J_wire_result').addClass('hidden')
                    $('#J_apply_type_list').addClass('hidden')
                }
            })
            // 查看其它适配车型
            $('#J_query_other').on('click', function() {
                var id = $(this).data('id');
                if (id && id.toString().search(/[0-9]+/) != -1) {
                    $('span[data-type="line"]').addClass('active').siblings().removeClass('active');
                    $('.query-container[data-type="line"]').removeClass('hidden').siblings('.query-container').addClass('hidden')
                    $('#J_wire_list').val(id).change()
                }
            })
        }
    }
    query.init()
})
