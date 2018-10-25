$(function(){

    // init the affix bar
    $('.J_cart_affix_bar').length && initCartBarAffix();
    $(window).on('resize', function(){
        // refresh the affix bar
        destroyCartBarAffix();
        initCartBarAffix();
    })
    // affix bar for cart page
    function initCartBarAffix() {
        $('.J_cart_affix_bar').affix({
            offset: {
                top: ($('.J_cart_affix_bar').offset().top - $(window).innerHeight() + $('.J_cart_affix_bar').innerHeight())
            }
        })
    }
    function destroyCartBarAffix() {
        // destroy affix manually
        $('.J_cart_affix_bar').removeClass('affix-top').removeData();
        $(window).off('.affix');
    }

    // 生成商品列表
    function getList(data) {
        var tpl = $('#J_tpl_table').html();
        var price = function(data) {
            return data.toFixed(2)
        }
        juicer.register('price_build', price);
        var list = juicer(tpl,data);
        $('.J_list_box').html(list);
    }

    //修改商品订单数量
    function setNumber(id, number) {
        var _data
        var data = {
            item_id: id,
            count: number
        }
        function setCB(data) {
            _data = data
        }
        requestUrl('/cart/change', 'POST', data, setCB, '', false);
        return _data;
    }

    //删除订单
    function delProduct(id) {
        var data = {items_id: id};
        function delCB(data) {
            for (var i = 0; i < id.length; i++) {
                var _parent = $('.J_id_box[data-id="' + id[i] + '"]').parents('tbody');
                $('.J_id_box[data-id="' + id[i] + '"]').remove();
                $('.J_error_msg[data-id="' + id[i] + '"]').remove();
                if (_parent.find('.J_id_box').length < 1) {
                    _parent.remove();
                }
                if ($('.J_list_box .J_id_box').length < 1) {
                    $('.have-items').addClass('hidden');
                    $('.cart-none').removeClass('hidden');
                }
            }
            setTotalPrice();
        }
        requestUrl('/cart/remove', 'POST', data, delCB)
    }

    //计算选中商品数和总价
    function setTotalPrice() {
        var len = $('input:checked[class*="flag"]').length;
        var number = 0,
            price = 0;
        for (var i = 0; i < len; i++) {
            number += $('input:checked[class*="flag"]').eq(i).parents('.J_id_box').find('.J_only_int').val() - 0;
            price += $('input:checked[class*="flag"]').eq(i).parents('.J_id_box').attr('data-price') - 0;
        }
        $('.J_pro_number').html(number);
        $('.J_total_price').html('¥ ' + price.toFixed(2));
    }

    //获取选中商品ID
    function getProId() {
        var id = [];
        var len = $('input:checked[class*="flag"]').length;
        for (var i = 0; i < len; i++) {
            id.push($('input:checked[class*="flag"]').eq(i).parents('.J_id_box').data('id'))
        }
        return id;
    }

    //初始化购物车列表
    function initShoppingList() {
        var data = {
            page_size: 99999
        }
        function initCB(data) {
            //生成列表
            if (data.count < 1) {
                $('.cart-none').removeClass('hidden');
                return
            }
            $('.have-items').removeClass('hidden');
            getList(data);
            setTotalPrice();
            // init the affix bar
            destroyCartBarAffix();
            initCartBarAffix();

            var timeoutflag = null;
             //减少数量
            $('.J_input_box').on('click','.J_number_minus', function() {
                var nub = $(this).siblings('.J_only_int').val() - 0;
                if (nub > $(this).parents('.J_id_box').data('stock')) {
                    nub = $(this).parents('.J_id_box').data('stock');
                    $(this).siblings('.J_only_int').val(nub);
                    var data = setNumber($(this).parents('.J_id_box').data('id'), nub);
                    var _id = $(this).parents('.J_id_box').data('id');
                    $('.J_error_msg[data-id="' + _id + '"]').remove();
                    $(this).siblings('.J_only_int').val(data.count);
                    var _price = $(this).parents('.J_id_box').find('.J_end_price').data('price');
                    $(this).parents('.J_id_box').find('.J_end_price').html('¥ ' + (_price * data.count).toFixed(2));
                    $(this).parents('.J_id_box').attr('data-price', (_price * data.count).toFixed(2));
                    setTotalPrice();
                    return false;
                } 
                nub--;
                if (nub < 1) {
                    return false
                }
                $(this).siblings('.J_only_int').val(nub)
                var $this = $(this);
                if (timeoutflag != null) {
                    clearTimeout(timeoutflag)
                }
                timeoutflag = setTimeout(function() {
                    var data = setNumber($this.parents('.J_id_box').data('id'), $this.siblings('.J_only_int').val() - 0)
                    $this.siblings('.J_only_int').val(data.count);
                    var _price = $this.parents('.J_id_box').find('.J_end_price').data('price');
                    $this.parents('.J_id_box').find('.J_end_price').html('¥ ' + (_price * data.count).toFixed(2));
                    $this.parents('.J_id_box').attr('data-price', (_price * data.count).toFixed(2));
                    setTotalPrice();
                }, 1000)
            })

            //增加数量
            $('.J_input_box').on('click', '.J_number_add',function() {
                var nub = $(this).siblings('.J_only_int').val() - 0;
                nub++;
                if (nub > $(this).parents('.J_id_box').data('stock')) {
                    return false
                }
                $(this).siblings('.J_only_int').val(nub);
                var $this = $(this);
                if (timeoutflag != null) {
                    clearTimeout(timeoutflag)
                }
                timeoutflag = setTimeout(function() {
                    var data = setNumber($this.parents('.J_id_box').data('id'), $this.siblings('.J_only_int').val() - 0)
                    $this.siblings('.J_only_int').val(data.count);
                    var _price = $this.parents('.J_id_box').find('.J_end_price').data('price');
                    $this.parents('.J_id_box').find('.J_end_price').html('¥ ' + (_price * data.count).toFixed(2));
                    $this.parents('.J_id_box').attr("data-price", (_price * data.count).toFixed(2));
                    setTotalPrice();
                }, 1000)
            })

            //全选
            $('#apx_cart_checkAll').on('click', function() {
                var isChecked = $(this).prop("checked");
                $("input[type='checkbox'][disabled!=disabled]").prop("checked", isChecked);
                setTotalPrice();
            })
            $('#apx_cart_checkAll2').on('click', function() {
                var isChecked = $(this).prop("checked");
                $("input[type='checkbox'][disabled!=disabled]").prop("checked", isChecked);
                setTotalPrice();
            })
            //次级全选
            $('.J_list_box').on('click', '.J_checked_box', function() {
                var name = $(this).attr('name');
                var isChecked = $(this).prop("checked");
                $("input[name='" + name + "']").prop("checked", isChecked);
                setTotalPrice();
            })

            //删除订单
            $('.J_id_box').on('click', '.J_product_del', function() {
                var id = [];
                id[0] = $(this).parents('.J_id_box').data('id');
                var yes = confirm('确定要删除此订单？');
                if (yes == false) {
                    return
                }
                delProduct(id);
            })

            //批量删除订单
            $('.J_del_all').on('click', function() {
                var id = getProId();
                var yes = confirm('确定要删除此订单？');
                if (yes == false) {
                    return
                }
                delProduct(id);
            })

            //计算总数和总价
            $('input[class*="flag"]').on('click', function() {
                setTotalPrice()
            })

            //结算
            $('.J_go_buy').on('click', function() {
                var id = getProId();
                if (id.length <= 0) {
                    alert('请至少选择一件商品！')
                    return
                }
                var data = {
                    items_id: id
                }
                function buyCB(data) {
                    window.location.href = data.url
                }
                requestUrl('/cart/order', 'POST', data, buyCB)
            })
            
        }
        requestUrl('/cart/list', 'GET', data, initCB)
    }
    initShoppingList()
    var _likeTpl = $('#J_tpl_like').html();
    var _likeData = [1523, 1357, 1617, 1271, 1417];
    requestUrl('/product-recommend/goods', 'GET', {id: _likeData}, function(data) {
        var _data = [];
        for (var index = 0; index < data.length / 5; index++) {
            _data.push({
                item: data.slice(index * 5, index * 5 + 5)
            })
        }
        $('#J_like_box').html(juicer(_likeTpl, _data))
    })

})