$(function () {
    $('.J_modal_admin_add_attr .detail-promo-ammount .input-group-addon').on('click', function (e) {
        // add minus function
        if ($(this).children('.glyphicon-minus').length != 0) {
            var _val = $(this).siblings('input').val() - 1;
            $(this).siblings('input').val(_val < 1 ? 1 : _val);
        } else {
            $(this).siblings('input').val($(this).siblings('input').val() - 0 + 1);
        }
        // change the count of inputs
        updateAttrDetailList($(this).siblings('input').val());
    });

    $('.J_modal_admin_add_attr .detail-promo-ammount input').on('change', function (e) {
        updateAttrDetailList($(this).val());
    })
    function updateAttrDetailList(count) {

        if ($('.attr-detail-box .form-group').length == (count - 0)) return;
        else if ($('.attr-detail-box .form-group').length > (count - 0)) {
            $('.attr-detail-box .form-group').last().remove();
            updateAttrDetailList(count)
        } else {
            var template = '<div class="form-group col-xs-4">\
	                        <input type="text" class="form-control">\
	                    </div>'
            $('.attr-detail-box').append(template);
            updateAttrDetailList(count)
        }
    }
    //请求API接口地址
    function getAPI() {
        function apiCB(data) {
            $("#J_menu_box").data('herf', data.hostname);
            // init the 1st column
            if ($('.apx-admin-multi-list').length > 0) 
                getProductList($('#J_menu_box').data('herf'), $('.apx-admin-multi-list > .col-xs-4').eq(0).find('.list-unstyled'));
        }
        requestUrl('/api-hostname', 'GET', '', apiCB)
    }

    getAPI()

    //获取分类列表
    var final_product_id = '';
    var tpl = $('#J_tpl_list').html();
    var compiled_tpl = juicer(tpl); //juicer生成模板
    function getProductList(address, $column, id) {
        var _address = address + "/category/category";
        var data = {
            'parent_category_id': id ? id : ''
        }
        function productCB(data) {
            var dataObj = data;
            $column.find('li').off('.getProdList');
            var result = compiled_tpl.render(dataObj);
            $column.parents('.col-xs-4').next('.col-xs-4').find('.list-unstyled').empty();
            $column.empty().append(result);
            $column.find('li').hover(function() {
                $(this).find('.pull-right').removeClass('invisible')
            }, function() {
                $(this).find('.pull-right').addClass('invisible')
            });
            $column.siblings('a').removeClass('invisible');
            bindingEvent();
            $column.find('li').on('click.getProdList', function(e) {
                $(this).siblings().removeClass('selected');
                $(this).addClass('selected');
                $('.J_attr_big').addClass('invisible');
                $('.J_sort_keyword').addClass('invisible');
                $column.parents('.col-xs-4').next('.col-xs-4').find('.list-unstyled').data('id', $(this).data('id'));
                var index = $('.apx-admin-multi-list .col-xs-4').index($(this).parents('.col-xs-4'));
                final_product_id = '';
                if ($(this).parents('.col-xs-4').next('.col-xs-4').length === 0) {
                    final_product_id = $(this).attr('data-id');
                    return;
                }
                getProductList($('#J_menu_box').data('herf'), $(this).parents('.col-xs-4').next('.col-xs-4').find('.list-unstyled'), $(this).attr('data-id'))
                e.stopPropagation();
            });
        }
        requestUrl(_address, 'GET', data, productCB)
    }
    //获取属性列表
    var tpl_attr = $('#J_tpl_attr').html();
    var attr_tpl = juicer(tpl_attr);
    var data_keyword = [];
    function getAttrs(id) {
        var data = {
            end_category_id: id
        }
        function attrsCB(data) {
            $('.J_attr_big').removeClass('invisible');
            $('#J_attrs_box').html(attr_tpl.render(data.attribute));
            //展示分类关键字
            $('.J_sort_keyword').removeClass('invisible');
            buildTag(2);
            $('.input_list').find('input').val('');
            var span_text = $('.input_list').find('span');
            data_keyword = data.keyword;
            span_text.each(function(index){
                $(this).text(data.keyword[index]);
                if ( index >= data.keyword.length) {
                    $(this).text('');
                }
            });
            //结束
        }
        requestUrl('/site/category/attributes', 'GET', data, attrsCB)
    }

    //绑定删除按钮hover
    $('#J_attrs_box').on('mouseenter', '.J_dele_one_attr', function() {
        // $(this).addClass('active');
        $(this).find('span').removeClass('invisible');
    }).on('mouseleave', '.J_dele_one_attr', function() {
        // $(this).removeClass('active');
        $(this).find('span').addClass('invisible');
    })
    //绑定属性展示事件
    $('.J_list_end').on('click', 'li', function(e) {
        $('.J_attr_big .title .col-xs-4').eq(0).html($('#J_menu_box .list-unstyled li[class*="selected"] > a').eq(0).html()); 
        $('.J_attr_big .title .col-xs-4').eq(1).html($('#J_menu_box .list-unstyled li[class*="selected"] > a').eq(1).html()); 
        $('.J_attr_big .title .col-xs-4').eq(2).html($('#J_menu_box .list-unstyled li[class*="selected"] > a').eq(2).html()); 
        $('#modalAddAttr').data('id', $(this).data('id'));
        getAttrs($(this).data('id'));
        e.stopPropagation()
    })

    //添加分类
    function addSort(id, name) {
        var data = {
            parent_category_id: id ? id : '',
            name: name
        }
        function addCB(data) {
            window.location.reload();
            alert('添加成功！')
        }
        requestUrl('/site/category/add', 'POST', data, addCB)
    }
    //绑定添加分类事件
    $('.J_add_sort').on('click', function() {
        $('#modalAddType .J_add_end').data('flag', true);
        var id = $(this).siblings('ul').data('id');
        if (id == undefined) {
            id = 0
        }
        $('#modalAddType').data('id', id);
    })
    //提交新类别
    $('#modalAddType .J_add_end').on('click', function() {
        var flag = $(this).data('flag');
        if (flag != false) {
            var id = $(this).parents('#modalAddType').data('id');
            var name = $('#p_type').val();
            addSort(id, name);
        } else {
            var id = $(this).parents('#modalAddType').data('id');
            var name = $('#p_type').val();
            editSort(id, name);
        }
    })
    $('#p_type').on('keydown', function(e) {
        if (e.keyCode == 13) {
            return false
        }
        e.stopPropagation()
    })
    //绑定删除和修改事件
    function bindingEvent() {
        //删除分类
        $('#J_menu_box .J_dele_sort').off('.dele').on('click.dele', function(e) {
            var id = $(this).parents('li').data('id');
            var flag = confirm('确定删除？');
            if (!flag) {
                return;
            }
            var data = {
                category_id: id
            }
            function delCB(data) {
                alert('删除成功！')
                window.location.reload()
            }
            requestUrl('/site/category/remove', 'POST', data, delCB)
            e.stopPropagation();
        })
        //修改分类事件
        $('#J_menu_box .J_edit_sort').off('.edit').on('click.edit', function(e) {
            $('#modalAddType .J_add_end').data('flag', false);
            $('#modalAddType').data('id', $(this).parents('li').data('id'));
            $('#modalAddType').modal();
            $('#modalAddType .modal-title').html('修改名称：'+ $(this).parent('.pull-right').siblings('a').html());
            $('#modalAddType .modal-body label').html('请输入新的商品类别名称：');
            e.stopPropagation();
        })
    }
    //修改函数
    function editSort(id, name) {
        var data = {
            category_id: id,
            new_name: name
        }
        function editCB(data) {
            alert('修改成功！')
            window.location.reload()
        }
        requestUrl('/site/category/edit', 'POST', data, editCB);
    }

    $('#modalAddAttr .attr-detail-box input').length
    //添加属性
    function addAttrs(id, name, option) {
        var data = {
            end_category_id: id,
            attribute_name: name,
            attribute_options: option
        }
        function addAttrsCB(data) {
            $('#modalAddAttr .J_attr_end').removeClass('disabled');
            alert('添加成功！');
            getAttrs($('#modalAddAttr').data('id'));
            $('.close[type="button"]').click();
            $('#attr-name').val('');
            $('#modalAddAttr .attr-detail-box input').val('');
        }
        function errorCB(data) {
            $('#modalAddAttr .J_attr_end').removeClass('disabled');
            alert(data.data.errMsg);
        }
        requestUrl('/site/category/add-attribute', 'POST', data, addAttrsCB, errorCB);
    }
    
    //绑定修改类别规范事件
    $('#modalAddAttr').on('show.bs.modal', function(e) {
        $('#modalAddAttr #attr-name').data('id', undefined);
        $('#modalAddAttr input').not('input[title="请输入数量"]').val('');
        $('#modalAddAttr input[title="请输入数量"]').val(12);
        $('.J_modal_admin_add_attr .detail-promo-ammount .input-group-addon').click();
        var $this = $(e.relatedTarget);
        if ($this.hasClass('newflag')) {
            $('#modalAddAttr .modal-title').html('新类别规范');
            //绑定添加属性事件
            $('#modalAddAttr .J_attr_end').off().on('click', function() {
                var id = $('#modalAddAttr').data('id');
                var name = $('#modalAddAttr #attr-name').val().trim();
                if (name == '') {
                    alert('属性名称不能为空！')
                    return false
                }
                var attr = [];
                $('#modalAddAttr .attr-detail-box input').each(function(i) {
                    if ($(this).val().trim() != '') {
                        attr.push($(this).val())
                    }
                })
                if (attr.length <= 0) {
                    alert('至少添加一个属性！')
                    return false
                }
                $('#modalAddAttr .J_attr_end').addClass('disabled');
                addAttrs(id, name, attr)
            })
        } else {
            $('#modalAddAttr .modal-title').html('新增类别规范');
            var id = $this.data('id');
            var attrName = $this.find('span').html();
            var len = $this.siblings('dd').find('label').length;
            $('#modalAddAttr input[title="请输入数量"]').val(12);
            $('.J_modal_admin_add_attr .detail-promo-ammount .input-group-addon').click();
            $('#modalAddAttr #attr-name').data('id', id);
            $('#modalAddAttr #attr-name').val(attrName);
            $('#modalAddAttr .J_attr_end').off().on('click', function() {
                var id = $('#modalAddAttr #attr-name').data('id');
                var name = $('#modalAddAttr #attr-name').val().trim();
                if (name == '') {
                    alert('属性名称不能为空！')
                    return false
                }
                var attr = [];
                $('#modalAddAttr .attr-detail-box input').each(function(i) {
                    if ($(this).val().trim() != '') {
                        attr.push($(this).val())
                    }
                })
                if (attr.length <= 0) {
                    alert('至少添加一个属性！')
                    return false
                }
                $('#modalAddAttr .J_attr_end').addClass('disabled');
                editOldAttr(id, name, attr)
            })
        }
    })
    //增加属性
    function editOldAttr(id, name, option) {
        var data = {
            attribute_id: id,
            attribute_name: name,
            attribute_options: option
        }
        function editAttrsCB(data) {
            $('#modalAddAttr .J_attr_end').removeClass('disabled');
            alert('添加成功！');
            getAttrs($('#modalAddAttr').data('id'));
            $('.close[type="button"]').click();
            $('#attr-name').val('');
            $('#modalAddAttr .attr-detail-box input').val('');
        }
        function errorCB(data) {
            $('#modalAddAttr .J_attr_end').removeClass('disabled');
            alert(data.data.errMsg);
        }
        requestUrl('/site/category/edit-attribute', 'POST', data, editAttrsCB, errorCB);
    }
    //删除单个属性
    commonConfrim();
    $('#J_attrs_box').on('click', '.J_dele_one_attr span', function() {
        var id = $(this).siblings('label').data('id');
        $('#apxModalAdminConfrim').modal();
        function deleOneCB(data) {
            getAttrs($('#modalAddAttr').data('id'));
        }
        $('#J_common_sure').off().on('click', function() {
            $('#apxModalAdminConfrim').modal('hide');
            requestUrl('/site/category/delete-option', 'POST', {option_id: id}, deleOneCB)
        })
    })

    //添加关键字功能
    $('.btn-contain').on('click.modify','.J_modify_btn',function() {
        buildTag(1);
        var input_list = $('.input_list').find('input');
        input_list.each(function(index){
            $(this).val(data_keyword[index]);
            if ( index >= data_keyword.length) {
                $(this).val('');
            }
        });
    });    
  
    var inputArr = [];
    function buildTag(type) {
        if ( type === 1) {
            var sure_btn = '<span type="button" class="input-confirm-btn J_sure_btn" data-id="revise" data-toggle="modal" data-target="">确定</span>';
            $('.btn-contain').html(sure_btn);
            $('.input_list_detail').html('<input type="text">');
            $('.J_modify_btn').remove();
        } else {
            var modify_btn = '<span type="button" class="input-confirm-btn J_modify_btn" data-id="revise">修改</span>';
            $('.btn-contain').html(modify_btn);
            $('.input_list_detail').html('<span></span>');
            var span_text = $('.input_list').find('span');
            span_text.each(function(index){
                $(this).text(inputArr[index]);
                if ( index >= inputArr.length) {
                    $(this).text('');
                }
            });
            $('.J_sure_btn').remove();
        }
    }

    $('.btn-contain').on('click.sure','.J_sure_btn',function() {
        $(".input_list input").each(function() {
            if($.trim($(this).val()) != '' ) {
                inputArr.push($(this).val().trim());
            }
        });
        if(inputArr.length <= 0){
            alert("至少添加一个关键字！");
            $(".input-confirm-btn").attr("data-target","");
            return;
        }
        $(".input-confirm-btn").attr("data-target","#apxModalAdminAlert");
    });

    $('#add_conf').click(function() {
       var id_str = $(".J_list_end").find(".selected").attr("data-id");
        var data = {
            end_category_id:id_str,
            keyword:inputArr
        };
        requestUrl("/site/category/add-keyword",'POST',data,function(){
            buildTag(2);
            inputArr = [];
            getAttrs($('#modalAddAttr').data('id'));
        }); 
    });


})