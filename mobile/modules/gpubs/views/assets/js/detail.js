$(function() {  
    $('.J_pro_href').attr('href', '/goods/detail?id=' + url('?id'))
    var detail = {
        attrTpl: $('#J_tpl_buy_attribute').html(),
        proSku: {},
        sku: {},
        selectSku: '',
        getProInfo: function(params) {
            requestUrl('/goods/get-goods-info', 'GET', params, function(data) {
                $('#J_pro_main_img').attr('src', data.big_images[0])
                $('.J_buy_img').attr('src', data.big_images[0])
                $('.J_pro_title').text(data.title)
                if (data.price.min === data.price.max) {
                    $('#J_pro_price_box').text('￥' + data.price.min)
                } else {
                    $('#J_pro_price_box').text('￥' + data.price.min + '-' + data.price.max)
                }
                // 渲染SKU
                detail.proSku = data.SKU.sku
                var attr = [];
                $.each(data.SKU.attributes, function(i, v) {
                    var option = [];
                    var name = '';
                    $.each(v, function(index, value) {
                        name = index;
                        $.each(value, function(inde, val) {
                            option.push({
                                id: inde,
                                name: val,
                            })
                        })
                    })
                    attr.push({
                        id: i,
                        name: name,
                        options: option
                    })
                })
                var tpl_attr = detail.attrTpl;
                var sellAttr = juicer(tpl_attr, attr);
                $(".goods_attr_items").html(sellAttr);
            })
        },
        getGroupInfo: function(params) {
            requestUrl('/gpubs/api/product', 'GET', params, function(data) {
                detail.sku = data.sku
                if (data.min_price === data.max_price) {
                    $('#J_group_price_box').text('￥' + data.min_price)
                    $('.J_product_price').text('￥' + data.min_price)
                } else {
                    $('#J_group_price_box').text('￥' + data.min_price + '-' + data.max_price)
                    $('.J_product_price').text('￥' + data.min_price + '-' + data.max_price)
                }
                if (data.expire_time > 0) {
                    $('#J_start_num').text(data.min_quantity_per_group)
                }
            })
        },
        getJoinInfo: function(params) {
            // 参团信息
            requestUrl('/gpubs/share/info', 'GET', params, function(data) {
                if (data.status !== 1) {
                    var test = data.status === 2 ? '拼团已满！': '拼团过期！';
                    alert(test)
                    setTimeout(function() {
                        location.href = '/'
                    }, 1000);
                }
                var tpl = $('#J_tpl_user_list').html()
                $('#J_group_num').text(data.group_number)
                $('#J_start_num').text(data.target_quantity)
                $('.user-name').text(data.owner_account)
                $('#J_pro_img').text(data.product.image)
                $('#J_pro_title').text(data.product.title)
                var num = data.target_quantity - data.present_quantity
                if (num < 0) {
                    num = 0
                }
                $('#J_balance_num').text(num)
                $('#J_consignee').text(data.consignee + ' ' + data.mobile)
                $('#J_address').text(data.full_address)
                if (data.product.original_price.min === data.product.original_price.max) {
                    $('#J_group_price').text(data.product.original_price.min)
                } else {
                    $('#J_group_price').text('￥' + data.product.original_price.min + '-' + data.product.original_price.max)
                }
                $('.user-list').html(juicer(tpl, data.member))
                if (data.left_unixtime > 0) {
                    jdy.seckill.timerFun(data.left_unixtime, function(data) {
                        var hour = (data.hour - 0) + (data.day * 24)
                        if (hour < 10) {
                            hour = '0' + hour.toString()
                        }
                        $('#retroclockbox span').eq(0).text(hour)
                        $('#retroclockbox span').eq(1).text(data.minute)
                        $('#retroclockbox span').eq(2).text(data.second)
                    })
                }
            })  
        },
        init: function() {
            this.getProInfo({
                id: url('?id')
            })
            this.getGroupInfo({
                product_id: url('?id')
            })

            if (url('?group_id')) {
                this.getJoinInfo({
                    group_id: url('?group_id')
                })
                $('.activity-info').removeClass('hidden')
            } else {
                $('.start-group-container').removeClass('hidden')
            }
            // 绑定开团弹窗
            $('.J_start_buy_btn').on('click', function() {
                $('#maskLayersh').show()
            })
            $('.J_buy_cancel').on('click', function() {
                $('#maskLayersh').hide()
            })

            // 绑定选择属性
            $('.goods_attr_items').on('click', 'li[data-id]',function() {
                $(this).addClass('li-active').siblings().removeClass('li-active')
                var selectedObjs = $('li[data-id].li-active');
                var groupSku = [],selectOptions = '';
                selectedObjs.each(function () {
                    groupSku.push($(this).parents('.J_attr_box').data('id') + ':' + $(this).data('id'))
                    selectOptions += $(this).html() + '/';
                });
                selectOptions=selectOptions.substr(0,selectOptions.length-1);
                $(".J_buy_attribute").html(selectOptions);
                if (groupSku.length > 0) {
                    var _string = '';
                    $.each(groupSku, function(i, val) {
                        _string += val + ';'
                    })
                    var groupId = detail.proSku[_string.slice(0, -1)].id;
                    var groupPrice = detail.sku[groupId].price;
                    if (groupPrice) {
                        $('.J_product_price').text(groupPrice.toFixed(2))
                        $('.J_product_inventory').text(detail.sku[groupId].stock)
                        detail.selectSku = groupId
                    }
                }
            })
            //减少数量
            $("#subtract").off("click").on("click",function(){
                var number=parseInt($("#number").val() - 0);
                if(--number<=0){return false;}
                $("#number").val(number);

            });
            //新增数量
            $("#add").off("click").on("click",function(){
                var number=parseInt($("#number").val() - 0);
                if(++number>$("#number").data("max"))return false;
                $("#number").val(number);
            });

            // 确定购买
            $('.J_buy_submit').on('click', function() {
                if (detail.selectSku === '') {
                    alert('请选择商品属性！')
                    return
                }
                var num = $('#number').val()
                if (num > $('.J_product_inventory').text() - 0) {
                    alert('已超出库存数量！')
                    return
                }
                if (url('?group_id')) {
                    location.href = '/gpubs/confirm?id=' + url('?id') + '&skuid=' + detail.selectSku + '&num=' + num + '&group_id=' + url('?group_id')
                } else {
                    location.href = '/gpubs/confirm?id=' + url('?id') + '&skuid=' + detail.selectSku + '&num=' + num
                }
            })
        }
    }
    detail.init()


    wechatShare()

    // 微信分享
    function wechatShare() {
        var img = 'http://m.9daye.com.cn/images/event20180909/share.jpg';
        var title = '正品机油拼团购，打到骨折一起浪！';
        var desc = '我在九大爷平台和上万门店拼团买SK机油！工厂发货，正品保障！点击参团！';
        var _url = window.location.href;
        var share_url = 'http://m.9daye.com.cn/gpubs/detail?id=' + url('?id') + '&group_id=' + url('?group_id')
        if (location.host.search('test') != -1) {
            img = 'http://test.m.9daye.com.cn/images/event20180909/share.jpg';
            share_url = 'http://test.m.9daye.com.cn/gpubs/detail?id=' + url('?id') + '&group_id=' + url('?group_id')
        }
        $.ajax({
            url: 'http://106.14.255.215/api/9daye',
            data: {
                m: 'm_js_sdk',
                url: _url
            },
            success: function(data) {
                var data = data.data
                wx.config({
                    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                    appId: data.appId, // 必填，公众号的唯一标识
                    timestamp: data.timestamp, // 必填，生成签名的时间戳
                    nonceStr: data.nonceStr, // 必填，生成签名的随机串
                    signature: data.signature,// 必填，签名，见附录1
                    jsApiList: ['checkJsApi','getLocation','chooseImage','uploadImage', 'onMenuShareWeibo', 'onMenuShareTimeline', 'onMenuShareQZone',  'onMenuShareAppMessage', 'onMenuShareQQ']
                });
                wx.ready(function() {
                    //分享到朋友圈
                    wx.onMenuShareTimeline({
                        title: title, // 分享标题
                        link: share_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: img, // 分享图标
                        success: function () {
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    //分享给朋友
                    wx.onMenuShareAppMessage({
                        title: title, // 分享标题
                        desc: desc, // 分享描述
                        link: share_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: img, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    //分享到QQ
                    wx.onMenuShareQQ({
                        title: title, // 分享标题
                        desc: desc, // 分享描述
                        link: share_url, // 分享链接
                        imgUrl: img, // 分享图标
                        success: function () {
                           // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                           // 用户取消分享后执行的回调函数
                        }
                    });
                    //分享到QQ微博
                    wx.onMenuShareWeibo({
                        title: title, // 分享标题
                        desc: desc, // 分享描述
                        link: share_url, // 分享链接
                        imgUrl: img, // 分享图标
                        success: function () {
                           // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    //分享到QQ空间
                    wx.onMenuShareQZone({
                        title: title, // 分享标题
                        desc: desc, // 分享描述
                        link: share_url, // 分享链接
                        imgUrl: img, // 分享图标
                        success: function () {
                           // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                })
            },
            error: function(data) {
                console.log(data)
            }
        })
    }
})