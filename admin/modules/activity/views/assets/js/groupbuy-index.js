;(function () {
    var storagedData = {
        prodPageIndex: 1,
        listPageIndex: 1,
        pageSize: 10
    }

    // 插入确认框模版
    commonConfrim()

    // 初始化
    init()

    function init() {
        getProds()
    }

    // 数据接口
    function getProds(callback) {
        showProdMask()
        requestUrl('/activity/groupbuy/get-groupbuy-product', 'GET', {
            current_page: storagedData.prodPageIndex,
            page_size: storagedData.pageSize
        }, function (data) {
            data.pageIndex = storagedData.prodPageIndex
            $('#prodsContent').html(createProdComponent(data))
            interactForProd()
            hideProdMask()

            if (typeof callback === 'function') {
                callback()
            }
        })
    }
    function getList(id) {
        showListMask()
        requestUrl('/activity/groupbuy/get-groupbuy', 'GET', {
            groupbuy_id: id
        }, function (data) {
            $('#listContent').html(createListComponent(data[0]))
            hideListMask()
        })
    }
    function createProd(data, callback, errorCallback) {
        requestUrl('/activity/groupbuy/create-groupbuy', 'POST', data, callback, errorCallback)
    }
    function exportExcel(callback, errorCallback) {
        requestUrl('/activity/groupbuy/export', 'GET', null, callback, errorCallback)
    }

    // 组件渲染
    function createProdComponent(data) {
        var tpl = ''

        tpl += '<header><button class="button primary">添加活动商品</button><span>活动商品</span></header>'
        tpl += '<div class="body">' + renderProdTpl(data) + '</div>'
        tpl += '<footer>' + renderPagination(data) + '</footer>'

        return tpl
    }
    function renderProdTpl(data) {
        var tpl = ''

        tpl += '<ul>'

        for (var i = 0; i < data.groupbuy.length; i += 1) {
            var item = data.groupbuy[i]

            if (item) {
                tpl += '<li data-id="' + item.id + '" data-stock="' + item.stock + '">'
                tpl += '<div class="photo" style="background-image:url(' + (item.images && item.images[0]) + ')"></div>'
                tpl += '<div class="info">'
                tpl += '<h2 class="title">' + item.title + '</h2>'
                tpl += '<div class="content">'
                tpl += '<span>总库存数：' + item.stock + '</span>'
                tpl += '<span>原价格：￥' + (item.min_price || 0) + '-￥' + (item.max_price || 0) + '</span>'
                tpl += '</div>'
                tpl += '<div class="content">'
                tpl += '<span>团名称：' + item.groupbuy_name + '</span>'
                tpl += '</div>'
                tpl += '</div>'
                tpl += '</li>'
            }
        }

        tpl += '</ul>'

        return tpl
    }
    function createListComponent(data) {
        // sku 属性 id
        var skuids = []
        // sku 属性名称
        var skuNames = []
        // 表头字段
        var titles = [ '原价', '库存', '阶段一金额', '阶段二金额', '阶段三金额' ]
        var tpl = ''

        for (var i in data.attributes) {
            var item = data.attributes[i]
            skuids.push(i)

            for (var title in item) {
                skuNames.push(title)
            }
        }

        titles = skuNames.concat(titles)

        var colWidth = 100 / titles.length

        tpl += '<header><span>阶段一件数：' + data.first_gradient_sales_goals + '</span><span>阶段二件数：' + data.second_gradient_sales_goals + '</span><span>阶段三件数：' + data.third_gradient_sales_goals + '</span></header>'

        tpl += '<ul class="table-header"><li>'
        for (var i = 0; i < titles.length; i += 1) {
            tpl += '<div style="width: ' + colWidth + '%">' + titles[i] + '</div>'
        }
        tpl += '</li></ul>'

        tpl += '<ul class="table-body">'

        for (var i in data.sku) {
            var skuItem = data.sku[i]
            var sku = i.split(';')

            tpl += '<li>'

            for (var j = 0; j < sku.length; j += 1) {
                var key = sku[j].split(':')[0]
                var value = sku[j].split(':')[1]
                var name = skuNames[skuids.indexOf(key)]

                tpl += '<div style="width: ' + colWidth + '%">' + data.attributes[key][name][value] + '</div>'
            }

            tpl += '<div class="color-primary" style="width: ' + colWidth + '%">￥' + skuItem.price + '</div>'
            tpl += '<div style="width: ' + colWidth + '%">' + skuItem.stock + '</div>'
            tpl += '<div style="width: ' + colWidth + '%">' + skuItem.first_gradient_price + '</div>'
            tpl += '<div style="width: ' + colWidth + '%">' + skuItem.second_gradient_price + '</div>'
            tpl += '<div style="width: ' + colWidth + '%">' + skuItem.third_gradient_price + '</div>'
            tpl += '</li>'
        }

        tpl += '</ul>'

        return tpl
    }
    function renderPagination(data) {
        // 计算总页数
        var totalPage = Math.ceil(data.total_count / storagedData.pageSize) || 1

        var tpl = ''

        tpl += '<div class="paging">'
        tpl += '<div>'

        if (data.pageIndex === 1) {
            tpl += '<button class="button primary-o js-prev" title="上一页" disabled>上一页</button>'
        } else {
            tpl += '<button class="button primary-o js-prev" title="上一页">上一页</button>'
        }

        tpl += '<span>' + data.pageIndex + '</span><span>/</span><span>' + totalPage + '</span>'

        if (data.pageIndex < totalPage) {
            tpl += '<button class="button primary-o js-next" title="下一页">下一页</button>'
        } else {
            tpl += '<button class="button primary-o js-next" title="下一页" disabled>下一页</button>'
        }

        tpl += '</div>'
        tpl += '<div>'
        tpl += '到第<input class="js-num" type="text" maxlength="3" data-max="' + totalPage + '" />页<button class="button primary-o js-goto" data-total="' + totalPage + '">跳转</button>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
    }
    function showProdMask() {
        $('#prods > .mask').addClass('show')
    }
    function hideProdMask() {
        $('#prods > .mask').removeClass('show')
    }
    function showListMask() {
        $('#list > .mask').addClass('show')
    }
    function hideListMask() {
        $('#list > .mask').removeClass('show')
    }

    // 组件交互
    function interactForProd() {
        $('.group-buying-wrapper #prods header > button').unbind().on('click', function () {
            apex.goodsPanel.init({
                onsubmit: function (id) {
                    if (id) {
                        apex.goodsPanel.remove()
                        apex.setPriceForGroupBuyingPanel.init({
                            id: id,
                            onsubmit: function (data) {
                                if (data.pass) {
                                    data.showSubmitProgress(data.target)

                                    createProd(data.data, function () {
                                        getProds()
                                        apex.setPriceForGroupBuyingPanel.remove()
                                    }, function (err) {
                                        showTipBox(err.data.errMsg)
                                        data.rebindSubmit(data.target)
                                    })
                                } else {
                                    showTipBox('请确保团名，阶段件数以及阶段金额已经填写完整')
                                    data.rebindSubmit(data.target)
                                }
                            }
                        })
                    } else {
                        showTipBox('请选择一个商品')
                    }
                }
            })
        })
        $('.group-buying-wrapper #prods ul li').on('click', function () {
            var id = this.getAttribute('data-id')
            var stock = this.getAttribute('data-stock')
            var goal = this.getAttribute('data-goal')

            storagedData.curProdId = id
            storagedData.curStock = stock
            storagedData.curGoal = goal
            $('.group-buying-wrapper #prods ul li.on').removeClass('on')
            this.className = 'on'

            getList(id, stock, goal)
        })
        $('#prods .js-next').on('click', function () {
            storagedData.prodPageIndex += 1
            getProds()
        })
        $('#prods .js-prev').on('click', function () {
            storagedData.prodPageIndex -= 1
            getProds()
        })
        $('#prods .js-num').on('input', function () {
            var maxNum = Number(this.getAttribute('data-max'))
            var reg = new RegExp('[^1-' + maxNum + ']', 'g')

            if (this.value > maxNum) {
                this.value = maxNum
            }

            this.value = this.value.replace(reg, '')
        })
        $('#prods .js-goto').on('click', function () {
            var num = $(this).siblings('input').val()
            var max = this.getAttribute('data-total')

            if (num) {
                if (Number(num) > Number(max)) {
                    showTipBox('页数超出范围')
                } else {
                    storagedData.prodPageIndex = Number(num)
                    getProds()
                }
            } else {
                showTipBox('请输入页数')
            }
        })
        $('.js-export-excel').on('click', function (e) {
            e.preventDefault()
            exportExcel(function () {}, function (err) {
                location.href = '/activity/groupbuy/export'
            })
        })
    }

    // 公共方法
    function showTipBox(msg) {
        $('#J_alert_content').html(msg)
        $('#apxModalAdminAlert').modal('show')
    }
    function showConfirmBox(opt) {
        var $el = $('#apxModalAdminConfrim')
        var $submit = $('#J_common_sure')

        $('.J_confrim_content').html(opt.msg)

        $submit.unbind('click')
        $el.modal('show')

        $submit.on('click', function() {
            $el.modal('hide')
            typeof opt.submit === 'function' && opt.submit()
        })
    }
}());
