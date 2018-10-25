$(function() {
    // 返回上级
    $('#J_back').attr('href', '/fund/deposit-and-draw-list?id=' + url('?id') + '&page=' + url('?page') + '&status=' + url('?status'));

    function getInfo(id) {
        requestUrl('/fund/deposit-and-draw-detail/detail', 'GET', {id: id}, function(data) {
            var o_type = '未知';
            if (data.operate_type == 1) {
                o_type = '入账'
            } else if (data.operate_type == 2) {
                o_type = '出账'
            }
            $('.J_handle_type').html(o_type);
            var u_type = '未知';
            if (data.user_type == 1) {
                u_type = 'CUSTOM'
            } else if (data.user_type == 2) {
                u_type = 'BUSINESS'
            }
            $('.J_user_type').html(u_type);
            $('.J_user_account').html(data.user_account);
            $('.J_user_amount').html(data.amount);
            $('.J_operate_brief').html(data.operate_brief);
            $('.J_operate_detail').html(data.operate_detail);
            $('.J_cancel_reason').html(data.cancel_reason);
            var status = '未知';
            if (data.status == 1) {
                status = '未审核'
                $('#J_pass_btn').removeClass('hidden');
                $('#J_cancel_btn').removeClass('hidden');
            } else if(data.status == 2) {
                status = '已审核'
                $('#J_cancel_btn').removeClass('hidden');
            } else if(data.status == 3) {
                status = '执行成功'
            } else if (data.status == 4) {
                status = '执行失败'
            } else if (data.status == 5) {
                status = "已取消"
            }
            $('.J_status').html(status);
            $('.J_create_time').html(data.create_time);
            $('.J_pass_time').html(data.pass_time);
            $('.J_cancel_time').html(data.cancel_time);
            $('.J_handle_time').html(data.operate_time);

            getHandleInfo(url('?id'))
            requestUrl('/fund/deposit-and-draw-application/user-info', 'GET', {account: data.user_account}, function(data) {
                $('.J_user_mobile').html(data.mobile);
                $('.J_user_name').html(data.name);
                $('.J_user_role').html(data.role);
                $('.J_user_area').html(data.area);
                $('.J_user_balance').html(data.rmb.toFixed(2));
                var status = '正常';
                if (data.status == 1) {
                    status = '封停/删除'
                } else if (data.status == 2) {
                    status = '未注册'
                }
                $('.J_user_status').html(status);
            })
        })
    }
    getInfo(url('?id'))


    // 生成popover内容
    function popoverHtml(data) {
        var html_0 = `<div class="tip-container">
                            <h3>操作人信息：</h3>
                            <p>
                                账号：` + data.user_info.account + `
                            </p>
                            <p>
                                姓名：` + data.user_info.name + `
                            </p>
                            <p>
                                手机：` + data.user_info.mobile + `
                            </p>
                            <p>
                                邮箱：` + data.user_info.email + `
                            </p>
                            <p>
                                状态：` + (data.user_info.status == 0 ? '封停' : '正常') + `
                            </p>
                            <p>
                                部门：` + data.user_info.department + `
                            </p>
                            <p>
                                角色：` + data.user_info.role + `
                            </p>
                            <p>
                                IP：` + data.user_ip + `
                            </p>`;
        html_0 += '<p> 请求头：<br/>'
        for (let i = 0; i < data.user_request_header.length; i++) {
            html_0 +=  data.user_request_header[i] + '<br/>'
        }
        html_0 += `</p></div>`;
        return html_0;
    }

    // 获取操作详情
    function getHandleInfo(id) {
        $.ajax({
            url: '/fund/deposit-and-draw-detail/operate-info',
            data: {id: id},
            success: function(data) {
                if (data.status == 200) {
                    var data = data.data;
                    var img = '<img data-flag="pop" src="/images/user.png" alt=""/>'
                    $('.J_create_time').append(img);
                    var data_0 = data['0']
                    var html_0 = popoverHtml(data_0);
                    $('.J_create_time img').popover({
                        tirgger: 'manual',
                        placement: 'right',
                        html: true,
                        content: html_0
                    });
                    var html_1 = '';
                    if (data['2']) {
                        $('.J_pass_time').append(img);
                        var data_2 = data['2'];
                        var html_1 = popoverHtml(data_2);
                        $('.J_pass_time img').popover({
                            tirgger: 'manual',
                            placement: 'right',
                            html: true,
                            content: html_1
                        });
                    }
                    if (data['5']) {
                        $('.J_cancel_time').append(img);
                        var data_5 = data['5'];
                        var html_2 = popoverHtml(data_5);
                        $('.J_cancel_time img').popover({
                            tirgger: 'manual',
                            placement: 'right',
                            html: true,
                            content: html_2
                        });
                    }
                    $('td img').off().on('click', function() {
                        if (!$(this).data('show')) {
                            $('td img').not($(this)).popover('hide').data('show', false);
                            $(this).popover('show').data('show', true);
                        } else {
                            $('td img').popover('hide').data('show', false);
                        }
                    })
                }
            },
            error: function() {
                
            }
        })
    }
    $('td').on('show.bs.popover', 'img', function() {
        $('table').css('transform', 'translateX(-200px)')
    }).on('hide.bs.popover', function() {
        $('table').css('transform', 'translateX(0)')
    })
    $(document).on('click', function(e) {
        var target = $(e.target);
        if (!target.data('flag') && target.parent('.tip-container').length === 0) {
            $('td img').popover('hide').data('show', false)
        }
    })

    // 通过审核
    $('#J_pass_sure').on('click', function() {
        var _data = {
            id: url('?id')
        }
        if (!$('#J_has_pwd').hasClass("hidden")) {
            var pwd = $('#J_password').val();
            _data.authorize_password = pwd;
            if (pwd == '') {
                alert('密码不能为空！')
                return
            }
        }
        var _this = $(this);
        _this.addClass('disabled');
        requestUrl('/fund/deposit-and-draw-detail/pass', 'POST', _data, function(data) {
            $('#apxModalAdminPass').modal('hide');
            window.location.reload();
        }, function(data) {
            _this.removeClass('disabled');
            if (data.status == 5474) {
                $('#J_has_pwd').removeClass('hidden');
                $('#apxModalAdminPass .management-authen .h3').text('请输入密码！');
            } else {
                alert(data.data.errMsg)
            }
        })
    })
    $('#apxModalAdminPass').on('hidden.bs.modal', function() {
        $('#J_has_pwd').addClass('hidden');
        $('#apxModalAdminPass .management-authen .h3').text('确定通过？');
    })
    
    // 取消
    $('#J_reject_sure').on('click', function() {
        var remark = $('#J_reject_remark').val();
        if (remark == '') {
            alert('请填写拒绝理由！')
            return
        }
        var _data = {
            id: url('?id'),
            cancel_reason: remark
        }
        var _this = $(this);
        _this.addClass('disabled');
        $('#apxModalAdminRejectRemark').modal('hide');
        requestUrl('/fund/deposit-and-draw-detail/cancel', 'POST', _data, function(data) {
            window.location.reload();
        }, function(data) {
            _this.removeClass('disabled');
            alert(data.data.errMsg)
        })
    })
})