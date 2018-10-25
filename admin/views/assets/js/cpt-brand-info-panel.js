(function () {
    function BrandInfoPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(option) {
        var htmlStr = createTpl(option)
        var tempDiv = document.createElement('div')
        var _this = this

        option = option || {}

        tempDiv.innerHTML = htmlStr
        this.temp = tempDiv.firstChild
        this.container.appendChild(this.temp)

        interact(this, option)
    }
    function interact(instance, option) {
        var closeBtn = document.querySelector('.brand-info-panel .fa.fa-times')
        var changeBtn = document.querySelector('.brand-info-panel .button.primary-o')
        var file = document.querySelector('.brand-info-panel input[type=file]')
        var submitBtn = document.querySelector('.brand-info-panel .button.primary')

        closeBtn.onclick = function () {
            instance.remove()
        }

        if (changeBtn) {
            changeBtn.onclick = function () {
                if (option) {
                    typeof option.changeBrand === 'function' && option.changeBrand()
                }
            }
        }

        submitBtn.onclick = function () {
            if (option) {
                typeof option.submit === 'function' && option.submit(this)
            }
        }
        file.onchange = function () {
            if (option) {
              typeof option.onfilechange === 'function' && option.onfilechange(this.files[0], this)
            }
        }
    }
    function remove(dataTpl) {
        this.container.removeChild(this.temp)
        this.temp = null
    }
    function createTpl(data) {
        var tpl = [
          '<div class="brand-info-panel">',
            '<div class="brand-info-panel-inner">',
              '<div class="brand-info-panel-content">',
                '<header>编辑品牌<i class="fa fa-times" title="关闭"></i></header>',
                '<ul>',
                  '<li>',
                    '<label>品牌名称：</label>',
                    '<div>',
                      '<span>' + data.brandName + '</span>',
                      !data.edit ? '<button class="button primary-o">更换品牌</button>' : '',
                    '</div>',
                  '</li>',
                  '<li>',
                    '<label>供应商：</label>',
                    '<div>' + data.supplyName + '</div>',
                  '</li>',
                  '<li>',
                    '<label>原LOGO：</label>',
                    '<div><span class="brand-info-panel-thumbnail" style="background-image: url(' + data.originImage + ')"></span></div>',
                  '</li>',
                  '<li>',
                    '<label>展示LOGO：</label>',
                    '<div>',
                      !data.displayImage ? '<span class="brand-info-panel-thumbnail add">' : '<span class="brand-info-panel-thumbnail add" style="background-image: url(' + data.displayImage + ')">',
                        '<span class="bg"></span>',
                        '<input type="file" />',
                        '<span class="progress-bar"></span>',
                      '</span>',
                    '</div>',
                  '</li>',
                '</ul>',
                '<footer><button class="button primary">确认提交</button></footer>',
              '</div>',
            '</div>',
          '</div>',
        ].join('')

        return tpl
    }

    window.apex = window.apex || {}
    if (!window.apex.brandInfoPanel) {
        window.apex.brandInfoPanel = new BrandInfoPanel()
    }
}());
