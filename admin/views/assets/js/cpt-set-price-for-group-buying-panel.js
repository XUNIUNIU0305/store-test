;(function () {
    var timer = null
    var ok = false

    function SetPriceForGroupBuyingPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(option) {
        var _this = this
        var htmlStr = createComponent()
        var tempDiv = document.createElement('div')

        tempDiv.innerHTML = htmlStr
        _this.temp = tempDiv.firstChild
        _this.container.appendChild(this.temp)

        getData(option.id, function (data) {
            var info = data[0]
            var container = document.querySelector('.set-price-for-group-buying-panel-wrapper .content')
            container.innerHTML = renderList(info)
            fixBoxWidth()
            ok = true
        })

        timer = setInterval(function () {
            if (ok) {
                clearInterval(timer)
                interact(_this, option || {})
            }
        }, 200)

        setTimeout(function () {
            if (!ok) {
                clearInterval(timer)
                interact(_this, option || {})
            }
        }, 1000)
    }
    function fixBoxWidth() {
        var columns = $('.table-header li').length

        $('.set-price-for-group-buying-panel-wrapper .box').css({
            width: columns * 120 + 'px'
        })
    }
    function getData(id, callback) {
        requestUrl('/activity/groupbuy/get-sku', 'GET', {
            product_id: id
        }, function (data) {
            callback(data)
        })
    }
    function interact(instance, option, info) {
        var wrapper = document.querySelector('.set-price-for-group-buying-panel-wrapper')
        var btnClose = wrapper.querySelector('.box > header > a')
        var btnSubmit = wrapper.querySelector('.box > footer > button')
        var numInputs = wrapper.querySelectorAll('.js-num input[data-type="count"]')
        var priceNumInputs = wrapper.querySelectorAll('.js-num input[data-type="price"]')
        var groupNameInput = wrapper.querySelector('.long-input input')
        var stepCount1 = wrapper.querySelector('[data-id=step-1]')
        var stepCount2 = wrapper.querySelector('[data-id=step-2]')
        var stepCount3 = wrapper.querySelector('[data-id=step-3]')
        var data = {
            first_gradient_price: {},
            second_gradient_price: {},
            third_gradient_price: {}
        }

        btnClose.onclick = function (e) {
            e.preventDefault()
            instance.remove()
        }

        btnSubmit.onclick = handleSubmit

        for (var i = 0; i < numInputs.length; i += 1) {
            numInputs[i].oninput = function () {
                this.value = this.value.replace(/[^\d]/g, '')
            }
        }

        for (var i = 0; i < priceNumInputs.length; i += 1) {
            priceNumInputs[i].oninput = function () {
                // 不能输入非数字，除了小数点
                this.value = this.value.replace(/[^\d\.]/g, '')
                // 不能输入第二个小数点
                var dots = this.value.match(/\./g)
                if (dots) {
                    if (dots.length > 1) {
                        this.value = this.value.replace(/\.$/, '')
                    }
                }
                // 小数点不能在第一位
                if (this.value[0] === '.') {
                    this.value = this.value.replace('.', '')
                }
                // 小数点后不能超过 2 位
                var decimal = this.value.match(/\.(\d*)/)
                if (decimal) {
                    if (decimal[1].length > 2) {
                        this.value = this.value.slice(0, -(decimal[1].length - 2))
                    }
                }
            }
        }

        function handleSubmit() {
            var pass = false
            var passCount = 0
            var regEmpty = /^\s*$/

            if (!regEmpty.test(groupNameInput.value)) {
                passCount += 1
            }

            if (!regEmpty.test(stepCount1.value)) {
                passCount += 1
            }

            if (!regEmpty.test(stepCount2.value)) {
                passCount += 1
            }

            if (!regEmpty.test(stepCount3.value)) {
                passCount += 1
            }

            for (var i = 0; i < priceNumInputs.length; i += 1) {
                var input = priceNumInputs[i]

                if (!regEmpty.test(input.value)) {
                    passCount += 1

                    switch (input.getAttribute('data-id')) {
                        case 'step-1':
                            data.first_gradient_price[input.getAttribute('data-skuid')] = input.value
                            break;
                        case 'step-2':
                            data.second_gradient_price[input.getAttribute('data-skuid')] = input.value
                            break;
                        case 'step-3':
                            data.third_gradient_price[input.getAttribute('data-skuid')] = input.value
                            break;
                    }
                }
            }

            if (passCount === priceNumInputs.length + 4) {
                pass = true
            }

            if (pass) {
                data.product_id = option.id
                data.groupbuy_name = groupNameInput.value
                data.first_gradient_sales_goals = stepCount1.value
                data.second_gradient_sales_goals = stepCount2.value
                data.third_gradient_sales_goals = stepCount3.value
            }

            if (typeof option.onsubmit === 'function') {
                option.onsubmit({
                    id: option.id,
                    target: this,
                    showSubmitProgress: showSubmitProgress,
                    rebindSubmit: rebindSubmit,
                    data: data,
                    pass: pass
                })
            }
        }
        function showSubmitProgress(target) {
            target.innerHTML = '提交中...'
            target.onclick = null
        }
        function rebindSubmit(target) {
            target.innerHTML = '确认提交'
            target.onclick = handleSubmit
        }
    }
    function remove() {
        this.container.removeChild(this.temp)
        this.temp = null
    }
    function createComponent() {
        var tpl = ''

        tpl += '<div class="set-price-for-group-buying-panel-wrapper">'
        tpl += '<div class="inner">'
        tpl += '<div class="box">'
        tpl += '<header><span>设置拼团价格</span><a class="fa fa-times" title="关闭"></a></header>'

        tpl += '<ul>'
        tpl += '<li class="long-input"><label>团名：</label><div><input type="text" maxlength="10" /></div></li>'
        tpl += '<li>'
        tpl += '<label>阶段一件数：</label><div class="js-num"><input data-type="count" data-id="step-1" type="text" maxlength="10" /></div>'
        tpl += '<label>阶段二件数：</label><div class="js-num"><input data-type="count" data-id="step-2" type="text" maxlength="10" /></div>'
        tpl += '<label>阶段三件数：</label><div class="js-num"><input data-type="count" data-id="step-3" type="text" maxlength="10" /></div>'
        tpl += '</li>'
        tpl += '</ul>'

        tpl += '<div class="content"></div>'

        tpl += '<footer><button class="button primary">确认提交</button></footer>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
    }
    function renderList(info) {
        // sku 属性 id
        var skuids = []
        // sku 属性名称
        var skuNames = []
        // 表头字段
        var titles = [ '原价', '库存', '阶段一金额', '阶段二金额', '阶段三金额' ]
        var tpl = ''

        for (var i in info.attributes) {
            var item = info.attributes[i]
            skuids.push(i)

            for (var title in item) {
                skuNames.push(title)
            }
        }

        titles = skuNames.concat(titles)

        var colWidth = 100 / titles.length

        tpl += '<ul class="table-header">'

        for (var i = 0; i < titles.length; i += 1) {
            tpl += '<li style="width: ' + colWidth + '%">' + titles[i] + '</li>'
        }

        tpl += '</ul>'

        tpl += '<ul class="table-body">'

        for (var i in info.sku) {
            var skuItem = info.sku[i]
            var sku = i.split(';')

            tpl += '<li>'

            for (var j = 0; j < sku.length; j += 1) {
                var key = sku[j].split(':')[0]
                var value = sku[j].split(':')[1]
                var name = skuNames[skuids.indexOf(key)]

                tpl += '<div style="width: ' + colWidth + '%">' + info.attributes[key][name][value] + '</div>'
            }

            tpl += '<div class="price color-primary" style="width: ' + colWidth + '%">￥' + skuItem.price + '</div>'
            tpl += '<div class="num" style="width: ' + colWidth + '%">' + skuItem.stock + '</div>'
            tpl += '<div class="group-buying-price js-num" style="width: ' + colWidth + '%"><div><input data-type="price" data-id="step-1" maxlength="8" type="text" data-skuid="' + skuItem.id + '" data-sku="' + sku + '" /></div></div>'
            tpl += '<div class="group-buying-price js-num" style="width: ' + colWidth + '%"><div><input data-type="price" data-id="step-2" maxlength="8" type="text" data-skuid="' + skuItem.id + '" data-sku="' + sku + '" /></div></div>'
            tpl += '<div class="group-buying-price js-num" style="width: ' + colWidth + '%"><div><input data-type="price" data-id="step-3" maxlength="8" type="text" data-skuid="' + skuItem.id + '" data-sku="' + sku + '" /></div></div>'
            tpl += '</li>'
        }

        tpl += '</ul>'

        return tpl
    }

    window.apex = window.apex || {}
    if (!window.apex.setPriceForGroupBuyingPanel) {
        window.apex.setPriceForGroupBuyingPanel = new SetPriceForGroupBuyingPanel()
    }
}());
