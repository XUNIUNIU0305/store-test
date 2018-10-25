$(function () {
    var scrolls = [];
    $('.iscroll_container').each(function () {
        scrolls.push(new IScroll(this, {
            mouseWheel: true,
            scrollbars: true,
            scrollbars: 'custom'
        }))
    })
    

    //获取员工列表
    var tpl_employee = $('#J_tpl_employees').html();
    function mobile(data) {
        if (data == 0) {
            return ''
        }
        return data
    }
    juicer.register('mobile_build', mobile);
    function getEmployeeList(page, size, search) {
        requestUrl('/site/employee/list', 'GET', {current_page: page, page_size: size, search: search}, function(data) {
            var html = juicer(tpl_employee, data.codes);
            $('#J_employee_list').html(html);
            // refresh api
            scrolls.forEach(function (scroll) {
                scroll.refresh();
            })
            //分页
            var pages = getPagination(page, Math.ceil(data.total_count/size));
            $('.J_employee_page').html(pages);
            $('#J_page_box li').on('click', function() {
                var val = $(this).data('page');
                if (val == undefined) {
                    return false
                }
                getEmployeeList(val, size, {name: $('#J_search_input').data('val')})
            })
            $('#J_page_search input').on('keyup', function() {
                var number = $(this).val().replace(/\D/g,'') - 0;
                $(this).val(number);
                if ($(this).val().length < 1) {
                    $(this).val('1');
                    return false
                }
                if ($(this).val() < 1) {
                    $(this).val('1');
                    return false
                }
                if ($(this).val() > $('#J_page_box').data('max')) {
                    $(this).val($('#J_page_box').data('max'))
                    return false
                }
            })
            $('#J_page_search a').on('click', function() {
                var n = $('#J_page_search input').val();
                if (n > $('#J_page_box').data('max')) {
                    alert('已超过最大分页数')
                    return false;
                }
                getEmployeeList(n, size, {name: $('#J_search_input').data('val')})
            })
        })
    }
    getEmployeeList(1, 30)
    $('#apxModalManageEditRole').on('show.bs.modal', function(e) {
        $('#employee_name').val('');
        $('#employee_mobile').val('');
        $('#employee_remarks').val('');
        var $this = $(e.relatedTarget).parents('.J_employee');
        if ($this.hasClass('J_employee')) {
            $('#apxModalManageEditRole').data('id', $this.find('.J_employee_id').html());
            $('#apxModalManageEditRole .modal-title').html('修改员工信息');
            $('#employee_name').val($this.find('.J_employee_name').html());
            $('#employee_mobile').val($this.find('.J_employee_mobile').html());
            $('#employee_remarks').val($this.find('.J_employee_remarks').html());
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalManageEditRole').modal('hide');
                var id = $('#apxModalManageEditRole').data('id');
                var name = $('#employee_name').val().trim();
                var mobile = $('#employee_mobile').val();
                if (name == '') {
                    $('#J_alert_content').html('请填写姓名！')
                    $('#apxModalAdminAlert').modal('show');
                    return
                }
                if (mobile != '') {
                    var resultMB = mobile.search(/0?(1)[0-9]{10}/);
                    if (resultMB == -1) {
                        $('#J_alert_content').html('手机号格式错误！')
                        $('#apxModalAdminAlert').modal('show');
                        return
                    }
                } else {
                    mobile = 0;
                }
                var employee = {
                    name: name,
                    mobile: mobile,
                    remark: $('#employee_remarks').val()
                }
                requestUrl('/site/employee/edit-employee', 'POST', {id: id, set_employee: employee}, function(data) {
                   $('#J_alert_content').html('修改成功！')
                   $('#apxModalAdminAlert').modal('show');
                   var page = $('#J_page_box li[class*="active"]').data('page');
                   getEmployeeList(page, 30, {name: $('#J_search_input').data('val')});
                })
            })
        } else {
            $('#apxModalManageEditRole .modal-title').html('新增员工');
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalManageEditRole').modal('hide');
                var name = $('#employee_name').val().trim();
                var mobile = $('#employee_mobile').val();
                if (name == '') {
                    $('#J_alert_content').html('请填写姓名！')
                    $('#apxModalAdminAlert').modal('show');
                    return
                }
                if (mobile != '') {
                    var resultMB = mobile.search(/0?(1)[0-9]{10}/);
                    if (resultMB == -1) {
                        $('#J_alert_content').html('手机号格式错误！')
                        $('#apxModalAdminAlert').modal('show');
                        return
                    }
                } else {
                    mobile = 0;
                }
                var employee = {
                    name: name,
                    mobile: mobile,
                    remark: $('#employee_remarks').val()
                }
                requestUrl('/site/employee/add-employee', 'POST', {set_employee: employee}, function(data) {
                   $('#J_alert_content').html('添加成功！')
                   $('#apxModalAdminAlert').modal('show'); 
                   getEmployeeList(1, 30, {name: $('#J_search_input').data('val')});
                })
            })
        }
    })
    //删除员工
    $('#apxModalAdminAlertDel').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget).parents('.J_employee');
        $('#apxModalAdminAlertDel').data('id', $this.find('.J_employee_id').html());
        $('.J_alert_name').html($this.find('.J_employee_name').html());
        $('.J_alert_mobile').html($this.find('.J_employee_mobile').html());
        $('.J_alert_remarks').html($this.find('.J_employee_remarks').html());
    })
    $('#J_dele_sure').on('click', function() {
        $('#apxModalAdminAlertDel').modal('hide');
        requestUrl('/site/employee/remove-employee', 'POST', {id: $('#apxModalAdminAlertDel').data('id')}, function(data) {
            $('#J_alert_content').html('删除成功！')
            $('#apxModalAdminAlert').modal('show');
            var page = $('#J_page_box li[class*="active"]').data('page');
            getEmployeeList(page, 30, {name: $('#J_search_input').data('val')});
        })
    })
    //搜索员工
    $('#J_search_btn').on('click', function() {
        $('#J_search_input').data('val', $('#J_search_input').val());
        getEmployeeList(1, 30, {name: $('#J_search_input').data('val')})
    })
    $('#J_search_input').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('#J_search_btn').click()
            e.preventDefault()
        }
    })
})