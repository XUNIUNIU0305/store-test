$(function () {
    // 微信分享
    function wechatShare(detailid) {

        var detailparms = { product_id: detailid };
        requestUrl('/gpubs/share/wx-info', 'GET', detailparms, function (data) {
            var img = data.filename;
            var title = data.share_title;
            var desc = data.share_subtitle;
            // var img = 'http://m.9daye.com.cn/images/event20180821/wechat.jpg';
            // var title = '九大爷门店优选拼购大集结，速来!';
            // var desc = '8月众多优惠拼团，邀您参加';
            // var img = img;
            // var title = title;
            // var desc = desc;
            var _url = window.location.href;
            // var share_url = 'http://m.9daye.com.cn/gpubs/share?id=' + url('?p_id') + '&group_id=' + url('?id');
            var share_url = 'http://m.9daye.com.cn/gpubs/share?id=' + getSearchAtrr("id") + '&p_id=' + getSearchAtrr("p_id");
            if (location.host.search('test') != -1) {
                // img = 'http://test.m.9daye.com.cn/images/event20180821/wechat.jpg';
                share_url = 'http://test.m.9daye.com.cn/gpubs/share?id=' + getSearchAtrr("id") + '&p_id=' + getSearchAtrr("p_id");
            }
            $.ajax({
                url: 'http://106.14.255.215/api/9daye',
                data: {
                    m: 'm_js_sdk',
                    url: _url
                },
                success: function (data) {
                    var data = data.data;
                    wx.config({
                        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                        appId: data.appId, // 必填，公众号的唯一标识
                        timestamp: data.timestamp, // 必填，生成签名的时间戳
                        nonceStr: data.nonceStr, // 必填，生成签名的随机串
                        signature: data.signature,// 必填，签名，见附录1
                        jsApiList: ['checkJsApi', 'onMenuShareTimeline', 'chooseImage', 'updateTimelineShareData', 'onMenuShareWeibo', 'onMenuShareQZone', 'onMenuShareAppMessage', 'updateTimelineShareData']
                    });
                    $('.G_group-share-friends').on('click', function () {
                        wx.checkJsApi({
                            jsApiList: ['chooseImage', 'onMenuShareTimeline'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
                            success: function (res) {
                                // 以键值对的形式返回，可用的api值true，不可用为false
                                // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                            }
                        });
                    })
                    $('.G_group-share-friend').on('click', function () {
                        wx.checkJsApi({
                            jsApiList: ['chooseImage', 'onMenuShareAppMessage'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
                            success: function (res) {
                                // 以键值对的形式返回，可用的api值true，不可用为false
                                // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                            }
                        });
                    })
                    wx.ready(function () {
                        wx.checkJsApi({
                            jsApiList: ['chooseImage', 'updateAppMessageShareData'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
                            success: function (res) {
                                // 以键值对的形式返回，可用的api值true，不可用为false
                                // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                            }
                        });
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
                    }),
                        wx.error(function (res) {
                            // console.log(res)
                        })
                },
                error: function (data) {
                    // console.log(data)
                }
            })
        })
    }
    wechatShare(getSearchAtrr("p_id"));

    //两行显示省略号
    function fontsize(biaoqian) {
        var Size = parseInt($('.' + biaoqian).css('font-size'));
        var Width = parseInt($('.' + biaoqian).css('width'));
        var num = Width / Size * 2;
        var num1 = Width / Size;// 单行字数
        var num2 = Width / Size * 2 - 6;  // 显示字数
        $('.' + biaoqian).each(function () {
            if ($(this).text().length > num1) {
                $(this).html($(this).text().replace(/\s+/g, "").substr(0, num2) + "...")
            }
        })
    }
    fontsize('description');//传入字符串标签

    // 价格判断
    function fullNum(Num, Integer, many) {
        var price = Num + '';
        if (price.indexOf('.') != -1) {
            // $('.'+Integer).text(price.substring(0,price.indexOf('.')));
            // $('.'+many).text(price.substring(price.indexOf('.'),price.length));
            $('.' + Integer).text(price.split('.')[0]);
            $('.' + many).text('.' + price.split('.')[1]);
        } else {
            $('.' + Integer).text(Num);
            $('.' + many).text('. 00');
        }
    }
    function addprice(Num, Integer) {
        var price = Num + '';
        if (price.indexOf('.') != -1) {
            $('.' + Integer).text(Num);
        } else {
            $('.' + Integer).text(Num + '.00');
        }
    }
    // fullNum(1.25,'group-price-meny1','group-price-menyZero1');
    // fullNum(Num,Integer,many) //Num传数字，Integer,many传字符串标签\
    // url
    function getSearchAtrr(attr) {
        var attrArr = window.location.search.substr(1).split('&');
        var newArr = attrArr.map((item) => item.split('='));
        var i, len = newArr.length;
        for (i = 0; i < len; i++) {
            if (newArr[i][0] == attr) {
                return newArr[i][1];
            }
        }
    }
    // 倒计时
    function groupShareActivityTime(timer) {
        var time = timer;
        if (time > 0) {
            var day = parseInt(time / 60 / 60 / 24);
            var hour = parseInt(time / 60 / 60 % 24);
            var minute = parseInt(time / 60 % 60);
            var seconds = parseInt(time % 60);
            if (hour < 10) {
                hour = '0' + hour;
            }
            if (minute < 10) {
                minute = '0' + minute;
            }
            if (seconds < 10) {
                seconds = '0' + seconds;
            }
            day > 0 ? $('.balance-pro-d').text(day + '天') : $('.balance-pro-d').text();
            $('.balance-pro-h').text(hour);
            $('.balance-pro-m').text(minute);
            $('.balance-pro-s').text(seconds);
        }
    }

    function groupShareActivityTimes(timer, timer1) {
        var time = timer;
        setInterval(function () {
            if (time > 0) {
                var day = parseInt(time / 60 / 60 / 24);
                var hour = parseInt(time / 60 / 60 % 24);
                var minute = parseInt(time / 60 % 60);
                var seconds = parseInt(time % 60);
                if (hour < 10) {
                    hour = '0' + hour;
                }
                if (minute < 10) {
                    minute = '0' + minute;
                }
                if (seconds < 10) {
                    seconds = '0' + seconds;
                }
                day > 0 ? $('.balance-pro-d').text(day + '天') : $('.balance-pro-d').text();
                $('.balance-pro-h').text(hour);
                $('.balance-pro-m').text(minute);
                $('.balance-pro-s').text(seconds);
                time--;
            } else if (time == 0) {
                window.location.reload();
            }
        }, 1000)
    }

    //  获取团员拼购信息
    function group_getProInfo(params, parameter) {
        var postdata = { group_id: params };
        if (parameter) {
            postdata.ticket_id = parameter;
        }
        requestUrl('/gpubs/share/detail', 'GET', postdata, function (data) {
            // 获取头部信息
            $('.info-header .info-header-pic').css({ "background": "url(" + data.owner_account.header_img + ") no-repeat", "background-size": "100% 100%" });
            $('#G_group-share-user-name').text(data.owner_account.account);
            $('#G_group-share-pro-num').text(data.group_number);
            if (data.gpubs_type == 1) {
                $('#G_group-share-pro-way').addClass('pro-way1');
                $('#G_group-share-activity-address').removeClass('hidden');
                $('.border-address').removeClass('hidden');
                $('#G_group-share-activity-address .activity-address-point').text(data.spot_name);
                $('#G_group-share-activity-address .activity-address-name').text(data.consignee);
                $('#G_group-share-activity-address .activity-address-tel').text(data.mobile);
                $('#G_group-share-activity-address .activity-detial-address').text(data.full_address);
            } else if (data.gpubs_type == 2) {
                $('#G_group-share-pro-way').addClass('pro-way');
            }
            if (data.gpubs_rule_type == 1) {
                $('.start-num .pro-start-num').text(data.target_member + '人');
                $('#G_group-share-group-price').text(data.target_member + '人');
                if (data.target_member - data.present_member <= 0) {
                    $('#G_group-share-balance-pro').text('0人');
                } else if (data.target_member - data.present_member > 0) {
                    $('#G_group-share-balance-pro').text(data.target_member - data.present_member + '人');
                }
            } else if (data.gpubs_rule_type == 2) {
                $('.start-num .pro-start-num').text(data.target_quantity + '件');
                $('#G_group-share-group-price').text(data.target_quantity + '件');
                if (data.target_quantity - data.present_quantity <= 0) {
                    $('#G_group-share-balance-pro').text('0件');
                } else if (data.target_quantity - data.present_quantity > 0) {
                    $('#G_group-share-balance-pro').text(data.target_quantity - data.present_quantity + '件');
                }
            } else if (data.gpubs_rule_type == 3) {
                $('.start-num .pro-start-num').text(data.target_member + '人' + data.min_quanlity_per_member_of_group + '件');
                $('#G_group-share-group-price').text(data.target_member + '人' + data.min_quanlity_per_member_of_group + '件');
                if (data.target_member - data.present_member <= 0) {
                    $('#G_group-share-balance-pro').text('0人');
                } else if (data.target_member - data.present_member > 0) {
                    $('#G_group-share-balance-pro').text(data.target_member - data.present_member + '人');
                }
            }
            // 跳转商品详情
            $('.group-order-item').on("click", function () { location.href = '/goods/detail?id=' + data.product.id })
            $('#G_group-share-handle-box2').on('click', function () { location.href = '/' })
            $('#G_group-share-handle-box3').on('click', function () { location.href = '/goods/detail?id=' + data.product.id })
            $('#G_group-share-handle-box4').on('click', function () { location.href = '/goods/detail?id=' + data.product.id })
            // 跳转拼购规则详情
            $('.activity-rule .activity-rule-info').on('click', function () { location.href = '/goods/activity-rules?type=' + data.gpubs_type })
            // 获取商品信息
            $('.group-order-item .aside img').attr('src', data.product.image);
            $('#G_group-share-main-description').text(data.product.title);
            if (data.product.activity_price.min == data.product.activity_price.max) {
                fullNum(data.product.activity_price.min, 'group-price-meny1', 'group-price-menyZero1');
            } else if (data.product.activity_price.min != data.product.activity_price.max) {
                fullNum(data.product.activity_price.min, 'group-price-meny1', 'group-price-menyZero1');
                fullNum(data.product.activity_price.max, 'group-price-meny2', 'group-price-menyZero2');
                $('.group-price .group-price-meny3').text('-');
            }
            if (data.product.original_price.min == data.product.original_price.max) {
                addprice(data.product.original_price.min, 'G_group-share-main-param1')
            } else if (data.product.original_price.min != data.product.original_price.max) {
                addprice(data.product.original_price.min, 'G_group-share-main-param1');
                addprice(data.product.original_price.max, 'G_group-share-main-param3');
                $('.G_group-share-main-param2').text('-');
            }
            $('#G_group-share-pro-offer').text(data.product.description);
            // 团状态
            if (data.is_participate != 2) {
                // 先判断是否参团，再判断是否在拼购时间内，最后判断团状态
                if (data.is_participate == 0) {//未参团
                    if (data.left_unixtime < 0) {// 拼购超时
                        $('.activity-info .balance-pro-Invalid').removeClass('hidden').siblings().addClass('hidden');
                        $('#G_group-share-handle-box4').removeClass('hidden').siblings().addClass('hidden');
                    }else if(data.left_unixtime>0){
                        if(data.status == 1){// 拼购中
                            if(data.product.stockCount<=0){
                                $('.activity-info .balance-pro-Invalid').removeClass('hidden').siblings().addClass('hidden');
                                $('#G_group-share-handle-box2').removeClass('hidden').siblings().addClass('hidden');
                                alert("该商品库存不足,请看看其他的宝贝吧")
                            }else if(data.product.stockCount>0){
                                groupShareActivityTime(data.left_unixtime);
                                groupShareActivityTimes(data.left_unixtime, data.left_unixtime);
                                $('.activity-info .balance-pro').removeClass('hidden').siblings().addClass('hidden');
                                var pathname = window.location.pathname;
                                if (pathname == '/gpubs/share') {
                                    $('#G_group-share-handle-box1').removeClass('hidden').siblings().addClass('hidden');//参团
                                } else if (pathname == '/gpubs/share/inviting-friends') {
                                    $('#G_group-share-handle-box').removeClass('hidden').siblings().addClass('hidden');//邀请
                                    // 邀请好友的显示隐藏
                                    $('#G_group-share-handle-box').on('click', function () {
                                        $('.G_group-share-activity-invite').removeClass('hidden')
                                    })
                                    $('.G_group-share-activity-invite').on('click', function () {
                                        $('.G_group-share-activity-invite').addClass('hidden')
                                    })
                                    setInterval(function () { $('.G_group-share-activity-invite').addClass('hidden') }, 5000)
                                }
                            }
                        } else if (data.status == 2 || data.status == 4 || data.status == 3) {
                            $('.activity-info .balance-pro-Invalid').removeClass('hidden').siblings().addClass('hidden');
                            $('#G_group-share-handle-box4').removeClass('hidden').siblings().addClass('hidden');
                        }
                    }
                }else if(data.is_participate==1){//已参团
                    if(data.status == 1){// 拼购中
                        if(data.product.stockCount<=0){
                            $('.activity-info .balance-pro-Invalid').removeClass('hidden').siblings().addClass('hidden');
                            $('#G_group-share-handle-box2').removeClass('hidden').siblings().addClass('hidden');
                            alert("该库存不足,请看看其他的吧！")
                        }else if(data.left_unixtime>0){
                            groupShareActivityTime(data.left_unixtime);
                            groupShareActivityTimes(data.left_unixtime, data.left_unixtime);
                            $('.activity-info .balance-pro').removeClass('hidden').siblings().addClass('hidden');
                            var pathname = window.location.pathname;
                            if (pathname == '/gpubs/share') {
                                $('#G_group-share-handle-box1').removeClass('hidden').siblings().addClass('hidden');//参团
                            } else if (pathname == '/gpubs/share/inviting-friends') {
                                $('#G_group-share-handle-box').removeClass('hidden').siblings().addClass('hidden');//邀请
                                // 邀请好友的显示隐藏
                                $('#G_group-share-handle-box').on('click', function () {
                                    wechatShare(getSearchAtrr("p_id"));
                                    $('.G_group-share-activity-invite').removeClass('hidden')
                                })
                                $('.G_group-share-activity-invite').on('click', function () {
                                    $('.G_group-share-activity-invite').addClass('hidden')
                                })
                                setInterval(function () { $('.G_group-share-activity-invite').addClass('hidden') }, 5000)
                            }
                        }
                    } else if (data.status == 2 || data.status == 4) {// 拼购成功
                        $('.activity-info .balance-pro-success').removeClass('hidden').siblings().addClass('hidden');
                        $('#G_group-share-handle-box2').removeClass('hidden').siblings().addClass('hidden');
                    } else if (data.status == 3) {// 拼购失败
                        $('.activity-info .balance-pro-failure').removeClass('hidden').siblings().addClass('hidden');
                        $('#G_group-share-handle-box3').removeClass('hidden').siblings().addClass('hidden');
                    }
                }
            } else if (data.is_participate == 2) {//用户参团失败（支付出现异常）
                $('#G_group-share-handle-box2').removeClass('hidden').siblings().addClass('hidden');
                $('.activity-info .balance-pro-abnormal').removeClass('hidden').siblings().addClass('hidden');
            }
            // 规格详情的显示隐藏
            $("#G_group-share-handle-box1").on("click", function () {
                $("#specifications-detail-wrap").removeClass('hidden');
            })
            $("#specifications-detail-wrap-close").on("click", function () {
                $(".specifications-detail-wrap").addClass('hidden');
            })
            // 跳转团员信息
            if(data.member.length<=0){
                $(".G_group-share-user-head-img0").removeClass('G_group-share-user-portrait');
                $(".G_group-share-user-head-img0").css({ "background": "url(" + data.owner_account.header_img + ") no-repeat", "background-size": "100% 100%" });
                $(".user-list .user-head").addClass('user-list-pics');
            }else {
                $('.participates .user-list').on("click", function () {
                    location.href = '/gpubs/share/member-info?id=' + url('?id');
                })
                // 拼团人员信息 头像
                if (data.member.length > 5) {
                    for (var i = 0; i < 4; i++) {
                        $(".G_group-share-user-head-img" + i).removeClass('G_group-share-user-portrait');
                        $(".G_group-share-user-head-img" + i).css({ "background": "url(" + data.member[i].header_img + ") no-repeat", "background-size": "100% 100%" });
                        $(".G_group-share-user-head-img4").css({ "background": "url('/images/8-13/dy.png') no-repeat", "background-size": "100% 100%" });
                        $(".G_group-share-user-head-img" + i).addClass('user-list-pics')
                    }
                } else {
                    for (var i = 0; i < data.member.length; i++) {
                        $(".G_group-share-user-head-img" + i).removeClass('G_group-share-user-portrait');
                        $(".G_group-share-user-head-img" + i).css({ "background": "url(" + data.member[i].header_img + ") no-repeat", "background-size": "100% 100%" });
                        $(".G_group-share-user-head-img" + i).addClass('user-list-pics')
                    }
                }
            }
        })
    }
    // 调用group_getProInfo
    if (window.location.search.indexOf('ticket_id') != -1) {
        var ticket_v = getSearchAtrr('ticket_id');
        group_getProInfo(url('?id'), ticket_v);
    } else {
        group_getProInfo(url('?id'));
    }
    // 热门推荐
    function hotPro(pageSize, currentPage) {
        $.ajax({
            type: 'get',
            url: '/gpubs/recommend/hot-list',
            data: {
                page_size_num: pageSize,
                cur_page_num: currentPage
            },
            success: function (data) {
                if(data.data==false){
                    $(".hot-pro").addClass("hidden");
                }else{
                    var groupHotProList = document.getElementById('groupHotProList').innerHTML;//获取模板
                    var hotProList = juicer(groupHotProList, data);
                    $("#group-hot-pro-container").html(hotProList);
                    console.log(data.data[0].min_price.toString().indexOf('.'))
                }
            }
        })
    }
    hotPro();
    detailpro();
    function detailpro() {
        var _order_data;
        var _user_login_status;
        var urlParam = purl(location.href).param()
        var login = false;
        var Data, day, hour, minute, second;
        var DATA, Day, Hour, Minute, Second;

        var detailMAXprice, detailMINprice;
        var SKU_product_sku_id = [];
        var _all_stock = 0;

        var htmlprice, htmlDecimals;
        var MinHeight, MaxHeight;
        var totle_group;
        var group_count = 0;
        var product_DATA = {
            product_id: url('?id'),
        }
        var DATA = {
            id: url('?id'),
        }
        // 拼团类型
        var gpubs_type;

        // 传入字符串
        fontsize('goods-intro');
        fontsize('goods-intro-mess');
        isLogin(function (data) {
            login = data['is-login']
            init()
        })

        function init() {
            $("#deliver-goods-text").val(1)
            // 获取登录状态
            ! function () {
                requestUrl('/member/login/is-return', 'GET', {
                    return_url: location.href
                }, function (data) {
                    _user_login_status = data.status;
                    if (_user_login_status == 0) {
                        loadGoodsInfo();
                    }
                    if (_user_login_status == 1) {
                        loadGoodsInfo();
                        pingou();
                    }
                })
            }()
            ! function () {
                requestUrl('/index/get-user-status', 'GET', {
                    return_url: location.href
                }, function (data) {
                    user_level = data.level;
                })
            }()
            var sku_keys = [],
                sku_data = {},
                SKUResult = {};
            // 规格详情图片


            //加载购买 选择
            function loadBuyOption() {
                var attr = [];
                $.each(_order_data.SKU.attributes, function (i, v) {
                    var option = [];
                    var name = '';
                    $.each(v, function (index, value) {
                        name = index;
                        $.each(value, function (inde, val) {
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
                var tpl_attr = $('#J_tpl_buy_attribute').html();
                var sellAttr = juicer(tpl_attr, attr);
                $("#standard").html(sellAttr);

            }
            //加载正常商品信息
            function loadGoodsInfo() {
                var req_url = "/goods/get-goods-info";
                var _data = {
                    id: url('?p_id'),
                };

                function success(data) {
                    _order_data = data;
                    $(".detail-img>img").attr("src", _order_data.big_images[0]);
                    detailMAXprice = data.price.max;
                    detailMINprice = data.price.min;
                    var num = 1,
                        NUM = 1;
                    // 商品数量的加减
                    $("#deliver-goods-text").keyup(function () {
                        $(this).val($(this).val().replace(/[^0-9-]+/, ''));
                        if ($(this).val().length == 1) {
                            $(this).val() == '0' ? $(this).val('1') : $(this).val();
                        }

                    })

                    $('#deliver-goods-text').blur(function () {
                        num = Number($('#deliver-goods-text').val());
                        if ($(this).val().length == 0) {
                            num = 1;
                        }
                        goodsNum(num);
                    });
                    $("#deliver-goods-add").on("click", function () {
                        num += 1;
                        $('#deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                        goodsNum(num);
                    })
                    $("#deliver-goods-reduce").on("click", function () {
                        num -= 1;
                        if ($('#deliver-goods-text').val() == 1) {
                            num = 1;
                        }
                        goodsNum(num);

                    })
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
                    }


                    $("#alone-deliver-goods-add").on("click", function () {
                        NUM += 1;
                        $('#alone-deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                        goodsNUM(NUM);
                    })
                    $("#alone-deliver-goods-reduce").on("click", function () {
                        NUM -= 1;
                        if ($("#alone-deliver-goods-text").val() == 1) {
                            NUM = 1;
                        }
                        goodsNUM(NUM);

                    })
                    $("#alone-deliver-goods-text").keyup(function () {
                        $(this).val($(this).val().replace(/[^0-9-]+/, ''));
                        if ($(this).val().length == 1) {
                            $(this).val() == '0' ? $(this).val('1') : $(this).val();
                        }

                    })

                    $('#alone-deliver-goods-text').blur(function () {
                        NUM = Number($('#alone-deliver-goods-text').val());
                        if ($(this).val().length == 0) {
                            NUM = 1;
                        }
                        goodsNUM(NUM);
                    });
                    function goodsNUM(num) {
                        $('#alone-deliver-goods-text').val(num);
                        if ($('#alone-deliver-goods-text').val() == 1) {
                            $('#alone-deliver-goods-reduce').css({
                                display: 'inline-block',
                                background: 'url(/images/group_goods_detail/reduce_failure.png)  no-repeat',
                                backgroundSize: 'contain'
                            });
                        } else {
                            $('#alone-deliver-goods-reduce').css({
                                display: 'inline-block',
                                background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                                backgroundSize: 'contain'
                            });
                        }
                    }
                    $(".fuhao").html("￥");
                    // 拼购时该显示的价格
                    function pingouPRICE() {
                        if (data.price.min == data.price.max) {
                            // 规格页原价
                            $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥" + data.price.min.toFixed(2))
                            $("#alone-specifications-detail-wrap .detail-newprice .new-price").html(data.price.min.toFixed(2));
                        } else {
                            // 规格页原价
                            $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥" + data.price.min.toFixed(2) + "-" + data.price.max.toFixed(2))
                            $("#alone-specifications-detail-wrap .detail-newprice .new-price").html(data.price.min.toFixed(2) + "-" + data.price.max.toFixed(2));
                        }
                    }
                    pingouPRICE()
                    //加载购买选项
                    loadBuyOption();
                    if (_user_login_status == 1) {
                        goodsSKU(data.SKU.sku)
                    }
                }

                function errorCB(data) {
                    alert(data.data.errMsg);
                }
                requestUrl(req_url, 'get', _data, success, errorCB);
            }
            // 加载拼购商品SKU
            function goodsSKU(data) {
                var i, n;
                //加载库存总量
                // 初始化sku keys
                $('#specifications-detail-wrap .standard-title').each(function () {
                    var key = [];
                    $(this).find('[attr_id]').each(function () {
                        key.push($(this).attr('attr_id'));
                    })
                    sku_keys.push(key);
                })
                $.each(data, function (index, item) {
                    SKU_product_sku_id.push(item.id);
                })
                requestUrl('/gpubs/api/product', 'GET', {
                    product_id: url('?p_id'),
                }, function (infor) {
                    _all_stock = 0;
                    // 初始化有库存的sku_data
                    for (var skuOrder in data) {
                        var _arr1 = skuOrder.split(';');
                        var result = [];
                        _arr1.forEach(function (val) {
                            result.push(val.split(':')[1]);
                        })

                        _all_stock += data[skuOrder].stock;
                        if (data[skuOrder].stock >= 0) {
                            sku_data[result.join(';')] = data[skuOrder];
                        }
                    }
                    _pingou_data = infor;
                    $.each(SKU_product_sku_id, function (index, item) {
                        _all_stock += infor.sku[item].stock
                        if (_pingou_data.sku[item].stock == 0) {
                            n = _pingou_data.sku[item].product_sku_id;
                        }
                    })
                    $("#specifications-detail-wrap #inventory-num").html(_all_stock);
                    //绑定选择属性事件 
                    $('#specifications-detail-wrap .standard-title li[attr_id]').each(function () {
                        var self = $(this);
                        var attr_id = self.attr('attr_id');
                    }).click(function (e) {
                        var self = $(this);
                        if (self.hasClass('cant-choose')) return;
                        self.parent().parent().removeClass('has-error');
                        //选中自己，兄弟节点取消选中
                        if (self.hasClass('li-active')) {
                            return
                            e.preventDefault();
                            e.stopPropagation();
                            self.removeClass('li-active');
                            self.find('input')[0].checked = false;
                        } else {
                            self.toggleClass('li-active').siblings().removeClass('li-active');
                        }
                        //已经选择的节点
                        var selectedObjs = $('#specifications-detail-wrap li[attr_id].li-active');
                        var selectedIds = [];
                        var selectedIDs = [];
                        var selectOptions = "";
                        var Id_sku = "";
                        selectedObjs.each(function () {
                            selectedIDs.push($(this).parent().attr('data-id'));
                            selectedIds.push($(this).attr('attr_id'));
                            Id_sku += $(this).parent().attr('data-id')
                                + ":" + $(this).attr('attr_id') + ";"
                            selectOptions += '"' + $(this).html() + '"';
                        });
                        Id_sku = Id_sku.substring(0, Id_sku.length - 1);
                        $("#specifications-detail-wrap .choose-specifications-color>.yet-choose").show();
                        $("#specifications-detail-wrap .choose-specifications-color>.please-choose").hide();

                        $("#specifications-detail-wrap .choose-specifications-color .yet-choose").html("已选择：" + selectOptions);
                        $(".specifications-address .choose").hide();
                        $(".specifications-address .specifications-mess>.mess").html("已选择：" + selectOptions);
                        if (selectedObjs.length == $('#specifications-detail-wrap .standard-title ul[data-id]').length) {
                            var len = selectedIds.length;
                            var cartId = '';
                            var sku_id = '';
                            $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                                cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
                            })
                            cartId = cartId.substring(0, cartId.length - 1);
                            var sku_id = _order_data['SKU']['sku'][cartId].id;
                            _all_stock = 0;
                            $.each(SKU_product_sku_id, function (index, item) {
                                if (_pingou_data.sku[item].product_sku_id == sku_id) {
                                    _all_stock = _pingou_data.sku[item].stock;
                                    $('#specifications-detail-wrap .detail-newprice .new-price').text(_pingou_data.sku[item].price.toFixed(2));
                                    $(".want-open-group .price").text("￥" + _pingou_data.sku[item].price.toFixed(2));
                                    $(".goods-detail-price .new-price .min").addClass("hidden");
                                    var t1 = $(".goods-detail-price .new-price .max .max-price");
                                    var t2 = $(".goods-detail-price .new-price .max .max-price-decimals");
                                    huoquPrice(_pingou_data.sku[item].price, t1, t2)
                                }
                            })
                            $("#inventory-num").html(_all_stock);
                            initSKU();
                            var price = SKUResult[selectedIds.join(';')].guidance_price;
                            if (_user_login_status == 1) {
                                price = SKUResult[selectedIds.join(';')].price;
                            }
                            $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥" + Number(price).toFixed(2));
                            $(".goods-detail-price .old-price").html("￥" + Number(price).toFixed(2));
                            $("#purchase-separately-qubie .price").html("￥" + Number(price).toFixed(2));
                            //用已选中的节点验证待测试节点 underTestObjs
                            $('#specifications-detail-wrap .standard-title li[attr_id]').not(selectedObjs).not(self).each(function () {
                                var siblingsSelectedObj = $(this).siblings('.li-active');
                                var testAttrIds = []; //从选中节点中去掉选中的兄弟节点
                                if (siblingsSelectedObj.length) {
                                    var siblingsSelectedObjId = siblingsSelectedObj.attr('attr_id');
                                    for (var i = 0; i < len; i++) {
                                        (selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
                                    }
                                } else {
                                    testAttrIds = selectedIds.concat();
                                }
                                testAttrIds = testAttrIds.concat($(this).attr('attr_id'));
                                testAttrIds.sort(function (value1, value2) {
                                    return parseInt(value1) - parseInt(value2);
                                });
                                if (!SKUResult[testAttrIds.join(';')]) {
                                    $(this).addClass('disabled').removeClass('li-active');
                                } else {
                                    $(this).removeClass('disabled');
                                }
                            });
                        }
                    });
                })
            }
            //获得对象的key
            function getObjKeys(obj) {
                if (obj !== Object(obj)) throw new TypeError('Invalid object');
                var sku_keys = [];
                for (var key in obj)
                    if (Object.prototype.hasOwnProperty.call(obj, key))
                        sku_keys[sku_keys.length] = key;
                return sku_keys;
            }
            //把组合的key放入结果集SKUResult
            function add2SKUResult(combArrItem, sku) {
                var key = combArrItem.join(";");
                if (SKUResult[key]) { //SKU信息key属性·
                    SKUResult[key].stock += sku.stock;
                    SKUResult[key].price.push(sku.price);
                    SKUResult[key].guidance_price.push(sku.guidance_price);
                    SKUResult[key].id.push(sku.id);
                } else {
                    SKUResult[key] = {
                        stock: sku.stock,
                        price: [sku.price],
                        guidance_price: [sku.guidance_price],
                        original_price: [sku.original_price],
                        original_guidance_price: [sku.original_guidance_price],
                        id: [sku.id]
                    };
                }
            }
            //初始化得到结果集
            function initSKU() {
                var i, j, skuKeys = getObjKeys(sku_data);
                for (i = 0; i < skuKeys.length; i++) {
                    var skuKey = skuKeys[i]; //一条SKU信息key
                    var sku = sku_data[skuKey]; //一条SKU信息value
                    var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组
                    skuKeyAttrs.sort(function (value1, value2) {
                        return parseInt(value1) - parseInt(value2);
                    });
                    //对每个SKU信息key属性值进行拆分组合
                    var combArr = combInArray(skuKeyAttrs);
                    for (j = 0; j < combArr.length; j++) {
                        add2SKUResult(combArr[j], sku);
                    }
                    //结果集接放入SKUResult
                    SKUResult[skuKeyAttrs.join(";")] = {
                        stock: sku.stock,
                        price: [sku.price],
                        guidance_price: [sku.guidance_price],
                        original_price: [sku.original_price],
                        original_guidance_price: [sku.original_guidance_price],
                        id: [sku.id]
                    }
                }
            }
            /**
             * 从数组中生成指定长度的组合
             * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
             */
            function combInArray(aData) {
                if (!aData || !aData.length) {
                    return [];
                }
                var len = aData.length;
                var aResult = [];
                for (var n = 1; n < len; n++) {
                    var aaFlags = getCombFlags(len, n);
                    while (aaFlags.length) {
                        var aFlag = aaFlags.shift();
                        var aComb = [];
                        for (var i = 0; i < len; i++) {
                            aFlag[i] && aComb.push(aData[i]);
                        }
                        aResult.push(aComb);
                    }
                }
                return aResult;
            }
            /**
             * 得到从 m 元素中取 n 元素的所有组合
             * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
             */
            function getCombFlags(m, n) {
                if (!n || n < 1) {
                    return [];
                }
                var aResult = [];
                var aFlag = [];
                var bNext = true;
                var i, j, iCnt1;
                for (i = 0; i < m; i++) {
                    aFlag[i] = i < n ? 1 : 0;
                }
                aResult.push(aFlag.concat());
                while (bNext) {
                    iCnt1 = 0;
                    for (i = 0; i < m - 1; i++) {
                        if (aFlag[i] == 1 && aFlag[i + 1] == 0) {
                            for (j = 0; j < i; j++) {
                                aFlag[j] = j < iCnt1 ? 1 : 0;
                            }
                            aFlag[i] = 0;
                            aFlag[i + 1] = 1;
                            var aTmp = aFlag.concat();
                            aResult.push(aTmp);
                            if (aTmp.slice(-n).join("").indexOf('0') == -1) {
                                bNext = false;
                            }
                            break;
                        }
                        aFlag[i] == 1 && iCnt1++;
                    }
                }
                return aResult;
            }
            // 判断价格是整数还是小数
            function getPrice(price) {
                if (typeof price == "number") {
                    price = String(price);
                    if (price.split(".").length == 1) {
                        // 整数
                        return 1;
                    } else if (price.split(".").length > 1) {
                        // 带小数
                        return 2;
                    }
                }
            }
            // 返回数据
            function huoquPrice(price, integerText, decimalsText) {
                if (getPrice(price) == 1) {
                    integerText.html(price);
                    decimalsText.html(".00");
                } else if ((getPrice(price) == 2)) {
                    integerText.html(String(price).split(".")[0]);
                    decimalsText.html("." + String(price).split(".")[1]);
                }
            }
            // 拼购商品详情
            function pingou() {
                var req_url = "/gpubs/api/product";
                var _data = {
                    product_id: url('?p_id'),
                };
                function success(data) {
                    gpubs_type = data.gpubs_type;
                    // 判断拼购类型
                    function type() {
                        if (data.gpubs_type != undefined) {
                            $(".service-policy .mess").html(data.description);
                            $(".service-policy-detail-wrap .mess .mess-item").html(data.description);
                            $(".goods-detail-mess .spell-delivery").removeClass("hidden");
                            $(".goods-detail-count-down").removeClass("hidden");
                            $(".service-policy").removeClass("hidden");
                            $(".count-down-mess-wrap").removeClass("hidden");
                            $(".count-down-rule-wrap").removeClass("hidden");
                            $(".purchase-separately").removeClass("hidden");
                            $(".want-open-group").removeClass("hidden");
                            if (data.max_price == data.min_price) {
                                // 规格页拼购价
                                $("#specifications-detail-wrap .detail-newprice .new-price").html(data.max_price.toFixed(2));
                                $(".want-open-group .price").html("￥ " + data.min_price.toFixed(2))
                            } else {
                                // 规格页拼购价
                                $("#specifications-detail-wrap .detail-newprice .new-price .min-price").html(data.min_price.toFixed(2) + "-");
                                $("#specifications-detail-wrap .detail-newprice .new-price .max-price").html(data.max_price.toFixed(2));
                            }
                        }
                    }
                    if (data.gpubs_type == 1) {
                        // 自提
                        $(".spell-delivery>.delivery").addClass("delivery-ziti-bg");
                        $(".spell-delivery>.delivery").removeClass("delivery-songhuo-bg");
                        $(".count-down-mess-wrap").hide();
                        type()
                    }
                    if (data.gpubs_type == 2) {
                        // 送货
                        $(".spell-delivery>.delivery").addClass("delivery-songhuo-bg");
                        $(".spell-delivery>.delivery").removeClass("delivery-ziti-bg");
                        type()
                    }
                    // 判断成团规则类型
                    if (data.gpubs_rule_type == 1) {
                        var text = data.min_member_per_group + "人拼"
                        $(".spell-delivery>.spell").html(text);
                    } else if (data.gpubs_rule_type == 2) {
                        var text = data.min_quantity_per_group + "件拼"
                        $(".spell-delivery>.spell").html(text);
                    } else if (data.gpubs_rule_type == 3) {
                        var text = data.min_member_per_group + "人" + data.min_quanlity_per_member_of_group + "件拼"
                        $(".spell-delivery>.spell").html(text);
                    }
                }
                function errorCB(data) {
                    alert(data.data.errMsg);
                }
                requestUrl(req_url, 'GET', _data, success, errorCB);
            }
            // 我要参团
            $("#G_group-share-handle-box1").on("click", function () {
                confirmBtn();
            });
            function confirmBtn() {
                $("#specifications-detail-wrap").removeClass("hidden");
                $("#purchase-separately-qubie").addClass("hidden");
                $("#want-open-group-qubie").addClass("hidden");
                $("#confirm").removeClass("hidden")
                $("#confirm").click(function () {
                    var data = {
                        id: url('?id')
                    };
                    if (_user_login_status != 1) {
                        var con = confirm('您还未登录，是否跳转到登录页？');
                        if (con == true) {
                            window.location.href = '/member/login/index'
                        }
                        return
                    }
                    var len = $('#specifications-detail-wrap .standard-title').length;
                    $('#specifications-detail-wrap .standard-title').removeClass('has-error');
                    var _selectAll = true;
                    for (var i = 0; i < len; i++) {
                        var leng = $('#specifications-detail-wrap .standard-title').eq(i).find('li').length;
                        if (!$('#specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                            _selectAll = false
                            break
                        }
                    }
                    if (!_selectAll) {
                        alert('属性未选择完整！')
                        return false
                    }
                    var cartId = '';
                    $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                        cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
                    })

                    cartId = cartId.substring(0, cartId.length - 1);
                    var sku_id = _order_data['SKU']['sku'][cartId].id;

                    var count = parseInt($('#specifications-detail-wrap #deliver-goods-text').val());
                    if (count < 1) {
                        alert('购买数量为0！')
                        return false
                    }
                    $.each(_pingou_data.sku, function (index, item) {
                        if (index == sku_id) {
                            if (item.stock == 0) {
                                alert("该拼购商品此规格库存不足，无法开团");
                                return
                            } else {
                                if (getSearchAtrr('id')) {
                                    window.location.href = '/gpubs/confirm?id=' + getSearchAtrr('p_id') + '&skuid=' + sku_id + '&num=' + count + '&group_id=' + getSearchAtrr('id')
                                } else {
                                    window.location.href = '/gpubs/confirm?id=' + getSearchAtrr('p_id') + '&skuid=' + sku_id + '&num=' + count
                                }
                            }
                        }
                    })
                })
            }
            // 规格详情的显示隐藏
            $("#specifications-more").on("click", function () {
                $("#specifications-detail-wrap").removeClass("hidden");
                $("#purchase-separately-qubie").removeClass("hidden");
                $("#want-open-group-qubie").removeClass("hidden");
                $("#confirm").addClass("hidden")
            })
            $("#specifications-detail-wrap-close").on("click", function () {
                $("#specifications-detail-wrap").addClass("hidden");
            })
            $("#alone-specifications-detail-wrap-close").on("click", function () {
                $("#alone-specifications-detail-wrap").addClass("hidden");
                $("#specifications-detail-wrap .spell-delivery").css({
                    opacity: 1
                })
            })
            // 拼购规则详情的跳转
            $("#rule-more").on("click", function () {
                window.location.href = "/goods/activity-rules?type=" + gpubs_type;
            })
            $(".pingou-index-button").click(function () {
                window.location.href = "/";
            })
            $(".contact-service-button").click(function () {
                if (_user_login_status != 1) {
                    var con = confirm('您还未登录，是否跳转到登录页？');
                    if (con == true) {
                        window.location.href = '/member/login/index'
                    }
                    return
                }
            })
            // 服务说明
            $("#service-policy-more").click(function () {
                $("#service-policy-detail-wrap").removeClass("hidden")
            })
            $("#service-policy-detail-wrap-close").click(function () {
                $("#service-policy-detail-wrap").addClass("hidden")
            })
        }
        function isLogin(callback) {
            requestUrl('/temp/groupbuy/is-login', 'GET', null, callback)
        }
    }
    // 快速导航
    function fastGuid() {
        var flag = true;
        $('.pack-up').css({
            "background": "url('/images/group_goods_detail/fast_guid.png') no-repeat",
            "background-size": "cover"
        });
        $('#pack-up').on('click', function () {
            $('.guid-list .list').toggle();
            if (flag) {
                $('.fast-guid-list').removeClass('hidden');
                $('.pack-up').css({
                    "background": "url('/images/group_goods_detail/pack_up.png') no-repeat",
                    "background-size": "cover"
                });
                flag = false;
                return
            }
            $('.fast-guid-list').addClass('hidden');
            $('.pack-up').css({
                "background": "url('/images/group_goods_detail/fast_guid.png') no-repeat",
                "background-size": "cover"
            });
            flag = true;
        })
        $('.G_group-share-back-top').on('click', function () {
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        })
        $('#guid-list .pingou-index').on('click', function () {
            location.href = '/'
        })
        $('#guid-list .my-pingou').on('click', function () {
            location.href = '/member/gpubs-order/index'
        })
        $('#guid-list .ziti-pingou-tihuo').on('click', function () {
            location.href = '/member/gpubs-pick/index'
        })
    }
    fastGuid();
})
