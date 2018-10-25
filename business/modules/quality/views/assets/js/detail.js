$(function () {
    //获取质保单详情
    requestUrl('/quality/qualityorder/get-order-info', 'GET', {id: url('?id')}, function(data) {
        $('.J_owner_name').html(data.owner_name);
        $('.J_owner_mobile').html(data.owner_mobile);
        $('.J_owner_telephone').html(data.owner_telephone);
        $('.J_owner_email').html(data.owner_email);
        $('.J_owner_address').html(data.owner_address);
        var tpl = $('#J_tpl_list').html();
        var html = juicer(tpl, data);
        $('#J_pro_list').html(html);
        $('.J_quality_code').html(data.code);
        $('.J_car_number').html(data.car_number);
        $('.J_car_frame').html(data.car_frame);
        $('.J_type_name').html(data.type_name);
        $('.J_car_price_range').html(data.car_price_range);
        $('.J_shop_name').html(data.construct_unit);
        $('.J_construct_date').html(data.construct_date);
        $('.J_finished_date').html(data.finished_date);
        $('.J_price').html('￥' + data.price);
    })
})