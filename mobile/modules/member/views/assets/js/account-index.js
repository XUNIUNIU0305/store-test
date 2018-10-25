$(function() {
    requestUrl('/member/coupon/get-coupon-list', 'GET', {status: 1}, function(data) {
        if (data.total_count > 0) {
            $('.wechat-activity-0904-mask').addClass('in');
            $('main.container').css('overflowY', 'auto');
        }
    });
    // dismiss mask
    $('[data-dismiss="mask"]').click(function () {
        $(this).parents('.mask-container').removeClass('in');
        $('main.container').css('overflowY', 'auto');
    })
})
$(function(){
    function setCookie(c_name,value,expiredays){
        var exdate = new Date();
        exdate.setTime(Number(exdate) + expiredays);
        document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
    }

    function getCookie(name){ 
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)){
            return unescape(arr[2]); 
        }else{
            return null; 
        }
    }

    //加载会员信息
    function loadUserInfo(){
        var Url="/member/index/get-user-info";
        function success(data){
            $(".J_header_img img").attr("src",data.header_img);
            $(".J_shop_name").html(data.nick_name);
            $(".J_telephone").html(data.mobile);
            $(".J_area").html(data.district);
            $(".J_address").html(data.default_address);
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }
        requestUrl(Url,'get','',success,errorCB);
    }


    //加载最新订单
    function loadNewOrders(){
        var url="/member/order/get-order-list";
        var _data={
            current_page:1,
            page_size:1,
        };
        function success(data){
            var order_list = $('#J_tpl_neworder').html();
            var compiled_edit = juicer(order_list);
            var html = compiled_edit.render(data.orders);
            $('.J_neworder').html(html);
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }
        requestUrl(url,'get',_data,success,errorCB);
    }

    //加载车膜订单列表 
    function loadMyFilm() {
        requestUrl('/membrane/order/list', 'GET', {page:1, page_size: 1}, function(data) {
            var tpl_film = $('#J_tpl_film').html();
            $('.J_myfilm').html(juicer(tpl_film, data));
        })
    }
    loadMyFilm()

    //加载账户余额
    function loadAccountBalance(){
        var url="/member/index/get-user-balance";
        function success(data){
            $(".account-balance").html("￥"+data.rmb.toFixed(2));
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }
        requestUrl(url,'get','',success,errorCB);
    }

    //加载地址列表
    function loadAddressList(){
        var url="/member/address/get-default-address";
        function success(data){
            $(".J_receiver").html(data.consignee);
            $(".J_rec_address").html(data.detail);
            $(".J_rec_mobile").html(data.mobile);
            if (!data.id) {
                $('.acc-address-info .title').addClass('hidden')
            }
        }
        function erroorCB(data){
            alert(data.data.errMsg);
        }
        requestUrl(url,'get','',success,erroorCB);
    }

    //加载最新优惠券
    function loadNewestTicket(){
        var url="/member/coupon/get-coupon-list";
        var _data={
            page_size:1,
            current_page:1,
            status: 1
        };
        function success(data){
            var coupon_list = $('#J_coupon_list').html();
            var compiled_edit = juicer(coupon_list);
            var html = compiled_edit.render(data.codes);
            $('.J_coupon_list').html(html);
        }
        function errorCB(data){
            alert(data.data.errMsg);
        }
        requestUrl(url,'get',_data,success,errorCB);
    }

    if(!getCookie('boxFlag')){
        $('.mask-container').removeClass('hidden');
        setCookie("boxFlag","1",1*24*60*60*1000);
    }

    //只为运营商时显示
    requestUrl('/index/get-user-status','GET',{},function(data){
        if(data.level == 4){
            $('#pg-list .pg-alone').removeClass('hidden');
        }else {
            $('#pg-list .pg-alone').addClass('hidden');
        }
    });

    //加载
    loadUserInfo();
    loadNewOrders();
    loadAccountBalance();
    loadAddressList();
    
})