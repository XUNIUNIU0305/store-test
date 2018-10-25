$(function() {

    var shop = {
        id: url('?id'),
        carouselTpl: $('#J_carousel_tpl').html(),
        selectedTpl: $('#J_selected_tpl').html(),
        allProTpl: $('#J_allpro_tpl').html(),
        getMain: function(params) {
            // 获取主图和轮播图
            requestUrl('/shop/get-list', 'GET', params, function(data) {
                if (data.big.id) {
                    $('.brand-banner img').attr('src', data.big.image_path)
                    $('.brand-banner').removeClass('hidden')                    
                } else {
                    $('.shop-nav-search-box').addClass('black')
                    $('.brand-page-container').css('marginTop', '47px')
                }
                if (data.carousel.length > 0) {
                    $('#shop-slide').html(juicer(shop.carouselTpl, data.carousel))
                    $('#shop-slide').removeClass('hidden')
                    shop.runCarousel()
                    $('.brand-page-container').resize()
                }
            })
        },
        getSelected: function(params) {
            // 获取甄选商品
            requestUrl('/shop/get-list-product', 'GET', params, function(data) {
                if (data.length > 0) {
                    $('.recommend-pro').removeClass('hidden')
                    $('#J_selected_box').html(juicer(shop.selectedTpl, data))
                    $('.brand-page-container').resize()
                }
            })  
        },
        getAllPro: function(params) {
            // 获取店铺所有商品
            var _defult = {
                supply_user_id: shop.id,
                current_page: 1,
                page_size: 99,
                condition: 'sales',
                order: 0
            }
            $.extend(_defult, params)
            requestUrl('/shop/get-goods-by-condition', 'GET', _defult, function(data) {
                $('#J_allpro_box').html(juicer(shop.allProTpl, data.products))
                $('.brand-page-container').resize()
            })  
        },
        runCarousel: function() {
            // Carousel 
            $('#shop-slide').swipeSlide({
                continuousScroll: true,
                speed: 5000,
                autoSwipe: 3000,
                transitionType: 'cubic-bezier(0.22, 0.69, 0.72, 0.88)',
                callback: function (i) {
                    $('.dot').children().eq(i).addClass('cur').siblings().removeClass('cur');
                }
            });
        },
        getHistory: function() {
            // 获取搜索历史
            var history = window.localStorage;
            var s = '';
            var histStr = history.getItem('histStr');
            if(histStr == null) {
                $('.uls-list').html('');
                return false;
            }
            var histArr = histStr.split('&');
            if(histArr.length === 0){return false};
            for (var i = 0; i < 5; i++) {
                if(histArr[i]){
                    s += '<li>'+ histArr[i]+'</li>';
                }
            }
            if( s != '') {
                $('.uls-list').html(s);
            }
        },
        setHistory: function(data) {
            // 设置搜索历史
            var history = window.localStorage;
            //数据倒序去重
            function sortData(str,arr){
                arr.reverse().push(str);
                var arrb = arr.reverse();
                return unique(arrb);
            }
            //去重
            function unique(array){
                var n = [];
                for(var i = 0; i < array.length; i++){
                if (n.indexOf(array[i]) == -1) n.push(array[i]);
                }
                return n;
            }
            var histArr = [];
            var histStr = history.getItem('histStr');
            if(histStr == null){
                history.setItem('histStr',data);
            } else {
                histArr = histStr.split('&');
                var data_str = sortData(data,histArr).join("&");
                history.setItem('histStr',data_str);
            }
        },
        init: function() {
            this.getMain({
                id: shop.id
            })
            this.getSelected({
                id: shop.id
            })
            this.getAllPro()
            // 滚动监听
            $('main.container').on('scroll', function() {
                var top = $(this).scrollTop()
                if (top > 1) {
                    $('.shop-nav-search-box').addClass('black')
                    $('.brand-page-container').css('marginTop', '47px')
                } else {
                    if (!$('.brand-banner').hasClass('hidden')) {
                        $('.shop-nav-search-box').removeClass('black')
                        $('.brand-page-container').css('marginTop', '0')
                    } else {
                        $('.shop-nav-search-box').addClass('black')
                    }
                }
                if (top > 300) {
                    $('.brand-page-back-top').removeClass('hidden')
                } else {
                    $('.brand-page-back-top').addClass('hidden')
                }
            })
            // 商品排序
            $('.sort-list span').on('click', function() {
                var type = $(this).data('type');
                if ($(this).hasClass('active')) {
                    if ($(this).hasClass('down')) {
                        $(this).removeClass('down')
                        shop.getAllPro({
                            condition: type,
                            order: 1
                        })
                    } else {
                        $(this).addClass('down')
                        shop.getAllPro({
                            condition: type,
                            order: 0
                        })
                    }
                } else {
                    $(this).addClass('active down').siblings().removeClass('active')
                    shop.getAllPro({
                        condition: type,
                        order: 0
                    })
                }
            })
            // 搜索弹窗
            $('.shop-nav-search-box input').focus(function() {
                $('.shop-search-history').removeClass('hidden');
                $('.shop-nav-search-box').addClass('search');
                $('main.container').css('overflow-y', 'hidden');
                shop.getHistory();
            });
            // 关闭弹窗
            $('.shop-nav-search-box [data-type="close"]').on('click', function() {
                $('.shop-nav-search-box input').val('');
                $('.shop-nav-search-box').removeClass('search');
                $('.shop-search-history').addClass('hidden');
                $('main.container').css('overflow-y', 'auto');
            })
            // 搜索
            $('.shop-nav-search-box [data-type="search"]').on('click', function() {
                var val = $('.shop-nav-search-box input').val();
                if (val !== '') {
                    shop.setHistory(val);
                    window.location.href = "/search/index?keyword=" + val;
                }
            })
            $('.shop-nav-search-box input').on('keydown', function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $('[data-type="search"]').click()
                }
            })
            //点击历史记录搜索
            $('.uls-list').on('click','li',function() {
                window.location.href = "/search/index?keyword=" + $(this).text();
            });
            // 返回顶部
            $('.brand-page-back-top').on('click', function() {
                $('main.container').scrollTop('0')
            })
        }
    }
    shop.init()

    // 兼容IOS bug
	$('.brand-page-container').on('touchstart', function() {
        $('main.container').css('overflow-y', 'auto');
        $(this).resize()
	})
})