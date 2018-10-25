(function () {
    // 获取账号等级
    ;(!function getUserInfo() {
        requestUrl('/index/userinfo', 'GET', '', function(data) {
             if (data.level == 4) {
                 $('#J_bank_pay').removeClass('hidden')
             }
            if (data.level !== 2) {
                $('.J_apex_tab').removeClass('hidden')
            }
        })
    }());

    $.each($('select'), function() {
        $(this).val($(this).find('option').eq(0).attr('value'))
    })
    var $productModal = $('#product-detail').modal({ show: false });
    var $priceModal = $('#price-detail').modal({ show: false });
    var $productList = $('#product-list');
    
    var products = {}, balance = 0, formData = {};
    var $itemBox = $('#item-box');
    var itemTpl = $('#J_tpl_item').html();

    var addressTpl = '{@each items as item,k}<div class="col-xs-4">' +
        '<div class="apx-cart-address{@if item.is_default} default active{@/if}" data-index="${k}">' +
        '<h5>${item.city.name}&nbsp;${item.district.name}<span>（${item.consignee}）</span></h5>' +
        '<p>地址：${item.province.name} ${item.city.name} ${item.district.name}${item.detail}</p>' +
        '<p>电话：${item.mobile}</p>' +
        '</div>' +
        '</div>{@/each}';
    var $addressBox = $('#address-box');
    // 获取商品信息
    requestUrl('/membrane/product/index', 'get', {}, function (data) {
        products = data[0];
        $productList.on('click', 'li', function () {
            var index = $(this).data('id');
            var data = {};
            data['blocks'] = products.blocks;
            data['price'] = products.params[index].price;
            data['id'] = products.params[index].id;
            data['title'] = products.params[index].name;
            var html = juicer(itemTpl, data);
            $itemBox.append(html);
            refreshTotalPrice()
        })
        $productList.on('click', '.no-btn.detail', function(){
            $productModal.modal('show')
        })
        $productList.on('click', '.no-btn.type', function(){
            $priceModal.modal('show')
        })
        $itemBox.on('click', '.delete-btn', function () {
            var $this = $(this)
            $this.parents('.product').remove()
            refreshTotalPrice()
        })
    })

    // 2018/06/08 车膜更新
    var membrane = {
        infoData: [],
        tianyuPackage: {
            '1': [
                {
                    label: 1,
                    value: 1
                },
                {
                    label: 2,
                    value: 2
                },
                {
                    label: 3,
                    value: 2
                },
                {
                    label: 4,
                    value: 2
                },
                {
                    label: 5,
                    value: 2
                },
                {
                    label: 6,
                    value: 2
                }
            ],
            '2': [
                {
                    label: 1,
                    value: 1
                },
                {
                    label: 2,
                    value: 3
                },
                {
                    label: 3,
                    value: 3
                },
                {
                    label: 4,
                    value: 3
                },
                {
                    label: 5,
                    value: 3
                },
                {
                    label: 6,
                    value: 3
                }
            ],
            '3': [
                {
                    label: 1,
                    value: 1
                },
                {
                    label: 2,
                    value: 4
                },
                {
                    label: 3,
                    value: 4
                },
                {
                    label: 4,
                    value: 4
                },
                {
                    label: 5,
                    value: 4
                },
                {
                    label: 6,
                    value: 4
                }
            ]
        },
        apexPackage: {
            1: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 6
                },
                {
                    label: 3,
                    value: 6
                },
                {
                    label: 4,
                    value: 6
                },
                {
                    label: 5,
                    value: 6
                },
                {
                    label: 6,
                    value: 6
                }
            ],
            2: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 7
                },
                {
                    label: 3,
                    value: 7
                },
                {
                    label: 4,
                    value: 7
                },
                {
                    label: 5,
                    value: 7
                },
                {
                    label: 6,
                    value: 7
                }
            ],
            3: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 8
                },
                {
                    label: 3,
                    value: 8
                },
                {
                    label: 4,
                    value: 8
                },
                {
                    label: 5,
                    value: 8
                },
                {
                    label: 6,
                    value: 8
                }
            ],
            4: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 9
                },
                {
                    label: 3,
                    value: 9
                },
                {
                    label: 4,
                    value: 9
                },
                {
                    label: 5,
                    value: 9
                },
                {
                    label: 6,
                    value: 9
                }
            ],
            5: [
                {
                    label: 1,
                    value: 5
                },
                {
                    label: 2,
                    value: 10
                },
                {
                    label: 3,
                    value: 10
                },
                {
                    label: 4,
                    value: 10
                },
                {
                    label: 5,
                    value: 10
                },
                {
                    label: 6,
                    value: 10
                }
            ]
        },
        apexItems: {},
        getInfo: function() {
            // 获取车膜信息
            requestUrl('/membrane/product/index', 'GET', '', function(data) {
                membrane.infoData = data;
                // 渲染信息
                $.each(data[0].params, function(i, val) {
                    $('#coefficient_1 option[value="' + val.id + '"]').data('price', val.price)
                })
                // 初始化欧帕斯基准价
                var params = data[1].params;
                $('#benchmark-price-2').text(params[0].price.toFixed(2))
                $('#european-package li').eq(0).data('price', params[0].price);
                $('#european-package li').eq(1).data('price', params[5].price);
                $('#european-package li').eq(2).data('price', params[10].price);
                $('#european-package li').eq(3).data('price', params[15].price);
                $('#european-package li').eq(4).data('price', params[20].price);
            })
        },
        countTPrice: function() {
            // 计算天御价格  
            var val = $('#coefficient_1').val();
            if (val === '-1') {
                $('#purchase-price-1').text('0.00');
                $('#total-price').text('0.00');
                return
            }
            var price = $('#coefficient_1').find('option[value="' + val + '"]').data('price');
            var num = $('#ty-purchase-quantity').text() - 0;
            $('#total-num').text(num);
            $('#purchase-price-1').text(price.toFixed(2));
            $('#total-price').text((price * num).toFixed(2));
        },
        countOPrice: function() {
            // 计算欧帕斯价格  
            var apex = membrane.infoData[1].params;
            var package = {
                1: {
                    1: {
                        price: apex[0].price,
                        id: apex[0].id
                    },
                    2: {
                        price: apex[1].price,
                        id: apex[1].id,
                    },
                    3: {
                        price: apex[2].price,
                        id: apex[2].id,
                    },
                    4: {
                        price: apex[3].price,
                        id: apex[3].id,
                    },
                    5: {
                        price: apex[25].price,
                        id: apex[25].id,
                    },
                    6: {
                        price: apex[4].price,
                        id: apex[4].id,
                    },
                },
                2: {
                    1: {
                        price: apex[5].price,
                        id: apex[5].id,
                    },
                    2: {
                        price: apex[6].price,
                        id: apex[6].id,
                    },
                    3: {
                        price: apex[7].price,
                        id: apex[7].id,
                    },
                    4: {
                        price: apex[8].price,
                        id: apex[8].id,
                    },
                    5: {
                        price: apex[26].price,
                        id: apex[26].id,
                    },
                    6: {
                        price: apex[9].price,
                        id: apex[9].id,
                    },
                },
                3: {
                    1: {
                        price: apex[10].price,
                        id: apex[10].id,
                    },
                    2: {
                        price: apex[11].price,
                        id: apex[11].id,
                    },
                    3: {
                        price: apex[12].price,
                        id: apex[12].id,
                    },
                    4: {
                        price: apex[13].price,
                        id: apex[13].id,
                    },
                    5: {
                        price: apex[27].price,
                        id: apex[27].id,
                    },
                    6: {
                        price: apex[14].price,
                        id: apex[14].id,
                    },
                },
                4: {
                    1: {
                        price: apex[15].price,
                        id: apex[15].id
                    },
                    2: {
                        price: apex[16].price,
                        id: apex[16].id,
                    },
                    3: {
                        price: apex[17].price,
                        id: apex[17].id,
                    },
                    4: {
                        price: apex[18].price,
                        id: apex[18].id,
                    },
                    5: {
                        price: apex[28].price,
                        id: apex[28].id,
                    },
                    6: {
                        price: apex[19].price,
                        id: apex[19].id,
                    },
                },
                5: {
                    1: {
                        price: apex[20].price,
                        id: apex[20].id
                    },
                    2: {
                        price: apex[21].price,
                        id: apex[21].id,
                    },
                    3: {
                        price: apex[22].price,
                        id: apex[22].id
                    },
                    4: {
                        price: apex[23].price,
                        id: apex[23].id,
                    },
                    5: {
                        price: apex[29].price,
                        id: apex[29].id,
                    },
                    6: {
                        price: apex[24].price,
                        id: apex[24].id,
                    },
                }
            }
            membrane.apexItems = package;
            var val = $('#coefficient_2').val();
            var selected_p = $('#european-package li[class*="active"]').data('type');
            var num = $('#ops-purchase-quantity').text() - 0;
            $('#total-num').text(num);
            if (val === '-1') {
                $('#purchase-price-2').text('0.00');
                $('#total-price').text('0.00');
                return
            }
            var price = package[selected_p][val].price;
            $('#purchase-price-2').text(price.toFixed(2));
            $('#total-price').text((price * num).toFixed(2));
        },
        init: function() {
            this.getInfo()
            // 天御选择系数
            $('#coefficient_1').on('change', function() {
                membrane.countTPrice()
            })
            // 增加采购数量
            $('#ty-add-number').on('click', function() {
                $('#ty-del-number').removeClass('btn-disabled');
                var val = $('#ty-purchase-quantity').data('val') - 0;
                $('#ty-purchase-quantity').data('val', val + 1).text(val + 1);
                membrane.countTPrice()
            })
            // 减少采购数量
            $('#ty-del-number').on('click', function() {
                var val = $('#ty-purchase-quantity').data('val') - 1;
                if (val > 0) {
                    $('#ty-purchase-quantity').data('val', val).text(val);
                }
                if (val === 1) {
                    $('#ty-del-number').addClass('btn-disabled');
                }
                membrane.countTPrice()
            })

            // 欧帕斯套餐切换
            $('#european-package a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var price = $(this).parent().data('price');
                $('#benchmark-price-2').text(price.toFixed(2));
                membrane.countOPrice()
              })
            // 欧帕斯切换系数
            $('#coefficient_2').on('change', function() {
                membrane.countOPrice()
            })
            // 增加采购数量
            $('#ops-add-number').on('click', function() {
                $('#ops-del-number').removeClass('btn-disabled');
                var val = $('#ops-purchase-quantity').data('val') - 0;
                $('#ops-purchase-quantity').data('val', val + 1).text(val + 1);
                membrane.countOPrice()
            })
            // 减少采购数量
            $('#ops-del-number').on('click', function() {
                var val = $('#ops-purchase-quantity').data('val') - 1;
                if (val > 0) {
                    $('#ops-purchase-quantity').data('val', val).text(val);
                }
                if (val === 1) {
                    $('#ops-del-number').addClass('btn-disabled');
                }
                membrane.countOPrice()
            })
        }
    }
    membrane.init()

    // 
    $('#myTab a').on('shown.bs.tab', function() {
        var type = $(this).parent('li').data('type')
        if (type === 1) {
            membrane.countTPrice()
        } else {
            membrane.countOPrice()
        }
    })


    // 获取地址列表
    requestUrl('/membrane/home/address', 'get', {}, function(data){
        var html = juicer(addressTpl, { items: data })
        for(var i=0;i<data.length;i++){
            var add = data[i]
            if(add.is_default){
                fillAddress(add)
                formData.address = add.id
                break
            }
        }
        $addressBox.html(html)
        var $list = $addressBox.find('.apx-cart-address').on('click', function(){
            $list.removeClass('active')
            var index = this.getAttribute('data-index')
            var current = data[index]
            $(this).addClass('active')
            formData.address = current.id
            fillAddress(current)
        })
    })
    // 获取账户余额
    requestUrl('/membrane/home/balance', 'get', {}, function (data) {
        balance = data.rmb
        document.getElementById('balance').innerHTML = data.rmb
    })

    var $payment = $('.payment-btn').on('click', function(){
        if ($(this).hasClass('disabled')) {
            return false
        }
        $payment.removeClass('active')
        formData.method = this.getAttribute('data-payment')
        this.className = 'btn btn-default btn-sm payment-btn active'
    })

     var bankPay = {
        requestString: function(str) {
            var params = {};
            var search = str.slice(1);
            var arr = search.split("&");
            for (var i = 0; i < arr.length; i++) {
                var ar = arr[i].split("=");
                params[ar[0]] = unescape(ar[1])
            }
            return params;
        },
        submit: function(url, params) {
            var form = '<form action="' + url + '" method="post" target="_blank">'
            $.each(params, function(i, val) {
                form += '<input type="hidden" name="' + i + '" value="' + val + '" />'
            })
            form += '</form>';
            var dom = $(form);
            dom.appendTo('body').submit().remove();
        }
    }

    document.getElementById('submit').addEventListener('click', function(){
        // alert('此商品暂未开放购买！') 
        // return 
        
        var items = [];
        var type = $('#myTab li[class*="active"]').data('type');
        if (type === 1) {
            // 购买天御车膜
            // 系数ID
            var id = $('#coefficient_1').val();
            if (id === '-1') {
                alert('请选择系数！')
                return
            }
            // 购买数量
            var num = $('#ty-purchase-quantity').data('val');
            // 选择套餐
            var package = $('#J_tianyu_package li[class*="active"]').data('type');
            if (package !== 4) {
                var attributes = membrane.tianyuPackage[package];
            } else {
                var attributes = [];
                $.each($('#setMeal4 select'), function(i, val) {
                    var name = $(this).data('name');
                    var label = $(this).data('id');
                    var val = $(this).val();
                    if (val == '-1') {
                        alert('请选择' + name)
                        return false
                    }
                    attributes.push({
                        label: label,
                        value: val
                    })
                })
            }
            if (attributes.length < 6) {
                return
            }
            var remarks = $('#remarks').val();
            for (var i = 0; i < num; i++) {
                items.push({
                    id: id,
                    remark: remarks,
                    attributes: attributes
                })                
            }
        } else if (type === 2) {
            // 购买欧帕斯
            var val = $('#coefficient_2').val();
            if (val == -1) {
                alert('请选择系数！')
                return
            }
            var selected_p = $('#european-package li[class*="active"]').data('type');
            var num = $('#ops-purchase-quantity').text() - 0;
            var id = membrane.apexItems[selected_p][val].id;
            var attributes = membrane.apexPackage[selected_p];
            var remarks = $('#remarks-2').val();
            for (var i = 0; i < num; i++) {
                items.push({
                    id: id,
                    remark: remarks,
                    attributes: attributes
                })                
            }
        }



        if(!formData.method) {
            return alert('必须选择支付方式');
        }
        // $itemBox.find('.product').each(function(){
        //     var $this = $(this)
        //     var item = { attributes: [] }
        //     var attributes = $this.find('.form-select').each(function(){
        //         item.attributes.push({
        //             label: this.getAttribute('data-id'),
        //             value: this.value
        //         })
        //     })
        //     item.id = $this.find('.form-id').val()
        //     item.remark = $this.find('.form-remark').val()
        //     items.push(item)
        // })
        // if(items.length == 0)
        //     return alert('至少添加一个商品')
        if (formData.method == 1) {
            var balance = $('#balance').text() - 0;
            var _totalPrice = $('#total-price').text() - 0;
            if (_totalPrice > balance) {
                alert('余额不足，请选择其他支付方式！')
                return
            }
        }
        formData.items = items
        if (!formData.address) {
            alert('请选择收货地址！')
            return
        }
        $(this).addClass('disabled');
        requestUrl('/membrane/home/order', 'post', formData, function(data){
            if (formData.method == '4' || formData.method == '5') {
                var i = data.url.indexOf('?');
                var str = data.url.substring(i);
                var url = data.url.substring(0, i);
                var params = bankPay.requestString(str);
                $('#apxModalPass').modal({
                    keyboard: false,
                    backdrop: 'static',
                    modal: 'show'
                }).one('hidden.bs.modal', function() {
                    window.location.href = '/account/membrane'
                })
                $('#J_open_window').one('click', function() {
                    bankPay.submit(url, params);
                    $('#J_bank_success').addClass('hidden').siblings('p').removeClass('hidden');
                    $('#J_open_window').addClass('hidden').siblings('button').removeClass('hidden');
                })
                return
            }
            location.href = data.url
        }, function(data) {
            alert(data.data.errMsg);
            $('#submit').removeClass('disabled');
        })
    })

    var totalPriceDom = document.getElementById('total-price')
    var realPriceDom = document.getElementById('real-price')
    var totalNumDom = document.getElementById('total-num')
    function refreshTotalPrice(){
        var num = 0, price = 0;
        $itemBox.find('.product').each(function(){
            price += parseFloat(this.getAttribute('data-price'))
            num++
        })
        totalPriceDom.innerHTML = realPriceDom.innerHTML = '￥ ' + price
        totalNumDom.innerHTML = num
    }

    var sendToDom = document.getElementById('send-to')
    function fillAddress(data){
        sendToDom.innerHTML = '<span><b>寄送至：</b>' + data.province.name + data.city.name + data.district.name + data.detail +'</span>\n' +
            '<span class="phone">' + data.mobile + '</span>\n' +
            '<span class="consignee"><b>收货人：</b>' + data.consignee + '</span>';
    }
}())
