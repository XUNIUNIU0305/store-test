
(function () {
    var barstr = ''
    var arr = []
    var scroll_id = ['car','electric','beauty','cleaning','maintenance','carProduct','strick','store']

    function setSession () {
        $('.apex-product-category .default-understand-more a').on('click',function (e) {
            var getMapId = $(this).parents('.default-main').attr('id')
            sessionStorage.setItem('_map',getMapId)
        })
        save_groupbuy_id()
    }
    function save_groupbuy_id() {
        $('.apex-product-category .once-buying a').on('click',function (e) {
            var groupbuy_id = $(this).parents('span').attr('data-groupbuy_id')
            sessionStorage.setItem('groupbuy_id',groupbuy_id)
        })
    }
    function getProduct() {
        requestUrl('/temp/groupbuy/get-all-groupbuy','GET','',function (data) {
            $('.apex-product-category .row').html(addCategoryTemp(data))
            $('.aside-bar-menu .nav-middle').append(barstr + (arr.shift() || ''))
            understandMore()
            mapLink()
            setSession()
            var _map = sessionStorage.getItem('_map')
        })
    }
    function groupbuyData(data) {
        var result = {}

        for (var i = 0; i < data.length; i += 1) {
            var item = data[i]

            if (!result[item.top_level_category]) {
                result[item.top_level_category] = []
            }

            result[item.top_level_category].push(item)
        }

        return result
    }
    function renderSkuTable(data) {
        // sku 属性 id
        var skuids = []
        // sku 属性名称
        var skuNames = []
        // 表头
        var title = [
            '当前商品',
            '满' + data.first_gradient_sales_goals + '件',
            '满' + data.second_gradient_sales_goals + '件',
            '满' + data.third_gradient_sales_goals + '件'
        ]
        var tpl = ''

        for (var i in data.attributes) {
            var item = data.attributes[i]
            skuids.push(i)

            for (var name in item) {
                skuNames.push(name)
            }
        }

        tpl += '<div class="item-list-head">'
        for (var i = 0; i < title.length; i += 1) {
            tpl += '<span>' + title[i] + '</span>'
        }
        tpl += '</div>'

        tpl += '<div class="item-list-body">'
        for (var i in data.sku) {
            var skuItem = data.sku[i]
            var sku = i.split(';')
            var ary = []

            tpl += '<div class="list-body-child">'
            tpl += '<span style="word-break: break-all;">'
            for (var j = 0; j < sku.length; j += 1) {
                var key = sku[j].split(':')[0]
                var value = sku[j].split(':')[1]
                var name = skuNames[skuids.indexOf(key)]

                ary.push(data.attributes[key][name][value])
            }
            tpl += ary.join('/')
            tpl += '</span>'

            tpl += '<span class="color-primary">' + skuItem.first_gradient_price + '</span>'
            tpl += '<span class="color-primary">' + skuItem.second_gradient_price + '</span>'
            tpl += '<span class="color-primary">' + skuItem.third_gradient_price + '</span>'

            tpl += '</span>'
            tpl += '</div>'
        }

        tpl += '</div>'

        return tpl
    }
    function addCategoryTemp(data) {
        var str = ''
        var ari = []

        var data = groupbuyData(data.groupbuy)
        var count = 0
        for (var name in data) {

            if (name === '其他') {
                ari.push(returnProductTemp(data, name, 9))
                arr.push('<a href="#'+ name +'"><span>' + name + '</span></a>')
            } else {
                barstr += '<a href="#'+ name +'"><span>' + name + '</span></a>'
                str += returnProductTemp(data, name, ++count)
            }
        }

        return str + (ari.shift() || '')
    }
    function returnProductTemp (data, name, count) {
        var str = ""

        str +='<div class="default-main" id="' + name + '">'
        str +='<div class="default-list-item">'
        str +='<div class="list-item  category-picture">'
        str +='<div>'
        str +='<div class="default-picture-hall">'
        str +='<span style="background-image:url(/images/groupbuying/title' + count + '.png)"><em>' + name + '</em></span>'
        str +='</div>'
        str +='</div>'
        str +='</div>'

        for ( var k = 0; k < data[name].length; k++){
            var tiny = data[name][k]
            str +='<div class="list-item">'
            str +='<div>'
            str +='<div class="default-picture-hall">'
            str +='<span><img src="'+ tiny.images[0] +'" alt="" class="img-responsive"></span>'
            str +='</div>'
            str +='<div class="default-title-hall">'
            str +='<span>'+ tiny.title +'</span>'
            str +='</div>'
            str +='<div class="default-money-hall">'
            if(tiny.groupbuy_price == tiny.origin_price){
                str +='<span></span>'
                str +='<span>'
                str +='<div class="">'
                str +='<span>已购<em>'+ tiny.sales +'</em>件</span>'
                str +='<span class="translate_price">原价:<strong>￥</strong><em>'+ tiny.groupbuy_price +'</em></span>'
                str +='</div>'
            }
            else{
                str +='<span>￥<em>'+ tiny.groupbuy_price +'</em></span>'
                str +='<span>'
                str +='<div class="">'
                str +='<span>已购<em>'+ tiny.sales +'</em>件</span>'
                str +='<span>原价:￥<em>'+ tiny.origin_price +'</em></span>'
                str +='</div>'
            }
            str +='</span>'
            str +='</div>'
            str +='<div class="default-once-hall-buy default-understand-more">'
            str +='<span class="show-understand" data-product_id="'+ tiny.product_id+'" data-groupbuy_id="'+ tiny.groupbuy_id+'">了解更多</span>'
            str +='<span class="active once-buying" data-product_id="'+ tiny.product_id+'" data-groupbuy_id="'+ tiny.groupbuy_id+'"><a href="/product?id='+ tiny.product_id + '" target="_blank">立即购买</a></span>'
            str +='</div>'
            str +='</div>'
            str +='</div>'
        }

        str +='</div>'
        str +='</div>'

        return str
    }
    function showUnderstandMore() {
        $('.apex-product-category .show-understand').on('click',function (e) {
            $('.apex-understand-shadow').show()

            var getMapId = $(this).parents('.default-main').attr('id')
            sessionStorage.setItem('_map',getMapId)


            if (e.target.hasAttribute('data-product_id')) {
                var product_id =$(this).attr('data-product_id')
                var groupbuy_id =$(this).attr('data-groupbuy_id')
                sessionStorage.setItem('groupbuy_id',groupbuy_id)
                var title = $(this).parent('.default-once-hall-buy').siblings('.default-title-hall').find('span').text()

                $('.apex-understand-shadow .active >a').attr('href','/product?id='+product_id)
                $('.apex-understand-shadow .default-head').find('span').html(title)

                requestUrl('/temp/groupbuy/get-all-groupbuy-specific','GET',{
                    groupbuy_id:groupbuy_id
                },function (data) {
                    $('.apex-understand-shadow .item-list').html(renderSkuTable(data.groupbuy))
                })
            }
        })
    }
    function understandClose() {
        $('.apex-understand-shadow .default-understand-close').on('click',function (e) {
            $('.apex-understand-shadow').hide()
        })
    }
    function understandSubmit() {
    }
    function mapLink() {
        $('a[href*="#"]').click(function () {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var $target = $(this.hash)
                $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
                if ($target.length) {
                    var targetOffset = $target.offset().top
                    $('body').animate({
                        scrollTop: targetOffset
                    },
                    500)
                    return false
                }
            }
        })
    }
    function understandMore() {
        showUnderstandMore()
        understandClose()
        understandSubmit()
    }
    function init() {
        getProduct()
    }
    init()
}())
// 秒杀
$(function () {
    /*注册juicer自定义函数*/
    function price(data) {
        return parseFloat(data.min).toFixed(2)
    }
    juicer.register('price', price);
    var seckillTimer = {
        container: $('#J_timer_box'),
        timerDom: $('#J_seckill_timer'),
        data: [],
        startT: '',
        endT: '',
        seckillRun: function (intDiff, callback) {
            var _this = this;
            jdy.seckill.timerFun(intDiff / 1000, function (date) {
                var _day = date.day * 24;
                var _hour = parseInt(date.hour) + parseInt(_day);
                if (_hour < 10) {
                    _hour = '0' + _hour;
                }
                $('#timer_hour').html(_hour);
                $('#timer_minute').html(date.minute);
                $('#timer_second').html(date.second);
            }, function () {
                callback();
            })
        },
        product: {
            box: $('#J_product_box'),
            tpl: $('#J_tpl_pro').html(),
            id: [1306, 1307, 1308, 1309, 1310],
            getPro: function () {
                var _this = this;
                requestUrl('/product-recommend/goods', 'GET', { id: _this.id }, function (data) {
                    seckillTimer.data = data;
                    _this.box.html(juicer(_this.tpl, { data: data, temp: seckillTimer.temp }));
                })
            }
        },
        temp: 0,
        unstart: function (timer) {
            this.seckillRun(timer, function () {
                $('#timer_text').html('距离结束还剩：');
                seckillTimer.temp = 1;
                seckillTimer.product.box.html(juicer(seckillTimer.product.tpl, {
                    data: seckillTimer.data,
                    temp: seckillTimer.temp
                }));
                seckillTimer.start(seckillTimer.endT - new Date().getTime());
            });
        },
        start: function (timer) {
            seckillTimer.seckillRun(timer, function () {
                seckillTimer.temp = 0;
                seckillTimer.product.box.html(juicer(seckillTimer.product.tpl, {
                    data: seckillTimer.data,
                    temp: seckillTimer.temp
                }));
                seckillTimer.end();
            })
        },
        end: function () {
            $('#timer_hour').html('00');
            $('#timer_minute').html('00');
            $('#timer_second').html('00');
        },
        init: function () {
            var _this = this;
            var _now = new Date().getTime();
            var _start = new Date('2017/12/24 23:59:59').getTime();
            var _end = new Date('2017/12/25 23:59:59').getTime();
            this.startT = _start;
            this.endT = _end;
            if (_start - _now > 0) {
                _this.unstart(_start - _now);
            } else if (_now > _start && _end > _now) {
                this.temp = 1;
                this.start(_end - _now);
                $('#timer_text').html('距离结束还剩：');
            } else {
                this.temp = 0;
                this.end();
                $('#timer_text').html('距离结束还剩：');
            }
            this.product.getPro();
        }
    }
    seckillTimer.init();
})
