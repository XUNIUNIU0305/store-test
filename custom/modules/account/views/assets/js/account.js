var confirmBoxShow = false;
//获取订单数量
function getNumber() {
    function numberCB(data) {
        $('span[data-name="unpaid"]').html('(' + data.unpaid + ')');
        $('span[data-name="undeliver"]').html('(' + data.undeliver + ')');
        $('span[data-name="delivered"]').html('(' + data.delivered + ')');
        $('span[data-name="confirmed"]').html('(' + data.confirmed + ')');
        $('span[data-name="closed"]').html('(' + data.closed + ')');
        $('span[data-name="canceled"]').html('(' + data.canceled + ')');
        var all = data.unpaid + data.undeliver + data.delivered + data.confirmed + data.canceled;
        $('span[data-name="all"]').html('(' + all + ')');
    }
    requestUrl('/account/order/quantity', 'GET', '', numberCB);
}

$(function () {
    // 控制账户中心banner显示隐藏
    $('.J_acc_expand').on('click', function () {
        $('.apx-acc-aside').toggleClass('acc_expand');
    })
    //获得左侧菜单栏
    function getMenuList() {
        function menuCB(data) {
            var dataObj = data;
            //左侧菜单栏
            var url = function (data) {
                if (data == location.pathname) {
                    return 'active'
                } else {
                    return ''
                }
            }
            var name = function (data) {
                var index = data.lastIndexOf('/');
                return data.substring(index + 1);
            }
            juicer.register('name_build', name);
            juicer.register('url_build', url);
            var tpl_menu = $('#J_tpl_menu').html();
            var result = juicer(tpl_menu, dataObj);
            $('#J_menu_list').removeClass('loading').html(result);
            USER_LEVEL_COUNT++
            countLevel()
            if ($('#J_menu_list li').hasClass('active')) {
                $('#J_menu_list li[class*=active]').parents('.collapse').addClass('in').siblings('a').removeClass('collapsed');
            }
            getNumber(); //获取订单数量
        }

        requestUrl('/account/index/menu', 'GET', '', menuCB)
    }

    if ($('#J_menu_list').length > 0) getMenuList();
})

function countLevel() {
    if (USER_LEVEL_COUNT === 2 && USER_LEVEL === 4 || USER_TAG == 1) {
        var html = '<li><a href="/account/gpubs-picking-up">拼购提货</a></li>'
        $('#aside_panel_5 .list-unstyled').append(html)
    }
}

//获取账户信息
var USER_LEVEL = '', USER_LEVEL_COUNT = 0, USER_TAG = 0;
function getAccount() {
    function accountCB(data) {
        USER_TAG = data.tag
        USER_LEVEL = data.level
        USER_LEVEL_COUNT++
        countLevel()
        $('.J_acc_name').html(data.shop_name).attr('title', data.shop_name);
        $('.J_acc_district').html(data.business_area[1]+'/'+data.business_area[2]+'/'+data.business_area[3]+'/'+data.business_area[4]+'/'+data.business_area[5]);
        $('.J_acc_address').html(data.default_address);
        $(".J_acc_nickname").html(data.nick_name);
        if(data.mobile==0||data.mobile==''){
            $(".J_acc_mobile").html('');
        }else{
            $(".J_acc_mobile").html(data.mobile);
        }

        if (data.header_img != "") {
            $('.J_acc_head').attr('src', data.header_img);
        } else {
            //随机用户头像
            var random_head = Math.floor(Math.random() * 6 + 1);
            $('.J_acc_head').attr('src', '/images/head/head_' + random_head + '.jpg');
        }
        $('.J_acc_head').removeClass('hide');
    }
    requestUrl('/index/userinfo', 'GET', '', accountCB)
}
getAccount();

// 验证码 与 刷新
function getCaptchaImg() {
    $.ajax({
        url: "/captcha",
        method: "GET",
        data: {
            'refresh': Math.random() + ''
        }
    })
        .done(function (data) {
            // append image node
            $('.J_verify_captcha').empty()
                .css({
                    'padding': 0
                }).append('<img src="' + data.url + '">')
            // set style and event listener
            $('.J_verify_captcha img').css({
                'float': 'left',
                'background': '#000',
                'max-height': '34px',
                'padding-rigth': '4px'
            })
                .on('click', getCaptchaImg);
        })
        .fail(function () {
            setTimeout(function () {
                getCaptchaImg();
            }, 3000);
        })
}
if ($('.J_edit_msg').length > 0) getCaptchaImg();

//获取账户余额
function getBalance() {
    function balanceCB(data) {
        $('.J_acc_balance').html('¥' + data.rmb.toFixed(2));
    }

    requestUrl('/account/index/balance', 'GET', '', balanceCB)
}
getBalance()


//获取URL状态
function getUrlStatus() {
    var status = '';
    var url = window.location.pathname;
    if (url == '/account/order/unpaid') {
        status = 0
    }
    if (url == '/account/order/undeliver') {
        status = 1
    }
    if (url == '/account/order/delivered') {
        status = 2
    }
    if (url == '/account/order/confirmed') {
        status = 3
    }
    if (url == '/account/order/canceled') {
        status = 4
    }
    if (url == '/account/order/closed') {
        status = 5
    }
    return status
}

