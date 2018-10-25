(function () {
    function GoodsPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(option) {
        var htmlStr = createTpl()
        var tempDiv = document.createElement('div')

        tempDiv.innerHTML = htmlStr
        this.temp = tempDiv.firstChild
        this.container.appendChild(this.temp)
        interact(this, option)
    }
    function interact(instance, option) {
        var closeBtn = document.querySelector('.goods-panel a[data-control=close]')
        var trList = document.querySelectorAll('.goods-panel .content > div tr')
        var submitBtn = document.querySelector('.goods-panel button[data-control=submit]')
        var searching = document.querySelector('.goods-panel input[data-control=searching]')
        var form = document.querySelector('.goods-panel form')
        var content = document.querySelector('.goods-panel .content')
        var timer = null
        var id = ''

        content.innerHTML = createTplContent([])

        closeBtn.onclick = function (e) {
            e.preventDefault()
            instance.remove()
        }
        submitBtn.onclick = function (e) {
            e.preventDefault()
            option.onsubmit(id)
        }
        searching.oninput = function (e) {
            var value = this.value
            if (timer) {
                clearTimeout(timer)
            }
            timer = setTimeout(function () {
                if (!/^\s*$/.test(value)) {
                    getData(value, function (data) {
                        content.innerHTML = createTplContent(data)
                        $(content).find('tr').on('click', function () {
                            id = $(this).find('input').prop('checked', true).val()
                        })
                    })
                } else {
                    content.innerHTML = createTplContent([])
                    id = ''
                }
            }, 500)
        }

        for (var i = 0; i < trList.length; i += 1) {
            var tr = trList[i]
            tr.onclick = function () {
                this.querySelector('input[name=goods]').checked = true
            }
        }
    }
    function getData(name, callback) {
        requestUrl('/site/floor/product-list', 'GET', {
            product_name: name
        }, callback)
    }
    function remove() {
        this.container.removeChild(this.temp)
        this.temp = null
    }
    function createTplContent(data) {
        var tpl = ''

        tpl += '<table>'
        tpl += '<colgroup>'
        tpl += '<col width="50">'
        tpl += '<col width="100">'
        tpl += '<col width="100">'
        tpl += '<col width="100">'
        tpl += '<col width="100">'
        tpl += '<col>'
        tpl += '<col width="17">'
        tpl += '</colgroup>'
        tpl += '<thead>'
        tpl += '<tr>'
        tpl += '<th></th>'
        tpl += '<th>图片</th>'
        tpl += '<th>ID</th>'
        tpl += '<th>名称</th>'
        tpl += '<th>价格</th>'
        tpl += '<th>所属供应商</th>'
        tpl += '<th></th>'
        tpl += '</tr>'
        tpl += '</thead>'
        tpl += '</table>'
        tpl += '<div>'
        tpl += '<table>'
        tpl += '<colgroup>'
        tpl += '<col width="50">'
        tpl += '<col width="100">'
        tpl += '<col width="100">'
        tpl += '<col width="100">'
        tpl += '<col width="100">'
        tpl += '</colgroup>'

        if (data.length > 0) {
            for (var i = 0; i < data.length; i += 1) {
                var item = data[i]

                tpl += '<tr>'
                tpl += '<td>'

                tpl += '<input type="radio" name="goods" value="' + item.id + '" />'
                tpl += '</td>'
                tpl += '<td>'
                tpl += '<div class="thumbnail-pic" style="background-image: url(' + item.main_image + ')"></div>'
                tpl += '</td>'
                tpl += '<td>' + item.id + '</td>'
                tpl += '<td>' + item.title + '</td>'
                tpl += '<td><span class="color-primary">￥' + item.price.max + '</span></td>'
                tpl += '<td>' + item.supplier_name + '</td>'

                tpl += '</tr>'
            }
        } else {
            tpl += '<tr><td colspan="6">请搜索商品</td></tr>'
        }

        tpl += '</table>'

        return tpl
    }
    function createTpl() {
        var tpl = ''

        tpl += '<div class="goods-panel">'
        tpl += '<div class="wrapper">'

        tpl += '<header>'
        tpl += '<h2>选择商品</h2>'
        tpl += '<a class="fa fa-times" title="关闭" data-control="close"></a>'
        tpl += '</header>'

        tpl += '<div class="searching">'
        tpl += '<input type="text" placeholder="请输入商品名或ID" data-control="searching" />'
        tpl += '<i class="fa fa-search" aria-hidden="true"></i>'
        tpl += '</div>'

        tpl += '<div class="content"></div>'

        tpl += '<footer>'
        tpl += '<button class="button primary" data-control="submit">确认提交</button>'
        tpl += '</footer>'

        tpl += '</div>'
        tpl += '</div>'

        return tpl;
    }

    window.apex = window.apex || {}
    if (!window.apex.goodsPanel) {
        window.apex.goodsPanel = new GoodsPanel()
    }
}());
