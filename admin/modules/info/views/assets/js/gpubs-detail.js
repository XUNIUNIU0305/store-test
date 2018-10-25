$(function () {

    var tpl = $('#J_list_tpl').html()
    var nowDate = new Date();
    var nowTime = nowDate.getFullYear() + '-' + (nowDate.getMonth() + 1) + '-' + nowDate.getDate() + '' + nowDate.getHours() + ':' + nowDate.getMinutes() + ':' + nowDate.getSeconds();
    $('input.J_start_time').val(nowTime);
    $('input.J_end_time').val(nowTime);

    $('.date-picker').datetimepicker({
        locale: 'zh-cn',
        format: "YYYY-MM-DD HH:mm:ss"
    });
    // show date
    $('.date-show').on('click', function() {
        $(this).siblings('input').focus()
    })
    data = {
        current_page: 1,
        page_size: 15,
        gpubs_type: 3
    }

    function getOrderlist(data) {
        requestUrl(
            '/info/gpubs-detail/list',
            'GET',
            data,
            function(res) {
                $('#J_list_box').html('')
                var html = juicer(tpl, res.list)
                $('#J_list_box').html(html)
                pagingBuilder.build($('#J_coupon_page'), data.current_page, data.page_size, res.total_count);
                pagingBuilder.click($('#J_coupon_page'), function(page) {
                    data.current_page = page
                    getOrderlist(data)
                })
            }
        )
    }
    getOrderlist(data)

    juicer.register('status', function(data) {
        var status = {
        0: '拼购失败',
        1: '拼购中',
        2: '未提货',
        3: '部分提货',
        4: '全部提货',
        5: '全部',
        6: '未发货',
        7: '已发货',
        8: '已确认收货',
        9: '已关闭'
        }
        return status[data]
    })

    $('.search-btn').on('click', function () {
        var current_page = 1,
            page_size = 15,
            status = $('#order_status').val(),
            detail_number = $('#detail_number').val(),
            mobile = $('#mobile').val(),
            start_datetime =  $('#J_start_time').val(),
            end_datetime = $('#J_end_time').val(),
            group_number = $('#group_number').val(),
            custom_user_account = $('#custom_user_account').val(),
            gpubs_type = $('#gpubs_type').val(),
            product_title = $('#product_title').val()

            data.current_page = current_page
            data.page_size = page_size
            data.status = status
            data.detail_number = detail_number
            data.mobile = mobile
            data.start_datetime = start_datetime
            data.end_datetime = end_datetime
            data.group_number = group_number
            data.custom_user_account = custom_user_account
            data.gpubs_type = gpubs_type
            data.product_title = product_title

            getOrderlist(data)
    })

    $('#gpubs_type').change(function(){
        if($(this).val() == 3){
            $('#order_status').html('<option value="5">全部</option><option value="1">拼购中</option><option value="6">未发货</option><option value="7">已发货</option><option value="8">已确认收货</option><option value="2">未提货</option><option value="3">部分提货</option><option value="4">全部提货</option><option value="9">已关闭</option><option value="0">拼购失败</option>')
        }else if($(this).val() == 1){
            $('#order_status').html('<option value="5">全部</option><option value="1">拼购中</option><option value="2">未提货</option><option value="3">部分提货</option><option value="4">全部提货</option><option value="0">拼购失败</option>')
        }else if($(this).val() == 2){
            $('#order_status').html('<option value="5">全部</option><option value="1">拼购中</option><option value="6">未发货</option><option value="7">已发货</option><option value="8">已确认收货</option><option value="9">已关闭</option><option value="0">拼购失败</option>')
        }
    })

})
