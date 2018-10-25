$(function () {

    var defaultImg = 'http://images.9daye.com.cn/a_9/9bb6dcf74c7230e40d675475a12b677dbb8704a21.jpg'

    apex.commonApi.getHost(function (host) {
        sessionStorage.setItem('HOST9DAYE', host)
        // 页面初始化
        ;(function () {
            var index = {
                banner_tpl: $('#J_tpl_banner').html(),
                indicators_tpl: $('#J_tpl_indicators').html(),
                getBanner: function() {
                    var tpl = this.banner_tpl;
                    var tpl_i = this.indicators_tpl;
                    requestUrl('/index/carousel', 'GET', '', function(data) {
                        var html = juicer(tpl, data);
                        var html1 = juicer(tpl_i, data)
                        $('#J_banner_list').html(html);
                        $('#J_indicators_list').html(html1);
                        $('.carousel').carousel();
                        // 移动端禁止首页商品自动轮播
                        var ua  = navigator.userAgent.toLowerCase();
                        if(ua.indexOf('android') != -1 || ua.indexOf('iphone') != -1 || ua.indexOf('ipad') != -1){
                            $('.apx-index-section .carousel').carousel({
                                interval: 999999999
                            });
                        }
                    });
                },
                getItem: function(arr, callback, error) {
                    var params = {
                        id: []
                    }
                    params.id = arr;
                    requestUrl('/product-recommend/goods', 'GET', params, function(data) {
                        if (typeof callback == 'function') {
                            callback(data)
                        }
                    }, function(data) {
                        console.log(data.data.errMsg)
                    })
                },
                isLogin: function() {
                    if (CUSTOM_USER_LOGIN) {
                        $('#J_index_logout_info').removeClass('hidden')
                    } else {
                        $('#J_index_logon_info').removeClass('hidden')
                    }
                },
                init: function() {
                    this.getBanner()
                    this.isLogin()
                }
            }
        	index.init()
            // 切换顶部
            // $('body').css('marginTop', 80);
            // $('.index-top-nav').css('top', 228);
            // $('.national-first').eq(0).removeClass('hidden').css('top', 80);
            // 吸顶搜索
            $(document).on('scroll', function() {
                $('#search-ipts').val($('#search-ipt').val());
                if ($(this).scrollTop() > 800) {
                    $('.fixed-header').addClass('in');
                } else {
                    $('.fixed-header').removeClass('in');
                }
            })
            // 吸顶搜索跳转s
            $('#search-btns').click(function (){
              var keyword = $('#search-ipts').val();
              if(keyword) {
                window.location.href = "/search/index?keyword="+keyword;
              }
            });
            $('#search-ipts').on('keypress',function(ev) {
                var ipt_val = $(this).val();
                if(ev.keyCode === 13) {
                    if(ipt_val) {
                      window.location.href = "/search/index?keyword=" + ipt_val;
                    }
                }
            });
            // 登录状态
            if (CUSTOM_USER_LOGIN) {
                $('#J_index_logon_info').addClass('hidden')
                $('#J_index_logout_info').removeClass('hidden')
            }
        }());
        // 分类菜单
        ;(function () {
            getData()
            function getData() {
                apex.commonApi.getGroup(function (list) {
                    $('#jsGroup').html(createTpl(list))
                    interact()
                })
            }
            function createTpl(list){
                var tpl = ''

                tpl += '<ul id="J_sort_list">'

                for (var i = 0; i < list.length; i += 1) {
                    var item = list[i]

                    tpl += '<li>'
                    tpl += '<div class="sort-tag" data-id="' + item.id + '">'
                    tpl += '<p class="tag-title">' + item.name + '</p>'
                    tpl += '<p class="tag-detail">'

                    var detailLinkAry = []

                    for (var j = 0; j < 3; j += 1) {
                        if (item.item[j]) {
                            if (item.item[j].cate_id > 0) {
                                detailLinkAry.push('<a href="/search/category?end_category_id=' + item.item[j].cate_id + '&end_category_name=' + item.item[j].name + '" target="_blank">' + item.item[j].name + '</a>')
                            } else {
                                detailLinkAry.push('<span>' + item.item[j].name + '</span>')
                            }
                        }
                    }

                    if (detailLinkAry.length > 0) {
                        tpl += detailLinkAry.join('/')
                    } else {
                        tpl += '&nbsp;'
                    }

                    tpl += '</p>'
                    tpl += '</div>'
                    tpl += '<div class="sort-items">'
                    tpl += '<div class="items-title">' + item.name + '</div>'
                    tpl += '<div class="item-list">'
                    tpl += '<ul>'

                    for (var j = 0; j < item.item.length; j += 1) {
                        var subGroup = item.item[j]

                        tpl += '<li>'

                        if (subGroup.cate_id > 0) {
                            tpl += '<a href="/search/category?end_category_id=' + subGroup.cate_id + '&end_category_name=' + subGroup.name + '" title="' + subGroup.name + '" target="_blank">' + subGroup.name + '</a>'
                        } else {
                            tpl += '<span title="' + subGroup.name + '">' + subGroup.name + '</span>'
                        }

                        tpl += '</li>'
                    }

                    tpl += '</ul>'
                    tpl += '</div>'
                    tpl += '<div class="hot-brand">'
                    tpl += '<div class="brand-title">热门品牌推荐</div>'
                    tpl += '<ul>'

                    for (var j = 0; j < 5; j += 1) {
                        var brandItem = item.brand[j]

                        if (brandItem) {
                            tpl += '<li>'
                            tpl += '<a href="/shop?id=' + brandItem.brand_id + '" target="_blank">'
                            tpl += '<img src="' + resizeOssPicturePad(brandItem.img, 120, 66) + '" alt="' + brandItem.brand_name + '">'
                            tpl += '</a>'
                            tpl += '</li>'
                        }
                    }
                    tpl += '</ul>'

                    tpl += '</div>'
                    tpl += '</div>'
                    tpl += '</li>'
                }

                tpl += '</ul>'

                return tpl
            }
            function interact() {
                $('#J_sort_list > li')
                .on('mouseover', function () {
                    $(this).find('.sort-items').show()
                })
                .on('mouseout', function () {
                    $(this).find('.sort-items').hide()
                })
            }
        }());
        // 商品信息
        ;(function () {
            function fontSize (className){
                var size = parseInt($('.' + className).css('font-size'));
                var width = parseInt($('.' + className).css('width'));
                // 行数
                var num = width / size * 2;     //2行
                // 每行字数
                var num1 = width / size;
                var num2 = width / size * 2 - 1; 
                $('.' + className).each(function() {
                    if ($(this).text().length > 11) {
                        $(this).html($(this).text().replace(/\s+/g, "").substr(0, 13) + "...")
                    }
                })
            }
        
            getData()
            function getData() {
                apex.commonApi.getShop(function (list) {
                    for (var i = 0; i < list.length; i += 1) {
                        var shop = list[i]
                        
                        if (shop.show == '1' && shop.type == '1') {
                            switch(shop.name) {
                                case '门店优选-pc':
                                    $('#newProdWrapper2').html(createNewProdTpl2(shop.group[0].product))
                                    $('#J_new_arrival2 .item').eq(0).addClass('active')
                                    fontSize('detail');
                                    break;
                                case '新品上市':
                                    $('#newProdWrapper').html(createNewProdTpl(shop.group[0].product))
                                    $('#J_new_arrival .item').eq(0).addClass('active')
                                    break;
                                case '包邮专场':
                                    $('#freeWrapper').html(createFreeTpl(shop.group))
                                    break;
                                case '发现好货':
                                    $('#excellentWrapper').html(createExcellentTpl(shop.group[0].product))
                                    break;
                                case '汽车装饰':
                                    $('#decorationWrapper').html(createDecorationTpl(shop))
                                    break;
                                case '美容用品':
                                    $('#beautifyWrapper').html(createBeautifyTpl(shop.group[0].product))
                                    break;
                                case '门店装饰':
                                    $('#decorationForShopWrapper').html(createDecorationForShopTpl(shop))
                                    break;
                                case '安全自驾':
                                    $('#saveWrapper').html(createSaveTpl(shop))
                                    break;
                                case '车载电器':
                                    $('#electronicWrapper').html(createElectronicTpl(shop))
                                    break;
                                case '汽车内饰':
                                    $('#decorationForCarWrapper').html(createDecorationForCarTpl(shop))
                                    break;
                                case '贴膜工具':
                                    $('#toolsWrapper').html(createToolTpl(shop))
                                    break;
                                case '还没逛够':
                                    $('#moreWrapper').html(createMoreTpl(shop))
                                    break;
                            }
                        }
                    }
                })
            }
            function createNewProdTpl(list) {
                var cnow = new Date().getTime();
                var cstart1 = new Date('2018/09/05 00:00:00').getTime();
                var cend1 = new Date('2018/09/08 23:59:59').getTime();
                var cbstart2 = new Date('2018/09/09 00:00:00').getTime();
                var cend2 = new Date('2018/09/10 23:59:59').getTime();
                var cimg = ''
                if(cnow >= cstart1 && cnow <= cend2){
                    cimg = '/images/event20180909/xinpinshangshi.jpg'
                }else{
                    cimg = '/images/18-6-29/index_new/new.jpg'
                }
                var tpl = ''

                tpl += '<div class="new-arrival clearfix">'
                tpl += '<div class="pull-left arrival-left">'
                tpl += '<img class="static" src="' + cimg + '">'
                tpl += '</div>'
                tpl += '<div class="pull-left new-pro">'
                tpl += '<div id="new-arrival-carousel" class="new-carousel carousel slide" data-interval="false">'
                tpl += '<div class="carousel-inner" role="listbox" id="J_new_arrival">'

                for (var i = 0; i < list.length; i += 1) {
                    var item = list[i]
                    var title = (item.sell_point || item.title)

                    if (i % 5 === 0) {
                        tpl += '<div class="item">'
                        tpl += '<ul>'
                    }
                    tpl += '<li>'
                    tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                    tpl += '<div class="img">'
                    tpl += '<img src="' + resizeOssPicturePad(item.index_image, 145, 145) + '"title="' + title + '">'
                    tpl += '</div>'
                    tpl += '<p class="detail" title="' + title + '">' + title + '</p>'
                    //tpl += '<p class="price"><small>￥</small><span>' + item.min_price + '</span></p>'
                    tpl += '</a>'
                    tpl += '</li>'

                    if ((i - 4) % 5 === 0 || i === list.length - 1) {
                        tpl += '</ul>'
                        tpl += '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '<a class="left carousel-control" href="#new-arrival-carousel" role="button" data-slide="prev">'
                tpl += '<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>'
                tpl += '<span class="sr-only">Previous</span>'
                tpl += '</a>'
                tpl += '<a class="right carousel-control" href="#new-arrival-carousel" role="button" data-slide="next">'
                tpl += '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>'
                tpl += '<span class="sr-only">Next</span>'
                tpl += '</a>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createNewProdTpl2(list) {
                var tpl = ''

                tpl += '<div class="new-arrival clearfix">'
                tpl += '<div class="pull-left arrival-left">'
                tpl += '<img class="static" src="/images/18-6-29/index/select-purchase.jpg">'
                tpl += '</div>'
                tpl += '<div class="pull-left new-pro">'
                tpl += '<div id="new-arrival-carousel2" class="new-carousel carousel slide" data-interval="false">'
                tpl += '<div class="carousel-inner" role="listbox" id="J_new_arrival2">'

                for (var i = 0; i < list.length; i += 1) {
                    var item = list[i]

                    if (i % 3 === 0) {
                        tpl += '<div class="item">'
                        tpl += '<ul>'
                    }
                    tpl += '<li>'
                    tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                    tpl += '<div class="img">'
                    tpl += '<img src="' + resizeOssPicturePad(item.index_image, 145, 145) +  '"title="' + item.title +'">'
                    tpl += '</div>'
                    tpl += '<div class="txt-wrap">'
                    tpl += '<p class="detail" title="' + item.title +'">' + item.title + '</p>'
                    //tpl += '<p class="price"><small>￥</small><span>' + item.min_price + '</span></p>'
                    tpl += '<span class="sell_point" title="' + item.sell_point + '">' + item.sell_point + '</span>'
                    tpl += '</div>'
                    tpl += '<span class="details-btn">查看详情</span>'
                    tpl += '</a>'
                    tpl += '</li>'

                    if ((i - 2) % 3 === 0 || i === list.length - 1) {
                        tpl += '</ul>'
                        tpl += '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '<a class="left carousel-control" href="#new-arrival-carousel2" role="button" data-slide="prev">'
                tpl += '<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>'
                tpl += '<span class="sr-only">Previous</span>'
                tpl += '</a>'
                tpl += '<a class="right carousel-control" href="#new-arrival-carousel2" role="button" data-slide="next">'
                tpl += '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>'
                tpl += '<span class="sr-only">Next</span>'
                tpl += '</a>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createFreeTpl(group) {
                var item = ''

                var tpl = ''

                tpl += '<div class="pull-left find-good">'
                tpl += '<p class="find-title section-title">'
                tpl += '<span>无忧购</span>'
                tpl += '<a href="" class="pull-right hidden">好货包邮</a>'
                tpl += '</p>'
                tpl += '<div class="pro-top">'

                tpl += '<div class="item">'
                item = group[0].product[0]

                if (item) {
                    tpl += '<a target="_blank" href="/product?id=' + item.original_id + '" title="' + item.title + '"><img class="left" src="' + resizeOssPicturePad(item.index_image, 185, 228) + '"></a>'
                } else {
                    tpl += '<a><img class="left" src="' + resizeOssPicturePad(defaultImg, 185, 228) + '"></a>'
                }

                tpl += '</div>'

                tpl += '<div class="item inline">'

                if (group[1]) {
                    for (var i = 0; i < 2; i += 1) {
                        var item = group[1].product[i]

                        if (item) {
                            var title = (item.sell_point || item.title)

                            tpl += '<div class="one" title="' + item.title + '">'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                            tpl += '<div class="text">'
                            tpl += '<p class="title">' + item.title + '</p>'
                            tpl += '<p class="sub-title">' + title + '</p>'
                            tpl += '</div>'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 80, 80) + '">'
                            tpl += '</a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="one">'
                            tpl += '<a>'
                            tpl += '<div class="text">'
                            tpl += '<p class="title"></p>'
                            tpl += '<p class="sub-title"></p>'
                            tpl += '</div>'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 80, 80) + '">'
                            tpl += '</a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'

                tpl += '<div class="item">'
                item = group[0].product[1]

                if (item) {
                    tpl += '<a target="_blank" href="/product?id=' + group[0].product[1].original_id + '" title="' + item.title + '"><img class="left" src="' + resizeOssPicturePad(group[0].product[1].index_image, 185, 228) + '"></a>'
                } else {
                    tpl += '<a><img class="left" src="' + resizeOssPicturePad(defaultImg, 185, 228) + '"></a>'
                }

                tpl += '</div>'

                tpl += '<div class="item inline">'

                if (group[1]) {
                    for (var i = 2; i < 4; i += 1) {
                        var item = group[1].product[i]

                        if (item) {
                            tpl += '<div class="one" title="' + item.title + '">'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                            tpl += '<div class="text">'
                            tpl += '<p class="title">' + item.title + '</p>'
                            tpl += '<p class="sub-title">' + (item.sell_point || item.title) + '</p>'
                            tpl += '</div>'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 80, 80) + '">'
                            tpl += '</a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="one">'
                            tpl += '<a>'
                            tpl += '<div class="text">'
                            tpl += '<p class="title"></p>'
                            tpl += '<p class="sub-title"></p>'
                            tpl += '</div>'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 80, 80) + '">'
                            tpl += '</a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'
                tpl += '</div>'

                tpl += '<div class="pro-bottom">'

                if (group[2]) {
                    for (var i = 0; i < 4; i += 1) {
                        var item = group[2].product[i]

                        if (item) {
                            tpl += '<div class="item" title="' + (item.sell_point || item.title) + '">'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '"><img src="' + resizeOssPicturePad(item.index_image, 185, 114) + '"></a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="item">'
                            tpl += '<a><img src="' + resizeOssPicturePad(defaultImg, 185, 114) + '"></a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'
                tpl += '</div>'


                return tpl
            }
            function createExcellentTpl(list) {
                var tpl = ''

                tpl += '<div class="pro-sort pull-left" id="J_find_good">'
                tpl += '<div class="sort-title section-title">'
                tpl += '<span>发现好货</span>'
                tpl += '<a href="" class="pull-right hidden">查看排行</a>'
                tpl += '<span class="pull-right">精选人气商品</span>'
                tpl += '</div>'
                tpl += '<div class="sort-content">'

                tpl += '<div class="tab-content">'
                tpl += '<div role="tabpanel" class="tab-pane active" id="home">'

                tpl += '<div class="sort-item">'

                if (list.length > 0) {
                    for (var i = 0; i < 3; i += 1) {
                        var item = list[i]
                        if (item) {
                            tpl += '<div class="item" title="' + (item.sell_point || item.title) + '">'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 110, 110) + '">'
                            tpl += '<p class="detail">' + (item.sell_point || item.title) + '</p>'
                            tpl += '</a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="item">'
                            tpl += '<a>'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 110, 110) + '">'
                            tpl += '<p class="detail"></p>'
                            tpl += '</a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'

                tpl += '<div class="sort-item">'

                if (list.length > 0) {
                    for (var i = 3; i < 6; i += 1) {
                        var item = list[i]
                        if (item) {
                            tpl += '<div class="item" title="' + (item.sell_point || item.title) + '">'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 110, 110) + '">'
                            tpl += '<p class="detail">' + (item.sell_point || item.title) + '</p>'
                            tpl += '</a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="item">'
                            tpl += '<a>'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 110, 110) + '">'
                            tpl += '<p class="detail"></p>'
                            tpl += '</a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'

                tpl += '</div>'

                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createDecorationTpl(list) {
                var item = ''
                var tpl = ''

                tpl += '<div class="section-title">'
                tpl += '<span>汽车装饰</span>'
                tpl += '<a href="" class="pull-right hidden">不愿下车的理由</a>'
                tpl += '<span class="pull-right">不愿下车的理由</span>'
                tpl += '</div>'
                tpl += '<div class="section-content clearfix">'

                tpl += '<div class="pull-left pro-left">'

                item = list.group[0]

                if (item && item.product[0]) {
                    tpl += '<a target="_blank" href="/product?id=' + item.product[0].original_id + '" title="' + item.product[0].title + '"><img class="left" src="' + resizeOssPicturePad(item.product[0].index_image, 285, 341) + '"></a>'
                } else {
                    tpl += '<a><img class="left" src="' + resizeOssPicturePad(defaultImg, 285, 341) + '"></a>'
                }

                tpl += '</div>'

                tpl += '<div class="pull-left pro-right">'
                tpl += '<div class="items">'

                if (list.group[1]) {
                    for (var i = 0; i < 3; i += 1) {
                        var item = list.group[1].product[i]

                        if (item) {
                            tpl += '<div class="item" title="' + item.title + '">'
                            tpl += '<p class="item-title">' + item.title + '</p>'
                            tpl += '<p class="sub-title">' + item.sell_point + '</p>'
                            tpl += '<p class="buy-now"><a target="_blank" href="/product?id=' + item.original_id + '">立即购买</a></p>'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '"><img src="' + resizeOssPicturePad(item.index_image, 120, 120) + '" alt="' + item.title + '"></a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="item">'
                            tpl += '<p class="item-title"></p>'
                            tpl += '<p class="sub-title"></p>'
                            tpl += '<p class="buy-now"><a>立即购买</a></p>'
                            tpl += '<a><img src="' + resizeOssPicturePad(defaultImg, 120, 120) + '"></a>'
                            tpl += '</div>'
                        }

                    }
                }

                tpl += '</div>'

                tpl += '<div class="items">'

                if (list.group[1]) {
                    for (var i = 3; i < 6; i += 1) {
                        var item = list.group[1].product[i]

                        if (item) {
                            tpl += '<div class="item" title="' + (item.sell_point || item.title) + '">'
                            tpl += '<p class="item-title">' + item.title + '</p>'
                            tpl += '<p class="sub-title">' + item.sell_point + '</p>'
                            tpl += '<p class="buy-now"><a target="_blank" href="/product?id=' + item.original_id + '">立即购买</a></p>'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '"><img src="' + resizeOssPicturePad(item.index_image, 120, 120) + '"></a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="item">'
                            tpl += '<p class="item-title"></p>'
                            tpl += '<p class="sub-title"></p>'
                            tpl += '<p class="buy-now"><a>立即购买</a></p>'
                            tpl += '<a><img src="' + resizeOssPicturePad(defaultImg, 120, 120) + '"></a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'
                tpl += '</div>'

                tpl += '<div class="pro-bottom">'

                if (list.group[2]) {
                    for (var i = 0; i < 4; i += 1) {
                        var item = list.group[2].product[i]

                        if (item) {
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '" title="' + (item.sell_point || item.title) + '">'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 285, 165) + '">'
                            tpl += '</a>'
                        } else {
                            tpl += '<a>'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 285, 165) + '">'
                            tpl += '</a>'
                        }
                    }
                }

                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createBeautifyTpl(list) {
                var tpl = ''

                tpl += '<div class="section-title">'
                tpl += '<span>美容用品</span>'
                tpl += '<a href="" class="pull-right hidden"></a>'
                tpl += '<span class="pull-right">车比你更在乎面子</span>'
                tpl += '</div>'

                tpl += '<div class="section-content">'
                tpl += '<div id="beauty-section-carousel" class="carousel slide" data-interval="false">'
                tpl += '<div class="carousel-inner" role="listbox">'
                tpl += '<div class="item active" id="J_beauty_list">'
                tpl += '<div class="pro-box">'

                for (var i = 0; i < 10; i += 1) {
                    var item = list[i]

                    if (item) {
                        tpl += '<div class="pro" title="' + (item.sell_point || item.title) + '">'
                        tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 208, 208) + '">'
                        tpl += '<p class="detail">' + (item.sell_point || item.title) + '</p>'
                        //tpl += '<p class="price">￥<span>' + item.min_price + '</span></p>'
                        tpl += '</a>'
                        tpl += '</div>'
                    } else {
                        tpl += '<div class="pro">'
                        tpl += '<a>'
                        tpl += '<img src="' + resizeOssPicturePad(defaultImg, 208, 208) + '">'
                        tpl += '<p class="detail"></p>'
                        tpl += '<p class="price">￥<span></span></p>'
                        tpl += '</a>'
                        tpl += '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createDecorationForShopTpl(list) {
                var tpl = ''

                tpl += '<div class="section-title">'
                tpl += '<span>门店装饰</span>'
                tpl += '<a href=""></a>'
                tpl += '<span class="pull-right">店铺陈列解决方案</span>'
                tpl += '</div>'
                tpl += '<div class="section-content">'

                tpl += '<div class="pro-top">'

                if (list.group[0]) {
                    for (var i = 0; i < 3; i += 1) {
                        var item = list.group[0].product[i]
                        if (item) {
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '" title="' + (item.sell_point || item.title) + '"><img src="' + resizeOssPicturePad(item.index_image, 383, 200) + '"></a>'
                        } else {
                            tpl += '<a><img src="' + resizeOssPicturePad(defaultImg, 383, 200) + '"></a>'
                        }
                    }
                }

                tpl += '</div>'

                tpl += '<div class="pro-bottom" id="J_store_pro">'

                if (list.group[1]) {
                    for (var i = 0; i < 5; i += 1) {
                        var item = list.group[1].product[i]

                        if (item) {
                            tpl += '<div class="item" title="' + (item.sell_point || item.title) + '">'
                            tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 210, 210) + '">'
                            tpl += '<p class="detail">' + (item.sell_point || item.title) + '</p>'
                            //tpl += '<p class="price">￥<span>' + item.min_price + '</span></p>'
                            tpl += '</a>'
                            tpl += '</div>'
                        } else {
                            tpl += '<div class="item">'
                            tpl += '<a>'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 210, 210) + '">'
                            tpl += '<p class="detail"></p>'
                            tpl += '<p class="price">￥<span></span></p>'
                            tpl += '</a>'
                            tpl += '</div>'
                        }
                    }
                }

                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createSaveTpl(list) {
                var item = ''
                var tpl = ''

                tpl += '<div class="pull-left section-left" id="J_safe_pro">'
                tpl+= '<div class="section-title">'
                tpl+= '<span>安全自驾</span>'
                tpl+= '<span class="pull-right">陪你面对崎岖艰险</span>'
                tpl+= '</div>'
                tpl+= '<div class="section-content clearfix">'

                tpl+= '<div class="pull-left left">'

                item = list.group[0]

                if (item && item.product[0]) {
                    tpl+= '<a target="_blank" href="/product?id=' + item.product[0].original_id + '" title="' + (item.product[0].sell_point || item.product[0].title) + '"><img src="' + resizeOssPicturePad(item.product[0].index_image, 230, 450) + '" alt="' + item.product[0].title + '"></a>'
                } else {
                    tpl+= '<a><img src="' + resizeOssPicturePad(defaultImg, 230, 450) + '"></a>'
                }

                tpl+= '</div>'

                tpl+= '<div class="pull-left right">'

                if (list.group[1]) {
                    for (var i = 0; i < 2; i += 1) {
                        tpl+= '<div class="items">'

                        for (var j = i; j < i + 2; j += 1) {
                            var item = list.group[1].product[i + j]

                            if (item) {
                                tpl+= '<div class="item" title="' + (item.sell_point || item.title) + '">'
                                tpl+= '<a target="_blank" href="/product?id=' + item.original_id + '">'
                                tpl+= '<p class="title">' + (item.sell_point || item.title) + '</p>'
                                tpl+= '<img src="' + resizeOssPicturePad(item.index_image, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            } else {
                                tpl+= '<div class="item">'
                                tpl+= '<a>'
                                tpl+= '<p class="title"></p>'
                                tpl+= '<img src="' + resizeOssPicturePad(defaultImg, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            }
                        }

                        tpl+= '</div>'
                    }
                }

                tpl+= '</div>'
                tpl+= '</div>'
                tpl+= '</div>'

                return tpl
            }
            function createElectronicTpl(list) {
                var tpl = ''

                tpl += '<div class="pull-left section-right" id="J_cart_pro">'
                tpl+= '<div class="section-title">'
                tpl+= '<span>车载电器</span>'
                tpl+= '<span class="pull-right">让诗从容飘向远方</span>'
                tpl+= '</div>'
                tpl+= '<div class="section-content clearfix">'

                tpl+= '<div class="pull-left left">'

                var item = list.group[0]

                if (item && item.product[0]) {
                    tpl+= '<a target="_blank" href="/product?id=' + item.product[0].original_id + '" title="' + (item.product[0].sell_point || item.product[0].title) + '"><img src="' + resizeOssPicturePad(item.product[0].index_image, 230, 450) + '" alt="' + item.product[0].title + '"></a>'
                } else {
                    tpl+= '<a><img src="' + resizeOssPicturePad(defaultImg, 230, 450) + '"></a>'
                }

                tpl+= '</div>'

                tpl+= '<div class="pull-left right">'

                if (list.group[1]) {
                    for (var i = 0; i < 2; i += 1) {
                        tpl+= '<div class="items">'

                        for (var j = i; j < i + 2; j += 1) {
                            var item = list.group[1].product[i + j]

                            if (item) {
                                tpl+= '<div class="item" title="' + (item.sell_point || item.title) + '">'
                                tpl+= '<a target="_blank" href="/product?id=' + item.original_id + '">'
                                tpl+= '<p class="title">' + (item.sell_point || item.title) + '</p>'
                                tpl+= '<img src="' + resizeOssPicturePad(item.index_image, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            } else {
                                tpl+= '<div class="item">'
                                tpl+= '<a>'
                                tpl+= '<p class="title"></p>'
                                tpl+= '<img src="' + resizeOssPicturePad(defaultImg, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            }
                        }

                        tpl+= '</div>'
                    }
                }

                tpl+= '</div>'
                tpl+= '</div>'
                tpl+= '</div>'

                return tpl
            }
            function createDecorationForCarTpl(list) {
                var tpl = ''

                tpl += '<div class="section-title">'
                tpl += '<span>汽车内饰</span>'
                tpl += '<a href="" class="pull-right hidden"></a>'
                tpl += '<span class="pull-right">我的地盘我做主</span>'
                tpl += '</div>'
                tpl += '<div class="section-content">'
                tpl += '<div id="beauty-section-carousel" class="carousel slide" data-interval="false">'
                tpl += '<div class="carousel-inner" role="listbox">'
                tpl += '<div class="item active" id="J_cart_trim">'
                tpl += '<div class="pro-box">'

                for (var i = 0; i < 10; i += 1) {
                    var item = list.group[0].product[i]

                    if (item) {
                        tpl += '<div class="pro" title="' + (item.sell_point || item.title) + '">'
                        tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 208, 208) + '">'
                        tpl += '<p class="detail">' + (item.sell_point || item.title) + '</p>'
                        //tpl += '<p class="price">￥<span>' + item.min_price + '</span></p>'
                        tpl += '</a>'
                        tpl += '</div>'
                    } else {
                        tpl += '<div class="pro">'
                        tpl += '<a>'
                        tpl += '<img src="' + resizeOssPicturePad(defaultImg, 208, 208) + '">'
                        tpl += '<p class="detail"></p>'
                        tpl += '<p class="price">￥<span></span></p>'
                        tpl += '</a>'
                        tpl += '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createToolTpl(list) {
                var item = ''
                var tpl = ''

                tpl += '<div class="section-title">'
                tpl += '<span>维修养护</span>'
                tpl += '<a href="" class="pull-right hidden"></a>'
                tpl += '<span class="pull-right">更专注所以更专业</span>'
                tpl += '</div>'
                tpl += '<div class="pull-left section-left">'
                tpl += '<div class="section-content clearfix">'

                tpl += '<div class="pull-left left">'

                item = list.group[0]
                if (item && item.product[0]) {
                    tpl += '<a target="_blank" href="/product?id=' + item.product[0].original_id + '" title="' + (item.product[0].sell_point || item.product[0].title) + '"><img src="' + resizeOssPicturePad(item.product[0].index_image, 230, 450) + '" alt="' + item.product[0].title + '"></a>'
                } else {
                    tpl += '<a><img src="' + resizeOssPicturePad(defaultImg, 230, 450) + '"></a>'
                }

                tpl += '</div>'

                tpl += '<div class="pull-left right">'

                if (list.group[1]) {
                    for (var i = 0; i < 2; i += 1) {
                        tpl+= '<div class="items">'

                        for (var j = i; j < i + 2; j += 1) {
                            item = list.group[1].product[i + j]

                            if (item) {
                                tpl+= '<div class="item" title="' + (item.sell_point || item.title) + '">'
                                tpl+= '<a target="_blank" href="/product?id=' + item.original_id + '">'
                                tpl+= '<p class="title">' + (item.sell_point || item.title) + '</p>'
                                tpl+= '<img src="' + resizeOssPicturePad(item.index_image, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            } else {
                                tpl+= '<div class="item">'
                                tpl+= '<a>'
                                tpl+= '<p class="title"></p>'
                                tpl+= '<img src="' + resizeOssPicturePad(defaultImg, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            }
                        }

                        tpl+= '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                tpl += '<div class="pull-left section-right" >'
                tpl += '<div class="section-content clearfix">'

                tpl += '<div class="pull-left left">'

                item = list.group[0]
                if (item && item.product[1]) {
                    tpl += '<a target="_blank" href="/product?id=' + item.product[1].original_id + '" title="' + (item.product[0].sell_point || item.product[0].title) + '"><img src="' + resizeOssPicturePad(item.product[1].index_image, 230, 450) + '" alt="' + item.product[1].title + '"></a>'
                } else {
                    tpl += '<a><img src="' + resizeOssPicturePad(defaultImg, 230, 450) + '"></a>'
                }

                tpl += '</div>'

                tpl += '<div class="pull-left right">'

                if (list.group[1]) {
                    for (var i = 2; i < 4; i += 1) {
                        tpl+= '<div class="items">'

                        for (var j = i; j < i + 2; j += 1) {
                            item = list.group[1].product[i + j]

                            if (item) {
                                tpl+= '<div class="item" title="' + (item.sell_point || item.title) + '">'
                                tpl+= '<a target="_blank" href="/product?id=' + item.original_id + '">'
                                tpl+= '<p class="title">' + (item.sell_point || item.title) + '</p>'
                                tpl+= '<img src="' + resizeOssPicturePad(item.index_image, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            } else {
                                tpl+= '<div class="item">'
                                tpl+= '<a>'
                                tpl+= '<p class="title"></p>'
                                tpl+= '<img src="' + resizeOssPicturePad(defaultImg, 130, 130) + '">'
                                tpl+= '</a>'
                                tpl+= '</div>'
                            }
                        }

                        tpl+= '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '</div>'
                tpl += '</div>'

                return tpl
            }
            function createMoreTpl(list) {
                var tpl = ''

                tpl += '<div class="section-6-title text-center">'
                tpl += '<span>——&nbsp;&nbsp;&nbsp;还没逛够&nbsp;&nbsp;&nbsp;——</span>'
                tpl += '<span></span>'
                tpl += '</div>'

                tpl += '<div class="section-content" id="J_more_pro">'

                for (var i = 0; i < 10; i += 1) {
                    var item = list.group[0].product[i]

                    if (item) {
                        tpl += '<div class="item" title="' + (item.sell_point || item.title) + '">'
                        tpl += '<a target="_blank" href="/product?id=' + item.original_id + '">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 210, 210) + '">'
                        tpl += '<p class="detail">' + (item.sell_point || item.title) + '</p>'
                        //tpl += '<p class="price">￥<span>' + item.min_price + '</span></p>'
                        tpl += '</a>'
                        tpl += '</div>'
                    } else {
                        tpl += '<div class="item">'
                        tpl += '<a>'
                        tpl += '<img src="' + resizeOssPicturePad(defaultImg, 210, 210) + '">'
                        tpl += '<p class="detail"></p>'
                        tpl += '<p class="price">￥<span></span></p>'
                        tpl += '</a>'
                        tpl += '</div>'
                    }
                }

                tpl += '</div>'

                return tpl
            }
        }());
        // 品牌
        ;(function () {
            getData(function (data) {
                if (data.length > 0) {
                    $('#jsBrandWrapper').html(createTpl(data))
                    interact()
                }
            })
            function createTpl(list) {
                var bnow = new Date().getTime();
                var bstart1 = new Date('2018/09/05 00:00:00').getTime();
                var bend1 = new Date('2018/09/08 23:59:59').getTime();
                var bstart2 = new Date('2018/09/09 00:00:00').getTime();
                var bend2 = new Date('2018/09/10 23:59:59').getTime();
                var bimg = ''
                if(bnow >= bstart1 && bnow <= bend2){
                    bimg = '/images/event20180909/jingxuanpinpai.jpg'
                }else{
                    bimg = '/images/18-6-29/index_new/brand.jpg'
                }
                var tpl = ''

                tpl += '<div class="select-title">'
                tpl += '<img class="static" src="' + bimg + '">'
                tpl += '<span></span>'
                tpl += '</div>'
                tpl += '<div id="select-brand-carousel" class="carousel slide" data-interval="false">'
                tpl += '<div class="carousel-inner" role="listbox">'

                for (var i = 0; i < list.length; i += 1) {
                    var item = list[i]

                    if (i % 8 === 0) {
                        tpl += '<div class="item">'
                        tpl += '<ul>'
                    }

                    tpl += '<li>'
                    tpl += '<a target="_blank" href="/shop?id=' + item.brand_id + '">'
                    tpl += '<img src="' + resizeOssPicturePad(item.logo_name, 100, 60) + '">'
                    tpl += '</a>'
                    tpl += '</li>'

                    if ((i - 7) % 8 === 0 || i === list.length - 1) {
                        tpl += '</ul>'
                        tpl += '</div>'
                    }
                }

                tpl += '</div>'
                tpl += '<a class="left carousel-control" href="#select-brand-carousel" role="button" data-slide="prev">'
                tpl += '<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>'
                tpl += '<span class="sr-only">Previous</span>'
                tpl += '</a>'
                tpl += '<a class="right carousel-control" href="#select-brand-carousel" role="button" data-slide="next">'
                tpl += '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>'
                tpl += '<span class="sr-only">Next</span>'
                tpl += '</a>'
                tpl += '</div>'

                return tpl
            }
            function interact() {
                $('#jsBrandWrapper .item').eq(0).addClass('active')
            }
            function getData(callback) {
                requestUrl('/index-brand/get-brands', 'GET', null, callback)
            }
        }());
        // 热搜关键字
        ;(function () {
            getData(function (data) {
                $('.search-content').append(createTpl(data))
            })
            function getData(callback) {
                apex.commonApi.getHotKeywords(callback)
            }
            function createTpl(list) {
                var tpl = ''

                tpl += '<ul class="hot-keyword-wrapper">'

                for (var i = 0; i < list.length; i += 1) {
                    var item = list[i]

                    tpl += '<li data-id="' + item.id + '">'
                    tpl += '<a data-id="' + item.id + '" href="/search/index?keyword=' + item.name + '">' + item.name + '</a>'
                    tpl += '</li>'
                }

                tpl += '</ul>'

                return tpl
            }
            function interact() {

            }
        }());
    })
    // 公共方法
    function resizeOssPicturePad(imgUrl, width, height) {
        return imgUrl + '?x-oss-process=image/resize,m_pad,h_' + height + ',w_' + width
    }
    ;(function() {
        var now = new Date().getTime();
        var start = new Date('2018/04/28 23:59:59').getTime();
        // var start2 = new Date('2018/04/01 00:00:00');
        var end = new Date('2018/05/01 23:59:59').getTime();
        if (start < now && end > now) {
            $('.national-first').eq(0).addClass('hidden');
            $('.national-first').eq(1).removeClass('hidden');
            $.each($('.national-first').eq(1).find('img'), function (i, v) {
                $(this).attr('src', $(this).data('src'));
            })
        }
    }())
    // 弹窗
    ;(function() {
        var now = new Date().getTime();
        var start1 = new Date('2018/09/05 00:00:00').getTime();
        var end1 = new Date('2018/09/08 23:59:59').getTime();
        var start2 = new Date('2018/09/09 00:00:00').getTime();
        var end2 = new Date('2018/09/10 23:59:59').getTime();
        var hoverFlag = 0;
        var mouseFlag = 0;
        var t;

        var start3 = new Date('2018/10/26 00:00:00').getTime();
        var end3 = new Date('2018/10/27 23:59:59').getTime();

        if(now >= start3 && now <= end3){
            $('.index-activity-tankuang-wrap').css('display','block');
            $('.index-activity-tankuang-wrap .index-activity-tankuang').css({'background':'url(/images/20181015/tu.png) no-repeat center/cover'});
            $('.index-activity-tankuang-wrap .index-activity-tankuang .activity-close').css({'background':'url(/images/20181015/an.png) no-repeat center/cover'});

            $('.index-activity-tankuang-wrap .index-activity-tankuang .activity-close').click(function(event){
                event.stopPropagation();
                $('.index-activity-tankuang-wrap').css('display','none');
            })
            $('.index-activity-tankuang-wrap .index-activity-tankuang').on('click',function(){
                window.location.href = '/temp/betabet/q'
            })

        }else{
            $('.index-activity-tankuang-wrap').css('display','none');
        }


        var start4 = new Date('2018/09/17 00:00:00').getTime();
        var end4 = new Date('2018/09/18 23:59:59').getTime();

        if(now >= start4 && now <= end4){
            $('.index-activity-popup-wrap').css('display','block');
            $('.index-activity-popup-wrap .index-activity-popup').css({'background':'url(/images/180917/1.png) no-repeat center/cover'});
            $('.index-activity-popup-wrap .rush-buy').css({'background':'url(/images/180917/3.png)center no-repeat'});
            $('.index-activity-popup-wrap .index-activity-popup .activity-popup-close').css({'background':'url(/images/180917/2.png) no-repeat center/cover'});

            $('.index-activity-popup-wrap .index-activity-popup .activity-popup-close').click(function(event){
                event.stopPropagation();
                $('.index-activity-popup-wrap').css('display','none');
            })
            $('.index-activity-popup-wrap .rush-buy').on('click',function(){
                window.location.href = '/product?id=2359'
            })

        }else{
            $('.index-activity-popup-wrap').css('display','none');
        }


        var start5 = new Date('2018/09/19 00:00:00').getTime();
        var end5 = new Date('2018/10/07 23:59:59').getTime();
        var start6 = new Date('2018/09/19 00:00:00').getTime();
        var end6 = new Date('2018/09/19 23:59:59').getTime();
        var start7 = new Date('2018/09/22 00:00:00').getTime();
        var end7 = new Date('2018/09/22 23:59:59').getTime();
        var start8 = new Date('2018/09/25 00:00:00').getTime();
        var end8 = new Date('2018/09/25 23:59:59').getTime();
        var start9 = new Date('2018/09/28 00:00:00').getTime();
        var end9 = new Date('2018/09/28 23:59:59').getTime();

        if(now >= start5 && now <= end5){

            if(now >= start6 && now <= end6){
                $('.new-activities-wrap').css('display','block');
                $('.new-activities-wrap .new-activities').css({'background':'url(/images/180919/miao.png) no-repeat center/cover'});
                $('.new-activities-wrap .new-activities-close').css({'background':'url(/images/180919/2.png) no-repeat center/cover'});

                $('.new-activities-wrap .new-activities-close').click(function(event){
                    event.stopPropagation();
                    $('.new-activities-wrap').css('display','none');
                })
                $('.new-activities-wrap new-activities').on('click',function(){
                    window.location.href = '/temp/betabet/n'
                })

            }else if(now >= start7 && now <= end7){
                $('.new-activities-wrap').css('display','block');
                $('.new-activities-wrap .new-activities').css({'background':'url(/images/180919/miao.png) no-repeat center/cover'});
                $('.new-activities-wrap .new-activities-close').css({'background':'url(/images/180919/2.png) no-repeat center/cover'});

                $('.new-activities-wrap .new-activities-close').click(function(event){
                    event.stopPropagation();
                    $('.new-activities-wrap').css('display','none');
                })
                $('.new-activities-wrap new-activities').on('click',function(){
                    window.location.href = '/temp/betabet/n'
                })
            }else if(now >= start8 && now <= end8){
                $('.new-activities-wrap').css('display','block');
                $('.new-activities-wrap .new-activities').css({'background':'url(/images/180919/miao.png) no-repeat center/cover'});
                $('.new-activities-wrap .new-activities-close').css({'background':'url(/images/180919/2.png) no-repeat center/cover'});

                $('.new-activities-wrap .new-activities-close').click(function(event){
                    event.stopPropagation();
                    $('.new-activities-wrap').css('display','none');
                })
                $('.new-activities-wrap new-activities').on('click',function(){
                    window.location.href = '/temp/betabet/n'
                })
            }else if(now >= start9 && now <= end9){
                $('.new-activities-wrap').css('display','block');
                $('.new-activities-wrap .new-activities').css({'background':'url(/images/180919/miao.png) no-repeat center/cover'});
                $('.new-activities-wrap .new-activities-close').css({'background':'url(/images/180919/2.png) no-repeat center/cover'});

                $('.new-activities-wrap .new-activities-close').click(function(event){
                    event.stopPropagation();
                    $('.new-activities-wrap').css('display','none');
                })
                $('.new-activities-wrap new-activities').on('click',function(){
                    window.location.href = '/temp/betabet/n'
                })
            }else{
                $('.new-activities-wrap').css('display','block');
                $('.new-activities-wrap .new-activities').css({'background':'url(/images/180919/pc.png) no-repeat center/cover'});
                $('.new-activities-wrap .new-activities-close').css({'background':'url(/images/180919/2.png) no-repeat center/cover'});

                $('.new-activities-wrap .new-activities-close').click(function(event){
                    event.stopPropagation();
                    $('.new-activities-wrap').css('display','none');
                })
                $('.new-activities-wrap new-activities').on('click',function(){
                    window.location.href = '/temp/betabet/n'
                })
            }
        }else{
            $('.new-activities-wrap').css('display','none');
        }

        var start10 = new Date('2018/10/28 00:00:00').getTime();
        var end10 = new Date('2018/10/31 23:59:59').getTime();

        if(now >= start10 && now <= end10){
            $('.index-activity-1025-wrap').css('display','block');
            $('.index-activity-1025-wrap .index-activity-1025').css({'background':'url(/images/181025/bg.png) no-repeat center/cover'});
            $('.index-activity-1025-wrap .index-activity-1025 .activity-close').css({'background':'url(/images/180911/an.png) no-repeat center/cover'});

            $('.index-activity-1025-wrap .index-activity-1025 .activity-close').click(function(event){
                event.stopPropagation();
                $('.index-activity-1025-wrap').css('display','none');
            })
            $('.index-activity-1025-wrap .index-activity-1025').on('click',function(){
                window.location.href = '/temp/betabet/u'
            })

        }else{
            $('.index-activity-1025-wrap').css('display','none');
        }


        function bannerSilder(parmas){
            function dropdown(){
                $(parmas.obj).find('img').eq(4).stop().animate({'top':'-80px'},parmas.dropSpeed)
                $('.index-top-nav').stop().animate({'margin-top':'720px'},250)
                $('.global-top-nav').stop().animate({'margin-top':'800px'},parmas.dropSpeed)
                $('#closeSilder').stop().animate({'margin-top':'0'},parmas.dropSpeed)
                $('#logoCon').stop().animate({'margin-top':'0'},parmas.dropSpeed)
                $(parmas.obj).find('img').eq(2).stop().animate({'margin-top':'720px'},250)
                $(parmas.obj).find('img').eq(3).stop().animate({'margin-top':'720px'},250)
            }
            function dropup(){
                $(parmas.obj).find('img').eq(4).stop().animate({'top':'-880px'},parmas.dropSpeed)
                $('.index-top-nav').stop().animate({'margin-top':'0'},250)
                $('.global-top-nav').stop().animate({'margin-top':'0'},parmas.dropSpeed)
                $('#closeSilder').stop().animate({'margin-top':'-800px'},parmas.dropSpeed)
                $('#logoCon').stop().animate({'margin-top':'-800px'},parmas.dropSpeed)
                $(parmas.obj).find('img').eq(2).stop().animate({'margin-top':'0'},250)
                $(parmas.obj).find('img').eq(3).stop().animate({'margin-top':'0'},250)
            }
            if(localStorage.getItem(parmas.local) == 0 || localStorage.getItem(parmas.local) == null || localStorage.getItem(parmas.local) == undefined){
                localStorage.setItem(parmas.local,1)
                setTimeout(function(){
                    dropdown()
                },parmas.timeDelay)
            }
            $(parmas.obj).find('img').eq(0).click(function(){
                if(hoverFlag == 0){
                    hoverFlag = 1
                    dropdown()
                }else{
                    return false
                }
            })
            $('#closeSilder').click(function(){
                hoverFlag = 0
                dropup()
            });
            $(window).mousemove(function(e) {
                if(mouseFlag == 0){
                    var xx = e.originalEvent.x ||e.originalEvent.layerX || 0;
                    var yy = e.originalEvent.y ||e.originalEvent.layerY || 0;
                    if(yy > 810){
                        hoverFlag = 0
                        mouseFlag = 1
                        setTimeout(function(){
                            mouseFlag = 0
                        },1500)
                        dropup()
                    }
                }
            })
            $(window).scroll(function(){
                if($(window).scrollTop() >= 150){
                    hoverFlag = 0
                    dropup()
                }
            })
            if(parmas.callback){
                parmas.callback()
            }
        }

        if(now >= start1 && now <= end1){
            $('#topBanner').addClass('hidden');
            $('#event99').addClass('hidden');
            $('#hot99').removeClass('hidden');
            $('body').css('background','none');
            bannerSilder({
                obj : '#hot99',
                local : 'hot',
                dropSpeed : 300,
                timeDelay : 500
            });
        }else if(now >= start2 && now <= end2){
            $('#topBanner').addClass('hidden');
            $('#event99').removeClass('hidden');
            $('#hot99').addClass('hidden');
            $('body').css('background','none');
            bannerSilder({
                obj : '#event99',
                local : 'event',
                dropSpeed : 300,
                timeDelay : 500,
                callback : function(){
                    $('#event99').find('img').eq(4).click(function(){
                        window.location.href = '/temp/betabet/j'
                    })
                }
            });
        }else{
            $('#topBanner').removeClass('hidden');
            $('#event99').addClass('hidden');
            $('#hot99').addClass('hidden');
        }

        // var _stage = {
        //     stage2: {
        //         url: '/shop?id=55',
        //         src: '/images/modal/615/6.180-12.png'
        //     },
        //     stage3: {
        //         url: '/shop?id=54',
        //         src: '/images/modal/615/6.18pc12-24.png'
        //     },
        //     stage4: {
        //         url: '/shop?id=8',
        //         src: '/images/modal/615/PC_619-8(0-12.png'
        //     },
        //     stage5: {
        //         url: '/shop?id=24',
        //         src: '/images/modal/615/PC_619-24(12-24).png'
        //     },
        //     stage6: {
        //         url: '/shop?id=9',
        //         src: '/images/modal/615/6.20pc0-12.png'
        //     },
        //     stage7: {
        //         url: '/shop?id=97',
        //         src: '/images/modal/615/6.20pc12-18.png'
        //     },
        //     stage8: {
        //         url: '/shop?id=33',
        //         src: '/images/modal/615/6.20pc18-24.png'
        //     },
        //     stage9: {
        //         url: '/shop?id=59',
        //         src: '/images/modal/615/6.21PC0-12.png'
        //     },
        //     stage10: {
        //         url: '/shop?id=75',
        //         src: '/images/modal/615/6.21WEP12-24.png'
        //     },
        //     stage11: {
        //         url: '/product?id=1773',
        //         src: '/images/modal/615/PC_622-17730-12.png'
        //     },
        //     stage12: {
        //         url: '/product?id=1209',
        //         src: '/images/modal/615/PC_622-1209(12-18.png'
        //     },
        //     stage13: {
        //         url: '/product?id=1878',
        //         src: '/images/modal/615/PC_622-1878(18-24).png'
        //     },
        //     stage14: {
        //         url: '/temp/alphabet/w',
        //         src: '/images/modal/615/6.23-6.25PC.png'
        //     },
        //     stage15: {
        //         url: '/product?id=1903',
        //         src: '/images/modal/615/6.26pc0-12.png'
        //     },
        //     stage16: {
        //         url: '/product?id=1672',
        //         src: '/images/modal/615/6.26PC12-24.png'
        //     },
        //     stage17: {
        //         url: '/temp/betabet/a',
        //         src: '/images/modal/629/modal.png'
        //     }
        // }
        // function changeModal(item) {
        //     $('#J_modal_main_link').attr('href', _stage[item].url)
        //     $('#J_modal_main_img').attr('src', _stage[item].src)
        // }
        // var stage1 = new Date('2018/06/16 00:00:00').getTime();
        // var stage2 = new Date('2018/06/18 00:00:00').getTime();
        // var stage3 = new Date('2018/06/18 12:00:00').getTime();
        // var stage4 = new Date('2018/06/19 00:00:00').getTime();
        // var stage5 = new Date('2018/06/19 12:00:00').getTime();
        // var stage6 = new Date('2018/06/20 00:00:00').getTime();
        // var stage7 = new Date('2018/06/20 12:00:00').getTime();
        // var stage8 = new Date('2018/06/20 18:00:00').getTime();
        // var stage9 = new Date('2018/06/21 00:00:00').getTime();
        // var stage10 = new Date('2018/06/21 12:00:00').getTime();
        // var stage11 = new Date('2018/06/22 00:00:00').getTime();
        // var stage12 = new Date('2018/06/22 12:00:00').getTime();
        // var stage13 = new Date('2018/06/22 18:00:00').getTime();
        // var stage14 = new Date('2018/06/23 00:00:00').getTime();
        // var stage15 = new Date('2018/06/26 00:00:00').getTime();
        // var stage16 = new Date('2018/06/26 12:00:00').getTime();
        // var stage17 = new Date('2018/06/27 00:00:00').getTime();
        // var end = new Date('2018/06/29 23:59:59').getTime();
        // if ((now < stage1 || now > stage2) && now < end) {
        //     $('#modalFreeLunch').modal('show')
        // }
        // if (now > stage2 && now < stage3) {
        //     changeModal('stage2')
        // }
        // if (now > stage3 && now < stage4) {
        //     changeModal('stage3')
        // }
        // if (now > stage4 && now < stage5) {
        //     changeModal('stage4')
        // }
        // if (now > stage5 && now < stage6) {
        //     changeModal('stage5')
        // }
        // if (now > stage6 && now < stage7) {
        //     changeModal('stage6')
        // }
        // if (now > stage7 && now < stage8) {
        //     changeModal('stage7')
        // }
        // if (now > stage8 && now < stage9) {
        //     changeModal('stage8')
        // }
        // if (now > stage9 && now < stage10) {
        //     changeModal('stage9')
        // }
        // if (now > stage10 && now < stage11) {
        //     changeModal('stage10')
        // }
        // if (now > stage11 && now < stage12) {
        //     changeModal('stage11')
        // }
        // if (now > stage12 && now < stage13) {
        //     changeModal('stage12')
        // }
        // if (now > stage13 && now < stage14) {
        //     changeModal('stage13')
        // }
        // if (now > stage14 && now < stage15) {
        //     changeModal('stage14')
        // }
        // if (now > stage15 && now < stage16) {
        //     changeModal('stage15')
        // }
        // if (now > stage16 && now < stage17) {
        //     changeModal('stage16')
        // }
        // if (now > stage17 && now < end) {
        //     changeModal('stage17')
        // }
    }())

})
