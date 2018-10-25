;(function () {
    function SetGroupBuyingPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }

    function createComponent(option) {
        var tpl = ''

        tpl += '<div class="set-group-buying-panel-wrapper">'
        tpl += '<div class="inner">'
        tpl += '<div class="box">'
        tpl += '<header><span>' + (option.text || '创建拼团') + '</span><a class="fa fa-times" title="关闭"></a></header>'
        tpl += '<ul></ul>'
        tpl += '<footer><button class="button primary">确认提交</button></footer>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
    }

    function getProvince(callback) {
        requestUrl('/activity/groupbuy-specific/get-province', 'GET', null, callback)
    }

    function renderTpl(data, option) {
        var tpl = ''

        tpl += '<li><label>团名：</label><div>'

        if (option.name) {
            tpl += '<input class="js-group-name" type="text" maxlength="10" value="' + option.name + '" />'
        } else {
            tpl += '<input class="js-group-name" type="text" maxlength="10" />'
        }

        tpl += '</div></li>'
        tpl += '<li><label>对应区域：</label>'
        tpl += '<select>'

        for (var i = 0; i < data.length; i += 1) {
            var item = data[i]

            if (item.name === option.province) {
                tpl += '<option value="' + item.id + '" selected>' + item.name + '</option>'
            } else {
                tpl += '<option value="' + item.id + '">' + item.name + '</option>'
            }
        }

        tpl += '</select>'
        tpl += '</li>'
        tpl += '<li><label>此团目标数：</label><div>'

        if (option.goal) {
            tpl += '<input class="js-group-goal" type="text" maxlength="10" value="' + option.goal + '" />'
        } else {
            tpl += '<input class="js-group-goal" type="text" maxlength="10" />'
        }

        tpl += '</div></li>'

        return tpl
    }

    function init(option) {
        var _this = this
        var htmlStr = createComponent(option)
        var tempDiv = document.createElement('div')

        tempDiv.innerHTML = htmlStr
        this.temp = tempDiv.firstChild
        this.container.appendChild(this.temp)

        getProvince(function (data) {
            document.querySelector('.set-group-buying-panel-wrapper .box > ul').innerHTML = renderTpl(data[0], option)
            interact(_this, option || {})
        })
    }

    function remove() {
        this.container.removeChild(this.temp)
        this.temp = null
    }

    function interact(instance, option) {
        var btnClose = document.querySelector('.set-group-buying-panel-wrapper .box header .fa')
        var btnSubmit = document.querySelector('.set-group-buying-panel-wrapper .box footer > button')
        var inputGname = document.querySelector('.set-group-buying-panel-wrapper .js-group-name')
        var inputGgoal = document.querySelector('.set-group-buying-panel-wrapper .js-group-goal')
        var select = document.querySelector('.set-group-buying-panel-wrapper select')

        btnClose.onclick = function (e) {
            e.preventDefault()
            instance.remove()
        }

        btnSubmit.onclick = handleSubmit

        inputGgoal.oninput = function () {
            this.value = this.value.replace(/[^\d*]/g, '')
        }

        function handleSubmit(e) {
            if (typeof option.onsubmit === 'function') {
                option.onsubmit({
                    id: option.id,
                    name: inputGname.value,
                    goal: inputGgoal.value,
                    province: select.value,
                    target: this,
                    showSubmitProgress: showSubmitProgress,
                    rebindSubmit: rebindSubmit
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

    window.apex = window.apex || {}
    if (!window.apex.setGroupBuyingPanel) {
        window.apex.setGroupBuyingPanel = new SetGroupBuyingPanel()
    }
}());
