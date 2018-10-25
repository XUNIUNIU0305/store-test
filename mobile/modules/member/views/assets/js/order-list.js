$(function () {
    //获取订单列表模板
    var order_list = $('#J_tpl_order').html();
    var current_page = 1;
    var page_size = 3;
    var status = '';
    var total_count = '';

    function init(){
        if (status === '') {
            status = url('?status');
        }
        $('#status-span span').removeClass('spam-first');                 
        $('.J_order_info[status="' + status + '"]').addClass('spam-first'); 
        $('#J_order_list').html('');
        current_page = 1;
        loadOrderList(status, current_page, page_size);
    }

    function loadOrderList() {
        var url = "/member/order/get-order-list?status=" + status + '&current_page=' + current_page + '&page_size=' + page_size;
        function success(data) {
            //导航切换
            $(".J_order_info").off("click").on("click", function () {
                status = $(this).attr('status'); 
                $('#status-span span').removeClass('spam-first');
                $(this).addClass('spam-first');
                $('#J_order_list').html('');
                current_page = 1;
                loadOrderList();              
            }); 
            
            var tofixed = function (data) {
                return data.toFixed(2)
            }
            var attr = function (data) {
                var len = data.length;
                var str = '';
                for (var i = 0; i < len; i++) {
                    str += data[i].attribute + '-' + data[i].option;
                    if (i < len - 1) {
                        str += ';'
                    }
                }
                return str
            }

            var itemsCount = function(data){
                var count = 0;
                var len = data.length
                for (var i = 0; i < len; i++) {
                    count +=parseInt(data[i].count);
                }
                return count;
            }

            total_count = data.total_count;
            juicer.register('attr_build', attr);
            juicer.register('tofixed_build', tofixed); //注册自定义函数
            juicer.register('count_items', itemsCount);


            var compiled_edit = juicer(order_list);
            var html = compiled_edit.render(data);
            $('#J_order_list').append(html);

        }
        function errorCB(data) {
            alert(data.data.errMsg);
        }
        //提交请求
        requestUrl(url, 'get', '', success, errorCB);
            
    }

    //初始化数据
    init();

    $(window).scroll(function () {
        var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
        if ($(document).height() <= totalheight) {
            ++current_page;
            if (current_page <= Math.ceil(total_count / page_size)) {
                loadOrderList();
            }
           
        }
    });
    /*取消订单*/
    $('#J_order_list').on('click', '.J_cancel_order', function() {
        var no = $(this).data('no');
        var yes = confirm('确定要取消该订单？');
        if (!yes) {return};
        requestUrl('/member/order/cancel', 'POST', {orders_no: [no]}, function(data) {
            init()
        })
    })

    /*确认收货*/
    $('#J_order_list').on('click', '.J_sure_get', function() {
        var no = $(this).data('no');
        var yes = confirm('确定已收到商品？');
        if (!yes) {return};
        requestUrl('/member/order/confirm', 'POST', {no: no}, function(data) {
            init()
        })
    })
});