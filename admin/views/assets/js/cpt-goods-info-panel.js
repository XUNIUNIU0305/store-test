(function () {
    function GoodsInfoPanel() {
        this.container = document.body
        this.init = init
        this.remove = remove
    }
    function init(opt) {
        getData(this, opt)
    }
    function remove() {
        this.container.removeChild(this.temp)
        this.temp = null
    }
    function getData(instance, opt) {
        var api = ''

        if (opt.edit) {
            api = '/site/floor/product-detail'
        } else {
            api = '/site/floor/product-info'
        }

        requestUrl(api, 'GET', { product_id: opt.id }, function (data) {
            var htmlStr = createTpl(data)
            var tempDiv = document.createElement('div')

            tempDiv.innerHTML = htmlStr
            instance.temp = tempDiv.firstChild
            instance.container.appendChild(instance.temp)
            interact(instance, opt || {}, data)
        })
    }
    function interact(instance, opt, data) {
        var closeBtn = document.querySelector('.goods-info-panel a[data-control=close]')
        var uploadBtn = document.querySelector('.goods-info-panel input[data-control=upload]')
        var progressEl = document.querySelector('.goods-info-panel div[data-control=progress]')
        var uploadedBoxEl = document.querySelector('.goods-info-panel div[data-control=uploadedBox] > div')
        var delUploadedImgBtn = document.querySelector('.goods-info-panel a[data-control=delUploadedImg]')
        var changeEl = document.querySelector('.goods-info-panel div[data-control=change]')
        var submitBtn = document.querySelector('.goods-info-panel button[data-control=submit]')
        var picItems = document.querySelectorAll('.goods-info-panel .pic-item[data-id=picItem]')
        var titleEl = document.querySelector('.goods-info-panel input[data-id="title"]')
        var sellPoint = document.querySelector('.goods-info-panel input[data-id="sellPoint"]')
        var prevItem = picItems[0];
        var indexImg = prevItem.getAttribute('data-img')

        if (data.main_image || data.index_image) {
            indexImg = data.main_image || data.index_image
            showUploadedImg(data.main_image || data.index_image)
        }

        for (var i = 0; i < picItems.length; i += 1) {
            var picItem = picItems[i]

            picItem.onclick = function () {
              if (prevItem) {
                prevItem.className = 'pic-item'
              }
              this.className = 'pic-item off'
              indexImg = this.getAttribute('data-img')
              showUploadedImg(indexImg)
              prevItem = this
            }
        }

        closeBtn.onclick = function (e) {
            e.preventDefault()
            instance.remove()
        }
        delUploadedImgBtn.onclick = function (e) {
            e.preventDefault()
            delUploadedImg()
        }
        delUploadedImgBtn.onmouseover = function (e) {
            e.stopPropagation()
        }
        submitBtn.onclick = function () {
            var resultdata = {
                prodId: opt.id,
                prodOriginalId: !opt.edit ? opt.id : data.original_id,
                title: titleEl.value,
                sellPoint: sellPoint.value,
                indexImg: indexImg
            }

            opt.afterSubmit && opt.afterSubmit(resultdata)
        }
        uploadBtn.onchange = function () {
            var file = this.files[0]

            if (file) {
                showProgress()
                apex.uploadImg(file, {
                    loading: function (progress) {
                        progressEl.style.width = progress + '%'
                    },
                    loaded: function (data) {
                        showUploadedImg(data.url)
                        prevItem.className = 'pic-item'
                        indexImg = data.url
                        hideProgress()
                    },
                    error: function () {
                        $('#J_alert_content').html('图片格式有误')
                        $('#apxModalAdminAlert').modal('show')
                        hideProgress()
                    }
                })
            }
        }
        function delUploadedImg() {
            uploadedBoxEl.style.backgroundImage = 'url(/images/icons/plus.png)'
            uploadedBoxEl.style.backgroundSize = '30%'
            delUploadedImgBtn.style.display = 'none'
            changeEl.style.display = 'none'
            uploadedBoxEl.onmouseover = null
            uploadedBoxEl.onmouseout = null
            prevItem.className = 'pic-item'
            indexImg = ''
        }
        function showUploadedImg(imgUrl) {
            uploadedBoxEl.style.backgroundImage = 'url(' + imgUrl + ')'
            uploadedBoxEl.style.backgroundSize = 'cover'
            delUploadedImgBtn.style.display = 'block'
            uploadedBoxEl.onmouseover = function () {
                changeEl.style.display = 'block'
            }
            uploadedBoxEl.onmouseout = function () {
                changeEl.style.display = 'none'
            }
        }
        function showProgress() {
            progressEl.style.display = 'block'
        }
        function hideProgress() {
            progressEl.style.width = '0'
            progressEl.style.display = 'none'
        }
    }
    function createTpl(data) {
        var tpl = ''

        tpl += '<div class="goods-info-panel">'
        tpl += '<div class="wrapper">'
        tpl += '<div class="form">'
        tpl += '<header>'
        tpl += '<span>编辑商品信息</span>'
        tpl += '<a class="fa fa-times" data-control="close"></a>'
        tpl += '</header>'
        tpl += '<ul>'
        tpl += '<li>'
        tpl += '<label>商品ID：</label>'
        tpl += '<div>' + (data.original_id || data.id) + '</div>'
        tpl += '</li>'
        tpl += '<li>'
        tpl += '<label>商品名称：</label>'
        tpl += '<div>' + (data.original_title || data.title) + '</div>'
        tpl += '</li>'
        tpl += '<li>'
        tpl += '<label>价格：</label>'
        tpl += '<div>'
        tpl += '<span class="price">￥' + data.price.max + '</span>'
        tpl += '</div>'
        tpl += '</li>'
        tpl += '<li>'
        tpl += '<label>商品卖点：</label>'
        tpl += '<div>' + (data.original_sell_point || data.description) + '</div>'
        tpl += '</li>'
        tpl += '<li>'
        tpl += '<label>供应商：</label>'
        tpl += '<div>' + (data.supplier_name || data.supplier) + '</div>'
        tpl += '</li>'
        tpl += '<li class="align-to-top">'
        tpl += '<label>商品原图：</label>'
        tpl += '<div>'

        if (data.big_images.length > 0) {
            for (var i = 0; i < data.big_images.length; i += 1) {
              var imgUrl = data.big_images[i]

              if (imgUrl !== (data.main_image || data.index_image)) {
                tpl += '<div class="pic-item" style="background-image: url(' + imgUrl + ')" data-id="picItem" data-img="' + imgUrl + '">'
              } else {
                tpl += '<div class="pic-item off" style="background-image: url(' + imgUrl + ')" data-id="picItem" data-img="' + imgUrl + '">'
              }

              tpl += '<div class="set-to-current"></div>'
              tpl += '</div>'
            }
        } else {

        }

        tpl += '</div>'
        tpl += '</li>'

        tpl += '<li class="align-to-top">'
        tpl += '<label><span class="color-primary">*</span>首页图片：</label>'
        tpl += '<div>'

        tpl += '<div class="pic-item add" data-control="uploadedBox">'

        tpl += '<a class="fa fa-times-circle" title="删除图片" data-control="delUploadedImg"></a>'
        tpl += '<div>'
        tpl += '<input type="file" accept="image/*" data-control="upload" />'
        tpl += '<div class="progress-bar" data-control="progress"></div>'
        tpl += '<div class="change" data-control="change"></div>'
        tpl += '</div>'

        tpl += '</div>'
        tpl += '</div>'
        tpl += '</li>'

        tpl += '<li>'
        tpl += '<label>首页标题：</label>'
        tpl += '<div>'
        tpl += '<div class="input-wrapper">'
        tpl += '<input type="text" data-id="title" value="' + (data.title || '') + '" maxlength="30" />'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</li>'
        tpl += '<li>'
        tpl += '<label>首页卖点：</label>'
        tpl += '<div>'
        tpl += '<div class="input-wrapper">'
        tpl += '<input type="text" data-id="sellPoint" value="' + (data.sell_point || '') + '" maxlength="30" />'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</li>'
        tpl += '</ul>'
        tpl += '<footer>'
        tpl += '<button class="button primary" data-control="submit">确认提交</button>'
        tpl += '</footer>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'

        return tpl
    }

    window.apex = window.apex || {}
    if (!window.apex.goodsInfoPanel) {
        window.apex.goodsInfoPanel = new GoodsInfoPanel()
    }
}());
