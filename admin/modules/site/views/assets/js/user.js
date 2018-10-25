$(function(){
    var _tempAccount = '';

    var scrolls = [];
    $('.iscroll_container').each(function () {
        scrolls.push(new IScroll(this, {
            mouseWheel: true,
            scrollbars: true,
            scrollbars: 'custom'
        }))
    })
    // refresh api
    scrolls.forEach(function (scroll) {
        scroll.refresh();
    })
    //用户信息
    function getuserlist(username) {
        function listUser(data) {
            var $this =  $('#J_department_users');
            var l_operator = '';
            $.each(data.fiveArea, function(i, val) {
                l_operator += val.name + ' '
            })
            var l_store = (data.province?data.province.name:"") +"/"+ (data.city?data.city.name:"") +"/"+ (data.district?data.district.name:"");
            $this.find('img').attr('src',data.headerImg);
            $this.find('#shop-name').text(data.shopName);
            $this.find('#user-name').text(data.nickName);
            $this.find('#user-id').text(data.id);
            $this.find('#l-operator').text(l_operator);
            $this.find('#l-store').text(l_store);
            $this.find('#l-mobile').text(data.mobile);
            $this.find('#l-wechat').text(data.wechatUserName);

            if (data.id) {
                $('.J_update_btn').attr('disabled', false)
                if (data.mobile.toString().length === 11) {
                    $('.J_remove_mobile').attr('disabled', false)
                } else {
                    $('.J_remove_mobile').attr('disabled', true)
                }
            }

            var levelT = ['', '', '邀请门店', '体系内门店', '运营商'];
            $this.find('#l-level').text(levelT[data.level]);
            if (data.level === 4) {
                $('.J_down_level').attr('disabled', false)
                $('.J_up_level').attr('disabled', true)
            } else if (data.level === 2) {
                $('.J_up_level').attr('disabled', false)
                $('.J_down_level').attr('disabled', true)
            } else {
                $('.J_up_level').attr('disabled', false)
                $('.J_down_level').attr('disabled', false)
            }

            _tempAccount = data.id;
            $this.find('#account-code').text(data.account);
            $this.find('#user_balance').text(data.balance);
            if (data.status === 0) {
                $('.J_remove_account').attr('disabled', false)
                $this.find('#user_status').text('正常')
            } else {
                $this.find('#user_status').text('已封停')
                $('.J_remove_account').attr('disabled', true).siblings().attr('disabled', true)
            }
            getorderlist(data.id,1, 10);
        }
        requestUrl('/site/user/user-info', 'GET', {search_name: username}, listUser);
    }
    //订单列表信息
    var tpl_order = $('#J_tpl_order').html();
    var attr_tpl_order = juicer(tpl_order);
    function getorderlist(userid,page, size) {
        function listOrder(data) {
            //status = 1：未发货 2：已发货 3：已确认收货 4：已取消 5：已关闭
            function isfrist(status) {
                if(status == 1){
                    return '未发货';
                }
                if(status == 2){
                    return '已发货';
                }
                if(status == 3){
                    return '已确认收货';
                }
                if(status == 4){
                    return '已取消';
                }
                if(status == 5){
                    return '已关闭';
                }
            }
            function isattr(attributes) {
                var attrs = "";
                for(var i=0;i<attributes.length;i++){
                    attrs+=attributes[i].attribute+"-"+attributes[i].option+"；"
                }
                return attrs;
            }
            juicer.register('first_build', isfrist);
            juicer.register('attr_build', isattr);
            var html = attr_tpl_order.render(data);
            $('#J_department_list').html(html);
            scrolls.forEach(function (scroll) {
                scroll.refresh();
            })
            pagingBuilder.build($('#J_user_page'),page,size,data.totalCount);
            pagingBuilder.click($('#J_user_page'),function(page){
                getorderlist(userid,page,size);
            })
        }
        requestUrl('/site/user/order-info', 'GET', {customer_user_id:userid,current_page:page,page_size:size}, listOrder);
    }

    //重置密码
    $("#J_pass_user").on('click',function(){
        var userid = $('#user-id').text();
        var data = getAlertData(userid);
        if(data === false){return}
        $('#apxModalAdminAlertUser').modal('hide')
        function userupdata(data){
            $('#J_alert_content').html('密码重置成功！');
            $('#apxModalAdminAlert').modal('show');
            $('#apxModalAdminAlertUser').modal('hide');
        }
        requestUrl('/site/user/reset-password', 'post', data , userupdata);
    })

    //点击搜索用户名
    $("#btn-search").on('click',function(){
        var userId = $("#J_search").val();
        if(userId == ""|| userId == undefined){
            $('#J_alert_content').html('请输入用户ID！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        getuserlist(userId);
    })

    //判断修改密码输入
    function getAlertData(userid) {
        if(userid == ''){
            $('#J_alert_content').html('操作错误！');
            $('#apxModalAdminAlert').modal('show');
            return
        }
        var yes = judgeYes();
        if (!yes) {
            return false
        }
        var data = {
            customer_user_id: userid
        }
        return data;
    }

    // 封停用户账号
    function cancelAccount(account) {
        var yes = judgeYes();
        if (!yes) {
            return false
        }
        $('#apxModalAdminAlertUser').modal('hide')
        requestUrl('/site/user/cancel-account', 'POST', {customer_user_id: account}, function(data) {
            $('#btn-search').click()
        })
    }
    $('#J_del_user').on('click', function() {
        cancelAccount(_tempAccount)
    })

    // 用户操作
    var handle_type = ['', '重置密码', '封停账号', '解绑', '升级', '降级'];
    $('#apxModalAdminAlertUser').on('show.bs.modal', function(e) {
        var type = $(e.relatedTarget).data('type');
        var userId = $('#user-id').text();
        if(userId == ""|| userId == undefined){
            $('#J_alert_content').html('请输入用户ID！');
            $('#apxModalAdminAlert').modal('show');
            return false
        }
        $('#J_reset_sure').val("");
        $('#J_handle_type').text(handle_type[type]);
        $('#apxModalAdminAlertUser .modal-footer .btn').not('.btn-default').addClass('hidden');
        $('#apxModalAdminAlertUser .modal-footer .btn').eq(type - 1).removeClass('hidden');
    })
    // 判断是否为YES
    function judgeYes() {
        var str = $('#J_reset_sure').val();
        if (str === 'YES') {
            return true
        }
        $('#J_alert_content').html('请输入确认文字！!');
        $('#apxModalAdminAlert').modal('show');
        return false
    }

    // 解绑手机号
    function unbindMobile() {
        var userId = $('#user-id').text();
        $('#apxModalAdminAlertUser').modal('hide')
        requestUrl('/site/user/unbind-mobile', 'POST', {customer_user_id: userId}, function(data) {
            $('#btn-search').click()
        })
    }
    $('#J_del_mobile').on('click', function() {
        var yes = judgeYes();
        if (yes) {
            unbindMobile()
        }
    })

    // 改变账户等级
    function changLevel(type) {
        // type 0升级 1降级
        var userId = $('#user-id').text();
        $('#apxModalAdminAlertUser').modal('hide');
        requestUrl('/site/user/upgrade-user', 'POST', {customer_user_id: userId, action: type}, function(data) {
            $('#btn-search').click()
        })
    }
    // 升级
    $('#J_up_level').on('click', function() {
        var yes = judgeYes();
        if (yes) {
            changLevel(0)
        }
    })
    // 降级
    $('#J_down_level').on('click', function() {
        var yes = judgeYes();
        if (yes) {
            changLevel(1)
        }
    })
})