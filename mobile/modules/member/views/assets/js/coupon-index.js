$(function () {
    //优惠劵数据加载
    var coupon_list = $('#J_coupon_list').html();
    var compiled_coupon = juicer(coupon_list);
    function supplier(data) {
        if (data === false) {
            return '全场可用'
        }
        return data.brand_name + '专用'
    }
    juicer.register('supplier_build', supplier);
    //优惠劵获取数据加载列表
    function getList(statu) {
        function listCoupon(data) {
            var html = compiled_coupon.render(data.codes);
            $('.available-info').html(html);
            totalpage = Math.ceil(data.total_count/10);

        }
        requestUrl('/member/coupon/get-coupon-list','GET',{current_page: 1, page_size: 10,status:statu},  listCoupon);
    }
    getList(1);
    var statu = 1;
    var current_page = 1, page_size = 999;
    var coupon_list = $('#J_coupon_list').html();
    var compiled_coupon = juicer(coupon_list);
    var already_list = $('#J_coupon_list_already').html();
    var compiled_already = juicer(already_list);
    $(".bottom-nav-coupons").on('click','.span-available',function(){
        $(this).addClass('spam-first');
        $('.span-already').removeClass('spam-first');
        $('.coupon-ul-body').show();
        $('.coupon-ul-body-already').hide();
        getalreadylist(1,3);
        statu = 1;
        current_page = 1;
    });
    $(".bottom-nav-coupons").on('click','.span-already',function(){
        $(this).addClass('spam-first');
        $('.span-available').removeClass('spam-first');
        $('.coupon-ul-body-already').show()
        $('.coupon-ul-body').hide();
        getalreadylist(2,3);
        statu = 2;
        current_page = 1;
    });
    var totalpage = 0;
    function getalreadylist(statu,num) {
        if(statu == 1){
            //优惠劵数据加载
            //优惠劵获取数据加载列表
            function listCoupon(data) {
                var html = compiled_coupon.render(data.codes);
                if(num == 4){
                    var htmlinfo = $('.available-info').html();
                    $('.available-info').html(htmlinfo+html);
                }else {
                    $('.available-info').html(html);
                }
                totalpage = Math.ceil(data.total_count/10);
            }
            requestUrl('/member/coupon/get-coupon-list','GET',{current_page: current_page, page_size: page_size,status:statu},  listCoupon);
        }
        if(statu == 2){
            //优惠劵获取数据加载列表
            function listCoupon(data) {
                var html = compiled_already.render(data.codes);
                if(num == 4){
                    var htmlinfo = $('.already-info').html();
                    $('.already-info').html(htmlinfo+html);
                }else {
                    $('.already-info').html(html);
                }
                totalpage = Math.ceil(data.total_count/10);
            }
            requestUrl('/member/coupon/get-coupon-list','GET',{current_page: current_page, page_size: page_size,status:statu},  listCoupon);
        }
    }
    // $(window).scroll(function () {
    //     totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
    //     if ($(document).height() <= totalheight) {
    //         ++current_page;
    //         if(current_page <= totalpage){
    //             getalreadylist(statu,4);
    //         }
    //     }
    // });
})