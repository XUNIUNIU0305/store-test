$(function () {
    //选择支付方式
    $('#mask_select_payment li').on('click', function (e) {
        var $this = $(this);
        var result = $this.data('name');
        $this.siblings().removeClass('actived');
        $this.addClass('actived');

        $this.siblings().find($(".mode-payment-right span")).removeClass("mode-payment-right-btn")
        $this.find($(".mode-payment-right span")).addClass("mode-payment-right-btn")

    });

    var num = Number(url('?num'));
    var payment;
    var confirms = {
        address: '',
        addressTpl: $('#J_tpl_address').html(),
        addressData: [],
        gpubs_type: '',
        gpubs_rule_type: '',
        min_quanlity_per_member_of_group: '',
        totalPrice:'',
        i:'',
        getPro: function (params) {
            requestUrl('/gpubs/detail/product-sku', 'GET', params, function (data) {
                confirms.gpubs_type = data.gpubs_type;
                $(".address-more").removeClass("hidden")

                if (data.gpubs_type == 1) {
                    $(".head-tit").html("自提点信息");

                    if (url("?group_id")) {
                        confirms.getzitiDefaultAddress()
                    } else {
                        confirms.getzitiAddressDefault()
                        confirms.getzitiAddress()
                        confirms.chooseAddress()
                    }

                }
                if (data.gpubs_type == 2) {
                    $(".head-tit").html("收货地址");
                    confirms.getsonghuoDefaultAddressList()
                    confirms.getsonghuoAddress()
                    confirms.chooseAddress()
                    confirms.gpubs_rule_type = data.gpubs_rule_type;
                    confirms.min_quanlity_per_member_of_group = data.min_quanlity_per_member_of_group;
                }

                $('.sec-pic img').attr('src', data.image)

                $('.sec-pic').attr('data-id', url("?id"))
                $('.sec-main .c-txt').text(data.title)
                $(".merchant-title").html(data.brand_name);
                $(".txt-gray").html(data.sku[0].selectedOption.name);
                $(".txt-money .fuhao").text("￥");
                $(".txt-money .price").text(data.price.toFixed(2));

                $(".policy-con").html(data.description);
                var _attr = '';
                $.each(data.sku, function (i, val) {
                    _attr += val.name + '：' + val.selectedOption.name + '; '
                })
                $('.txt-gray').text(_attr)

                $('#deliver-goods-text').val(num);
                // 商品数量的加减
                $("#deliver-goods-add").on("click", function () {
                    num += 1;
                    goodsNum(num);
                })
                $("#deliver-goods-reduce").on("click", function () {
                    num -= 1;
                    if ($('#deliver-goods-text').val() <= 1) {
                        num = 1;
                    }
                    goodsNum(num);

                })
                $("#deliver-goods-text").keyup(function () {
                    $(this).val($(this).val().replace(/[^0-9-]+/, ''));
                    num = $(this).val() == '' ? '' : Number($('#deliver-goods-text').val())
                    if ($(this).val() != '' && $(this).val() == 0) {
                        num = 1;
                    }
                    goodsNum(num);

                })


                goodsNum(num);

                function goodsNum(num) {
                    $('#deliver-goods-text').val(num);
                    if ($('#deliver-goods-text').val() == 1) {
                        $('#deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_failure.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                    } else {
                        $('#deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                    }
                    $(".cost-number span").text(num);
                    var totalPrice = Number($(".txt-money .price").html() * num).toFixed(2);
                    confirm.totalPrice = Number($(".txt-money .price").html() * num).toFixed(2);
                    $("#money-total").html("￥" + totalPrice);
                    $("#confirm-payment .price").html("￥" + totalPrice);
                    $("#good-price").html("￥ " + totalPrice);
                }


            })
        },
        getBalance: function () {
            requestUrl('/member/index/get-user-balance', 'GET', '', function (data) {
                $('.mode-payment-else-yue span').html("￥" + data.rmb.toFixed(2))
            })
        },
        getzitiAddress: function () {
            requestUrl('/member/spot-address/gpubs-list', 'GET', '', function (data) {
                confirms.addressData = data;
                var tpl_address = confirms.addressTpl;
                var result = juicer(tpl_address, data);
                $(".J_address_list").html(result);
                $(".J_add_address .choose_address").html("选择自提点")
                $(".zitiaddress").removeClass('hidden');
                $(".lianximan").removeClass('hidden');
                $(".J_add_address .btn").html("新增自提点")
                $(".J_add_address .btn").attr("href", "/member/spot-address/gpubs-add")

            })
        },
        getzitiAddressDefault: function () {
            requestUrl('/member/spot-address/get-default-address', 'GET', '', function (data) {
                if (data == '') {
                    $("#choose-address").removeClass("hidden");
                    $(".choose-address .text").html("请选择自提点")
                    $("#address-mess").addClass("hidden");
                } else {
                    $("#address-mess").removeClass('hidden')
                    $("#choose-address").addClass('hidden')
                    $("#address-mess .pick-up-site").removeClass("hidden");
                    confirms.addressData = data;
                    confirms.address = data.id;
                    $(".pick-up-site").html(data.spot_name)
                    $('#head-addr').html(data.province.name + " " + data.city.name + " " + data.district.name + data.detailed_address);
                    $('.user-number').html(data.mobile);
                    $('.user-name').html(data.consignee);
                    $(".head-cont").data("id", data.id);

                    $(".X_spot_name").html(data.spot_name)
                    $('.X_address').html(data.province.name + " " + data.city.name + " " + data.district.name + data.detailed_address);
                    $('.X_mobile').html(data.mobile);
                    $('.X_contact').html(data.consignee);
                }

            })
        },
        getzitiDefaultAddress: function () {
            var data = {
                group_id: url('?group_id'),
            }
            requestUrl('/gpubs/api/group', 'GET', data, function (data) {
                $("#choose-address").addClass("hidden");
                $("#address-mess").removeClass("hidden");
                $(".head-user-more").addClass("hidden");
                $('#head-addr').html(data.full_address);
                $('.user-number').html(data.mobile);
                $('.user-name').html(data.consignee);
                $(".pick-up-site").html(data.spot_name);
                
                $('.X_address').html(data.full_address);
                $('.X_mobile').html(data.mobile);
                $('.X_contact').html(data.consignee);
                $(".X_spot_name").html(data.spot_name);
                confirms.address = 1111111;

            })
        },
        getsonghuoAddress: function () {
            requestUrl('/member/address/get-address-list', 'GET', '', function (data) {

                confirms.addressData = data;
                var tpl_address = confirms.addressTpl;
                var result = juicer(tpl_address, data);
                $(".J_address_list").html(result);
                $(".ziti-detail").addClass("hidden")
                $(".songhuoaddress").removeClass("hidden");
                $(".shouhuoman").removeClass('hidden');
                $(".J_add_address .choose_address").html("选择地址")
                $(".J_add_address .btn").html("新增地址")
                $(".J_add_address .btn").attr("href", "/member/address/add")
            })
        },
        //获取默认收货地址
        getsonghuoDefaultAddressList: function () {

            function addressCB(data) {
                if (data == '') {
                    $("#choose-address").removeClass("hidden");
                    $(".choose-address .text").html("请选择收货地址")
                    $("#address-mess").addClass("hidden");
                    $("#address-mess .pick-up-site").addClass("hidden");
                } else {
                    $("#choose-address").addClass("hidden");
                    $("#address-mess").removeClass("hidden");
                    $('#head-addr').html(data.province.name + data.city.name + data.district.name + data.detail);
                    $('.user-number').html(data.mobile);
                    $('.user-name').html(data.consignee);
                    $(".head-cont").data("id", data.id);
                    confirms.address = data.id;
                }

            }
            requestUrl('/member/address/get-default-address', 'GET', '', addressCB)
        },
        submit: function (params) {
            requestUrl('/gpubs/confirm/order', 'POST', params, function (data) {
                location.href = data.url;
            }, function (data) {
                alert(data.data.errMsg)
                $("#affirm").css('pointer-events', 'auto')
                $("#confirm-payment").css('pointer-events', 'auto')
                $("#confirm-payment").text('提交订单');
            })
        },

        getJoinInfo: function (params) {
            requestUrl('/gpubs/share/info', 'GET', params, function (data) {
                $('.order-shop-acc-info li').eq(0).find('span').text(data.full_address)
                $('.order-shop-acc-info li').eq(1).find('span').text(data.consignee)
            })
        },
        chooseAddress: function () {
            // 选择地址
            $('.address-main').on('click', function (data) {
                $('#J-address-selected').removeClass('hidden');
                $("#confirm-payment").addClass('hidden');
                $(".container").addClass('hidden');
            })
            $('.J_address_list').on('click', '.J_address_item', function () {
                var id = $(this).data('id');
                var detailStr = $(this).find('div').not('.hidden').find('.J_address').html();
                var spot_name = $(this).find('.J_spot_name').text();
                var mobile = $(this).find('.J_mobile').text();
                var consignee = $(this).find('.J_contact').text();
                confirms.address = id;

                $("#choose-address").addClass("hidden");
                $("#address-mess").removeClass("hidden");
                $(".pick-up-site").text(spot_name);
                $('#head-addr').text(detailStr);
                $('.user-number').text(mobile);
                $('.user-name').text(consignee);
                
                $(".X_spot_name").text(spot_name);
                $('.X_address').text(detailStr);
                $('.X_contact').text(consignee);
                $('.X_mobile').text(mobile);
                $('#J-address-selected').addClass('hidden');
                $('#confirm-payment').removeClass('hidden');
                $('.container').removeClass('hidden');
            })
        },
        init: function () {
            payment = $('#mask_select_payment').find('li.actived').data("pay")
            this.getBalance()
            this.getPro({
                product_sku: url('?skuid')
            })
            $(".sec-pic").click(function () {
                var pro_id = $(this).data("id");
                window.location.href = "/goods/detail?id=" + pro_id;
            })
            // 提交订单
            $('#confirm-payment').off("click").on('click', function () {
                if (num == 0 || num == '') {
                    alert("商品数量不可为0或为空")
                    return
                }
                if (confirms.address == "") {
                    alert("请选择地址")
                    return
                }
                // 
                if (confirms.gpubs_type == 1) {
                    payment = $('#mask_select_payment li[class*="actived"]').data('pay')
                    var sku_id = url('?skuid')
                    var remark = $('#J_remark_input').val()
                    
                    if (url('?group_id')) {
                        $(".cantuantitle").removeClass("hidden");
                    } else {
                        $(".kaituantitle").removeClass("hidden");
                    }
                    $(".address-confirm-wrap").removeClass("hidden");
                    $("#affirm").off("click").click(function(){
                        $('#confirm-payment').text('订单提交中...');
                        $(this).css('pointer-events', 'none')
                        if (url('?group_id')) {
                            confirms.submit({
                                group_id: url('?group_id'),
                                product_sku_id: sku_id,
                                address_id: confirms.address,
                                quantity: num,
                                comment: remark,
                                payment_method: payment
                            })
                        } else {
                            confirms.submit({
                                product_sku_id: sku_id,
                                address_id: confirms.address,
                                quantity: num,
                                comment: remark,
                                payment_method: payment
                            })
                        }
                        
                    })
                    
                    
                }
                if (confirms.gpubs_type == 2) {
                    
                    if (confirms.gpubs_rule_type == 3) {
                        if (confirms.min_quanlity_per_member_of_group > num) {
                            alert("此商品需" + confirms.min_quanlity_per_member_of_group + "件起拼");
                            return;
                        }
                    }
                    $(this).css('pointer-events', 'none')
                    $(this).text('订单提交中...');
                    payment = $('#mask_select_payment li[class*="actived"]').data('pay')
                    var sku_id = url('?skuid')
                    var remark = $('#J_remark_input').val()
                    if (url('?group_id')) {
                        confirms.submit({
                            group_id: url('?group_id'),
                            product_sku_id: sku_id,
                            address_id: confirms.address,
                            quantity: num,
                            comment: remark,
                            payment_method: payment
                        })
                    } else {
                        confirms.submit({
                            product_sku_id: sku_id,
                            address_id: confirms.address,
                            quantity: num,
                            comment: remark,
                            payment_method: payment
                        })
                    }
                    
                }

            })
            
        }
    }
    confirms.init()

    $("#close-tishi").click(function(){
        $(".address-confirm-wrap").addClass("hidden")
        $("#affirm").unbind();
    })
    $("#cancel").click(function(){
        $(".address-confirm-wrap").addClass("hidden");
        $("#affirm").unbind();
    })
})