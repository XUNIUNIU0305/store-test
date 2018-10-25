$(function () {
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
   
    $('#apxModalManageStore').on('shown.bs.modal', function (e) {
        // refresh api
        scrolls.forEach(function (scroll) {
            scroll.refresh();
        })
    })
    getOperator('', Top);
    //获取运营商信息
    function getOperator(data, fn, flag) {
        requestUrl('/site/business/list', 'GET', data, function(data) {
            var result = juicer(tpl_list, data);
            if (typeof(fn) == 'function') fn(result);
            scrolls.forEach(function (scroll) {
                scroll.refresh();
            })
        })
    }
    //省级列表
    var tpl_list = $('#J_tpl_list').html();
    var tpl_list_two = $('#J_tpl_list_two').html();
    var tpl_list_third = $('#J_tpl_list_third').html();
    function Top(data) {
        $('#J_province_list').html(data);
        $('#J_city_list').html('');
        $('#J_district_list').html('');
        $('#J_group_list').html('');
        $('.J_add_city').addClass('hide');
        $('.J_add_district').addClass('hide');
        $('.J_add_group').addClass('hide');
        //二级联动
        $('#J_province_list .J_parent').off('.top').on('click.top', function() {
            $(this).addClass('active').siblings('.J_parent').removeClass('active');
            $('#J_detail_list').addClass('hide');
            $('.J_add_city').removeClass('hide');
            $('.J_add_district').addClass('hide');
            $('.J_add_group').addClass('hide');
            var _data = {
                top: $(this).data('id')
            }
            getOperator(_data, Second)
        })
    }
    //二级列表
    function Second(data) {
        $('#J_city_list').html(data);
        $('#J_district_list').html('');
        $('#J_group_list').html('');
        //三级联动
        $('#J_city_list .J_parent').off('.second').on('click.second', function() {
            $(this).addClass('active').siblings('.J_parent').removeClass('active');
            $('#J_detail_list').addClass('hide');
            $('.J_add_district').removeClass('hide');
            $('.J_add_group').addClass('hide');
            var _data = {
                    top: $('#J_province_list .J_parent[class*="active"]').data('id'),
                    secondary: $(this).data('id')
                }
            getOperator(_data, Third, 3)
        })
    }
    //三级列表
    function Third(data) {
        $('#J_district_list').html(data);
        $('#J_group_list').html('');
        //四级联动
        $('#J_district_list .J_parent').off('.third').on('click.third', function() {
            $(this).addClass('active').siblings('.J_parent').removeClass('active');
            $('#J_detail_list').removeClass('hide');
            $('.J_add_group').removeClass('hide');
            $('.J_more_leader').html('&nbsp;');
            $('.J_top').html($('#J_province_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_second').html($('#J_city_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_third').html($('#J_district_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_fourth').html('');
            $('.J_edit_btn_one').data('area', 3);
            $('.J_edit_btn_one').data('parent', $(this).data('id'));
            var _data = {
                    top: $('#J_province_list .J_parent[class*="active"]').data('id'),
                    secondary: $('#J_city_list .J_parent[class*="active"]').data('id'),
                    tertiary: $(this).data('id')
                }
            var $this = $(this);
            getOperator(_data, Fourth, 4);
            requestUrl('/site/business/leader', 'GET', {id: $this.data('id'), area_id: 3}, function(data) {
                $('.J_leader').html('督导：' + data.leader);
                $('.J_more_leader').html('&nbsp;');
            })
        })
        
    }
    //四级列表
    function Fourth(data) {
        $('#J_group_list').html(data);
        $('#J_group_list .J_parent').off().on('click', function() {
            $(this).addClass('active').siblings('.J_parent').removeClass('active');
            $('#J_detail_list').removeClass('hide');
            $('.J_top').html($('#J_province_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_second').html($('#J_city_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_third').html($('#J_district_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_fourth').html($('#J_group_list .J_parent[class*="active"]').find('.J_name').html());
            $('.J_more_leader').html();
            var $this = $(this);
            requestUrl('/site/business/commissar', 'GET', {id: $this.data('id'),area_id: 4}, function(data) {
                $('.J_leader').html('组长：' + data.leader);
                $('.J_more_leader').html('<div class="col-xs-6"><strong>政委：<span>' + data.commissar + '</span></strong></div>\
                        <div class="col-xs-6"><button class="btn btn-lg btn-default  J_edit_btn_two" data-toggle="modal" data-target="#apxModalManageStore">修改</button></div>')
                $('.J_edit_btn_one').data('area', 4);
                $('.J_edit_btn_one').data('parent', $this.data('id'));
                $('.J_edit_btn_two').data('area', 4);
                $('.J_edit_btn_two').data('parent', $this.data('id'));
            })
        })
    }
    //修改及添加
    $('#apxModalAdminAlertEdit').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        $('#edit_val').val('');
        //省
        if ($this.hasClass('J_add_province')) {
            $('#apxModalAdminAlertEdit .modal-title').html('新增');
            $('#apxModalAdminAlertEdit label').html('新增省为：');
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalAdminAlertEdit').modal('hide');
                var val = $('#edit_val').val();
                requestUrl('/site/business/add', 'POST', {area_id: 1, set_business:{title: val}}, function(data) {
                    showMsg('添加成功！');
                    getOperator('', Top);
                })
            })
        }
        //市
        if ($this.hasClass('J_add_city')) {
            $('#apxModalAdminAlertEdit .modal-title').html('新增');
            $('#apxModalAdminAlertEdit label').html('新增城市为：');
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalAdminAlertEdit').modal('hide');
                var val = $('#edit_val').val();
                var data = {
                    area_id: 2,
                    set_business: {
                        business_top_area_id: $('#J_province_list .J_parent[class*="active"]').data('id'),
                        title: val
                    }
                }
                requestUrl('/site/business/add', 'POST', data, function(data) {
                    showMsg('添加成功！');
                    $('#J_province_list .J_parent[class*="active"]').click();
                })
            })
        }
        //督导区
        if ($this.hasClass('J_add_district')) {
            $('#apxModalAdminAlertEdit .modal-title').html('新增');
            $('#apxModalAdminAlertEdit label').html('新增督导区为：');
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalAdminAlertEdit').modal('hide');
                var val = $('#edit_val').val();
                var data = {
                    area_id: 3,
                    set_business: {
                        business_top_area_id: $('#J_province_list .J_parent[class*="active"]').data('id'),
                        business_secondary_area_id: $('#J_city_list .J_parent[class*="active"]').data('id'),
                        business_area_leader_id: 1,
                        title: val
                    }
                }
                requestUrl('/site/business/add', 'POST', data, function(data) {
                    showMsg('添加成功！');
                    $('#J_city_list .J_parent[class*="active"]').click();
                })
            })
        }
        //组
        if ($this.hasClass('J_add_group')) {
            $('#apxModalAdminAlertEdit .modal-title').html('新增');
            $('#apxModalAdminAlertEdit label').html('新增组为：');
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalAdminAlertEdit').modal('hide');
                var val = $('#edit_val').val();
                var data = {
                    area_id: 4,
                    set_business: {
                        business_top_area_id: $('#J_province_list .J_parent[class*="active"]').data('id'),
                        business_secondary_area_id: $('#J_city_list .J_parent[class*="active"]').data('id'),
                        business_tertiary_area_id: $('#J_district_list .J_parent[class*="active"]').data('id'),
                        business_area_leader_id: 1,
                        commissar: 1,
                        title: val
                    }
                }
                requestUrl('/site/business/add', 'POST', data, function(data) {
                    showMsg('添加成功！');
                    $('#J_district_list .J_parent[class*="active"]').click();
                })
            })
        }
        //修改运营商
        if ($this.hasClass('J_edit')) {
            $('#apxModalAdminAlertEdit .modal-title').html($this.parents('.J_parent').find('.J_name').html());
            $('#apxModalAdminAlertEdit label').html('修改为：');
            $('#J_edit_sure').off().on('click', function() {
                $('#apxModalAdminAlertEdit').modal('hide');
                requestUrl('/site/business/edit', 'POST', {id: $this.parents('.J_parent').data('id'), area_id: $this.parents('.J_area').data('id'), set_business:{title: $('#edit_val').val()}}, function(data) {
                    showMsg('修改成功！');
                    var id = $this.parents('.J_area').data('id');
                    if (id > 1) {
                        $('.J_area[data-id="' + (id - 1) + '"]').find('li[class*="active"]').click();
                    } else {
                        getOperator('', Top)
                    }
                })
            })
        }
    })

    //修改负责人
    var tpl = $('#J_tpl_employee').html();
    $('#apxModalManageStore').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);
        $('#J_search_input').val('');
        requestUrl('/site/employee/list', 'GET', '', function(data) {
            $('#J_employee_list').html(juicer(tpl, data.codes));
            $('#J_employee_list .J_employee').on('click', function() {
                $(this).addClass('active').siblings('.J_employee').removeClass('active');
            })
        })
        if ($('#J_employee_list .J_employee').length > 0) $('#J_employee_list .J_employee').removeClass('active');
        if ($this.data('area') == 3) {
           $('#J_replace_sure').off().on('click', function() {
                var data = {
                    id: $this.data('parent'),
                    area_id: 3,
                    set_business: {
                        business_area_leader_id: $('#J_employee_list .J_employee[class*="active"]').data('id')
                    }
                }
                if ($('#J_employee_list .J_employee[class*="active"]').data('id') == undefined) {
                    $('#J_alert_content').html('请选择人员！');
                    $('#apxModalAdminAlert').modal('show');
                    return
                }
                $('#apxModalManageStore').modal('hide');
                requestUrl('/site/business/edit', 'POST', data, function(data) {
                    showMsg('修改成功！');
                    $('#J_district_list .J_parent[class*="active"]').click();
                })
           }) 
        }
        if ($this.data('area') == 4) {
            $('#J_replace_sure').off().on('click', function() {
                if ($this.hasClass('J_edit_btn_one')) {
                    var data = {
                        id: $this.data('parent'),
                        area_id: 4,
                        set_business: {
                            business_area_leader_id: $('#J_employee_list .J_employee[class*="active"]').data('id')
                        }
                    }
                } else if ($this.hasClass('J_edit_btn_two')) {
                    var data = {
                        id: $this.data('parent'),
                        area_id: 4,
                        set_business: {
                            commissar: $('#J_employee_list .J_employee[class*="active"]').data('id')
                        }
                    }
                }
                if ($('#J_employee_list .J_employee[class*="active"]').data('id') == undefined) {
                    $('#J_alert_content').html('请选择人员！');
                    $('#apxModalAdminAlert').modal('show');
                    return
                }
                $('#apxModalManageStore').modal('hide');
                requestUrl('/site/business/edit', 'POST', data, function(data) {
                    showMsg('修改成功！');
                    $('#J_group_list .J_parent[class*="active"]').click();
                })
           })
        }
    })
    function showMsg(cont) {
        $('#J_alert_content').html(cont);
        $('#apxModalAdminAlert').modal('show');
    }

    //删除
    $('#apxModalAdminAlertDel').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget).parents('.J_parent');
        $('#apxModalAdminAlertDel').data('id', $this.data('id'));
        $('#J_dele_sure').off().on('click', function() {
            $('#apxModalAdminAlertDel').modal('hide');
            requestUrl('/site/business/remove', 'POST', {id: $this.data('id'), area_id: $this.parents('.J_area').data('id')}, function(data) {
                showMsg('删除成功！');
                var id = $this.parents('.J_area').data('id');
                if (id > 1) {
                    $('.J_area[data-id="' + (id - 1) + '"] li[class*="active"]').click() 
                } else {
                    getOperator('', Top)
                }
            })
        })
    })
    $('#J_search_btn').on('click', function() {
        requestUrl('/site/employee/list', 'GET', {search: {name: $('#J_search_input').val()}}, function(data) {
            $('#J_employee_list').html(juicer(tpl, data.codes));
            setTimeout(function() {
                scrolls.forEach(function (scroll) {
                    scroll.refresh();
                })
            }, 300)
            $('#J_employee_list .J_employee').on('click', function() {
                $(this).addClass('active').siblings('.J_employee').removeClass('active');
            })
        })
    })
    $('#J_search_input').on('keydown', function(e) {
        if (e.keyCode == 13) {
           $('#J_search_btn').click()
        }
    })
})