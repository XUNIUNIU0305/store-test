$(function() {
    var final_product_id = '',
        launch_btn_status = [0, 0];
    var tpl = $('#J_tpl_list').html();
    var compiled_tpl = juicer(tpl); //juicer生成模板
    function getProductList($column, id) {
        var data = {
            'parent_id' :  id ? id : ''
        }
        function productCb(data) {
            var dataObj = data.category;
            $column.find('li').off('.getProdList');
                var result = compiled_tpl.render(dataObj);
                $column.parents('.col-xs-4').next('.col-xs-4').find('.list-unstyled').empty();
                $column.empty().append(result);
                $column.find('li').on('click.getProdList', function(e) {
                    $(this).siblings().removeClass('selected')
                    $(this).addClass('selected')
                    launch_btn_status[0] = 0;
                    checklaunchBtnStatus();
                    var index = $('.apx-seller-multi-list .col-xs-4').index($(this).parents('.col-xs-4'));
                    $('.apx-seller-tip-box span').eq(index).nextAll().empty();
                    $('.apx-seller-tip-box span').eq(index).text((index !== 0 ? ' >' : ' ') + $(this).text().trim());
                    final_product_id = '';
                    if ($(this).parents('.col-xs-4').next('.col-xs-4').length === 0) {
                        final_product_id = $(this).attr('data-id');
                        launch_btn_status[0] = 1;
                        checklaunchBtnStatus();
                        return;
                    }
                    getProductList($(this).parents('.col-xs-4').next('.col-xs-4').find('.list-unstyled'), $(this).attr('data-id'))
                    e.stopPropagation();
                });
        }
        requestUrl("/release/category", 'GET', data, productCb)
    }

    // init the 1st column
    if ($('.apx-seller-multi-list').length > 0) getProductList($('.apx-seller-multi-list > .col-xs-4').eq(0).find('.list-unstyled'));

    // launch product
    $('.J_read_rule').on('change', function() {
        if ($(this).is(':checked')) launch_btn_status[1] = 1;
        else launch_btn_status[1] = 0;
        checklaunchBtnStatus();
    })

    function checklaunchBtnStatus() {
        if (launch_btn_status[0] && launch_btn_status[1]) $('.apx-seller-launch-btn').removeClass('disabled');
        else $('.apx-seller-launch-btn').addClass('disabled');
    }

    $('.apx-seller-launch-btn').on('click', function(e) {
        if ($(this).hasClass('disabled')) return;
        // add category to cookie
        var category = $('.apx-seller-tip-box span').text().trim().split('>');
        Cookies.set('category-col-0', category[0]);
        Cookies.set('category-col-1', category[1]);
        Cookies.set('category-col-2', category[2]);

        $('.J_read_rule').click();
        window.open(location.pathname + '?category=' + final_product_id);
        e.stopPropagation();
    })

})
