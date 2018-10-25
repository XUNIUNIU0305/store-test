;(function () {
    function ClassifyAddNewItemPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(opt) {
        var htmlStr = createTpl(opt.name || '')
        var tempDiv = document.createElement('div')

        tempDiv.innerHTML = htmlStr
        this.temp = tempDiv.firstChild
        this.container.appendChild(this.temp)

        interact(this, opt || {})
    }
    function remove() {
        this.container.removeChild(this.temp)
        this.temp = null
    }
    function interact(instance, opt) {
        var btnClose = document.querySelector('.classify-add-new-item-panel a[data-id="close"]')
        var submit = document.querySelector('.classify-add-new-item-panel button[data-id=submit]')
        var edit = document.querySelector('.classify-add-new-item-panel button[data-id=edit]')
        var inputEl = document.querySelector('.classify-add-new-item-panel .body input')

        btnClose.onclick = function (e) {
          e.preventDefault()
          instance.remove()
        }

        if (submit) {
          submit.onclick = function () {

            if (!/^\s*$/.test(inputEl.value)) {
                inputEl.disabled = true
                submit.disabled = true
                submit.onclick = null
                submit.innerHTML = '提交中...'
            }

            typeof opt.submit === 'function' && opt.submit(inputEl.value)
          }
        }

        if (edit) {
          edit.onclick = function () {
            typeof opt.edit === 'function' && opt.edit(inputEl.value)
          }
        }
    }
    function createTpl(name) {
        var tpl = ''

        tpl += '<div class="classify-add-new-item-panel">'
        tpl += '<div class="inner">'
        tpl += '<div class="content">'
        tpl += '<header>'

        if (name) {
          tpl += '<span>新增栏目</span>'
        } else {
          tpl += '<span>编辑栏目</span>'
        }

        tpl += '<a class="fa fa-times" data-id="close" title="关闭"></a>'
        tpl += '</header>'
        tpl += '<div class="body">'
        tpl += '<label>请输入栏目名称：</label>'
        tpl += '<div>'

        if (name) {
          tpl += '<input type="text" value="' + name + '" maxlength="10" />'
        } else {
          tpl += '<input type="text" maxlength="10" />'
        }

        tpl += '</div>'
        tpl += '</div>'
        tpl += '<footer>'

        if (name) {
          tpl += '<button class="button primary" data-id="edit">确认提交</button>'
        } else {
          tpl += '<button class="button primary" data-id="submit">确认提交</button>'
        }

        tpl += '</footer>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
    }

    window.apex = window.apex || {}
    if (!window.apex.classifyAddNewItemPanel) {
        window.apex.classifyAddNewItemPanel = new ClassifyAddNewItemPanel()
    }
}());
