$(function() {
    // 质保单详情
    var detail = {
        order_code: url('?order_code'),
        tpl: $('#J_tpl_list').html(),
        getInfo: function() {
            requestUrl('/quality/quality-search/detail-by-itemcode', 'GET', {order_code: detail.order_code}, function(data) {
                $('#J_order_code').text(data.order.quality_order)
                $('#J_order_create_time').text(data.order.construct_time)
                $('#J_owner_name').text(data.owner.owner_name)
                $('#J_car_brand').text(data.car.car_brand_type)
                $('#J_construct_custom').text(data.construct.construct_custom)
                $('#J_construct_time').text(data.construct.construct_time)
                $('#J_finished_time').text(data.construct.finished_time)
                $('#J_product_box').html(juicer(detail.tpl, data.items))
                if (data.type == 1) {
                    $('.J_work_option').removeClass('hidden')
                }
            })
        }
    }
    detail.getInfo()
})