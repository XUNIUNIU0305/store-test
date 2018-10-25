$(function() {
    // 按质保单查询
    var code_search = {
        tpl: $('#J_code_tpl_list').html(),
        getInfo: function(order_code) {
            requestUrl('/quality/quality-search/search-by-ordercode', 'GET', {order_code: order_code}, function(data) {
                if (data.package) {
                    $('#home .search-result-container .empty-result-tip').addClass('hidden')
                    $('#J_code_result').removeClass('hidden')
                    $('#J_code_package_type').text(data.package)
                    $('#J_code_order_code').text(data.order.quality_order)
                    $('#J_code_create_time').text(data.order.construct_time)
                    $('#J_code_package_list').html(juicer(code_search.tpl, data.items))

                    $('#J_code_owner_name').text(data.owner.owner_name)
                    $('#J_code_car_brand').text(data.car.car_brand_type)
                    $('#J_code_construct_custom').text(data.construct.construct_custom)
                    $('#J_code_construct_time').text(data.construct.construct_time)
                    $('#J_code_finished_custom').text(data.construct.finished_time)
                    if (data.type == 1) {
                        $('.J_work_option').removeClass('hidden')
                    }
                }
            }, function(data) {
                $('#home .search-result-container .empty-result-tip').removeClass('hidden')
                $('#J_code_result').addClass('hidden')
            })
        },
        init: function() {
            $('#J_code_btn').on('click', function() {
                var code = $('#J_code_input').val().trim()
                $('#home .search-result-container').removeClass('hidden')
                if (code === '') {
                    $('#home .search-result-container .empty-tip').removeClass('hidden')
                    $('#J_code_result').addClass('hidden')
                    return
                }
                $('#home .search-result-container .empty-tip').addClass('hidden')
                code_search.getInfo(code)
            })
        }
    }
    code_search.init()
    // 按管芯号查询
    var tubecode_search = {
        tpl: $('#J_tubecode_tpl_list').html(),
        getInfo: function(item_code) {
            $('#profile .search-result-container').removeClass('hidden')
            requestUrl('/quality/quality-search/list-by-itemcode', 'GET', {item_code: item_code}, function(data) {
                if (data) {
                    $('#profile .search-result-container .empty-result-tip').addClass('hidden')
                    $('#J_tubecode_result').removeClass('hidden')
                    $('#J_tubecode_package_list').html(juicer(tubecode_search.tpl, data))
                }
            }, function() {
                $('#profile .empty-result-tip').removeClass('hidden')
                $('#J_tubecode_result').addClass('hidden')
            })
        },
        init: function() {
            $('#J_tubecode_btn').on('click', function() {
                var code = $('#J_tubecode_input').val()
                tubecode_search.getInfo(code)
            })
        }
    }
    tubecode_search.init()
})