var statu = function (data) {
    if (data == 0) {
        return '待付款'
    }
    if (data == 1) {
        return '待发货'
    }
    if (data == 2) {
        return '待收货'
    }
    if (data == 3) {
        return '确认收货'
    }
    if (data == 4) {
        return '已取消'
    }
    if (data == 5) {
        return '已关闭'
    }
}
juicer.register('statu_build', statu);
var price = function (data) {
    return data.toFixed(2)
}
juicer.register('price_build', price);
//获取订单列表
var tpl_order = $('#J_tpl_order').html();
var compiled_tpl = juicer(tpl_order);
function getOrderList(page, size) {
    var status = getUrlStatus();
    var data = {
        status: status,
        current_page: page,
        page_size: size
    }

    function orderListCB(data) {
        var orders = data.orders;
        if (orders.length == 0) {
            if (page > 1) {
                getOrderList(page - 1, size);
            }
        }
        //订单
        for (var i = 0; i < orders.length; i++) {
            var total_price = 0;
            for (var j = 0; j < orders[i].items.length; j++) {
                total_price += orders[i].items[j].total_fee
            }

        }
        
        
        var table = compiled_tpl.render(data);
        $('#J_order_list').html(table);
        var top = $('#tab_all').offset().top;
        if ($(document).scrollTop() > 600) {
            $(document).scrollTop(top)
        }
        //支付方式
        var paymentData = [];
        $('.J_collapse_box').on('show.bs.collapse', function () {
            if (paymentData.length != 0) {
                paymentCB(paymentData)
                return
            }
            function paymentCB(data) {
                paymentData = data;
                var html = '';
                for (var i = 0; i < 2; i++) {
                    html += '<div class="radio">\
	                                <label>\
	                                    <input type="radio" name="payType" id="payType' + i + '" value="payment" data-id="' + data[i].id + '">\
	                                    ' + data[i].name + '\
	                                </label>\
	                            </div>'
                }
                html += '<div class="radio">\
                            <label>\
                                <input type="radio" name="payType" id="payType' + i + '" value="payment" data-id="' + data[4].id + '">\
                                ' + data[4].name + '\
                            </label>\
                        </div>'
                $('.J_payment_box').html(html);
            }

            requestUrl('/confirm-order/payment', 'GET', '', paymentCB);
        })

        //去付款事件
        $('.J_to_pay').off('click.toPay').on('click.toPay', function () {
            var no = [];
            no[0] = $(this).parents('.J_table_box').data('no');
            var payment = $(this).parents('.acc-pay-again').find('.J_payment_box input:checked').data('id');
            if (payment == undefined) {
                alert('请选择支付方式！')
                return
            }
            var _data = {
                orders_no: no,
                payment: payment
            }

            function goBugCB(data) {
                window.location.href = data.url
            }

            function errCB(data) {
                alert('请确保余额充足！')
            }

            requestUrl('/account/order/pay', 'POST', _data, goBugCB, errCB)
        })

        //生成分页
        var total = Math.ceil(data.total_count / size);
        $('#J_page_list').html(getPagination(page, total));
        $('#J_page_box').data('total', total);
        //点击换页
        $('#J_page_list li').on('click', function () {
            var val = $(this).data('page');
            if (val == undefined) {
                return false
            }
            getOrderList(val, size)
        })

        $('.J_off_order').off('click.delOrder').on('click.delOrder', function () {
            if (confirmBoxShow) return;
            confirmBoxShow = true;
            var yes = confirm('确认取消订单？');
            if (yes == false) {
                confirmBoxShow = false;
                return
            }
            var no = [];
            no[0] = $(this).parents('.J_table_box').data('no');
            var $this = $(this);
            var data = {
                orders_no: no
            }

            requestUrl('/account/order/cancel', 'POST', data, function(data) {
                getNumber();
                confirmBoxShow = false;
                var page = $('#J_page_box li[class*=active]').data('page') - 0;
                if ($('#J_page_box').length <= 0) {
                    getOrderList(1, 3)
                    getBalance();
                    return
                }
                getOrderList(page, size);
                getBalance();
            }, function(data) {
                confirmBoxShow = false;
                alert(data.data.errMsg);
            });
        })

        //输入换页
        $('#J_page_search input').on('keyup', function () {
            var number = $(this).val().replace(/\D/g, '') - 0;
            $(this).val(number);
            if ($(this).val().length < 1) {
                $(this).val('1');
                return false
            }
            if ($(this).val() < 1) {
                $(this).val('1');
                return false
            }
            if ($(this).val() > $('#J_page_box').data('max')) {
                $(this).val($('#J_page_box').data('max'))
                return false
            }
        })
        $('#J_page_search a').on('click', function () {
            var n = $('#J_page_search input').val();
            if (n > $('#J_page_box').data('max')) {
                alert('已超过最大分页数')
                return false;
            }
            getOrderList(n, size);
        })
        //申请售后
        $('.J_create_refund').on('click', function() {
            var no = $(this).data('no');
            var id = $(this).data('id');
            requestUrl('/account/refund/if-refund', 'GET', {order_code: no, item_id: id}, function(data) {
                window.location.href = '/account/refund/create?no=' + no + '&&id=' + id;
            })
        })
    }

    requestUrl('/account/order/list', 'GET', data, orderListCB);
}

