(function () {
    function BrandPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(option) {
        var htmlStr = createTpl()
        var tempDiv = document.createElement('div')
        var _this = this

        option = option || {}

        tempDiv.innerHTML = htmlStr
        this.temp = tempDiv.firstChild
        this.container.appendChild(this.temp)

        getData('', function (data) {
          var dataWrapper = document.querySelector('.brand-panel .data-wrapper')
          dataWrapper.innerHTML = createDataTpl(data)
          interact(_this, option)
        })
    }
    function interact(instance, option) {
        var searchingIpt = document.querySelector('.brand-panel .searching input')
        var li = document.querySelectorAll('.brand-panel ul li')
        var submit = document.querySelector('.brand-panel .button.primary')
        var closeBtn = document.querySelector('.brand-panel .fa.fa-times')
        var timer = null

        searchingIpt.oninput = function () {
          var value = this.value

          if (timer) {
              clearTimeout(timer)
          }
          timer = setTimeout(function () {
              getData(value, function (data) {
                  var dataWrapper = document.querySelector('.brand-panel .data-wrapper')
                  dataWrapper.innerHTML = createDataTpl(data)
                  interact(instance, option)
              })
          }, 300)
      }

      for (var i = 0; i < li.length; i += 1) {
          var item = li[i]

          item.onclick = function () {
              this.querySelector('.row-1 input').checked = true
          }
      }

      submit.onclick = function() {
          var selectedItem = document.querySelector('.brand-panel input[type=radio]:checked')

          if (option.submit && typeof option.submit === 'function') {
            if (selectedItem) {
              option.submit({
                id: selectedItem.getAttribute('data-id'),
                supplyName: selectedItem.getAttribute('data-supplyname'),
                name: selectedItem.getAttribute('data-name'),
                img: selectedItem.getAttribute('data-img')
              })
            } else {
              option.submit()
            }
          }
      }

      closeBtn.onclick = function() {
          instance.remove()
      }
    }
    function getData(name, callback) {
        requestUrl('/homepage/supply/index', 'GET', {
            name: name
        }, callback)
    }
    function remove(dataTpl) {
        this.container.removeChild(this.temp)
        this.temp = null
    }
    function createTpl() {
        var tpl = [
          '<div class="brand-panel">',
            '<div class="brand-panel-inner">',
              '<div class="brand-panel-content">',
                '<header>选择品牌<i class="fa fa-times" title="关闭"></i></header>',
                '<div class="searching">',
                  '<input type="text" placeholder="请输入品牌名称" />',
                  '<i class="fa fa-search"></i>',
                '</div>',
                '<div>',
                  '<ul class="title-bar">',
                    '<li style="padding-right: 17px;"><span class="row-1"></span><span class="row-2">品牌LOGO</span><span class="row-3">名称</span><span class="row-3">所属供应商</span></li>',
                  '</ul>',
                  '<div class="data-wrapper">',
                  '</div>',
                '</div>',
                '<footer><button class="button primary">确认提交</button></footer>',
              '</div>',
            '</div>',
          '</div>',
        ].join('')

        return tpl
    }
    function createDataTpl(data) {
        var tpl = []

        if (data.length > 0) {
            tpl.push('<ul>')

            for (var i = 0; i < data.length; i += 1) {
              var item = data[i]

              tpl.push([
                '<li>',
                  '<span class="row-1"><input type="radio" name="brandlist" data-id="' + item.id + '" data-supplyname="' + item.company_name + '" data-name="' + item.name + '" data-img="' + item.img + '" /></span>',
                  '<span class="row-2">',
                    '<span style="background-image: url(' + item.img + ')"></span>',
                  '</span>',
                  '<span class="row-3">' + item.name + '</span>',
                  '<span class="row-4">' + item.company_name + '</span>',
                '</li>'
              ].join(''))
          }

            tpl.push('</ul>')
        } else {
            tpl.push('<div>暂无数据</div>')
        }

        return tpl.join('')
    }

    window.apex = window.apex || {}
    if (!window.apex.brandPanel) {
        window.apex.brandPanel = new BrandPanel()
    }
}());
