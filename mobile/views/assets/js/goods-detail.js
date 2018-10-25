/**
 * Created by Administrator on 2018/8/20.
 */
$(function () {
    var _order_data;
    var _user_login_status;
    var urlParam = purl(location.href).param()
    var login = false;
    var Data, day, hour, minute, second;
    var DATA, Day, Hour, Minute, Second;

    var detailMAXprice, detailMINprice;
    var SKU_product_sku_id = [];
    var _all_stock = 0;

    var htmlprice, htmlDecimals;
    var MinHeight, MaxHeight;
    var totle_group;
    var group_count = 0;
    var product_DATA = {
        product_id: url('?id'),
    }
    var DATA = {
        id: url('?id'),
    }
    var _pingou_data;
    var _PINGOU_data;
    var user_level;
    var SIZE;
    // 拼团类型
    var gpubs_type;
    // 两文本超出显示...
    function fontsize(biaoqian) {
        var Size = parseInt($('.' + biaoqian).css('line-height'));
        SIZE = Size*2;
        var Height = parseInt($('.' + biaoqian).height());
        if(Height>SIZE){

            $('.' + biaoqian).css({'height':SIZE,});
            $('.' + biaoqian).addClass("main-goods")
            $('.' + biaoqian).attr('data-attr',"...")
        }
        
    }
    
    // 快速导航
    function fastGuid() {
        var flag = true;
        $('.pack-up').css({
            "background": "url('/images/group_goods_detail/fast_guid.png') no-repeat",
            "background-size": "cover"
        });
        $('#pack-up').on('click', function () {
            $('.guid-list .list').toggle();
            if (flag) {
                $('.fast-guid-list').removeClass('hidden');
                $('.pack-up').css({
                    "background": "url('/images/group_goods_detail/pack_up.png') no-repeat",
                    "background-size": "cover"
                });
                flag = false;
                return
            }
            $('.fast-guid-list').addClass('hidden');
            $('.pack-up').css({
                "background": "url('/images/group_goods_detail/fast_guid.png') no-repeat",
                "background-size": "cover"
            });
            flag = true;
        })
        $('.G_group-share-back-top').on('click', function () {
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        })
        $('#guid-list .pingou-index').on('click', function () {
            location.href = '/'
        })
        $('#guid-list .search').on('click', function () {
            location.href = '/'
        })
        $('#guid-list .my-pingou').on('click', function () {
            location.href = '/member/gpubs-order/index'
        })
        $('#guid-list .ziti-pingou-tihuo').on('click', function () {
            location.href = '/member/gpubs-pick/index'
        })
    }
    fastGuid();
    isLogin(function (data) {
        login = data['is-login']
        init()
    })

    function init() {
        // 获取登录状态
        $("#deliver-goods-text").val(1);
        $("#alone-deliver-goods-text").val(1);
        ! function () {
            requestUrl('/member/login/is-return', 'GET', {
                return_url: location.href
            }, function (data) {
                _user_login_status = data.status;
                if (_user_login_status == 0) {
                    loadGoodsInfo();
                }
                if(_user_login_status == 1) {
                    pingou();
                    
                    setTimeout(() => {
                        loadGoodsInfo();
                    }, 500);
                    
                    // 绑定客服事件
                    var _srp = document.createElement('script')
                    _srp.type = 'text/javascript'
                    _srp.sync = 'sync'
                    _srp.src = JDY_SERVICE_LIST[data.supplier]
                    if (!JDY_SERVICE_LIST[data.supplier]) {
                        _srp.src = JDY_SERVICE_LIST['default']
                    }
                    $('body').append(_srp)
                }
            })
        }()
        ! function () {
            requestUrl('/index/get-user-status', 'GET', {
                return_url: location.href
            }, function (data) {
                user_level = data.level;
            })
        }()
        var initPrice;
        var sku_keys = [],
            sku_data = {},
            SKUResult = {};
        //加载商品信息

        

        //加载图片信息
        function loadGoodsImg() {
            // 轮播图Carousel
            var bigImages = _order_data.big_images;
            bigImagesLength = bigImages.length;
            var html = "";
            bigImages.forEach((item, i) => {
                html += `<div class="swiper-slide">
                                <img src="${item}" alt="">
                            </div>`
            });
            $("#swiper-wrapper").html(html);
            // 轮播图
            var mySwiper = new Swiper('.swiper-container', {
                loop: true,
                autoplay: {
                    disableOnInteraction: false,
                },
                speed: 300,
                on: {
                    slideChangeTransitionStart: function () {
                        var index = this.activeIndex;
                        if (index > bigImagesLength) {
                            index = 1;
                        }
                        if (index < 1) {
                            index = bigImagesLength
                        }
                        $('.goods-detail-pages>.pages>.change-pages').eq(0).html(index);
                        $('.goods-detail-pages>.pages>.totle-pages').eq(0).html(bigImagesLength);
                    }
                }
            })

            // 规格详情图片
            $(".detail-img>img").attr("src", _order_data.big_images[0]);

        }

        //加载商品属性
        function loadGoodsAttribute(){
            var tpl_data=$("#J_tpl_goods_attribute").html();
            var html=juicer(tpl_data,_order_data);
            $(".J_goods_attribute").html(html);
        }

        //加载购买 选择
        function loadBuyOption() {
            var attr = [];
            $.each(_order_data.SKU.attributes, function (i, v) {
                var option = [];
                var name = '';
                $.each(v, function (index, value) {
                    name = index;
                    $.each(value, function (inde, val) {
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
            $("#standard").html(sellAttr);
            var tpl_attr_alone = $('#J_tpl_buy_attribute_alone').html();
            var sellAttr_alone = juicer(tpl_attr_alone, attr);
            $("#standard_alone").html(sellAttr_alone);

        }


        //加载正常商品信息
        function loadGoodsInfo() {
            var req_url = "/goods/get-goods-info";
            var _data = {
                id: url('?id'),
            };

            function success(data) {
                _order_data = data;

                detailMAXprice = data.price.max;
                detailMINprice = data.price.min;
                var num = 1,
                    NUM = 1;
                // 商品数量的加减
                $("#deliver-goods-text").keyup(function(){
                    $(this).val($(this).val().replace(/[^0-9-]+/,''));  
                    if($(this).val().length == 1){
                        $(this).val() == '0' ? $(this).val('1') : $(this).val();
                    }
                    
                })
            
                $('#deliver-goods-text').blur( function () { 
                    num = Number($('#deliver-goods-text').val());
                    if($(this).val().length == 0){
                        num = 1;
                    }
                    goodsNum(num);
                });
                $("#deliver-goods-add").on("click", function () {
                    num += 1;
                    $('#deliver-goods-reduce').css({
                        display: 'inline-block',
                        background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                        backgroundSize: 'contain'
                    });
                    goodsNum(num);
                })
                $("#deliver-goods-reduce").on("click", function () {
                    num -= 1;
                    if ($('#deliver-goods-text').val() == 1) {
                        num = 1;
                    }
                    goodsNum(num);

                })
                function goodsNum(num) {
                    $('#deliver-goods-text').val(num);
                    if ($('#deliver-goods-text').val() == 1) {
                        $('#deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_failure.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                    }else {
                        $('#deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                    }
                }
                

                $("#alone-deliver-goods-add").on("click", function () {
                    NUM += 1;
                    $('#alone-deliver-goods-reduce').css({
                        display: 'inline-block',
                        background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                        backgroundSize: 'contain'
                    });
                    goodsNUM(NUM);
                })
                $("#alone-deliver-goods-reduce").on("click", function () {
                    NUM -= 1;
                    if ($("#alone-deliver-goods-text").val() == 1) {
                        NUM = 1;
                    }
                    goodsNUM(NUM);

                })
                $("#alone-deliver-goods-text").keyup(function(){
                    $(this).val($(this).val().replace(/[^0-9-]+/,''));  
                    if($(this).val().length == 1){
                        $(this).val() == '0' ? $(this).val('1') : $(this).val();
                    }
                    
                })
            
                $('#alone-deliver-goods-text').blur( function () { 
                    NUM = Number($('#alone-deliver-goods-text').val());
                    if($(this).val().length == 0){
                        NUM = 1;
                    }
                    goodsNUM(NUM);
                });

                

                function goodsNUM(num) {
                    $('#alone-deliver-goods-text').val(num);
                    if ($('#alone-deliver-goods-text').val() == 1) {
                        $('#alone-deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_failure.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                    }else {
                        $('#alone-deliver-goods-reduce').css({
                            display: 'inline-block',
                            background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
                            backgroundSize: 'contain'
                        });
                    }
                }




                // 加载图片信息
                loadGoodsImg();
                // 加载商户信息
                loadSupplierInfo(data.supplier);
                // 加载商品属性
                loadGoodsAttribute();


                $(".fuhao").html("￥");


                // 不拼购时显示的价格
                function normalPrice(){
                    $("#specifications-detail-wrap .spell-delivery").css({
                        opacity: 0
                    });
                    $(".goods-detail-mess .spell-delivery").addClass("hidden");
                    $(".goods-detail-count-down").addClass("hidden");
                    $(".service-policy").addClass("hidden");
                    $(".count-down-mess-wrap").addClass("hidden");
                    $(".count-down-rule-wrap").addClass("hidden");
                    $(".purchase-separately").addClass("hidden");
                    $(".want-open-group").addClass("hidden");
                    
                    // 现价区间
                    if (data.price.min == data.price.max) {
                        // 商品详情中的现价
                        $('.goods-detail-price>.new-price>.min').addClass("hidden");
                        var text = $(".goods-detail-price>.new-price>.max>.max-price");
                        var test = $(".goods-detail-price>.new-price>.max>.max-price-decimals");
                        huoquPrice(data.price.max, text, test);
                        $('#specifications-detail-wrap .detail-newprice>.new-price').html(data.price.max.toFixed(2));

                        initPrice = '￥ ' + data.price.min.toFixed(2);
                    } else {
                        // 商品详情中的现价
                        var text1 = $(".goods-detail-price>.new-price>.min>.min-price");
                        var test2 = $(".goods-detail-price>.new-price>.min>.min-price-decimals");
                        huoquPrice(data.price.min, text1, test2);

                        var min_html = $(".goods-detail-price>.new-price>.min>.min-price-decimals").html()
                        $(".goods-detail-price>.new-price>.min>.min-price-decimals").html(min_html + "-");

                        var text = $(".goods-detail-price>.new-price>.max>.max-price");
                        var test = $(".goods-detail-price>.new-price>.max>.max-price-decimals");
                        huoquPrice(data.price.max, text, test);

                        // 规格详情中的现价
                        $('#specifications-detail-wrap .detail-newprice>.new-price>.min-price').html(data.price.min.toFixed(2) + "-");

                        $('#specifications-detail-wrap .detail-newprice>.new-price>.max-price').html(data.price.max.toFixed(2));
                    }
                    // 原价区间
                    if(data.original_price.min == 0 && data.original_price.max == 0) {
                        $(".goods-detail-price .old-price").html("");
                        $(".detail-oldprice .old-price").html("");
                        $('.goods-detail-price>.old-price').addClass("hidden");
                        $("#specifications-detail-wrap .detail-oldprice .old-price").css({
                            opacity: 0
                        });
                    }
                    if(data.original_price.min == data.original_price.max) {
                        // 商品详情中的原价
                        $('.goods-detail-price .old-price').html("￥"+data.original_price.max.toFixed(2));
                        // 规格详情中的原价
                        $(".detail-oldprice .old-price").html("￥"+data.original_price.max.toFixed(2));
                    } else {
                        // 商品详情中的原价
                        $('.goods-detail-price .old-price .min-price').html("￥"+data.original_price.min.toFixed(2));
                        $('.goods-detail-price .old-price .max-price').html("-"+data.original_price.max.toFixed(2));
                        // 规格详情中的原价
                        $(".detail-oldprice .old-price").html("￥" + data.original_price.min.toFixed(2) + "-" + data.original_price.max.toFixed(2));
                    }
                }
                // 拼购时该显示的价格
                function pingouPRICE(){
                    if (data.price.min == data.price.max) {
                        // 详情页原价
                        $(".goods-detail-price .old-price").html("￥" + data.price.min.toFixed(2))
                        // 规格页原价
                        $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥" + data.price.min.toFixed(2));
                        $("#alone-specifications-detail-wrap .detail-newprice .new-price").html(data.price.min.toFixed(2));
                    } else {
                        // 详情页原价
                        $(".goods-detail-price .old-price").html("￥" + data.price.min.toFixed(2) + "-" + data.price.max.toFixed(2))

                        // 规格页原价
                        $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥" + data.price.min.toFixed(2) + "-" + data.price.max.toFixed(2));
                        $("#alone-specifications-detail-wrap .detail-newprice .new-price").html(data.price.min.toFixed(2) + "-" + data.price.max.toFixed(2));

                    }
                    if(data.original_price.min == 0 && data.original_price.max == 0) {
                        $('#alone-specifications-detail-wrap .goods-detail-price>.old-price').addClass("hidden");
                        $("#alone-specifications-detail-wrap .detail-oldprice .old-price").css({
                            opacity: 0
                        });
                    }
                    if(data.original_price.min == data.original_price.max) {
                        // 规格详情中的原价
                        $("#alone-specifications-detail-wrap .detail-oldprice .old-price").html("￥"+data.original_price.max.toFixed(2));
                    } else {
                        // 规格详情中的原价
                        $("#alone-specifications-detail-wrap .detail-oldprice .old-price").html("￥" + data.original_price.min.toFixed(2) + "-" + data.original_price.max.toFixed(2));
                    }


                    $(".purchase-separately .price").html("￥" + data.price.min.toFixed(2))
                }
                if (gpubs_type == undefined) {
                    normalPrice()
                } 
                if(gpubs_type == 1){
                    if(user_level == 4) {
                        pingouPRICE()
                    } else {
                        normalPrice()
                    }
                }
                if(gpubs_type == 2){
                    pingouPRICE()
                }


                // 加载商品标题
                $(".goods-detail-mess>.mess>.goods-intro").html(data.title);


                // 加载商品描述
                $(".goods-detail-mess>.mess>.goods-intro-mess").html(data.description);

                // 两行超出显示省略号
                fontsize('goods-intro');
                fontsize('goods-intro-mess');

                //加载商品详情介绍
                $(".good-detail-wrap .detail-main").html(data.detail);
                $(".good-detail-wrap .detail-main").children().css("width","100%")


                //加载购买选项
                loadBuyOption();

                // 加载正常商品SKU
                if (_user_login_status == 0) {
                    normalgoodSKU(data.SKU.sku);
                }
                if (_user_login_status == 1) {
                    if (gpubs_type == undefined) {

                        normalgoodSKU(data.SKU.sku);
                    }
                    if (gpubs_type == 1) {
                        if(user_level == 4){
                            goodsSKU(data.SKU.sku)
                            aloneNormalgoodSKU(data.SKU.sku)
                        } else {
                            normalgoodSKU(data.SKU.sku);
                        }
                    }
                    if (gpubs_type == 2) {
                        goodsSKU(data.SKU.sku)
                        aloneNormalgoodSKU(data.SKU.sku)

                    }
                }
            }

            function errorCB(data) {
                alert(data.data.errMsg);
            }
            requestUrl(req_url, 'get', _data, success, errorCB);
        }

        // 加载正常商品SKU
        function normalgoodSKU(data) {
            //加载库存总量
            // 初始化sku keys
            $('#specifications-detail-wrap .standard-title').each(function () {
                var key = [];
                $(this).find('[attr_id]').each(function () {
                    key.push($(this).attr('attr_id'));
                })
                sku_keys.push(key);
            })


            // 初始化有库存的sku_data
            for (var skuOrder in data) {
                var _arr1 = skuOrder.split(';');
                var result = [];
                _arr1.forEach(function (val) {
                    result.push(val.split(':')[1]);
                })
                _all_stock += data[skuOrder].stock;

                if (data[skuOrder].stock >= 0) sku_data[result.join(';')] = data[skuOrder];

            }
            $("#specifications-detail-wrap #inventory-num").html(_all_stock);

            initSKU();
            //绑定选择属性事件 
            $('#specifications-detail-wrap .standard-title li[attr_id]').each(function () {
                var self = $(this);
                var attr_id = self.attr('attr_id');
                // 如果这个不存在，这里的disabled样式需要去设置一下
                if (SKUResult[attr_id].stock == 0) {
                    self.addClass('disabled')
                    self.addClass('cant-choose')
                }
            }).click(function (e) {

                var self = $(this);
                if (self.hasClass('cant-choose')) return;
                self.parent().parent().removeClass('has-error');
                //选中自己，兄弟节点取消选中
                if (self.hasClass('li-active')) {
                    return
                    e.preventDefault();
                    e.stopPropagation();
                    self.removeClass('li-active');
                    self.find('input')[0].checked = false;
                } else {
                    self.toggleClass('li-active').siblings().removeClass('li-active');
                }

                //已经选择的节点
                var selectedObjs = $('#specifications-detail-wrap li[attr_id].li-active');
                var selectedIds = [];
                var selectOptions = "";
                selectedObjs.each(function () {

                    selectedIds.push($(this).attr('attr_id'));
                    selectOptions += '"' + $(this).html() + '"';

                });
                $("#specifications-detail-wrap .choose-specifications-color>.yet-choose").show();
                $("#specifications-detail-wrap .choose-specifications-color>.please-choose").hide();

                $("#specifications-detail-wrap .choose-specifications-color .yet-choose").html("已选择：" + selectOptions);
                $(".specifications-address .choose").hide();
                $(".specifications-address .specifications-mess>.mess").html("已选择：" + selectOptions);


                if (selectedObjs.length == $('#specifications-detail-wrap .standard-title ul[data-id]').length) {
                    var len = selectedIds.length;
                    if (_user_login_status == 1) {
                        var price = SKUResult[selectedIds.join(';')].price;
                        var originalPrice = SKUResult[selectedIds.join(';')].original_price;
                    }
                    $(".goods-detail-price .new-price .min").addClass("hidden");
                    var t1 = $(".goods-detail-price .new-price .max .max-price");
                    var t2 = $(".goods-detail-price .new-price .max .max-price-decimals");
                    huoquPrice(price[0], t1, t2)
                    $(".goods-detail-price .old-price").html("￥"+Number(originalPrice).toFixed(2));

                    $("#specifications-detail-wrap .detail-newprice .new-price").html(Number(price).toFixed(2));
                    $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥"+Number(originalPrice).toFixed(2))

                    var _stock = SKUResult[selectedIds.join(';')].stock;
                    $('#inventory-num').text(_stock);
                    //用已选中的节点验证待测试节点 underTestObjs
                    $('#specifications-detail-wrap .standard-title li[attr_id]').not(selectedObjs).not(self).each(function () {
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
                        testAttrIds.sort(function (value1, value2) {
                            return parseInt(value1) - parseInt(value2);
                        });
                        if (!SKUResult[testAttrIds.join(';')]) {
                            $(this).addClass('disabled').removeClass('li-active');
                        } else {

                            $(this).removeClass('disabled');
                        }
                    });
                }
            });
        }
        // 加载拼购页面中单独购买商品的SKU
        function aloneNormalgoodSKU(data) {
            //加载库存总量
            // 初始化sku keys
            $('#alone-specifications-detail-wrap .standard-title').each(function () {
                var key = [];
                $(this).find('[attr_id]').each(function () {
                    key.push($(this).attr('attr_id'));
                })
                sku_keys.push(key);
            })
            _all_stock = 0;

            // 初始化有库存的sku_data
            for (var skuOrder in data) {
                var _arr1 = skuOrder.split(';');
                var result = [];
                _arr1.forEach(function (val) {
                    result.push(val.split(':')[1]);
                })

                _all_stock += data[skuOrder].stock;
                if (data[skuOrder].stock>=0) {
                    sku_data[result.join(';')] = data[skuOrder];
                } 
            }
            $("#alone-inventory-num").html(_all_stock);

            initSKU();
            //绑定选择属性事件 
            $('#alone-specifications-detail-wrap .standard-title li[attr_id]').each(function () {
                var self = $(this);
                var attr_id = self.attr('attr_id');
                // 如果这个不存在，这里的disabled样式需要去设置一下
                if (SKUResult[attr_id].stock == 0) {
                    self.addClass('disabled')
                    self.addClass('cant-choose')
                }
            }).click(function (e) {

                var self = $(this);
                if (self.hasClass('cant-choose')) return;
                self.parent().parent().removeClass('has-error');
                //选中自己，兄弟节点取消选中
                if (self.hasClass('li-active')) {
                    return
                    e.preventDefault();
                    e.stopPropagation();
                    self.removeClass('li-active');
                    self.find('input')[0].checked = false;
                } else {
                    self.toggleClass('li-active').siblings().removeClass('li-active');
                }

                //已经选择的节点
                var selectedObjs = $('#alone-specifications-detail-wrap li[attr_id].li-active');
                var selectedIds = [];
                var selectOptions = "";
                selectedObjs.each(function () {

                    selectedIds.push($(this).attr('attr_id'));
                    selectOptions += '"' + $(this).html() + '"';

                });
                $("#alone-specifications-detail-wrap .choose-specifications-color>.yet-choose").show();
                $("#alone-specifications-detail-wrap .choose-specifications-color>.please-choose").hide();

                $("#alone-specifications-detail-wrap .choose-specifications-color .yet-choose").html("已选择：" + selectOptions);


                if (selectedObjs.length == $('#alone-specifications-detail-wrap .standard-title ul[data-id]').length) {
                    var len = selectedIds.length;

                    if (_user_login_status == 1) {
                        var price = SKUResult[selectedIds.join(';')].price;
                        var originalPrice = SKUResult[selectedIds.join(';')].original_price;
                    }
                    
                    $("#alone-specifications-detail-wrap .detail-newprice .new-price").html(Number(price).toFixed(2));
                    $("#alone-specifications-detail-wrap .detail-oldprice .old-price").html("￥"+Number(originalPrice).toFixed(2))

                    var _stock = SKUResult[selectedIds.join(';')].stock;
                    $('#alone-specifications-detail-wrap #alone-inventory-num').text(_stock);
                    //用已选中的节点验证待测试节点 underTestObjs
                    $('#alone-specifications-detail-wrap .standard-title li[attr_id]').not(selectedObjs).not(self).each(function () {
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
                        testAttrIds.sort(function (value1, value2) {
                            return parseInt(value1) - parseInt(value2);
                        });
                        if (!SKUResult[testAttrIds.join(';')]) {
                            $(this).addClass('disabled').removeClass('li-active');
                        } else {

                            $(this).removeClass('disabled');
                        }
                    });
                }
            });
        }

        // 加载拼购商品SKU
        function goodsSKU(data) {
            var i,n;
            //加载库存总量
            // 初始化sku keys
            $('#specifications-detail-wrap .standard-title').each(function () {
                var key = [];
                $(this).find('[attr_id]').each(function () {
                    key.push($(this).attr('attr_id'));
                })
                sku_keys.push(key);
            })
            $.each(data, function (index, item) {
                SKU_product_sku_id.push(item.id);
                
            })
            requestUrl('/gpubs/api/product', 'GET', {
                product_id: url('?id'),
            }, function (infor) {
                _all_stock = 0;
                _pingou_data = infor;
                $.each(SKU_product_sku_id, function (index, item) {
                    _all_stock += infor.sku[item].stock;
                })
                $("#specifications-detail-wrap #inventory-num").html(_all_stock);
                //绑定选择属性事件 
            $('#specifications-detail-wrap .standard-title li[attr_id]').each(function (j) {
                var self = $(this);
                var attr_id = self.attr('attr_id');
                $.each(SKU_product_sku_id, function (index, item) {
                    if(_pingou_data.sku[item].stock == 0 ) {
                        n = _pingou_data.sku[item].product_sku_id;
                        var Stock = _pingou_data.sku[item].stock;
                        $.each(data, function (index, item) {
                            if(item.stock == 0){
                                i = item.id;
                                if(i == n){
                                attr_id  = index.split(":")[1];
                                if (Stock == 0 && SKUResult[attr_id].stock == 0) {
                                        if($("#standard .item").find("li").eq(j).attr("attr_id") == attr_id) {
                                            $("#standard .item").find("li").eq(j).addClass('disabled')
                                            $("#standard .item").find("li").eq(j).addClass('cant-choose')
                                        }
                                    }
                                }
                            }
                        })
                        
                    }
                })
                
                
            }).click(function (e) {

                var self = $(this);
                if (self.hasClass('cant-choose')) return;
                self.parent().parent().removeClass('has-error');
                //选中自己，兄弟节点取消选中
                if (self.hasClass('li-active')) {
                    return
                    e.preventDefault();
                    e.stopPropagation();
                    self.removeClass('li-active');
                    self.find('input')[0].checked = false;
                } else {
                    self.toggleClass('li-active').siblings().removeClass('li-active');
                }

                //已经选择的节点
                var selectedObjs = $('#specifications-detail-wrap li[attr_id].li-active');
                var selectedIds = [];
                var selectedIDs = [];
                var selectOptions = "";
                var Id_sku="";
                selectedObjs.each(function () {
                    selectedIDs.push($(this).parent().attr('data-id'));
                    selectedIds.push($(this).attr('attr_id'));
                    Id_sku += $(this).parent().attr('data-id')
                    +":"+$(this).attr('attr_id')+";"
                    selectOptions += '"' + $(this).html() + '"';

                });
                Id_sku = Id_sku.substring(0,Id_sku.length-1);
                
                $("#specifications-detail-wrap .choose-specifications-color>.yet-choose").show();
                $("#specifications-detail-wrap .choose-specifications-color>.please-choose").hide();

                $("#specifications-detail-wrap .choose-specifications-color .yet-choose").html("已选择：" + selectOptions);
                $(".specifications-address .choose").hide();
                $(".specifications-address .specifications-mess>.mess").html("已选择：" + selectOptions);


                if (selectedObjs.length == $('#specifications-detail-wrap .standard-title ul[data-id]').length) {
                    // if($(".footer").find(".confirm").hasClass("hidden")){
                    //     var ID ;
                    //     $.each(data, function (index, item) {
                    //         if(index == Id_sku){
                    //             ID = item.id;
                    //             if(item.stock == 0){
                    //                 alert("该商品单独购买此规格库存不足，请选择其他规格或者拼团!")
                    //                 return
                    //             }
                    //         }
                            
                    //     })
                    //     $.each(SKU_product_sku_id, function (index, item) {
                    //         if(ID == item){
                    //             if(_pingou_data.sku[item].stock == 0) {
                    //                 alert("该拼购商品此规格库存不足，请选择其他规格或单独购买！")
                    //                 return
                    //             }
                    //         }
                    //     })
                    // }else {
                    //     if($("#detail-want-open-group").hasClass('kaituan')){
                    //         var ID ;
                    //         $.each(data, function (index, item) {
                    //             if(index == Id_sku){
                    //                 ID = item.id;
                    //             }
                                
                    //         })
                    //         $.each(SKU_product_sku_id, function (index, item) {
                    //             if(ID == item){
                    //                 if(_pingou_data.sku[item].stock == 0) {
                    //                     alert("该拼购商品此规格库存不足！")
                    //                     return
                    //                 }
                    //             }
                    //         })
                    //     }
                    // }
                    
                    var len = selectedIds.length;
                    
                    var cartId = '';
                    var sku_id = '';
                    $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                        cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
                    })

                    cartId = cartId.substring(0, cartId.length - 1);


                    var sku_id = _order_data['SKU']['sku'][cartId].id;
                    
                    _all_stock = 0;
                    $.each(SKU_product_sku_id, function (index, item) {
                        if (_pingou_data.sku[item].product_sku_id == sku_id) {
                            _all_stock = _pingou_data.sku[item].stock;
                            $('#specifications-detail-wrap .detail-newprice .new-price').text(_pingou_data.sku[item].price.toFixed(2));
                            $(".want-open-group .price").text("￥" + _pingou_data.sku[item].price.toFixed(2));
                            $(".goods-detail-price .new-price .min").addClass("hidden");
                            var t1 = $(".goods-detail-price .new-price .max .max-price");
                            var t2 = $(".goods-detail-price .new-price .max .max-price-decimals");
                            huoquPrice(_pingou_data.sku[item].price, t1, t2)
                        }
                    })
                    $("#inventory-num").html(_all_stock);
                    var price = SKUResult[selectedIds.join(';')].guidance_price;
                        if (_user_login_status == 1) {
                            price = SKUResult[selectedIds.join(';')].price;
                        }
                        $("#specifications-detail-wrap .detail-oldprice .old-price").html("￥" + Number(price).toFixed(2));
    
                        $(".goods-detail-price .old-price").html("￥" + Number(price).toFixed(2));
                        $("#purchase-separately-qubie .price").html("￥" + Number(price).toFixed(2));
                    //用已选中的节点验证待测试节点 underTestObjs
                    $('#specifications-detail-wrap .standard-title li[attr_id]').not(selectedObjs).not(self).each(function () {
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
                        testAttrIds.sort(function (value1, value2) {
                            return parseInt(value1) - parseInt(value2);
                        });
                        if (!SKUResult[testAttrIds.join(';')]) {
                            $(this).addClass('disabled').removeClass('li-active');
                        } else {

                            $(this).removeClass('disabled');
                        }
                    });
                }
            });
        })
            
        }



        //加载商户信息
        function loadSupplierInfo(supply_id) {
            var url = "/shop/get-supply-info";
            var _data = {
                id: supply_id
            };

            function success(data) {
                $(".acw-wrap .acw-main>.location").html("所在地："+data.province);
                $(".acw-wrap .good-num").html(data.count);
                $(".acw-wrap .logo>img").attr("src", data.header_img);
                $(".acw-wrap .acw-main>.title").html(data.brand_name);
            }
            requestUrl(url, 'get', _data, success);
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
                SKUResult[key].id.push(sku.id);
            } else {
                SKUResult[key] = {
                    stock: sku.stock,
                    price: [sku.price],
                    guidance_price: [sku.guidance_price],
                    original_price: [sku.original_price],
                    original_guidance_price: [sku.original_guidance_price],
                    id: [sku.id]
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
                skuKeyAttrs.sort(function (value1, value2) {
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
                    guidance_price: [sku.guidance_price],
                    original_price: [sku.original_price],
                    original_guidance_price: [sku.original_guidance_price],
                    id: [sku.id]
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



        // 判断价格是整数还是小数
        function getPrice(price) {
            if (typeof price == "number") {
                price = String(price);
                if (price.split(".").length == 1) {
                    // 整数
                    return 1;
                } else if (price.split(".").length > 1) {
                    // 带小数
                    return 2;
                }
            }
        }

        // 返回数据
        function huoquPrice(price, integerText, decimalsText) {
            if (getPrice(price) == 1) {
                integerText.html(price);
                decimalsText.html(".00");
            } else if ((getPrice(price) == 2)) {
                integerText.html(String(price).split(".")[0]);
                decimalsText.html("." + String(price).split(".")[1]);
            }
        }

        // 倒计时
        function daojishi(Data) {

            // 转换为天、时、分、秒
            // 天
            day = parseInt(Data / 60 / 60 / 24);
            if (day < 10) {
                day = "0" + day;
            }
            $(".goods-detail-count-down #day").html(day);

            // 时
            hour = parseInt((Data - day * 60 * 60 * 24) / 60 / 60);
            if (hour < 10) {
                hour = "0" + hour;
            }
            $(".goods-detail-count-down #hour").html(hour);

            // 分
            minute = parseInt(((Data - day * 60 * 60 * 24) - hour * 60 * 60) / 60);
            if (minute < 10) {
                minute = "0" + minute;
            }
            $(".goods-detail-count-down #minute").html(minute);

            // 秒
            second = parseInt(((Data - day * 60 * 60 * 24) - hour * 60 * 60) - minute * 60);
            if (second < 10) {
                second = "0" + second;
            }
            if(Data<0){
                second = 00;
            }
            $(".goods-detail-count-down #second").html(second);
        }

        // 拼购商品详情
        function pingou() {
            var req_url = "/gpubs/api/product";
            var _data = {
                product_id: url('?id'),
            };

            function success(data) {
                if(data.status == 1){
                    gpubs_type = data.gpubs_type;
                }
                

                // 获取 到截止时间的秒数
                Data = data.expire_time;
                daojishi(Data);
                // 拼购倒计时
                
                setInterval(() => {

                    Data -= 1;
                    daojishi(Data)
                    if(Data<=0) {
                        window.location.reload();
                    }

                }, 1000)
                
                

                // 判断拼购类型
                if(data.status == 1){
                    if (data.gpubs_type == undefined) {

                        $("#specifications-detail-wrap .spell-delivery").css({
                            opacity: 0
                        });
                        $(".goods-detail-mess .spell-delivery").addClass("hidden");
                        $(".goods-detail-count-down").addClass("hidden");
                        $(".service-policy").addClass("hidden");
                        $(".count-down-mess-wrap").addClass("hidden");
                        $(".count-down-rule-wrap").addClass("hidden");
                        $(".purchase-separately").addClass("hidden");
                        $(".want-open-group").addClass("hidden");
                        $(".goods-detail-price .old-price").html("");
                        $(".detail-oldprice .old-price").html("");
                    }
                    function type(){
                        if (data.gpubs_type != undefined) {
                            $(".service-policy .mess").html(data.description);
                            $(".service-policy-detail-wrap .mess .mess-item").html(data.description);
                            $(".goods-detail-mess .spell-delivery").removeClass("hidden");
                            $(".goods-detail-count-down").removeClass("hidden");
                            $(".service-policy").removeClass("hidden");
                            $(".count-down-mess-wrap").removeClass("hidden");
                            $(".count-down-rule-wrap").removeClass("hidden");
                            $(".purchase-separately").removeClass("hidden");
                            $(".want-open-group").removeClass("hidden");
                            if (data.max_price == data.min_price) {
                                // 详情页品拼购价
                                $(".goods-detail-price .new-price .min").addClass("hidden");
                                var text2 = $(".goods-detail-price .new-price .max .max-price");
                                var test3 = $(".goods-detail-price .new-price .max .max-price-decimals");
                                huoquPrice(data.min_price, text2, test3);
        
        
                                // 规格页拼购价
                                $("#specifications-detail-wrap .detail-newprice .new-price").html(data.max_price.toFixed(2));
        
                                $(".want-open-group .price").html("￥" + data.min_price.toFixed(2))
        
                            } else {
                                // 详情页拼购价
                                $(".goods-detail-price .new-price .min .min-price").removeClass("hidden");
                                var text2 = $(".goods-detail-price .new-price .min .min-price");
                                var test2 = $(".goods-detail-price .new-price .min .min-price-decimals");
                                huoquPrice(data.min_price, text2, test2);
        
                                var min_html = $(".goods-detail-price>.new-price>.min>.min-price-decimals").html()
                                $(".goods-detail-price>.new-price>.min>.min-price-decimals").html(min_html + "-");
                                var text3 = $(".goods-detail-price .new-price .max .max-price");
                                var test3 = $(".goods-detail-price .new-price .max .max-price-decimals");
                                huoquPrice(data.max_price, text3, test3);
        
        
                                // 规格页拼购价
                                $("#specifications-detail-wrap .detail-newprice .new-price .min-price").html(data.min_price.toFixed(2) + "-");
                                $("#specifications-detail-wrap .detail-newprice .new-price .max-price").html(data.max_price.toFixed(2));
        
                                $(".want-open-group .price").html("￥" + data.min_price.toFixed(2))
        
        
                            }
        
                        }
                    }
                }
                
                if(data.status == 1){
                    if (data.gpubs_type == 1) {
                        if(user_level == 4) {
                            // 自提
                            $(".spell-delivery>.delivery").addClass("delivery-ziti-bg");
                            $(".spell-delivery>.delivery").removeClass("delivery-songhuo-bg");
                            $(".count-down-mess-wrap").hide();
                            $(".addCart").addClass("hidden");
                            $(".purchase-now").addClass("hidden");
                            type()
                        }
                        
    
                    }
                    if (data.gpubs_type == 2) {
                        // 送货
                        GroupInforHasAssembled();
                        $(".spell-delivery>.delivery").addClass("delivery-songhuo-bg");
                        $(".spell-delivery>.delivery").removeClass("delivery-ziti-bg");
                        $(".addCart").addClass("hidden");
                        $(".purchase-now").addClass("hidden");
                        type()
    
                    }
    
                    // 判断成团规则类型
                    if (data.gpubs_rule_type == 1) {
                        var text = data.min_member_per_group + "人拼"
                        $(".spell-delivery>.spell").html(text);
                    } else if (data.gpubs_rule_type == 2) {
                        var text = data.min_quantity_per_group + "件拼"
                        $(".spell-delivery>.spell").html(text);
                    } else if (data.gpubs_rule_type == 3) {
                        var text = data.min_member_per_group + "人" + data.min_quanlity_per_member_of_group + "件拼"
                        $(".spell-delivery>.spell").html(text);
                    }
                }
                

            }

            function errorCB(data) {
                alert(data.data.errMsg);
            }
            requestUrl(req_url, 'GET', _data, success, errorCB);
        }



        // 请求已经拼团信息
        function GroupInforHasAssembled() {
            var cur_page = 1;
            var page_size = "";
            var req_url = "/gpubs/recommend/get-group";
            var _data = {
                product_id: url('?id'),
                cur_page: cur_page,
                page_size: 10
            };

            function success(data) {
                if (data.group.length == 0) {
                    $(".notyet-group").removeClass("hidden");
                    $(".my-open-group").click(function () {
                        confirmBtn();
                    })
                } else {
                    $(".notyet-group").addClass("hidden");
                }
                $(".people-num").html(data.already_join_num + "人");
                if(data.already_join_num <=0 ){
                    $(".totle-count").addClass('hidden');
                }
                totle_group = Math.ceil(data.group.length / 2);
                
                $.each(data.group, function (index, item) {
                    // 获取 到截止时间的秒数
                    DATA = item.left_unixtime;
                    var surplus_num = "";
                    // 拼购倒计时

                    if (item.gpubs_rule_type == 2) {
                        surplus_num = item.surplus_goods_num + "件";
                    } else if (item.gpubs_rule_type == 1 || item.gpubs_rule_type == 3) {
                        surplus_num = item.surplus_people_num + "人";
                    }
                    var html = "";
                    html += `<div class="count-down-one">
                                        <div class="one-mess">
                                            <div class="left">
                                                <div class="head-portrait">
                                                    <img src="${item.header_img}" alt="">
                                                </div>
                                                
                                                <div class="user-tuan">
                                                    <span class="user">${item.account}</span>的团
                                                </div>
                                            </div>
                                            <div class="right">
                                                <!-- 还差几人 -->
                                                <div class="almost">
                                                    <div class="almost-people">还差<span class="num">${surplus_num}</span>成团</div>
                                                    <div class="almost-time">剩余<span class="time"></span></div>
                                                </div>
                                                <!-- 去参团 -->
                                                <div class="tuxedo" data-groupid="${item.group_id}">
                                                    去参团
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    $(".count-down-mess-wrap .count-down-mess .count-down-one-wrap .wrap-count-down-one").append(html);
                    getTimer(DATA, index)
                    getDjsTimer(DATA, index)

                })
                // 总高度
                var totleHeight = $(".count-down-mess").height() - $(".count-down-mess .title-mess").height();
                MinHeight = $(".count-down-one").height();
                // 两个的高度
                MaxHeight = $(".count-down-one").height() * 2;

                if (data.group.length == 1) {
                    $(".count-down-one-wrap").css({
                        "max-height": MaxHeight,
                        "min-height": MinHeight,
                    })
                } else {
                    $(".count-down-one-wrap").css({
                        "max-height": MaxHeight,
                        "min-height": MaxHeight
                    })
                }
                setInterval(move, 5000)
                $(".tuxedo").click(function () {
                    var groupId = $(this).data("groupid");
                    window.location.href = '/gpubs/share?id=' + groupId + '&p_id=' + url("?id")
                })

            }



            function errorCB(data) {
                alert(data.data.errMsg);
            }
            requestUrl(req_url, 'GET', _data, success, errorCB);
        }

        function move() {
            group_count++;
            if (group_count >= totle_group) {
                group_count = 0;
                $('.wrap-count-down-one').css({
                    top: 0,
                })
            }
            $('.wrap-count-down-one').css({
                top: -MaxHeight * group_count,

            })
        }

        function getTimer(DATA, index) {
            if (DATA > 0) {
                DATA -= 1;
                // 转换为天、时、分、秒
                // 天
                Day = parseInt(DATA / 60 / 60 / 24);
                if (Day < 10) {
                    Day = "0" + Day;
                }

                // 时
                Hour = parseInt((DATA - Day * 60 * 60 * 24) / 60 / 60);
                if (Hour < 10) {
                    Hour = "0" + Hour;
                }

                // 分
                Minute = parseInt(((DATA - Day * 60 * 60 * 24) - Hour * 60 * 60) / 60);
                if (Minute < 10) {
                    Minute = "0" + Minute;
                }

                // 秒
                Second = parseInt(((DATA - Day * 60 * 60 * 24) - Hour * 60 * 60) - Minute * 60);
                if (Second < 10) {
                    Second = "0" + Second;
                }
                let format = ''
                if(Day>0){
                    format = `${Day}天${Hour}:${Minute}:${Second}`
                }else {
                    format = `${Hour}:${Minute}:${Second}`
                }
                
                $(".almost-time .time").eq(index).html(format);
            } else {

                self.content = 'over'
            }

        }

        function getDjsTimer(DATA, index) {
            let time = setInterval(function () {
                if (DATA > 0) {
                    DATA -= 1;
                    // 转换为天、时、分、秒
                    // 天
                    Day = parseInt(DATA / 60 / 60 / 24);
                    if (Day < 10) {
                        Day = "0" + Day;
                    }

                    // 时
                    Hour = parseInt((DATA - Day * 60 * 60 * 24) / 60 / 60);
                    if (Hour < 10) {
                        Hour = "0" + Hour;
                    }

                    // 分
                    Minute = parseInt(((DATA - Day * 60 * 60 * 24) - Hour * 60 * 60) / 60);
                    if (Minute < 10) {
                        Minute = "0" + Minute;
                    }

                    // 秒
                    Second = parseInt(((DATA - Day * 60 * 60 * 24) - Hour * 60 * 60) - Minute * 60);
                    if (Second < 10) {
                        Second = "0" + Second;
                    }
                    let format = ''
                    if(Day>0){
                        format = `${Day}天${Hour}:${Minute}:${Second}`
                    }else {
                        format = `${Hour}:${Minute}:${Second}`
                    }
                    $(".almost-time .time").eq(index).html(format);
                } else {

                    self.content = 'over'
                }
            }, 1000)

        }
        $("#addCart").click(function(){
            $("#specifications-detail-wrap").removeClass("hidden");
        })
        $("#purchase-now").click(function(){
            $("#specifications-detail-wrap").removeClass("hidden");
        })


        //添加购物车
        $("#addCart-qubie").on("click", function () {
            
            var data = {
                id: url('?id')
            };

            if (_user_login_status != 1) {
                var con = confirm('您还未登录，是否跳转到登录页？');
                if (con == true) {
                    window.location.href = '/member/login/index'
                }
                return
            }
            var len = $('#specifications-detail-wrap .standard-title').length;
            $('#specifications-detail-wrap  .standard-title').removeClass('has-error');
            var _selectAll = true;
            for (var i = 0; i < len; i++) {
                var leng = $('#specifications-detail-wrap .standard-title').eq(i).find('li').length;
                if (!$('#specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                    _selectAll = false
                    break
                }
            }
            if (!_selectAll) {
                alert('属性未选择完整！')
                return false
            }
            var cartId = '';
            $.each($('.li-active'), function (i, v) {
                cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
            })

            cartId = cartId.substring(0, cartId.length - 1);


            var sku_id = _order_data['SKU']['sku'][cartId].id;

            var count = parseInt($('#deliver-goods-text').val());
            if (count < 1) {
                alert('数量为0！')
                return false
            }
            var _data = {
                product_id: url('?id'),
                sku_id: sku_id,
                count: count
            }

            function addCB(data) {
                alert('添加成功！');
                $("#specifications-detail-wrap").addClass("hidden");
            }
            if ($(this).attr("status") == 1) {
                return
                
            } else{
                $.each(_order_data.SKU.sku, function (index, item) {
                    if(item.id == sku_id){
                        if(item.stock < count){
                            alert("该商品库存不足");
                            return
                        } else {
                            requestUrl('/shopping/add-cart', 'POST', _data, addCB, function (data) {
                                if (data.status == '3031') {
                                    alert('商品已下架！')
                                } else {
                                    alert(data.data.errMsg)
                                }
                            });
                        }
                    }
                })
            }


        });

        // 立即购买
        $("#purchase-now-qubie").on("click", function () {
            var data = {
                id: url('?id')
            };
            if (_user_login_status != 1) {
                var con = confirm('您还未登录，是否跳转到登录页？');
                if (con == true) {
                    window.location.href = '/member/login/index'
                }
                return
            }
            var len = $('#specifications-detail-wrap .standard-title').length;
            $('#specifications-detail-wrap .standard-title').removeClass('has-error');
            var _selectAll = true;
            for (var i = 0; i < len; i++) {
                var leng = $('#specifications-detail-wrap .standard-title').eq(i).find('li').length;
                if (!$('#specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                    _selectAll = false
                    break
                }
            }

            if (!_selectAll) {
                alert('属性未选择完整！')
                return false
            }
            var cartId = '';
            $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
            })

            cartId = cartId.substring(0, cartId.length - 1);
            var sku_id = _order_data['SKU']['sku'][cartId].id;

            var count = parseInt($('#deliver-goods-text').val());
            if (count < 1) {
                alert('购买数量为0！')
                return false
            }
            
            var _data = {
                product_id: url('?id'),
                sku_id: sku_id,
                count: count
            }

            function nowBuyCB(data) {

                window.location.href = "/shopping" + data.url;
            }

            if ($(this).attr("status") == 1) {
                $.each(_order_data.SKU.sku, function (index, item) {
                    if(item.id == sku_id){
                        if(item.stock < count){
                            alert("该商品库存不足");
                            return
                        } else {
                            requestUrl('/shopping/order', 'POST', _data, nowBuyCB, function (data) {
                                if (data.status == '3031') {
                                    alert('商品已下架！')
                                } else {
                                    alert(data.data.errMsg)
                                }
                            });
                        }
                    }
                })
            }
        });


        // 规格详情页单独购买
        $("#purchase-separately-qubie").on("click", function () {
            
            var data = {
                id: url('?id')
            };
            if (_user_login_status != 1) {
                var con = confirm('您还未登录，是否跳转到登录页？');
                if (con == true) {
                    window.location.href = '/member/login/index'
                }
                return
            }
            var len = $('#specifications-detail-wrap .standard-title').length;
            $('#specifications-detail-wrap .standard-title').removeClass('has-error');
            var _selectAll = true;
            for (var i = 0; i < len; i++) {
                var leng = $('#specifications-detail-wrap .standard-title').eq(i).find('li').length;
                if (!$('#specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                    _selectAll = false
                    break
                }
            }
            if (!_selectAll) {
                alert('属性未选择完整！')
                return false
                $("#specifications-detail-wrap").removeClass("hidden");
            }
            var cartId = '';
            $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
            })

            cartId = cartId.substring(0, cartId.length - 1);
            var sku_id = _order_data['SKU']['sku'][cartId].id;

            var count = parseInt($('#deliver-goods-text').val());
            if (count < 1) {
                alert('购买数量为0！')
                return false
            }
            
            var _data = {
                product_id: url('?id'),
                sku_id: sku_id,
                count: count
            }

            function nowBuyCB(data) {

                window.location.href = "/shopping" + data.url;
            }
            if ($(this).attr("status") == 1) {
                $.each(_order_data.SKU.sku, function (index, item) {
                    if(item.id == sku_id){
                        if(item.stock == 0){
                            alert("该商品此规格库存不足，无法购买");
                            return
                        } 
                        if(item.stock < count){
                            alert("该商品库存不足");
                            return
                        } else {
                            requestUrl('/shopping/order', 'POST', _data, nowBuyCB, function (data) {
                                if (data.status == '3031') {
                                    alert('商品已下架！')
                                } else {
                                    alert(data.data.errMsg)
                                }
                            });
                        }
                    }
                })
                
            }
            
        });

        // 规格详情中我要开团
        $("#want-open-group-qubie").on("click", function () {
            var data = {
                id: url('?id')
            };
            if (_user_login_status != 1) {
                var con = confirm('您还未登录，是否跳转到登录页？');
                if (con == true) {
                    window.location.href = '/member/login/index'
                }
                return
            }
            var len = $('#specifications-detail-wrap .standard-title').length;
            $('#specifications-detail-wrap .standard-title').removeClass('has-error');
            var _selectAll = true;
            for (var i = 0; i < len; i++) {
                var leng = $('#specifications-detail-wrap .standard-title').eq(i).find('li').length;
                if (!$('#specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                    _selectAll = false
                    break
                }
            }
            if (!_selectAll) {
                alert('属性未选择完整！')
                return false
                $("#specifications-detail-wrap").removeClass("hidden");
            }
            var cartId = '';
            $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
            })

            cartId = cartId.substring(0, cartId.length - 1);
            var sku_id = _order_data['SKU']['sku'][cartId].id;

            var count = parseInt($('#deliver-goods-text').val());
            if (count < 1) {
                alert('购买数量为0！')
                return false
            }

            $.each(_pingou_data.sku, function (index, item) {
                if(index == sku_id){
                    if(item.stock == 0 || item.stock < count){
                        alert("该拼购商品此规格库存不足，无法开团");
                        return
                    } else {
                        if (url('?group_id')) {
                            window.location.href = '/gpubs/confirm?id=' + url('?id') + '&skuid=' + sku_id + '&num=' + count + '&group_id=' + url('?group_id')
                        } else {
                            window.location.href = '/gpubs/confirm?id=' + url('?id') + '&skuid=' + sku_id + '&num=' + count
                        }
                    }
                }
            })

        });



        // 商品详情页单独购买
        $("#detail-purchase-separately").on("click", function () {
            $("#alone-specifications-detail-wrap").removeClass("hidden");
            $("#alone-specifications-detail-wrap .spell-delivery").css({
                opacity: 0
            })
            $("#alone-confirm").click(function () {
                var data = {
                    id: url('?id')
                };
                if (_user_login_status != 1) {
                    var con = confirm('您还未登录，是否跳转到登录页？');
                    if (con == true) {
                        window.location.href = '/member/login/index'
                    }
                    return
                }
                var len = $('#alone-specifications-detail-wrap .standard-title').length;
                $('#alone-specifications-detail-wrap .standard-title').removeClass('has-error');
                var _selectAll = true;
                for (var i = 0; i < len; i++) {
                    var leng = $('#alone-specifications-detail-wrap .standard-title').eq(i).find('li').length;
                    if (!$('#alone-specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                        _selectAll = false
                        break
                    }

                }
                if (!_selectAll) {
                    alert('属性未选择完整！')
                    return false
                }
                var cartId = '';
                $.each($('#alone-specifications-detail-wrap .li-active'), function (i, v) {
                    cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
                })

                cartId = cartId.substring(0, cartId.length - 1);
                var sku_id = _order_data['SKU']['sku'][cartId].id;

                var count = parseInt($('#alone-deliver-goods-text').val());
                if (count < 1) {
                    alert('购买数量为0！')
                    return false
                }
                
                var _data = {
                    product_id: url('?id'),
                    sku_id: sku_id,
                    count: count
                }

                function nowBuyCB(data) {

                    window.location.href = "/shopping" + data.url;
                }
                if ($(this).attr("status") == 1) {
                    $.each(_order_data.SKU.sku, function (index, item) {
                        if(item.id == sku_id){
                            if(item.stock < count){
                                alert("该商品库存不足");
                                return
                            } else {
                                requestUrl('/shopping/order', 'POST', _data, nowBuyCB, function (data) {
                                    if (data.status == '3031') {
                                        alert('商品已下架！')
                                    } else {
                                        alert(data.data.errMsg)
                                    }
                                });
                            }
                        }
                    })
                    
                }
            })


        });

        // 商品详情页我要开团
        $("#detail-want-open-group").on("click", function () {
            confirmBtn();

        });
        function confirmBtn(){
            $("#specifications-detail-wrap").removeClass("hidden");
            $("#purchase-separately-qubie").addClass("hidden");
            $("#want-open-group-qubie").addClass("hidden");
            $("#confirm").removeClass("hidden")
            $("#confirm").bind('click',function () {
                var data = {
                    id: url('?id')
                };
                if (_user_login_status != 1) {
                    var con = confirm('您还未登录，是否跳转到登录页？');
                    if (con == true) {
                        window.location.href = '/member/login/index'
                    }
                    return
                }
                var len = $('#specifications-detail-wrap .standard-title').length;
                $('#specifications-detail-wrap .standard-title').removeClass('has-error');
                var _selectAll = true;
                for (var i = 0; i < len; i++) {
                    var leng = $('#specifications-detail-wrap .standard-title').eq(i).find('li').length;
                    if (!$('#specifications-detail-wrap .standard-title').eq(i).find('li').hasClass('li-active')) {
                        _selectAll = false
                        break
                    }
                }
                if (!_selectAll) {
                    alert('属性未选择完整！')
                    return false
                }
                var cartId = '';
                $.each($('#specifications-detail-wrap .li-active'), function (i, v) {
                    cartId += $(this).parent().parent().find("span").data("id") + ':' + $(this).attr('attr_id') + ';'
                })

                cartId = cartId.substring(0, cartId.length - 1);
                var sku_id = _order_data['SKU']['sku'][cartId].id;

                var count = parseInt($('#specifications-detail-wrap #deliver-goods-text').val());
                if (count < 1) {
                    alert('购买数量为0！')
                    return false
                }
                $.each(_pingou_data.sku, function (index, item) {
                    if(index == sku_id){
                        if(item.stock == 0 || item.stock < count){
                            alert("该拼购商品此规格库存不足，无法开团");
                            return
                        } else {
                            if (url('?group_id')) {
                                window.location.href = '/gpubs/confirm?id=' + url('?id') + '&skuid=' + sku_id + '&num=' + count + '&group_id=' + url('?group_id')
                            } else {
                                window.location.href = '/gpubs/confirm?id=' + url('?id') + '&skuid=' + sku_id + '&num=' + count
                            }
                        }
                    }
                })
            })
        }



        // 规格详情的显示隐藏
        $(".specifications").on("click", function () {
            $("#specifications-detail-wrap").removeClass("hidden");
            $("#purchase-separately-qubie").removeClass("hidden");
            $("#want-open-group-qubie").removeClass("hidden");
            $("#confirm").addClass("hidden")
        })
        $("#specifications-detail-wrap-close").on("click", function () {
            $("#specifications-detail-wrap").addClass("hidden");
            $("#confirm").unbind();
        })
        $("#alone-specifications-detail-wrap-close").on("click", function () {
            $("#alone-confirm").unbind();
            $("#alone-specifications-detail-wrap").addClass("hidden");
            $("#specifications-detail-wrap .spell-delivery").css({
                opacity: 1
            })
        })

        // 拼购规则详情的跳转
        $("#rule-more").on("click", function () {
            window.location.href = "/goods/activity-rules?type=" + gpubs_type;
        })
        $(".pingou-index-button").click(function () {
            window.location.href = "/";
        })

        $(".contact-service-button").click(function(){
            if (_user_login_status != 1) {
                var con = confirm('您还未登录，是否跳转到登录页？');
                if (con == true) {
                    window.location.href = '/member/login/index'
                }
                return
            }
        })

        // 服务说明
        $("#service-policy-more").click(function(){
            $("#service-policy-detail-wrap").removeClass("hidden")
        })
        $("#service-policy-detail-wrap-close").click(function(){
            $("#service-policy-detail-wrap").addClass("hidden")
        })
         // 查看产品参数
         $('#J_show_params').on('click', function () {
            $('#mask_select_payment').addClass('in')
        })
        $('#mask_select_payment .close').on('click', function() {
            $('#mask_select_payment').removeClass('in')
        })
        $(".goods-intro-mess").click(function(){
            var Size = parseInt($(this).css('line-height'));
            SIZE = Size*2;
            var Height = parseInt($(this).height());
            if($(this).hasClass("main-goods")){
                $(this).removeClass("main-goods")
                $(this).css({'height':"",});
                $(this).removeAttr('data-attr');
            } else {
                if(Height>SIZE){
                    $(this).css({'height':SIZE,});
                    $(this).addClass("main-goods")
                    $(this).attr('data-attr',"...")
                }
            }
        })
        
    }
    function isLogin(callback) {
        requestUrl('/temp/groupbuy/is-login', 'GET', null, callback)
    }
})