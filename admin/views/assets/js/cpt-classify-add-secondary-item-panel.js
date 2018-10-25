;(function () {
    function ClassifyAddSecondaryItemPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(opt) {
        var htmlStr = createTpl(opt.name || '', opt)
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
        var btnClose = document.querySelector('.classify-add-secondary-item-panel a[data-id="close"]')
        var submit = document.querySelector('.classify-add-secondary-item-panel button[data-id=submit]')
        var edit = document.querySelector('.classify-add-secondary-item-panel button[data-id=edit]')
        var inputEl = document.querySelector('.classify-add-secondary-item-panel .body .input-field input')
        var fileEl = document.querySelector('.classify-add-secondary-item-panel .upload-field input')
        var progressBar = document.querySelector('.classify-add-secondary-item-panel .progress-bar')
        var preView = document.querySelector('.classify-add-secondary-item-panel .upload-field')

        btnClose.onclick = function (e) {
            e.preventDefault()
            instance.remove()
        }

        if (submit) {
            submit.onclick = function () {
                if (inputEl.value && preView.getAttribute('data-filename')) {
                    inputEl.disabled = true
                    submit.disabled = true
                    submit.onclick = null
                    submit.innerHTML = '提交中...'
                }

                typeof opt.submit === 'function' && opt.submit(inputEl.value, preView.getAttribute('data-filename'), preView.getAttribute('data-img'))
            }
        }

        if (edit) {
            edit.onclick = function () {
                typeof opt.edit === 'function' && opt.edit(inputEl.value)
            }
        }

        fileEl.onchange = function () {
            apex.uploadImg(this.files[0], {
                loaded: function (data) {
                    progressBar.style.display = 'none'
                    progressBar.style.width = 0;
                    preView.setAttribute('data-filename', data.filename)
                    preView.setAttribute('data-img', data.url)
                    preView.style.backgroundImage = 'url(' + data.url + ')'
                },
                loading: function (progress) {
                    progressBar.style.display = 'block'
                    progressBar.style.width = progress + '%'
                },
                error: function () {
                    progressBar.style.display = 'none'
                    progressBar.style.width = 0;
                    $('#J_alert_content').html('不支持该图片格式')
                    $('#apxModalAdminAlert').modal('show')
                }
            })
        }
    }
    function createTpl(name, opt) {
        var tpl = [
          '<div class="classify-add-secondary-item-panel">',
            '<div class="inner">',
              '<div class="content">',
                '<header>',
                  !opt.editable ? '<span>新增分类</span>' : '<span>编辑分类</span>',
                  '<a class="fa fa-times" data-id="close"></a>',
                '</header>',
                '<div class="body">',
                  '<ul>',
                    '<li>',
                      '<label>请输入分类名称：</label>',
                      '<div class="input-field">',
                        !opt.editable ? '<input type="text" maxlength="10" />' : '<input type="text" value="' + opt.originName + '" maxlength="10" />',
                      '</div>',
                    '</li>',
                    '<li>',
                      '<label>请添加分类图片：</label>',
                      '<div class="upload-field" data-img="' + (opt.editable ? opt.originImage : '') + '" data-filename="' + (opt.editable ? opt.originFilename : '') + '" style="background-image: url(' + (opt.editable ? opt.originImage : '') + ')">',
                        '<div class="bg"></div>',
                        '<input type="file" />',
                        '<span class="progress-bar"></span>',
                      '</div>',
                    '</li>',
                  '</ul>',
                '</div>',
                '<footer>',
                  '<button class="button primary" data-id="submit">确认提交</button>',
                '</footer>',
              '</div>',
            '</div>',
          '</div>'
        ]

        return tpl.join('')
    }

    window.apex = window.apex || {}
    if (!window.apex.classifyAddSecondaryItemPanel) {
        window.apex.classifyAddSecondaryItemPanel = new ClassifyAddSecondaryItemPanel()
    }
}());
