$(function(){

    var _g = {
        page : 1,
        maxPage : 1,
        $index : 0,
        $status : -1,
        $searchTxt : ''
    }
    var $items = $('#head-list').find('.head-item'),
        $sections = $('#section').find('.complete-collage-section');

    $('.bottom-nav').addClass('hidden');

    // 溢出显示省略号
    function fontSize (biaoqian){
        var Size = parseInt($('.' + biaoqian).css('font-size'));
        var Width = parseInt($('.' + biaoqian).css('width'));
        var num = Width / Size * 2;
        var num1 = Width / Size;
        var num2 = Width / Size * 2 - 1; 
        $('.' + biaoqian).each(function() {
            if ($(this).text().length > num1) {
                $(this).html($(this).text().replace(/\s+/g, "").substr(0, num2) + "...")
            }
        })
    }

    fontSize('item-txt-1');

    //搜索
    $('#search-btn').on('click',function(e){
        e.preventDefault();
        _g.$searchTxt = $('#search-prompt').val().trim();
        if(_g.$searchTxt != null && _g.$searchTxt.length > 0){
            _g.page = 1;
            var $activeItem = $('#head-list').find('.head-item.active');
            _g.$status = $activeItem.data('status');
            _g.$index = $activeItem.data('index');
            $sections.eq(_g.$index).html('');
            if(_g.$index != 4){
                getOrderList({
                    product_name : _g.$searchTxt
                });
            }else {     //拼购异常  
                getFailedList({
                    product_name : _g.$searchTxt
                });      
            }
        }
    });

    //列表切换
    $items.on('click',function(){
        _g.$index = $(this).data('index');
        _g.$status = $(this).data('status');
        _g.$searchTxt = $('#search-prompt').val().trim();
        $(this).addClass('active').siblings().removeClass('active');
        $sections.eq(_g.$index).removeClass('hidden').siblings().addClass('hidden');
        _g.page = 1;
        $sections.eq(_g.$index).html('');
        if(_g.$index != 4){
            getOrderList({
                product_name : _g.$searchTxt
            });
        }else {
            getFailedList({
                product_name : _g.$searchTxt
            });    //拼购异常    
        }
    });
    
    //拉取列表前4个状态数据
    getOrderList();
    function getOrderList(param){
        var _default = {
            status : _g.$status,
            current_page : _g.page,
            page_size : 3,
            gpubs_type : 0
        }
        $.extend(_default,param);
        requestUrl('/gpubs/api/order-list','GET',_default,function(data){
            if(data.total_count <= 0){
                $sections.eq(_g.$index).html('');
                $('#unrelated').removeClass('hidden');
                $('#no-more').addClass('hidden');
                return;
            }
            _g.maxPage = Math.ceil(data.total_count / 3);
            $('#unrelated').addClass('hidden');
            var _data = juicer($('#section-item').html(),{data : data.data, total : data.total_count});
            $sections.eq(_g.$index).append(_data);
        });
    }

    // 拉取拼购异常数据
    _g.$index == 4 && getFailedList();
    function getFailedList(param){
        var _default = {
            is_join : _g.$status,
            current_page : _g.page,
            page_size : 3
        }
        $.extend(_default,param);
        requestUrl('/gpubs/api/join-failed-list','GET',_default,function(data){
            if(data.total_count <= 0){
                $sections.eq(_g.$index).html('');
                $('#unrelated').removeClass('hidden');
                $('#no-more').addClass('hidden');
                return;
            }
            _g.maxPage = Math.ceil(data.total_count / 3);
            $('#unrelated').addClass('hidden');
            var _data = juicer($('#section-item').html(),{data : data.data, total : data.total_count});
            $sections.eq(_g.$index).append(_data);
        });
    }
    
    //上拉加载
    $(window).on('scroll',function(){
        var scrollTop = $(this).scrollTop();   
        var dHeight = $(document).height();       
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= dHeight && _g.page != _g.maxPage){
            _g.page++;
            if(_g.$index != 4){
                getOrderList({
                    current_page : _g.page,
                    product_name : _g.$searchTxt
                });
            }else {
                getFailedList({
                    current_page : _g.page,
                    product_name : _g.$searchTxt
                });
            }           
        }
        if(_g.page == _g.maxPage){
            $('#no-more').removeClass('hidden');
        }
    });

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
        $('#guid-list .search').on('click', function () {
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


});