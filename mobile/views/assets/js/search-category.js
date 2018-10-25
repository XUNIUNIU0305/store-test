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
    // 分类
    ;(function () {
        apex.commonApi.getGroup(function (list) {
            addTempCategoryItem(list)
            interact()
        })
        function addTempCategoryItem(data) {
            var temp = ''
            var temp_list = ''

            for (var k = 0; k< data.length; k++){
                var  build = data[k]

                temp += '<li class="sort-lis">'
                temp += '<a href="#' + build.id + '" data-link-section>'
                temp += '<span class="y-line hidden"></span>'
                temp += '<span class="sort-describe">' + build.name + '</span>'
                temp += '</a>'
                temp += '</li>'
                temp_list += addTempCategoryList(build.item,{
                    id: build.id,
                    name: build.name
                })
            }

            $('.category-list').html(temp)
            $('.category-detail').html(temp_list)
        }
        function addTempCategoryList(data, preLoad) {
            var temp = ''

            temp += '<li class="category-goods" id="' + preLoad.id + '">'
            temp += '<p class="title">'
            temp += '<span class="xl-line"></span>'
            temp += preLoad.name
            temp += '<span class="xr-line"></span>'
            temp += '</p>'
            temp += '<div class="goods-contain">'

            for (var k = 0; k < data.length; k++){
                var  build = data[k]

                if (build.cate_id > 0) {
                    temp += '<a class="goods-detail" href="/search/category-detail?end_category_id=' + build.cate_id + '&value=' + build.name + '" data-id="' + build.cate_id + '">'
                } else {
                    temp += '<a class="goods-detail">'
                }

                temp += '<img class="goods-img" src="'+ resizeOssPicturePad(build.img, 100, 100) + '">'
                temp += '<p class="goods-describe">'
                temp += build.name
                temp += '</p>'
                temp += '</a>'
            }

            temp += '</div>'
            temp += '</li>'

            return temp
        }
        function interact() {
            var raf_scroll

            $('[data-link-section]').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                //显示二级分类

                showItems($(this));
                scrollTo(Math.floor($($(this).attr('href'))[0].offsetTop - 45));
                // touch to stop
                $('main.container').one('touchstart', function(){
                    raf_scroll && cancelAnimationFrame(raf_scroll);
                })
            })
            // scroll animation
            function scrollTo(topVal) {
                var scrollArea = $('main.container')[0];
                var currentTop = scrollArea.scrollTop;
                var maxTop = $('main.container .category-detail').height() - $('main.container').height();
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
            //显示二级分类
            function showItems($that){
                $that.find('.sort-describe').addClass('active');
                $that.find('.y-line').removeClass('hidden');
                $that.find('.sort-items').removeClass('hidden');
                var $a_arr = $that.parents('.sort-lis').siblings();
                $a_arr.find('.sort-describe').removeClass('active');
                $a_arr.find('.y-line').addClass('hidden');
                $a_arr.find('.sort-items').addClass('hidden');
            }
            //内容滚动同步展示左边二级列表（未完成）
            $('main.container').on('touch scroll',function() {
               var scroll_des = $(this).scrollTop();
            });
        }
    }());
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

    //获取焦点时显示历史页面
    $('.search-ipt').focus(function() {
        $('.search-history').removeClass('hidden');
        $('.category-list').addClass('hidden');
        $('.search-cancel').removeClass('hidden');
        $('.category-contain').addClass('hidden');
        $('.search-head').addClass('chose');
        getHistory();
    });
      //点击取消显示搜索结果
    $('.search-cancel').click(function() {
        $('.search-history').addClass('hidden');
        $('.category-list').removeClass('hidden');
        $('.search-cancel').addClass('hidden');
        $('.category-contain').removeClass('hidden');
        $('.search-head').removeClass('chose');
    });

    /*历史纪录开始*/
    $('.hot-contain').on('click','span',function() {
        $('.search-ipt').val($(this).text());
        window.location.href = "/search/index?keyword=" + $(this).text();
    })
     //点击历史记录搜索
    $('.uls-list').on('click','li',function() {
        $('.search-history').addClass('hidden');
        $('.category-list').removeClass('hidden');
        $('.category-contain').removeClass('hidden');
        $('.search-cancel').addClass('hidden');
        $('.search-ipt').val($(this).text());
        window.location.href = "/search/index?keyword=" + $(this).text();
    });

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
    $('.search-ipt').on('keydown', function(e) {
        if (e.keyCode === 13) {
            $('.search-img').click()
        }
    })


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

    function resizeOssPicturePad(imgUrl, width, height) {
        return imgUrl + '?x-oss-process=image/resize,m_pad,h_' + height + ',w_' + width
    }

    //清空历史记录
    $('.hist-cancel').click(function(e) {
        e.preventDefault()

        var histStr = history.getItem('histStr'); //.split('&')
        if(histStr == null){
            return;
        } else {
            history.removeItem('histStr');
        }

        getHistory();
    });
    /*历史纪录结束*/
    //页面点击跳转
    $('.goods-contain').on('click','a',function() {
        window.location.href = "/search/category-detail?end_category_id=" + $(this).attr('data-id') + "&value=" + $(this).find('.goods-describe').text();
    });
})
