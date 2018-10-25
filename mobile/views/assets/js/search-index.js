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

    $('.go-top').on('click',function(){
        $('.container').scrollTop(0);
    });

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

    //推荐商品
    var g = {
        id: [1523, 1357, 1617, 1271]
    }
    //初始请求数据
    var data = {
        keyword: url('?keyword')
    }
    //分页数据
    var gsdata = {
        data:[],
        page:1,
        size:10,
        goods_tpl:$('#J_goods_list').html()
    }
    //推荐商品数据填充
    requestUrl('/product-recommend/goods','GET',g,function(res) {
        var recommend_tpl = $('#J_recommend_list').html();
        var recommend_html = juicer(recommend_tpl,res);
        $('#J_recommend_contain').html(recommend_html);
    })
    //下拉分类
    var mark = false;
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
    }

    $('.more-sort').click(function(){
        if(mark) {
            showMenu(1);
        } else {
            showMenu(2);
        }
        mark = !mark;
    });

    //分类点击跳转
    $('.result-sort').on('click','span',function() {
        window.location.href = "/search/category-detail?end_category_id=" + $(this).attr('data-id') + '&value=' + $(this).text();
    });
    //点击遮罩上拉或者滑动
    $('.close-mark').click(function() {
        showMenu(1);
        mark = !mark;
    });
    $('main.container').on('touch scroll',function() {
        showMenu(1);
        mark = !mark;
    });

    //初始搜索框内容
    $('.search-ipt').val(url('?keyword'));
	//获取焦点时显示历史页面
	$('.search-ipt').focus(function() {
		$('.search-history').removeClass('hidden');
		$('.result-sort-contain').addClass('hidden');
		$('.search-cancel').removeClass('hidden');
		$('.result-detail-contail').addClass('hidden');
		getHistory();
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
        g.keyword = ipt_val;
        setData(ipt_val);
        searchDate(g);
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
    //清空历史记录
    $('.hist-cancel').click(function(e) {
        e.preventDefault()

    	var histStr = history.getItem('histStr')

        if (histStr) {
            history.removeItem('histStr')
        }

        getHistory()
    });
    //点击历史记录搜索
    $('.uls-list').on('click','li',function() {
        $('.search-history').addClass('hidden');
        $('.result-sort-contain').removeClass('hidden');
        $('.result-detail-contail').removeClass('hidden');
        $('.search-cancel').addClass('hidden');
        g.keyword = $(this).text();
        $('.search-ipt').val($(this).text());
        searchDate(g);
    });
	/*历史纪录结束*/
    var save_data;
	//点击取消显示搜索结果
	$('.search-cancel').on('click', function() {
		cancelSearch()
	});

    function cancelSearch() {
        $('.search-history').addClass('hidden');
        if(save_data.category.length === 0){
            $('.result-sort-contain').addClass('hidden');
        } else {
            $('.result-sort-contain').removeClass('hidden');
        }
        $('.search-cancel').addClass('hidden');
        $('.result-detail-contail').removeClass('hidden');
        $('.recommend-goods').removeClass('hidden');
    }


	function searchDate(data) {
		requestUrl('/search/result','GET',data,function(res) {
            //判断数据
            if(res.goods.length === 0 && res.category.length != 0 ) {
                errorShow(1);
            } else if( res.goods.length === 0 && res.category.length === 0) {
                errorShow(2);
            } else {
                $('.result-detail-contail').removeClass('hidden');
                $('.search-result').removeClass('hidden');
                $('.result-sort-contain').removeClass('hidden');
                $('.empty').addClass('hidden');
            }
            save_data = res;
            gsdata.data = res.goods;
			//3个分类
			var sort_tp2 = $("#T_sort_list").html();
			var sort_title = juicer(sort_tp2,res.category);
			$("#J_sort_title").html(sort_title);
			//全部分类
			var sort_tpl = $("#J_sort_list").html();
			var sort_html = juicer(sort_tpl,res.category);
			$("#J_sort_contain").html(sort_html);
			//商品填充
            pageDate(1, gsdata.size,res.goods);
            counts = 1;
            $('.recommend-goods').removeClass('hidden');
		})
	}
	searchDate(data);
    //分页
    function pageDate(page,size,total) {
        var page_data = total.slice(((page-1)*size), (page * size));
        if( page_data == '') {
            return false;
        }
        var goods_html = juicer(gsdata.goods_tpl,page_data);
        if (page == 1) {
            $("#J_goods_contain").html('');
        }
        $("#J_goods_contain").append(goods_html);

    }
    //页面滑动拉取数据
    var counts=1;//计算拉取次数
    $('main.container').on('touch scroll',function() {
        var mark = $('#J_goods_contain').hasClass('hidden');
        if (!mark) {
            //数据长度
            var data_len = gsdata.data.length;
            var max_len = Math.floor(data_len/gsdata.size) + ((data_len % gsdata.size) > 0 ? 1 : 0);
            //拉取间距距离
            if(counts > max_len-1){
                return
            }
            var des = $('#J_goods_contain').height() - $('main.container').height() - 1;
            var scroll_des = $(this).scrollTop();
            if( scroll_des >= des ) {
                counts++;
                pageDate(counts,gsdata.size,gsdata.data);
            }
        }
    });

	//错误显示
    function errorShow(type) {
        $('.recommend-goods').removeClass('hidden');
        if( type === 1) {
        //无商品有分类
           $('.error1').removeClass('hidden');
           $('.error1').find('span').text($('.search-ipt').val());
           $('.error2').addClass('hidden');
           $('.search-result').addClass('hidden');
        } else {
        //全无
            $('.error2').find('span').text($('.search-ipt').val());
            $('.error1').addClass('hidden');
            $('.error2').removeClass('hidden');
            $('.search-result').addClass('hidden');
            $('.result-sort-contain').addClass('hidden');
        }
    }

    //初始隐藏错误信息
    function errorHidden (){
        $('.result-detail-contail').removeClass('hidden');
        $('.error1').addClass('hidden');
        $('.error2').addClass('hidden');
        $('.search-history').addClass('hidden');
        $('.search-result').removeClass('hidden');
        $('.result-sort-contain').removeClass('hidden');
    }
});
