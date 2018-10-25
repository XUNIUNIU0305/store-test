$(function () {
    var scrolls = [];
    $('.iscroll_container').each(function () {
        scrolls.push(new IScroll(this, {
            mouseWheel: true,
            scrollbars: true,
            scrollbars: 'custom',
            preventDefault: false
        }))
    })
    // init the datepicker
    $('.date-picker').datetimepicker({
        locale: 'zh-cn',
        format: "YYYY-MM-DD HH:mm:ss",
        defaultDate: new Date()
    });
    // show date
    $('.date-show').on('click', function() {
        $(this).siblings('input').focus()
    })
    //msg show
    function msgShow(title) {
        $('#J_alert_content').html(title);
        $('#apxModalAdminAlert').modal('show');
    }
    //刷新滚动条
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var status = $('#J_tab_box li[class*="active"]').data('status');
        if ($('#J_done_list').html() == '') {
            getRfundList(1, 20, status);
        }
        refreshScroll()
    });
    function refreshScroll() {
        setTimeout(function() {
            scrolls.forEach(function (scroll) {
                scroll.refresh();
            })
        }, 300)
    }
    //刷新页面
    function refreshWindow() {
        setTimeout(function() {
            window.location.reload()
        }, 1000)
    }
    //获取店铺列表
    function getSupplier() {
        requestUrl('/site/supply/get-supply-list', 'GET', {current_page: 1, page_size: 9999}, function(data) {
            var options = '<option value="-1">选择店铺</option>';
            for (var i = 0; i < data.codes.length; i++) {
                options += '<option value="' + data.codes[i].id + '">' + data.codes[i].brand_name + '(ID：' + data.codes[i].id + ')'  + '</option>'
            }
            $('#J_supplier_list').html(options);
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('show');
        })
    }
    getSupplier()
    //获取优惠券详情
    function getCouponInfo(id) {
        requestUrl('/activity/coupon/get-coupon-info', 'GET', {id: id}, function(data) {
            if (data.status == 2) {
                $('.J_handle_btn').addClass('hidden');
            }
            $('#J_coupon_name').html(data.name);
            $('#J_coupon_price').html(data.price);
            $('#J_coupon_time').html(data.start_time + '-' + data.end_time);
            $('#J_coupon_use_limit').html(data.consume_limit);
            if (data.receive_limit == 0) {
                $('#J_coupon_have_limit').html('无限制');
            } else {
                $('#J_coupon_have_limit').html(data.receive_limit);
            }
            $('#J_coupon_supplier').html(data.supplier.brand_name || '无限制');
            $('#J_coupon_total').html(data.total_quantity);
            $('#J_coupon_unsent').html(data.total_quantity - data.send_quantity);
            $('#J_entity_unsent').html(data.total_quantity - data.send_quantity);
            if (data.rule) {
                var supplierList = '';
                for (var i = 0; i < data.rule.supply_list.length; i++) {
                    supplierList += '<div class="btn-group supplier">\
                            <label class="btn btn-default">\
                                ' + data.rule.supply_list[i].brand_name + '\
                            </label>\
                            <span data-id="' + data.rule.supply_list[i].id + '" class="btn btn-danger J_dele_one"><i class="glyphicon glyphicon-remove"></i></span>\
                        </div>'
                    suppliers[data.rule.supply_list[i].id] = data.rule.supply_list[i].brand_name;
                }
                $('#J_supplier_box').html(supplierList);
                $('#J_start_time').val(data.rule.start_time);
                $('#J_end_time').val(data.rule.end_time);
                if (data.rule.money_limit == 0) {
                    $('[name="consumption"][value="0"]').prop('checked', true);
                } else {
                    $('[name="consumption"][value="1"]').prop('checked', true);
                    $('#J_money_limit').val(data.rule.money_limit);
                }
                if (data.rule.money_limit_type == 1) {
                    $('[name="condition"][value="1"]').prop('checked', true);
                } else if (data.rule.money_limit_type == 0) {
                    $('[name="condition"][value="0"]').prop('checked', true);
                }
                if (data.rule.post_limit == 1) {
                    $('[name="circulation"][value="1"]').prop('checked', true);
                } else if (data.rule.post_limit == 0) {
                    $('[name="circulation"][value="0"]').prop('checked', true);
                }
            }
        })
    }
    getCouponInfo(url('?id'))
    //新增发行数量
    $('#J_sure_add').on('click', function() {
        var count = $('#J_add_count').val().trim();
        if (/[^0-9]/g.test(count)) {
            msgShow('请填写正确数量！');
            return
        }
        if (count - 0 < 1) {
            msgShow('请填写大于0的数量！');
            return
        }
        $('#apxModalAdminAddCount').modal('hide');
        $('#J_add_count').val('');
        requestUrl('/activity/coupon/add-quantity', 'POST', {id: url('?id'), quantity: count}, function(data) {
            msgShow('添加成功！');
            refreshWindow();
        })
    })
    //分发优惠券
    $('#apxModalAdminSure').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        $('#J_sure_send').off().on('click', function() {
            $('#apxModalAdminSure').modal('hide');
            //手动分发
            if ($this.data('btn') == 'hand') {
                var ids = $('#J_hand_send_account').val();
                if (/[^0-9,]/g.test(ids)) {
                    msgShow('填写格式错误！')
                    return
                }
                var id = ids.split(',');
                for (var i = 0; i < id.length; i++) {
                    if (id[i] === '') {
                        msgShow('分隔号两边都不能为空！')
                        return
                    }
                }
                requestUrl('/activity/coupon/send-ticket', 'POST', {id: url('?id'), custom_code: id}, function(data) {
                    msgShow('发放成功！');
                    refreshWindow()
                })
            }
            //发放实体券
            if ($this.data('btn') == 'entity') {
                var count = $('#J_entity_count').val().trim();
                var data = {
                    id: url('?id'),
                    quantity: count
                }
                if (/[^0-9]/g.test(count) || count == 0) {
                    msgShow('只能填入正整数');
                    return
                }
                requestUrl('/activity/coupon/create-ticket', 'POST', data, function(data) {
                    msgShow('发放成功！');
                    refreshWindow()
                })
            }
            //系统自动发放
            if ($this.data('btn') == 'auto') {
                var id = [];
                $.each(suppliers, function(i, v) {
                    id.push(i)
                })
                if (id.length === 0) {
                    msgShow('请选择指定消费店铺！');
                    return;
                }
                var start_time = $('#J_start_time').val();
                var end_time = $('#J_end_time').val();
                if ($('input[name="consumption"]:checked').val() === '0') {
                    var money_limit = 0;
                } else {
                    var money_limit = $('#J_money_limit').val();
                }
                var money_limit_type = $('input[name="condition"]:checked').val();
                var post_limit = $('input[name="circulation"]:checked').val();
                if (/[^0-9.]/g.test(money_limit)) {
                    msgShow('请输入正确金额');
                    return;
                }
                var data = {
                    id: url('?id'),
                    start_time: start_time,
                    end_time: end_time,
                    money_limit: money_limit,
                    money_limit_type: money_limit_type,
                    suppliers: id,
                    post_limit: post_limit
                }
                requestUrl('/activity/coupon/create-rule', 'POST', data, function(data) {
                    msgShow('发放成功！');
                    refreshWindow();
                })
            }    
        })
    })
    //添加指定消费店铺
    var suppliers = {};
    $('#J_add_supplier').on('click', function() {
        var id = $('#J_supplier_list option:selected').val();
        if (id === '-1') {
            return
        }
        if (suppliers[id]) {
            return
        }
        var name = $('#J_supplier_list option:selected').text();
        suppliers[id] = name;
        var html = '<div class="btn-group supplier">\
                        <label class="btn btn-default">\
                            ' + name + '\
                        </label>\
                        <span data-id="' + id + '" class="btn btn-danger J_dele_one"><i class="glyphicon glyphicon-remove"></i></span>\
                    </div>'
        $('#J_supplier_box').append(html);
    })
    //删除指定店铺
    $('#J_supplier_box').on('click', '.J_dele_one', function() {
        $(this).parents('.supplier').remove();
        var id = $(this).data('id');
        delete suppliers[id];
    })
    //优惠券列表
    var tpl = $('#J_tpl_list').html();
    var compiled_tpl = juicer(tpl);//存储已编辑模板
    function customer(data) {
        if (data === false) {
            return '无';
        }
        return data.account;
    }
    juicer.register('customer_build', customer);
    //获取优惠券列表
    function getCouponList(page, size, status, id) {
        var data = {
            id: url('?id'),
            current_page: page,
            page_size: size,
            ticket_status: status
        }
        requestUrl('/activity/coupon/get-ticket-list', 'GET', data, function(data) {
            var html = compiled_tpl.render(data);
            //未激活
            if (status == 0) {
                $('#J_coupon_inactive').html(html);
                pagingBuilder.build($('#J_inactive_page'), page, size, data.total_count);
                pagingBuilder.click($('#J_inactive_page'), function(page) {
                    getCouponList(page, size, status, id)
                })
            }
            //已激活
            if (status == 1) {
                $('#J_coupon_activated').html(html);
                pagingBuilder.build($('#J_activated_page'), page, size, data.total_count);
                pagingBuilder.click($('#J_activated_page'), function(page) {
                    getCouponList(page, size, status, id)
                })
            }
            //已使用
            if (status == 2) {
                $('#J_coupon_used').html(html);
                pagingBuilder.build($('#J_used_page'), page, size, data.total_count);
                pagingBuilder.click($('#J_used_page'), function(page) {
                    getCouponList(page, size, status, id)
                })
            }
            refreshScroll()
        })
    }
    //绑定拉取数据事件
    $('#J_tab_box a').one('click', function() {
        var status = $(this).data('status');
        if (status == -1) {
            return;
        }
        getCouponList(1, 20, status, url('?id'))
    })
    //全选和反选事件
    function selectEvent(type) {
        $('#all_' + type).on('click', function() {
            $('[name="' + type +'"]:checkbox').prop('checked', this.checked)
        })
        $('#J_coupon_' + type).on('click', '[name="' + type +'"]:checkbox', function() {
            var flag = true;
            $('[name="' + type +'"]:checkbox').each(function() {
                if (!this.checked) {
                    flag = false;
                }
            })
            $('#all_' + type).prop('checked', flag);
        })
        $('#invert_' + type).on('click', function() {
            $('[name="' + type +'"]:checkbox').each(function() {
                $(this).prop('checked', !$(this).prop('checked'));
                var flag = true;
                $('[name="' + type +'"]:checkbox').each(function() {
                    if (!this.checked) {
                        flag = false;
                    }
                })
                $('#all_' + type).prop('checked', flag);
            })
        })
    }
    selectEvent('inactive');
    selectEvent('activated');
    selectEvent('used');

    //注销优惠券
    $('#apxModalAdminCancel').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        if ($this.data('id')) {
            //单独注销
            var ticket_id = [$this.data('id')];
        } else if ($this.data('type') === 'inactive'){
            //批量注销未激活的
            var ticket_id = [];
            $('[name="inactive"]:checked').each(function() {
                ticket_id.push($(this).data('id'))
            })
        } else if ($this.data('type' === 'activated')) {
            //批量注销已激活的
            var ticket_id = [];
            $('[name="activated"]:checked').each(function() {
                ticket_id.push($(this).data('id'))
            })
        }
        $('#J_sure_cancel').off().on('click', function() {
            $('#apxModalAdminCancel').modal('hide');
            requestUrl('/activity/coupon/cancel-ticket', 'POST', {id: url('?id'), ticket_id: ticket_id}, function(data) {
                msgShow('注销成功！');
                $('.tab-flag[class*="active"]').find('.page-flag li[class*="active"]').click();
            })
        })
    })

    //导出未激活优惠券
    $('#J_sure_export').on('click', function() {
        $('#apxModalAdminExport').modal('hide');
        window.location.href = '/activity/coupon/export?coupon_id=' + url('?id');
    })






})