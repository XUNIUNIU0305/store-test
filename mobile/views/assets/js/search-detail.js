// fallbacks
(function() {
    var lastTime = 0;
    var vendors = ['webkit', 'moz'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame) {
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16.7 - (currTime - lastTime));
            var id = window.setTimeout(function() {
                callback(currTime + timeToCall);
            }, timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
    }
    if (!window.cancelAnimationFrame) {
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
    }
}());

$(function(){
    jdy.scroll.scrollFix($('main.container')[0]);

    // 热搜关键字
    ;(function () {
        getData(function (data) {
            $('.hot-contain').html(createTpl(data))
            interact()
        })
        function getData(callback) {
            apex.commonApi.getHotKeywords(callback)
        }
        function createTpl(list) {
            var tpl = ''

            for (var i = 0; i < list.length; i += 1) {
                var item = list[i]

                tpl += '<span>' + item.name + '</span>'
            }

            return tpl
        }
        function interact() {
            $('.hot-contain').on('click','span',function() {
                setData(this.innerHTML);
                window.location.href = "/search/index?keyword=" + $(this).text();
            })
        }
    }());

    // 返回最上层
    $('[data-link-section]').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        scrollTo(Math.floor($($(this).attr('href'))[0].offsetTop - 45));
        // touch to stop
        $('main.container').one('touchstart', function(){
            raf_scroll && cancelAnimationFrame(raf_scroll);
        })
    })
    var raf_scroll;

    // scroll animation
    function scrollTo(topVal) {
        var scrollArea = $('main.container')[0];
        var currentTop = scrollArea.scrollTop;
        var maxTop = $('main.container div').eq(0).height() - $('main.container').height();
        if(maxTop < topVal) topVal = maxTop;

        switch(true) {
            case topVal > currentTop:
                currentTop += 30;
                if (currentTop >= topVal) currentTop = topVal;
                scrollArea.scrollTop = currentTop;
                raf_scroll = requestAnimationFrame(function(){
                    scrollTo(topVal);
                });
                break;
            case topVal < currentTop:
                currentTop -= 30;
                if (currentTop <= topVal) currentTop = topVal;
                scrollArea.scrollTop = currentTop;
                raf_scroll = requestAnimationFrame(function(){
                    scrollTo(topVal);
                });
                break;
            default:
                raf_scroll && cancelAnimationFrame(raf_scroll);
                break;
        }
    }
    /*---------------开始----------*/
    //推荐商品
    var g = {
        id: [1523, 1357, 1617, 1271]
    }

    //推荐商品数据填充
    requestUrl('/product-recommend/goods','GET',g,function(res) {
        var recommend_tpl = $('#J_recommend_list').html();
        var recommend_html = juicer(recommend_tpl,res);
        $('#J_recommend_contain').html(recommend_html);
    })
    //热门搜索
    $('.hot-contain').on('click','span',function() {
        $('.search-ipt').val($(this).text());
        window.location.href = "/search/index?keyword=" + $(this).text();
    })
    //下拉分类
 /*   var mark = false;
    function showMenu(type) {
        if( type === 1) {
            $('.sort-words').css('opacity','1');
            $('.sort-more-detail').addClass('hidden');
            $(this).attr('src','/images/custom_search/icon_30_more.png');
            $('.close-mark').addClass('hidden');
        } else {
            $('.sort-words').css('opacity','0');
            $('.sort-more-detail').removeClass('hidden');
            $(this).attr('src','/images/custom_search/icon_30_up_more.png');
            $('.close-mark').removeClass('hidden');
        }
    }*/
   /* $('.more-sort').click(function(){
        if(mark) {
            showMenu(1);
        } else {
            showMenu(2);
        }
        mark = !mark;
    });*/

    //点击遮罩上拉或者滑动
   /* $('.close-mark').click(function() {
        showMenu(1);
        mark = !mark;
    });
    $('main.container').on('touch scroll',function() {
        showMenu(1);
        mark = !mark;
    });*/

    //获取焦点时显示历史页面
    $('.search-ipt').focus(function() {
        getHistory();
        $('.search-history').removeClass('hidden');
        $('.result-sort-contain').addClass('hidden');
        $('.search-cancel').removeClass('hidden');
        $('.result-detail-contail').addClass('hidden');
        $('.result-sort-contain').addClass('hidden');
    });
    /*历史纪录开始*/
    var history = window.localStorage;

    // 获取localStorage;
    function getHistory () {
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
    }
    //添加事件、点击搜索
    $('.search-img').on('click',function() {
        var ipt_val = $('.search-ipt').val();
        if( ipt_val === '') {
            ipt_val = $('.search-ipt').data('default');
            $('.search-ipt').val(ipt_val);
        }
        setData(ipt_val);
        window.location.href = "/search/index?keyword=" + ipt_val;
        $('.search-cancel').addClass('hidden');
        $('.search-history').addClass('hidden');
        $('.result-sort-contain').removeClass('hidden');
        $('.result-detail-contail').removeClass('hidden');
        $('.recommend-goods').removeClass('hidden');
    });

    //添加到LocalStorage
    function setData(data) {
        var histArr = [];
        var histStr = history.getItem('histStr'); //.split('&')
        if(histStr == null){
            history.setItem('histStr',data);
        } else {
            histArr = histStr.split('&');
            var data_str = sortData(data,histArr).join("&");
            history.setItem('histStr',data_str);
        }
    }

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

    //清空历史记录
    $('.hist-cancel').click(function() {
        var histStr = history.getItem('histStr'); //.split('&')
        if(histStr == null){
            return;
        } else {
            history.removeItem('histStr');
        }
        getHistory();
    });
    //点击历史记录搜索
    $('.uls-list').on('click','li',function() {
        $('.search-history').addClass('hidden');
        $('.result-sort-contain').removeClass('hidden');
        $('.result-detail-contail').removeClass('hidden');
        $('.search-cancel').addClass('hidden');
        g.keyword = $(this).text();
        $('.search-ipt').val($(this).text());
        window.location.href = "/search/index?keyword=" + $(this).text();
    });
    /*历史纪录结束*/

    //点击取消显示搜索结果
    $('.search-cancel').click(function() {
        $('.search-history').addClass('hidden');
        $('.result-sort-contain').removeClass('hidden');
        $('.search-cancel').addClass('hidden');
        $('.result-detail-contail').removeClass('hidden');
        $('.recommend-goods').removeClass('hidden');
    });

    cData = {
        end_category_id : url('?end_category_id'),
        current_page : 1,
        page_size: 6,
        option_id:{},
        order_by: {}
    }
    var data_len = 0; //存放数据长度
    function categoryData(data) {
        requestUrl('/search/category-goods','GET',data,function(res){
            //判断数据
            if(res.codes.length === 0 ) {
                errorShow(1);
            }
            data_len = res.total_count;
            pageData(cData.size,cData.size,res.codes);
            $('.recommend-goods').removeClass('hidden');
        },function(){
            errorShow(2);
        })
    }
    categoryData(cData);
    //分页
    function pageData(page,size,total) {
        if( total == '') {
            return false;
        }
        var goods_tpl = $('#J_goods_list').html();
        var goods_html = juicer(goods_tpl,total);
        $("#J_goods_contain").append(goods_html);
    }
    //页面滑动拉取数据
    var counts=1;//计算拉取次数
    $('main.container').on('touch scroll',function() {
        var mark = $('#J_goods_contain').hasClass('hidden');
        if (!mark) {
            //数据长度
            var max_len = Math.floor(data_len/cData.page_size) + ((data_len % cData.page_size) > 0 ? 1 : 0);
            //每页数据
            if(counts > max_len-1){
                return
            }
            var des = $('#J_goods_contain').height() - $('main.container').height() - 1;
            var scroll_des = $(this).scrollTop();
            if( scroll_des >= des ) {
                counts++;
                cData.current_page =  counts;
                categoryData(cData);
            }
        }
    });

    //错误显示
    function errorShow(type) {
        $('.recommend-goods').removeClass('hidden');
        if( type === 1) {
        //无商品有分类
           $('.error1').removeClass('hidden');
           $('.error1').find('span').text(url('?value'));
           $('.error2').addClass('hidden');
           $('.search-result').addClass('hidden');
        } else {
        //全无
            $('.error2').find('span').text(url('?value'));
            $('.error1').addClass('hidden');
            $('.error2').removeClass('hidden');
            $('.search-result').addClass('hidden');

            $('.result-sort-contain').addClass('hidden');
        }
    }


    //销量和价格排序
    $('.J_sort_btn').on('click', function() {
        cData.order_by = {};
        var type = $(this).data('type');
        $('img.up-img').attr('src', "/images/category_details/up_grey.jpg");
        $('img.down-img').attr('src', "/images/category_details/down_grey.jpg");
        if ($(this).hasClass('active')) {
            if ($(this).hasClass('top')) {
                $(this).removeClass('top').find('.up-img').attr('src',  "/images/category_details/up_grey.jpg")
                .siblings('.down-img').attr('src', '/images/category_details/down_chosen.jpg');
                cData.order_by[type] = 'desc';
            } else {
                $(this).addClass('top').find('.up-img').attr('src',  "/images/category_details/up_chosen.jpg")
                .siblings('.down-img').attr('src', '/images/category_details/down_grey.jpg');
                cData.order_by[type] = 'asc'
            }
        } else {
            $('.J_sort_btn').removeClass('active');
            $(this).addClass('active');
            $(this).addClass('top').find('.up-img').attr('src',  "/images/category_details/up_chosen.jpg")
            .siblings('.down-img').attr('src', '/images/category_details/down_grey.jpg');
            cData.order_by[type] = 'asc'
        }
        cData.current_page = 1;
        $('#J_goods_contain').html('');
        categoryData(cData);
    })
});
