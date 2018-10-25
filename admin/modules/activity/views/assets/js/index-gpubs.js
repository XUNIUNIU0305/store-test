$(function () {

    var tpl_proList = $('#J_tpl_list').html(),
        tpl_searchList = $('#J_search_list').html()

    function getGpubsList(page) {
        requestUrl(
            '/activity/gpubs/list',
            'GET',
            {
                current_page: page,
                page_size: 15
            },
            function (res) {
                var html = juicer(tpl_proList, res.data)
                $('#J_prolist_box').html(html)

                $('#J_prolist_box').on('click', '.btn-open', function() {
                    if ($(this).hasClass('btn-disabled')) {
                        return flase
                    }
                    changeStatus($(this).attr('data-id'), 1, $(this))
                })

                $('#J_prolist_box').on('click', '.btn-stop', function() {
                    if ($(this).hasClass('btn-disabled')) {
                        return flase
                    }
                    changeStatus($(this).attr('data-id'), 0, $(this))
                })

                $('#J_prolist_box').on('click', '.btn-toDetail', function () {
                    location.href = '/activity/gpubs/detail-gpubs?gpubsProductId=' + $(this).attr('data-id')
                })

                $('#J_prolist_box').on('click', '.btn-toModify', function () {
                    location.href = '/activity/gpubs/modify-gpubs?gpubsProductId=' + $(this).attr('data-id')
                })

                pagingBuilder.build($('#J_coupon_page'), page, 15, res.totalCount);
                pagingBuilder.click($('#J_coupon_page'), function(page) {
                    getGpubsList(page)
                })

                editProduct()
            }
        )
    }

    function getSearchList(page) {
        requestUrl(
            '/activity/gpubs/search-gpubs',
            'GET',
            {
                current_page: page,
                page_size: 15,
                search_condition: $('#search-ipt').val(),
                search_gpubs_type: $('#searchType').val()
            },
            function (res) {
                var html = juicer(tpl_proList, res.data)
                $('#J_prolist_box').html(html)

                $('#J_prolist_box').on('click', '.btn-open', function() {
                    if ($(this).hasClass('btn-disabled')) {
                        return flase
                    }
                    changeStatus($(this).attr('data-id'), 1, $(this))
                })

                $('#J_prolist_box').on('click', '.btn-stop', function() {
                    if ($(this).hasClass('btn-disabled')) {
                        return flase
                    }
                    changeStatus($(this).attr('data-id'), 0, $(this))
                })

                $('#J_prolist_box').on('click', '.btn-toDetail', function () {
                    location.href = '/activity/gpubs/detail-gpubs?gpubsProductId=' + $(this).attr('data-id')
                })

                $('#J_prolist_box').on('click', '.btn-toModify', function () {
                    location.href = '/activity/gpubs/modify-gpubs?gpubsProductId=' + $(this).attr('data-id')
                })

                pagingBuilder.build($('#J_coupon_page'), page, 15, res.totalCount);
                pagingBuilder.click($('#J_coupon_page'), function(page) {
                    getSearchList(page)
                })

                editProduct()
            }
        )
    }
    getGpubsList(1)

    $('#search-btn').on('click', function () {
        /*
        var pro_id = $('#search-ipt').val()
        if ((/^\s*$/).test(pro_id)) {
            alert('请输入正确商品ID！')
            return
        }
        */
        getSearchList(1)
    })

    function editProduct(){
        // 新增热门标签
        $('.hotSpan').on('click',function(){
            var _this = $(this);
            requestUrl(
                '/activity/gpubs/set-recomment',
                'POST',
                {
                    product_id: _this.attr('data-id'),
                    is_hot: _this.attr('data-hot')
                },
                function () {
                    if(_this.attr('data-hot') == 1){
                        _this.attr('data-hot',2)
                        _this.attr('class','hotSpan')
                    }else{
                        _this.attr('data-hot',1)
                        _this.attr('class','hotSpan ishot')
                    }
                }
            )
        })
        // 新增是否打开
        $('.btn-isOpen').on('click',function(){
            var _this = $(this);
            requestUrl(
                '/activity/gpubs/set-status',
                'POST',
                {
                    product_id: _this.attr('data-id'),
                    status: _this.attr('data-isopen')
                },
                function () {
                    if(_this.attr('data-isopen') == 1){
                        _this.attr('data-isopen',0)
                        _this.html('禁用')
                        _this.parent().children('span:last').attr('class','btn btn-grey')
                        _this.parent().parent().parent().children('.openStatus').html('启用')
                    }else{
                        _this.attr('data-isopen',1)
                        _this.html('启用')
                        _this.parent().children('span:last').attr('class','btn btn-green btn-toModify')
                        _this.parent().parent().parent().children('.openStatus').html('禁用')
                    }
                }
            )
        })
    }

    //启用
    $('.btn-open').on('click', function () {
        changeStatus($(this).attr('data-id'), 1)
    })

    $('.btn-stop').on('click', function () {
        changeStatus($(this).attr('data-id'), 0)
    })

    function changeStatus(id, status, dom) {

        requestUrl(
            '/activity/gpubs/set-status',
            'POST',
            {
                product_id: id,
                status: status
            },
            function () {
                if ($(dom).text() == '启用') {
                    $(dom).parents('td').prev().text('启用')
                    $(dom).addClass('btn-disabled')
                    $(dom).siblings().eq(1).removeClass('btn-disabled')
                } else {
                    $(dom).parents('td').prev().text('禁用')
                    $(dom).addClass('btn-disabled').off('click')
                    $(dom).siblings().eq(0).removeClass('btn-disabled')
                }
            }
        )
    }
})
