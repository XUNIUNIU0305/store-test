$(function() {
    var list = {
        tpl: $('#J_tpl_list').html(),
        tplFail: $('#J_tpl_list_fail').html(),
        getList: function(params) {
            var _default = {
                current_page: 1,
                page_size: 20
            }
            $.extend(_default, params)
            requestUrl('/gpubs/api/order-list', 'GET', _default, function(data) {
                $('#J_list_box').html(juicer(list.tpl, data.data))
                if (data.count > 0) {
                    $('.order-title-box').find('table').eq(0).fadeIn(0);
                    pagingBuilder.build($('#J_page_box'), _default.current_page, _default.page_size, data.total_count)
                    pagingBuilder.click($('#J_page_box'), function(page) {
                        _default.current_page = page
                        list.getList(_default)
                    })
                } else {
                    $('.order-title-box').find('table').eq(0).fadeOut(0);
                    $('#J_page_box').html('<img src="/images/apex20180920/noimg.png" class="noImg" />')
                }
                if (_default.status === 1) {
                    $.each($('.J_group_time'), function() {
                        var time = $(this).data('time')
                        var $this = $(this)
                        jdy.seckill.timerFun(time, function(data) {
                            var hour = (data.hour - 0) + (data.day * 24)
                            if (hour < 10) {
                                hour = '0' + hour.toString()
                            }
                            $this.text(hour + ':' + data.minute + ':' + data.second)
                        })
                    })
                }
            })
        },
        getFail: function() {
            var _default = {
                current_page: 1,
                page_size: 20,
                is_join: 0
            }
            requestUrl('/gpubs/api/join-failed-list', 'GET', _default, function(data) {
                $('#J_list_box').html(juicer(list.tplFail, data.data))
                if (data.count > 0) {
                    $('.order-title-box').find('table').eq(0).fadeIn(0);
                    pagingBuilder.build($('#J_page_box'), _default.current_page, _default.page_size, data.total_count)
                    pagingBuilder.click($('#J_page_box'), function(page) {
                        _default.current_page = page
                        list.getFail(_default)
                    })
                } else {
                    $('.order-title-box').find('table').eq(0).fadeOut(0);
                    $('#J_page_box').html('<img src="/images/apex20180920/noimg.png" class="noImg" />')
                }
            })  
        },
        init: function() {
            this.getList({
                status: 1
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var status = $(this).parent().data('status')
                if (status !== 6) {
                    list.getList({
                        status: status
                    })
                } else {
                    list.getFail()
                }
            })
        }
    }
    list.init()
    juicer.register('status', function(data) {
        var status = {
            0: '拼购失败',
            1: '拼购中',
            2: '未提货',
            3: '部分提货',
            4: '全部提货',
            6: '参团失败'
        }
        return status[data]
    })
})