$(function() {
    /*删除底部导航*/
    $('.J_footer_menu').remove();
    jdy.scroll.scrollFix($('.wechat-shopping')[0]);
})

$(function() {
    var _user_login_status = 0;

    function checkLoginStatus() {
        function success(data) {

            _user_login_status = data.status;
            if (data.status == 1) {
                $(".J_btn_login").hide();
                return true;
            } else {
                $(".J_btn_login").show();
                return false;
            }
        };

        requestUrl("/index/get-user-status", 'get', '', success);
    }
    //检测用户是否已登录
    checkLoginStatus();
    //点击选择
    var _flag = false;
    $('.wechat-shopping').on('touchmove', '.shop-main.mian-view', function() {
        _flag = true;
    })
    $('.wechat-shopping').on('touchend', '.shop-main.mian-view', function() {
        if (_flag) {
            _flag = false;
            return;
        }
        $(this).siblings('.shop-box').find('.unit-box').click()
    })

    // 商品单选
    $(".wechat-shopping").on('click', '.unit-box', function() {
        var unit_prices = Number($('.price-em').text());
        var counts = $(this).attr("data-count");
        var dataprice = $(this).attr("data-price");
        var prices = Number(counts) * Number(dataprice);
        var price = 0;
        if ($(this).hasClass("checked")) {
            price = (unit_prices * 10000) - (prices.toFixed(2) * 10000);
            price = price / 10000;
        } else {
            price += (Number(counts) * Number(dataprice)) + unit_prices;
        }
        $('.price-em').text(price.toFixed(2));
        labelclick($(this));
        var checkedl = $(this).parents('.shopping-list').find("input[name='unit-box']:checked").length;
        var alll = $(this).parents('.shopping-list').find("input[name='unit-box']").length;
        if (checkedl == alll) {
            $(this).parents('.shopping-list').find('.check-all').addClass("checked");
            $(this).parents('.shopping-list').find("input[name='check-all']").siblings().prop("checked", true);
        } else {
            $(this).parents('.shopping-list').find('.check-all').removeClass("checked");
            $(this).parents('.shopping-list').find("input[name='check-all']").siblings().prop("checked", false);
        }
        var checkedlls = $('.shop-checknox').find(".checked").length;
        var allls = $('.shop-checknox').find(".myCheck").length;
        if (checkedlls == allls) {
            $('.check-alls').addClass("checked");
            $('.check-alls').siblings().prop("checked", true);
        } else {
            $('.check-alls').removeClass("checked");
            $('.check-alls').siblings().prop("checked", false);
        }

    });
    // 店铺全选操作
    $(".wechat-shopping").on('click', '.check-all', function() {
        var unitbox = $(this).parents('.shopping-list').find('.unit-box');
        var price = 0;
        var counts = 0;
        var dataprice = 0;
        var prices = 0;
        
        labelclick($(this));
        if ($(this).hasClass("checked")) {
            $(this).parents('.shopping-list').find("input[name='unit-box']").prop("checked", true);
            $(this).parents('.shopping-list').find('.unit-box').addClass("checked");
        } else {
            $(this).parents('.shopping-list').find("input[name='unit-box']").prop("checked", false);
            $(this).parents('.shopping-list').find('.unit-box').removeClass("checked");
        }
        var checkedlls = $('.shop-checknox').find(".checked").length;
        var allls = $('.shop-checknox').find(".myCheck").length;
        if (checkedlls == allls) {
            $('.check-alls').addClass("checked");
            $('.check-alls').siblings().prop("checked", true);
        } else {
            $('.check-alls').removeClass("checked");
            $('.check-alls').siblings().prop("checked", false);
        }
        $('.wechat-shopping .unit-box').each(function(index, dom) {
            if ($(this).hasClass('checked')) {
                counts = $(this).attr("data-count");
                dataprice = $(this).attr("data-price");
                prices = Number(counts) * Number(dataprice);
                price += prices
            }
        })
        $('.price-em').text(price.toFixed(2));
    });
    // 全选购物车内商品
    $("#checkboxalls").on('click', '.check-alls', function() {
        var price = 0;
        if ($(this).hasClass("checked")) {
            price = 0;
        } else {
            $(".unit-box").each(function(index, data) {
                var counts = $(this).attr("data-count");
                var dataprice = $(this).attr("data-price");
                var prices = Number(counts) * Number(dataprice);
                price += prices
            });
        }
        $('.price-em').text(price.toFixed(2));
        labelclick($(this));
        if ($(this).hasClass("checked")) {
            $('.check-all').addClass("checked");
            $('.check-all').find("input[name='check-all']").siblings().prop("checked", true);
            $('.unit-box').find("input[name='unit-box']").prop("checked", true);
            $('.unit-box').addClass("checked");
        } else {
            $('.check-all').removeClass("checked");
            $('.check-all').find("input[name='check-all']").siblings().prop("checked", false);
            $('.unit-box').find("input[name='unit-box']").prop("checked", false);
            $('.unit-box').removeClass("checked");
        }
    });

    function labelclick(obj) {
        if (obj.hasClass("checked")) {
            obj.removeClass("checked");
            obj.siblings("input").prop("checked", false);
        } else {
            obj.addClass("checked");
            obj.siblings("input").prop("checked", true);
        }
    }

    //购物车数据加载
    var shopping_list = $('#J_shopping_list').html();
    var compiled_shopping = juicer(shopping_list);
    function attributesId(data) {
        var id = '';
        $.each(data, function(i, v) {
            id += v.selected_option.id + ';'
        })
        var len = id.length;
        id = id.substring(0, len - 1);
        return id
    }
    juicer.register('attributesId', attributesId);
    //购物车获取数据加载列表
    function getList() {
        function listshop(data) {
            function isfrist(attributes) {
                var attrs = "";
                for (var i = 0; i < attributes.length; i++) {
                    if (i == (attributes.length - 1)) {
                        attrs += attributes[i].selected_option.name;
                        return attrs;
                    }
                    attrs += attributes[i].selected_option.name + "/"
                }
                return attrs;
            }
            juicer.register('first_build', isfrist);
            var html = compiled_shopping.render(data.items);
            if (data.length < 1) {
                $('.shop-settlement').addClass('disabled')
            }

            $('.wechat-shopping').html(html);
            $('.price-em').text(0);
            /*重置底部*/
            $('#checkboxalls .check-alls').removeClass('checked');
            $('.shop-price').css('visibility', 'visible');
            $('.shop-settlement').css('visibility', 'visible');
        }
        requestUrl('/cart/list', 'GET', { current_page: 1, page_size: 999 }, listshop);
    }
    getList();

    // 编辑
    $('.wechat-shopping').on('click', '.edit-btn', function() {
        var status = $(this).data('status');
        if (status === 'edit') {
            $(this).parents('.shopping-list').find('.mian-view').not('.no').addClass('hidden').siblings('.main-edit').removeClass('hidden')
            $(this).parents('.shopping-list').find('.del-btn').not('.no').removeClass('hidden');
            $(this).text('完成').data('status', '');
            $('.shop-price').css('visibility', 'hidden');
            $('.shop-settlement').css('visibility', 'hidden');
        } else {
            $(this).parents('.shopping-list').find('.mian-view').not('.no').removeClass('hidden').siblings('.main-edit').addClass('hidden')
            $(this).parents('.shopping-list').find('.del-btn').not('.no').addClass('hidden');
            $(this).text('编辑').data('status', 'edit');
            getList();
        }
    })

    // 结算
    $('.shop-settlement').on('click', function() {
        var shopsett = $(this).attr('data-sett');
        if (shopsett == 'sett') { //下单
            var arr = new Array();
            var indexs = 0;
            if ($(".checked").length == 0) {
                return }
            $(".checked").each(function(index, data) {
                if (index == undefined) return;
                if ($(this).attr("data-skuid") == undefined || $(this).attr("data-skuid") == "") return;
                arr[indexs] = $(this).attr("data-skuid");
                indexs++;
            });

            function addshop(data) {
                window.location.href = '/shopping' + data.url;
            }
            requestUrl('/shopping/place-order', 'post', { items_id: arr }, addshop);
        } else { //删除
            if (confirm("确认要删除？")) {
                var arr = new Array();
                var indexs = 0;
                $(".checked").each(function(index, data) {
                    if (index == undefined) return;
                    if ($(this).attr("data-id") == undefined || $(this).attr("data-id") == "") return;
                    arr[indexs] = $(this).attr("data-id");
                    indexs++;
                });

                function deleteshop(data) {
                    alert('删除成功');
                    getList();
                }
                requestUrl('/shopping/remove-item', 'post', { items_id: arr }, deleteshop);
            }
        }
    });
    var is_this,
        _attrTempId;
    //属性选择弹出
    $(".wechat-shopping").on('click', '.main-edit .param-edit', function() {
        var $this = $(this);
        var shopid = $(this).data("pid");
        var id = $(this).data('id');
        $('.J_buy_submit').data('id', id);
        var attributes = $this.attr("data-attributes");
        var stock = $this.parent('.shop-main').data("stock");
        _attrTempId = $(this).data('attr');
        var count = $this.siblings('.input-group').find('.num').text();
        $('#number').html(count).data('max', stock).data('count', count);
        $('.J_product_inventory').text(stock);
        $('.J_buy_attribute').text(attributes);
        loadGoodsInfo(shopid);
        $("#maskLayersh").show();
    })

    // 修改商品
    function editPro(params, cb) {
        var obj = {
            item_id: null,
            sku_id: null,
            num: null
        };
        $.extend(obj, params);
        requestUrl('/cart/change', 'POST', obj, function(data) {
            cb(data)
        })
    }

    //减少数量
    $(".wechat-shopping").on('click', '.main-edit .minu-btn', function(e) {
        var $this = $(this);
        var id = $(this).data('id');
        var stock = parseFloat($(this).parents('.main-edit').data('stock'));
        var count = parseFloat($(this).parents('.main-edit').data('count'));
        if (count <= 1) {
            alert('数量最小为1！')
            return
        }
        count--;
        if (count > stock) {
            count = stock
        }
        editPro({item_id: id, num: count}, function(data) {
            $this.parents('.main-edit').data('count', count);
            $this.siblings('.num').text(count);
            $this.parents('.shopping-item').find('.ammount').text(count);
        })
    });

    //新增数量
    $(".wechat-shopping").on('click', '.main-edit .add-btn', function(e) {
        var $this = $(this);
        var id = $(this).data('id');
        var count = parseFloat($(this).parents('.main-edit').data('count'));
        var stock = parseFloat($(this).parents('.main-edit').data('stock'));
        if (count >= stock) {
            alert('已经是最大库存数！')
            return
        }
        count++;
        editPro({item_id: id, num: count}, function(data) {
            $this.parents('.main-edit').data('count', count);
            $this.siblings('.num').text(count);
            $this.parents('.shopping-item').find('.ammount').text(count);
        })
    });

    // 删除
    $(".wechat-shopping").on('click', '.del-btn', function(data) {
        var $this = $(this);
        var $parent = $this.parents('ul');
        var id = $(this).data('id');
        var yes = confirm('删除该商品？')
        if (!yes) return;
        requestUrl('/cart/remove', 'POST', {item_id: id}, function(data) {
            $this.parent('li').remove()
            $('.shop-price').css('visibility', 'visible');
            $('.shop-settlement').css('visibility', 'visible');
            if ($parent.find('li').length < 1) {
                $parent.parent('.shopping-list').remove()
            }
        })
    })


    //关闭取消
    $(".description em").off("click").on("click", function() {
        $("#maskLayersh").hide();
        resetTemp()
    });
    $('.J_buy_cancel').on('click', function() {
        $("#maskLayersh").hide();
        resetTemp()
    })

    /*重置缓存*/
    function resetTemp() {
        sku_keys = [],
        sku_data = {},
        SKUResult = {};
    }

    var initPrice;
    var sku_keys = [],
        sku_data = {},
        SKUResult = {};
    var _order_data;
    //加载商品属性
    function loadGoodsAttribute(){
        var tpl_data=$("#J_tpl_goods_attribute").html();
        var html=juicer(tpl_data,_order_data);
        $(".J_goods_attribute").html(html);
    }
    //加载购买 选择
    function loadBuyOption() {
        var attr = [];
        $.each(_order_data.SKU.attributes, function(i, v) {
            var option = [];
            var name = '';
            $.each(v, function(index, value) {
                name = index;
                $.each(value, function(inde, val) {
                    option.push({
                        id: inde,
                        name: val,
                    })
                })
            })
            attr.push({
                id: i,
                name: name,
                options: option
            })
        })

        var tpl_attr = $('#J_tpl_buy_attribute').html();
        var sellAttr = juicer(tpl_attr, attr);
        $(".goods_attr_items").html(sellAttr);
    }
    //加载商品信息
    function loadGoodsInfo(shopid) {
        var req_url = "/goods/get-goods-info";
        var _data = {
            id: shopid
        };

        function success(data) {
            _order_data = data;

            $('.J_buy_img').attr('src', data.big_images[0])


            if (data.price.min == data.price.max) {
                initPrice = data.price.min.toFixed(2);
            } else {
                initPrice = data.price.min.toFixed(2) + '-' + data.price.max.toFixed(2);
            }
            $(".J_product_price").html('￥' + initPrice);
            //加载介绍
            $(".goodsInfo").eq(0).html(data.detail);
            // loadGoodsAttribute();
            //加载订购选项
            loadBuyOption();
            //加载库存总量
            // 初始化sku keys
            $('.J_attr_box').each(function() {
                var key = [];
                $(this).find('[attr_id]').each(function() {
                    key.push($(this).attr('attr_id'));
                })
                sku_keys.push(key);
            })
            var _all_stock = 0;
            // 初始化有库存的sku_data
            for (var skuOrder in data.SKU.sku) {
                var _arr1 = skuOrder.split(';');
                var result = [];
                _arr1.forEach(function(val) {
                    result.push(val.split(':')[1]);
                })
                _all_stock += data.SKU.sku[skuOrder].stock;

                if (data.SKU.sku[skuOrder].stock) sku_data[result.join(';')] = data.SKU.sku[skuOrder];
            }
            $(".J_stock").html(_all_stock);
            initSKU();
            
            //绑定选择属性事件
            $('.J_attr_box li[attr_id]').each(function() {
                var self = $(this);
                var attr_id = self.attr('attr_id');

                if (!SKUResult[attr_id]) {
                    // self.attr('disabled', 'disabled');
                    self.addClass('disabled')
                }
            }).click(function(e) {
                var self = $(this);

                if (self.hasClass('disabled')) return;
                self.parent().parent().removeClass('has-error');
                //选中自己，兄弟节点取消选中
                if (self.hasClass('li-active')) {
                    return;
                    e.preventDefault();
                    e.stopPropagation();
                    self.removeClass('li-active');
                    self.find('input')[0].checked = false;
                } else {
                    self.toggleClass('li-active').siblings().removeClass('li-active');
                }
                //已经选择的节点
                var selectedObjs = $('li[attr_id].li-active');


                if (selectedObjs.length) {
                    //获得组合key价格
                    var selectedIds = [];
                    var selectOptions = "";
                    selectedObjs.each(function() {
                        selectedIds.push($(this).attr('attr_id'));
                        selectOptions += $(this).html() + '/';
                    });

                    selectOptions = selectOptions.substr(0, selectOptions.length - 1);
                    $(".J_buy_attribute").html(selectOptions);
                    selectedIds.sort(function(value1, value2) {
                        return parseInt(value1) - parseInt(value2);
                    });
                    var len = selectedIds.length;

                    var price = SKUResult[selectedIds.join(';')].guidance_price;
                    if (_user_login_status == 1) {
                        price = SKUResult[selectedIds.join(';')].price;
                    }

                    var maxPrice = Math.max.apply(Math, price).toFixed(2);
                    var minPrice = Math.min.apply(Math, price).toFixed(2);

                    $('.J_product_price').text((maxPrice > minPrice ? minPrice + "-" + maxPrice : maxPrice));
                    var _stock = SKUResult[selectedIds.join(';')].stock;
                    $('.J_product_inventory').text(_stock);
                    $("#number").data('max',_stock).data('count', 1).text(1);

                    //用已选中的节点验证待测试节点 underTestObjs
                    $('.J_attr_box li[attr_id]').not(selectedObjs).not(self).each(function() {
                        var siblingsSelectedObj = $(this).siblings('.li-active');
                        var testAttrIds = []; //从选中节点中去掉选中的兄弟节点
                        if (siblingsSelectedObj.length) {
                            var siblingsSelectedObjId = siblingsSelectedObj.attr('attr_id');
                            for (var i = 0; i < len; i++) {
                                (selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
                            }
                        } else {
                            testAttrIds = selectedIds.concat();
                        }
                        testAttrIds = testAttrIds.concat($(this).attr('attr_id'));
                        testAttrIds.sort(function(value1, value2) {
                            return parseInt(value1) - parseInt(value2);
                        });
                        if (!SKUResult[testAttrIds.join(';')]) {
                            $(this).addClass('disabled').removeClass('li-active');
                        } else {

                            $(this).removeClass('disabled');
                        }
                    });
                } else {
                    //设置默认价格
                    $('.J_product_price').text(initPrice);
                    //设置属性状态
                    $('.J_attr_box li[attr_id]').each(function() {
                        SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('disabled') : $(this).addClass('disabled').removeClass('li-active');
                    })
                }
            });
        }

        function errorCB(data) {
            alert(data.data.errMsg);
        }
        requestUrl(req_url, 'get', _data, success, errorCB);
    }



    //获得对象的key
    function getObjKeys(obj) {
        if (obj !== Object(obj)) throw new TypeError('Invalid object');
        var sku_keys = [];
        for (var key in obj)
            if (Object.prototype.hasOwnProperty.call(obj, key))
                sku_keys[sku_keys.length] = key;
        return sku_keys;
    }

    //把组合的key放入结果集SKUResult
    function add2SKUResult(combArrItem, sku) {
        var key = combArrItem.join(";");
        if (SKUResult[key]) { //SKU信息key属性·
            SKUResult[key].stock += sku.stock;
            SKUResult[key].price.push(sku.price);
            SKUResult[key].guidance_price.push(sku.guidance_price);
        } else {
            SKUResult[key] = {
                stock: sku.stock,
                price: [sku.price],
                guidance_price: [sku.guidance_price]
            };
        }
    }

    //初始化得到结果集
    function initSKU() {
        var i, j, skuKeys = getObjKeys(sku_data);
        for (i = 0; i < skuKeys.length; i++) {
            var skuKey = skuKeys[i]; //一条SKU信息key
            var sku = sku_data[skuKey]; //一条SKU信息value
            var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组
            skuKeyAttrs.sort(function(value1, value2) {
                return parseInt(value1) - parseInt(value2);
            });

            //对每个SKU信息key属性值进行拆分组合
            var combArr = combInArray(skuKeyAttrs);
            for (j = 0; j < combArr.length; j++) {
                add2SKUResult(combArr[j], sku);
            }

            //结果集接放入SKUResult
            SKUResult[skuKeyAttrs.join(";")] = {
                stock: sku.stock,
                price: [sku.price],
                guidance_price: [sku.guidance_price]

            }
        }
    }

    /**
     * 从数组中生成指定长度的组合
     * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
     */
    function combInArray(aData) {
        if (!aData || !aData.length) {
            return [];
        }

        var len = aData.length;
        var aResult = [];

        for (var n = 1; n < len; n++) {
            var aaFlags = getCombFlags(len, n);
            while (aaFlags.length) {
                var aFlag = aaFlags.shift();
                var aComb = [];
                for (var i = 0; i < len; i++) {
                    aFlag[i] && aComb.push(aData[i]);
                }
                aResult.push(aComb);
            }
        }

        return aResult;
    }


    /**
     * 得到从 m 元素中取 n 元素的所有组合
     * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
     */
    function getCombFlags(m, n) {
        if (!n || n < 1) {
            return [];
        }

        var aResult = [];
        var aFlag = [];
        var bNext = true;
        var i, j, iCnt1;

        for (i = 0; i < m; i++) {
            aFlag[i] = i < n ? 1 : 0;
        }

        aResult.push(aFlag.concat());

        while (bNext) {
            iCnt1 = 0;
            for (i = 0; i < m - 1; i++) {
                if (aFlag[i] == 1 && aFlag[i + 1] == 0) {
                    for (j = 0; j < i; j++) {
                        aFlag[j] = j < iCnt1 ? 1 : 0;
                    }
                    aFlag[i] = 0;
                    aFlag[i + 1] = 1;
                    var aTmp = aFlag.concat();
                    aResult.push(aTmp);
                    if (aTmp.slice(-n).join("").indexOf('0') == -1) {
                        bNext = false;
                    }
                    break;
                }
                aFlag[i] == 1 && iCnt1++;
            }
        }
        return aResult;
    }

    /*弹窗加减*/
    $('#subtract').on('click', function() {
        var num = $('#number').data('count');
        var max = $('#number').data('max');
        if (num > 1) {
            num--;
            if (num > max) {
                num = max;
            }
            $('#number').data('count', num).text(num);
        }
    })
    $('#add').on('click', function() {
        var num = $('#number').data('count');
        var max = $('#number').data('max');
        if (num < max) {
            num++;
            $('#number').data('count', num).text(num);
        }
    })


    $(".J_buy_submit").on('click', function() {
        var len = $('.J_attr_box').length;
        $('.J_attr_box').removeClass('has-error');
        for (var i = 0; i < len; i++) {
            var leng = $('.J_attr_box').eq(i).find('li').length;
            if (!$('.J_attr_box').eq(i).find('li').hasClass('li-active')) {
                $('.J_attr_box').eq(i).addClass('has-error');
                alert('属性选择不完整！');
                return
            }
        }
        if ($('.J_attr_box').hasClass('has-error')) {
            return false
        }
        var cartId = '';
        $.each($('.li-active'), function(i, v) {
            cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
        })
        cartId = cartId.substring(0, cartId.length - 1);
        var sku_id = _order_data['SKU']['sku'][cartId].id;
        var attribute = $('.J_buy_attribute').text();
        var num = $('#number').data('count');
        var id = $('.J_buy_submit').data('id');
        editPro({
            item_id: id,
            sku_id: sku_id,
            num: num
        }, function(data) {
            $("#maskLayersh").hide();
            $('.param-edit[data-id="' + id + '"]').find('span').text(attribute);
            $('.param-edit[data-id="' + id + '"]').siblings('.input-group').find('.num').text(num);
            $('.param-edit[data-id="' + id + '"]').parent('.shop-main').data('count', num);
        })
    })
})
