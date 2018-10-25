$(function() {
    // 获取质保单详情
    var detail = {
        order_code: url('?order_code'),
        tpl: $('#J_tpl_list').html(),
        getDetail: function() {
            requestUrl('/quality/quality-search/detail-by-owner', 'GET', {order_code: detail.order_code}, function(data) {
                if (data.package) {
                    $('.empty-result-tip').addClass('hidden')
                    $('.container-detail').removeClass('hidden')
                    // 质保单信息
                    $('#J_order_code').text(data.order.quality_order)
                    $('#J_order_create_time').text(data.order.construct_time)
                    // 车主信息
                    $('#J_owner_name').text(data.owner.owner_name)
                    $('#J_owner_mobile').text(data.owner.owner_mobile)
                    $('#J_owner_address').text(data.owner.owner_address)
                    $('#J_owner_phone').text(data.owner.owner_telephone)
                    $('#J_owner_email').text(data.owner.owner_email)
                    // 车辆信息
                    $('#J_car_number').text(data.car.car_number)
                    $('#J_car_frame').text(data.car.car_frame)
                    $('#J_car_brand_type').text(data.car.car_brand_type)
                    $('#J_car_price_range').text(data.car.car_price_range)
                    // 产品信息
                    $('#J_package_name').text(data.package)
                    if (data.type == 1) {
                        $('.J_work_option').removeClass('hidden')
                    }
                    $('#J_package_box').html(juicer(detail.tpl, data.items))
                    // 施工信息
                    $('#J_construct_custom').text(data.construct.construct_custom)
                    $('#J_construct_price').text(data.construct.price)
                    $('#J_construct_time').text(data.construct.construct_time)
                    $('#J_finished_time').text(data.construct.finished_time)
                }
            }, function() {
                $('.empty-result-tip').reomveClass('hidden')
                $('.container-detail').addClass('hidden')
            })
        }
    }
    detail.getDetail()
})