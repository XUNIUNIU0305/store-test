$(function() {

    var list = {
        tpl: $('#J_tpl_list').html(),
        getInfo: function(params) {
            var _default = {
                type: -1,
                top_area_id: -1,
                secondary_area_id: -1,
                tertiary_area_id: -1,
                quaternary_area_id: -1,
                status: -1,
                activity_gpubs_id: $('#activity_gpubs_id').val()
            }
            $.extend(_default, params)
            requestUrl('/activity/gpubs-management/statistics', 'GET', _default, function(data) {
                $('.J_total_quantity').text(data.total_quantity)
                $('.J_wait_quantity').text(data.wait_quantity)
                $('.J_establish_quantity').text(data.establish_quantity)
                console.log(data.wait_total_fee)
                if(data.wait_total_fee == '' || data.wait_total_fee == null || data.wait_total_fee == undefined){
                    $('.J_wait_total_fee').text(0)
                }else{
                    $('.J_wait_total_fee').text(data.wait_total_fee)
                }
                $('.J_establish_product_quantity').text(data.establish_product_quantity)
                $('.J_establish_total_fee').text(data.establish_total_fee.toFixed(2))
            })
        },
        getTop: function() {
            requestUrl('/activity/gpubs-management/top-areas', 'GET', '', function(data) {
                var ops = '<option value="-1">全部</option>'
                $.each(data, function(i, val) {
                    ops += '<option value="' + val.id + '">' + val.name + '</option>'
                })
                $('#J_group_province').html(ops)
            })
        },
        getSecond: function(params) {
            requestUrl('/activity/gpubs-management/secondary-areas', 'GET', params, function(data) {
                var ops = '<option value="-1">全部</option>'
                $.each(data, function(i, val) {
                    ops += '<option value="' + val.id + '">' + val.name + '</option>'
                })
                $('#J_group_city').html(ops)
            })
        },
        getTertiary: function(params) {
            requestUrl('/activity/gpubs-management/tertiary-areas', 'GET', params, function(data) {
                var ops = '<option value="-1">全部</option>'
                $.each(data, function(i, val) {
                    ops += '<option value="' + val.id + '">' + val.name + '</option>'
                })
                $('#J_group_three').html(ops)
            })
        },
        getQuaternary: function(params) {
            requestUrl('/activity/gpubs-management/quaternary-areas', 'GET', params, function(data) {
                var ops = '<option value="-1">全部</option>'
                $.each(data, function(i, val) {
                    ops += '<option value="' + val.id + '">' + val.name + '</option>'
                })
                $('#J_group_four').html(ops)
            })
        },
        getList: function(params) {
            var _default = {
                current_page: 1,
                page_size: 20,
                type: -1,
                top_area_id: -1,
                secondary_area_id: -1,
                tertiary_area_id: -1,
                quaternary_area_id: -1,
                status: -1,
                activity_gpubs_id: $('#activity_gpubs_id').val()
            }
            $.extend(_default, params)
            requestUrl('/activity/gpubs-management/list', 'GET', _default, function(data) {
                $('#J_list_box').html(juicer(list.tpl, data.data))
                if (data.data.length > 0) {
                    pagingBuilder.build($('#J_page_box'), _default.current_page, _default.page_size, data.total_count)
                    pagingBuilder.click($('#J_page_box'), function(page) {
                        _default.current_page = page
                        list.getList(_default)
                    })
                } else {
                    $('#J_page_box').html('')
                }
            })  
        },
        forceEstablish: function(params) {
            requestUrl('/activity/gpubs-management/force-establish', 'POST', params, function(data) {
                alert('操作成功！')
                $('#J_search_btn').click()
            })
        },
        init: function() {
            this.getInfo()
            this.getList()
            this.getTop()
            // 区域联动
            $('#J_group_province').on('change', function() {
                var id = $(this).val()
                if (id === '-1') {
                    $('#J_group_city').html('<option value="-1">全部</option>')
                    $('#J_group_three').html('<option value="-1">全部</option>')
                    $('#J_group_four').html('<option value="-1">全部</option>')
                } else {
                    $('#J_group_three').html('<option value="-1">全部</option>')
                    $('#J_group_four').html('<option value="-1">全部</option>')
                    list.getSecond({
                        top_area_id: id
                    })
                }
            })
            $('#J_group_city').on('change', function() {
                var id = $(this).val()
                if (id === '-1') {
                    $('#J_group_three').html('<option value="-1">全部</option>')
                    $('#J_group_four').html('<option value="-1">全部</option>')
                } else {
                    $('#J_group_four').html('<option value="-1">全部</option>')
                    list.getTertiary({
                        secondary_area_id: id
                    })
                }
            })
            $('#J_group_three').on('change', function() {
                var id = $(this).val()
                if (id === '-1') {
                    $('#J_group_four').html('<option value="-1">全部</option>')
                } else {
                    list.getQuaternary({
                        tertiary_area_id: id
                    })
                }
            })
            // 绑定查询
            $('#J_search_btn').on('click', function() {
                var type = $('#J_group_type').val()
                var province = $('#J_group_province').val()
                var city = $('#J_group_city').val()
                var three = $('#J_group_three').val()
                var four = $('#J_group_four').val()
                var status = $('#J_group_status').val()
                var params = {
                    type: type,
                    top_area_id: province,
                    secondary_area_id: city,
                    tertiary_area_id: three,
                    quaternary_area_id: four,
                    status: status
                }
                list.getInfo(params)
                list.getList(params)
            })
            // 强制成团
            $('#J_list_box').on('click', '.handle', function() {
                var yes = confirm('一旦强制成团后，操作将不可逆转！是否确定成团？')
                if (!yes) {
                    return
                }
                var id = $(this).data('id')
                list.forceEstablish({
                    group_id: id
                })
            })
        }
    }
    list.init()

    juicer.register('status', function(data) {
        if (data.status === 2) {
            if (data.present_quantity < data.target_quantity || data.present_member < data.target_member) {
                return '强制成团'
            }
        }
        var status = {
            1: '待成团',
            2: '已成团',
            3: '已取消',
            4: '已发货'
        }
        return status[data.status]
    })
})
