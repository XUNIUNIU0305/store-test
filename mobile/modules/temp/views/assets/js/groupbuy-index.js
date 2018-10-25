(function () {
    var storagedData = {
        curPage: 1,
        login: false
    }

    isLogin(function (data) {
        storagedData.login = data['is-login']
        init()
    })

    // 页面
    function init() {
        getData(function (data) {
            $('.content .inner').html(renderProdsTpl(groupbuyData(data.groupbuy)))
            interact()
        })
        renderRandomProds()
    }
    // 数据
    function getData(callback) {
        requestUrl('/temp/groupbuy/get-all-groupbuy', 'GET', null, callback)
    }
    function getDetail(id, callback) {
        requestUrl('/temp/groupbuy/get-all-groupbuy-specific', 'GET', { groupbuy_id: id }, callback)
    }
    function groupbuyData(data) {
        var result = {}
        var sortResult = {}

        for (var i = 0; i < data.length; i += 1) {
            var item = data[i]

            if (item.top_level_category !== '其他') {
                if (!result[item.top_level_category]) {
                    result[item.top_level_category] = []
                }

                result[item.top_level_category].push(item)
            } else {
                if (!sortResult[item.top_level_category]) {
                    sortResult[item.top_level_category] = []
                }

                sortResult[item.top_level_category].push(item)
            }

        }

        return Object.assign(result, sortResult)
    }
    function isLogin(callback) {
        requestUrl('/temp/groupbuy/is-login', 'GET', null, callback)
    }
    // 模版
    function renderProdsTpl(data) {
        var tpl = ''

        for (var key in data) {
            var subData = data[key]

            tpl += '<section>'
            tpl += '<header>'
            tpl += '<h1>' + key + '</h1>'
            tpl += '</header>'
            tpl += '<ul>'

            for (var i = 0; i < subData.length; i += 1) {
                var item = subData[i]

                tpl += '<li>'
                tpl += '<div class="avatar" style="background-image:url(' + item.images[0] + ')"></div>'
                tpl += '<div class="info">'
                tpl += '<div class="title">' + item.title + '</div>'
                tpl += '<div class="price">'

                // 未登录时原价、拼团价显示为 '?'
                if (!storagedData.login) {
                    item.groupbuy_price = '?'
                    item.origin_price = '?'
                }

                // 未到阶段价，显示原价
                if (item.groupbuy_price == item.origin_price) {
                    tpl += '<span class="mark">￥</span><span class="current">' + item.origin_price + '</span>'
                } else {
                    tpl += '<span class="mark">￥</span><span class="current">' + item.groupbuy_price + '</span><span class="origin">原价：' + item.origin_price + '元</span>'
                }

                tpl += '</div>'
                tpl += '</div>'
                tpl += '<div class="sell">已售' + item.sales + '件</div>'
                tpl += '<a class="btn-pin" data-id="' + item.groupbuy_id + '" data-prodid="' + item.product_id + '">去拼团</a>'
                tpl += '</li>'
            }

            tpl += '</ul>'
            tpl += '</section>'
        }

        return tpl
    }
    function renderRuleBox() {
        var tpl = ''

        tpl += '<div class="rule-box">'
        tpl += '<div class="inner">'
        tpl += '<div class="box">'
        tpl += '<a class="close">'
        tpl += '<img src="/images/group-buy/close.png" />'
        tpl += '</a>'
        tpl += '<img class="rule-title" src="/images/group-buy/rule-title.png" />'
        tpl += '<img src="/images/group-buy/rule-bg.png" />'
        tpl += '<div class="content">'
        tpl += '<dl>'
        tpl += '<dt>规则说明：</dt>'
        tpl += '<dd>1、定制商品金额不计入满减金额内；</dd>'
        tpl += '<dd>2、所有满减金额均在结算时直接减去，请放心购买；</dd>'
        tpl += '<dd>3、满减活动与秒杀、拼团可叠加；</dd>'
        tpl += '<dd>4、1225 当日所有订单无法取消；</dd>'
        tpl += '<dd>5、九大爷保留对此活动最终解释权。</dd>'
        tpl += '</dl>'
        tpl += '<table>'
        tpl += '<tr>'
        tpl += '<th>品牌名称</th>'
        tpl += '<th>满减规则</th>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>岩崎</td>'
        tpl += '<td><div>品牌商品</div><div>满 1000 减 100；</div></td>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>奔瑞</td>'
        tpl += '<td><div>品牌商品</div><div>满 500 减 20；</div><div>满 1000 减 50；</div><div>满 3000 减 200；</div><div>满 5000 减 500；</div></td>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>肖勒</td>'
        tpl += '<td><div>品牌商品</div><div>满 1500 减 100；</div><div>满 3000 减 200；</div><div>满 5000 减 300；</div><div>满 7000 减 400；</div><div>满 9000 减 500；</div><div>满 20000 减 1000；</div></td>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>达里亚</td>'
        tpl += '<td><div>品牌商品</div><div>满 1000 减 100；</div><div>满 2000 减 200；</div></td>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>车品之巅</td>'
        tpl += '<td><div>品牌商品</div><div>满 1000 减 100；</div><div>满 500 减 50；</div></td>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>3M</td>'
        tpl += '<td><div>品牌商品</div><div>满 880 减 50；</div></td>'
        tpl += '</tr>'
        tpl += '<tr>'
        tpl += '<td>gigi</td>'
        tpl += '<td><div>品牌商品</div><div>满 1000 减 50；</div></td>'
        tpl += '</tr>'
        tpl += '</table>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
    }
    function renderDescBox() {
        var tpl = ''

        tpl += '<div class="desc-box">'
        tpl += '<div class="inner">'
        tpl += '<div class="box">'
        tpl += '<a class="close">'
        tpl += '<img src="/images/group-buy/close.png" />'
        tpl += '</a>'
        tpl += '<img class="rule-title" src="/images/group-buy/desc-title.png" />'
        tpl += '<img src="/images/group-buy/desc-bg.png" />'
        tpl += '<div class="content">'

        tpl += '<dl>'
        tpl += '<dt>规则说明：</dt>'
        tpl += '<dd>1、定制商品金额不计入满减金额内；</dd>'
        tpl += '<dd>2、所有涉及拼团商品的订单，无法取消，请仔细确认后下单；</dd>'
        tpl += '<dd>3、所有拼团商品先收取原价，在当日活动结束后，按照实际成团人数，再将优惠金额返还至用户账户余额；</dd>'
        tpl += '<dd>4、1225 当日所有订单无法取消；</dd>'
        tpl += '<dd>5、九大爷保留对此活动最终解释权。</dd>'
        tpl += '</dl>'

        tpl += '<div data-id="js-sku-table"></div>'

        tpl += '</div>'

        tpl += '<footer>'
        tpl += '<a class="close-close">关闭</a><a class="go-go-go">立即拼团</a>'
        tpl += '</footer>'

        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
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

        tpl += '<table>'
        tpl += '<tr>'

        for (var i = 0; i < title.length; i += 1) {
            tpl += '<th>' + title[i] + '</th>'
        }

        tpl += '</tr>'

        for (var i in data.sku) {
            var skuItem = data.sku[i]
            var sku = i.split(';')
            var ary = []

            tpl += '<tr>'

            tpl += '<td style="word-break: break-all;">'
            for (var j = 0; j < sku.length; j += 1) {
                var key = sku[j].split(':')[0]
                var value = sku[j].split(':')[1]
                var name = skuNames[skuids.indexOf(key)]

                ary.push(data.attributes[key][name][value])
            }
            tpl += ary.join('/')
            tpl += '</td>'

            tpl += '<td>' + skuItem.first_gradient_price + '</td>'
            tpl += '<td>' + skuItem.second_gradient_price + '</td>'
            tpl += '<td>' + skuItem.third_gradient_price + '</td>'

            tpl += '</tr>'
        }

        tpl += '</table>'

        return tpl
    }
    function renderRandomProds() {
        /*加载随机商品*/
        var tpl_random = '{@each _ as it}'

        tpl_random += '<li>'
        tpl_random += '<a href="/goods/detail?id=${it.id}">'
        tpl_random += '<div class="img-box">'
        tpl_random += '<img src="${it.main_image}?x-oss-process=image/resize,w_185,h_185,limit_1,m_lfit">'
        tpl_random += '</div>'
        tpl_random += '<p>${it.title}</p>'
        tpl_random += '<span>${it.description}</span>'
        tpl_random += '</a>'
        tpl_random += '</li>'
        tpl_random += '{@/each}'

        scroll($('.daye-group-buying-wrapper')[0])
            .then(function (data) {
                if (data.bottom) {
                    storagedData.curPage += 1
                    requestUrl('/product-recommend/rand', 'GET', { current_page: storagedData.curPage }, function(data) {
                        $('#J_random_list').append(juicer(tpl_random, data));
                    })
                }
            })
    }
    // 交互
    function showRuleBox() {
        $('#box').html(renderRuleBox())
        $('.close').unbind().on('click', hideRuleBox)
        $('.daye-group-buying-wrapper').css('overflow', 'hidden')
    }
    function hideRuleBox() {
        $('#box').html('')
        $('.daye-group-buying-wrapper').removeAttr('style')
    }
    function showDescBox() {
        $('#box').html(renderDescBox())
        $('.close').unbind().on('click', hideDescBox)
        $('.close-close').unbind().on('click', hideDescBox)
        $('.daye-group-buying-wrapper').css('overflow', 'hidden')
    }
    function hideDescBox() {
        $('#box').html('')
        $('.daye-group-buying-wrapper').removeAttr('style')
    }
    function interact() {
        $('.btn-rule').on('click', function () {
            showRuleBox()
        })
        $('.btn-pin').on('click', function () {
            var id = this.getAttribute('data-id')
            var prodid = this.getAttribute('data-prodid')

            showDescBox()

            if (storagedData.login) {
                getDetail(id, function (data) {
                    $('[data-id=js-sku-table]').html(renderSkuTable(data.groupbuy))
                    // 接口返回后给立即拼团添加跳转商品详情页链接
                })
            }

            $('.go-go-go').attr('href', '/goods/detail?id=' + prodid)
        })
    }
})()
