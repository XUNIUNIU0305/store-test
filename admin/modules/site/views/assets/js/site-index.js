(function() {
    var storagedData = {
        relativeResult: [],
        firstId: '',
        page: 1,
        timer: null,
        currentGroupId: '',
        cateId: ''
    }
    // 插入确认框模版
    commonConfrim()
    // tab 切换
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        switch (e.target.getAttribute('data-id')) {
            case 'edit_floors':
                getFloorList()
                break;
            case 'edit_carousels':
                getCarouselList()
                interactForCarousel()
                break;
            case 'edit_carousels_wap':
                getCarouselList(true)
                break;
            case 'edit_classify':
                getTitleList(function () {
                    $('#titleContent li').eq(0).trigger('click')
                })
                break;
            case 'edit_hot_keywords':
                getKeywordList()
                addKeywordsPop()
                break;
            /*case 'edit_notice':
                getNoticeList($('.selecting select').val(), $('.searching input').val())
                break;*/
            case 'edit_brands':
                getBrandsList()
                break;
        }
    })
    // PC/WAP 轮播
    $('button[data-id=addNewCarsousel]').on('click', function() {
        var tpl = createTplForAddingNewCarouse()
        var tempDiv = document.createElement('div')
        var container = document.getElementById('carouselContent')
        var carouelCount = Number($('#carouselCount').attr('data-carousel-count'))
        var maxCarouselCount = Number($('#maxCarouselCount').attr('data-max-carousel-count'))

        if (carouelCount >= maxCarouselCount) {
            showTipBox('PC 轮播上线为 ' + maxCarouselCount)
        } else {
            tempDiv.innerHTML = tpl
            container.insertBefore(tempDiv.firstChild, container.childNodes[1])
        }
    })
    $('button[data-id=addNewCarsouselWap]').on('click', function() {
        var tpl = createTplForAddingNewCarouse('wap')
        var tempDiv = document.createElement('div')
        var container = document.getElementById('carouselWapContent')
        var carouelCount = Number($('#carouselWapCount').attr('data-carousel-wap-count'))
        var maxCarouselCount = Number($('#maxCarouselWapCount').attr('data-max-carousel-wap-count'))

        if (carouelCount >= maxCarouselCount) {
            showTipBox('WAP 轮播上线为 ' + maxCarouselCount)
        } else {
            tempDiv.innerHTML = tpl
            container.insertBefore(tempDiv.firstChild, container.childNodes[1])
        }
    })
    function getCarouselList(wap) {
        if (wap) {
            requestUrl('/homepage/wap/index', 'GET', {
                current_page: 1,
                page_size: 99
            }, function(carouselList) {
                $('#carouselWapCount').html(carouselList.length).attr('data-carousel-wap-count', carouselList.length)
                $('#carouselWapContent').html(createTplForCarouseList(carouselList))
                interactForCarousel(wap)
            })
        } else {
            requestUrl('/site/carousel/get-carousel', 'GET', {
                current_page: 1,
                page_size: 99
            }, function(carouselList) {
                $('#carouselCount').html(carouselList.codes.length).attr('data-carousel-count', carouselList.codes.length)
                $('#carouselContent').html(createTplForCarouseList(carouselList.codes))
                interactForCarousel()
            })
        }
    }
    function createTplForCarouseList(carouselList) {
        var tpl = ''

        tpl += '<div class="header">'
        tpl += '<span class="row-1">序号</span>'
        tpl += '<span class="row-2">图片（1920*480px，可见区 1190px）</span>'
        tpl += '<span class="row-3">链接</span>'
        tpl += '<span class="row-4">操作</span>'
        tpl += '</div>'

        if (carouselList && carouselList.length > 0) {

            for (var i = 0; i < carouselList.length; i += 1) {
                var carousel = carouselList[i]

                tpl += '<div class="drag-box" draggable="true">'
                tpl += '<div' + ' class="carousel-item"' + ' data-id="' + carousel.id + '"' + ' data-sort="' + carousel.sort + '"' + ' data-filename="' + carousel.file_name + '"' + ' data-product-url="' + carousel.product_url + '"' + '>'

                tpl += '<span class="row-1">'
                tpl += '<span>' + (
                i + 1) + '</span>'
                // tpl += '<input type="text" maxlength="2" value="' + carousel.sort + '" />'
                tpl += '</span>'

                tpl += '<span class="row-2">'
                tpl += '<span class="carousel-thumbnail" style="background-image: url(' + carousel.img_url + ')" title="' + carousel.file_name + '">'
                tpl += '<span class="change"><input type="file" data-id="upload" /></span>'
                tpl += '<span class="progress-bar"></span>'
                tpl += '</span>'
                tpl += '</span>'

                tpl += '<span class="row-3">'
                tpl += '<span>' + carousel.product_url + '</span>'
                tpl += '<input type="text" value="' + carousel.product_url + '" />'
                tpl += '</span>'

                tpl += '<span class="row-4">'
                tpl += '<button class="button secondary" data-id="edit">编辑</button>'
                tpl += '<button class="button primary" data-id="delete">删除</button>'
                tpl += '</span>'

                tpl += '</div>'
                tpl += '</div>'
            }

        }

        return tpl
    }
    function createTplForAddingNewCarouse() {
        var tpl = ''

        tpl += '<div class="carousel-item on">'
        tpl += '<div class="logo">NEW</div>'
        tpl += '<span class="row-1">'
        tpl += '<span></span>'
        // tpl += '<input type="text" maxlength="2" />'
        tpl += '</span>'

        tpl += '<span class="row-2">'
        tpl += '<span class="carousel-thumbnail">'
        tpl += '<span class="change"><input type="file" data-id="upload" /></span>'
        tpl += '<span class="progress-bar"></span>'
        tpl += '</span>'
        tpl += '</span>'

        tpl += '<span class="row-3">'
        tpl += '<span></span>'
        tpl += '<input type="text" />'
        tpl += '</span>'

        tpl += '<span class="row-4">'
        tpl += '<button class="button secondary" data-id="save">保存</button>'
        tpl += '<button class="button primary" data-id="delete-new">删除</button>'
        tpl += '</span>'

        tpl += '</div>'

        return tpl
    }
    function interactForCarousel(wap) {
        var id = wap
            ? '#carouselWapContent'
            : '#carouselContent'

        $(id).unbind().on('click', function(e) {
            var key = e.target.getAttribute('data-id')
            var carouselItem = $(e.target).parents('.carousel-item')[0]
            var sourceElement = null

            switch (key) {
                case 'edit':
                    changeStatus(carouselItem)
                    break;
                case 'delete':
                    showConfirmBox({
                        submit: function() {

                            delCarousel({
                                id: $(carouselItem).attr('data-id')
                            }, wap)

                            $(carouselItem).remove()
                        }
                    })
                    break;
                case 'cancel':
                    revertStatus(carouselItem)
                    break;
                case 'save':
                    $(carouselItem).attr('data-sort', $(carouselItem).find('.row-1 input').val())
                    $(carouselItem).attr('data-filename', $(carouselItem).find('.row-2 .carousel-thumbnail').attr('title'))
                    $(carouselItem).attr('data-product-url', $(carouselItem).find('.row-3 input').val())

                    var data = {
                        sort: $(carouselItem).attr('data-sort'),
                        file_name: $(carouselItem).attr('data-filename'),
                        product_url: $(carouselItem).attr('data-product-url')
                    }

                    if ($(carouselItem).attr('data-id')) {
                        data.id = $(carouselItem).attr('data-id')
                    }

                    $(carouselItem).find('.row-1 span').html(data.sort)
                    $(carouselItem).find('.row-3 span').html(data.product_url)

                    if (saveCarousel(data, wap)) {
                        revertStatus(carouselItem)
                    }

                    break;
                case 'delete-new':
                    $(e.target).parents('.carousel-item').remove()
                    break;
                case 'upload':
                    e.target.onchange = function() {
                        var _this = this;
                        apex.uploadImg(this.files[0], {
                            loaded: function(data) {
                                $(_this).parents('.carousel-thumbnail').css('background-image', 'url(' + data.url + ')').attr('title', data.filename).find('.progress-bar').hide().css('width', 0)
                            },
                            loading: function(progress) {
                                $(_this).parents('.carousel-thumbnail').removeAttr('title').find('.progress-bar').show().css('width', progress + '%')
                            },
                            error: function () {
                                showTipBox('图片格式有误')
                                $(_this).parents('.carousel-thumbnail').removeAttr('title').find('.progress-bar').hide().css('width', 0)
                            }
                        })
                    }
                    break;
            }
        })

        $(id)
          .find('.drag-box')
          .unbind()
          .on('dragstart', function(e) {
              sourceElement = this
              e.originalEvent.dataTransfer.setData('text', 'go')
          })
          .on('dragover', function(e) {
              e.preventDefault()
          })
          .on('dragenter', function(e) {})
          .on('dragleave', function(e) {})
          .on('drop', function(e) {
              var sourceChild = sourceElement.children[0]
              var targetChild = this.children[0]
              var sourceOrdNum = $(sourceElement).find('.row-1 span').html()
              var targetOrdNum = $(targetChild).find('.row-1 span').html()
              var ary = []

              this.appendChild(sourceChild)
              sourceElement.appendChild(targetChild)

              e.originalEvent.dataTransfer.clearData()

              $(id).find('.carousel-item').each(function(index, item) {
                  ary.push(item.getAttribute('data-id'))
              })

              $(sourceElement).find('.row-1 span').html(sourceOrdNum)
              $(this).find('.row-1 span').html(targetOrdNum)

              swapOrder(ary, wap)
          })
    }
    function saveCarousel(data, wap) {
        if (!data.file_name) {
            showTipBox('图片不能为空')
            return false
        }
        if (/^\s*$/.test(data.product_url)) {
            showTipBox('链接不能为空')
            return false
        }

        if (data.id) {
            if (wap) {
                requestUrl('/homepage/wap/update', 'POST', data, function() {
                    getCarouselList(true)
                })
            } else {
                requestUrl('/site/carousel/update-carousel', 'POST', data, function() {
                    getCarouselList()
                })
            }
        } else {
            if (wap) {
                requestUrl('/homepage/wap/create', 'POST', data, function() {
                    getCarouselList(true)
                })
            } else {
                requestUrl('/site/carousel/insert-carousel', 'POST', data, function() {
                    getCarouselList()
                })
            }
        }

        return true
    }
    function delCarousel(data, wap) {
        if (wap) {
            requestUrl('/homepage/wap/delete', 'POST', data, function() {
                getCarouselList(true)
            })
        } else {
            requestUrl('/site/carousel/delete-carousel', 'POST', data, function() {
                getCarouselList()
            })
        }
    }
    function changeStatus(element) {
        $(element).addClass('on')
        $(element).find('.primary').html('取消').attr('data-id', 'cancel')
        $(element).find('.secondary').html('保存').attr('data-id', 'save')
    }
    function revertStatus(element) {
        $(element).removeClass('on')
        $(element).find('.primary').html('删除').attr('data-id', 'delete')
        $(element).find('.secondary').html('编辑').attr('data-id', 'edit')
    }
    function swapOrder(ary, wap) {
        if (wap) {
            requestUrl('/homepage/wap/sort', 'POST', {
                sort_items: ary
            }, function() {
                getCarouselList(true)
            })
        } else {
            requestUrl('/site/carousel/sort', 'POST', {
                sort_items: ary
            }, function() {
                getCarouselList()
            })
        }
    }
    // 商品楼层
    $('button[data-id=addNewFloor]').on('click', function() {
        addFloor(function(data) {
            location.href = '/site/carousel/goods?id=' + data.floor_id
        })
    })
    function getFloorList() {
        requestUrl('/site/floor/get-list', 'GET', null, function(floorList) {
            $('#floorContent').html(createTplForFloorList(floorList))
            interactForFloor()
        })
    }
    function createTplForFloorList(floorList) {
        var tpl = ''

        if (floorList.length > 0) {
            tpl += '<ul>'

            tpl += '<li>'
            tpl += '<span>序号</span><span>楼层名称</span><span>状态</span><span>操作</span>'
            tpl += '</li>'

            for (var i = 0; i < floorList.length; i++) {
                var floor = floorList[i]

                tpl += '<li data-id="' + floor.id + '">'
                tpl += '<span>' + (
                i + 1) + '</span>'
                tpl += '<span>' + (
                floor.name || '') + '</span>'

                if (floor.status == 0) {
                    tpl += '<span class="color-disabled">隐藏</span>'
                } else {
                    tpl += '<span class="color-success">展示</span>'
                }

                tpl += '<span>'

                if (floor.status == 0) {
                    tpl += '<button class="button primary-o" data-id="show">展示</button>'
                    tpl += '<button class="button secondary" data-id="edit">编辑</button>'
                    tpl += '<button class="button primary" data-id="delete">删除</button>'
                } else {
                    tpl += '<button class="button primary-o" data-id="hide">隐藏</button>'
                    tpl += '<button class="button secondary" data-id="edit" style="display: none">编辑</button>'
                    tpl += '<button class="button primary" data-id="delete" style="display: none">删除</button>'
                }

                tpl += '</span>'
                tpl += '</li>'
            }
            tpl += '</ul>'
        } else {
            tpl += '<div>暂无数据</div>'
        }

        return tpl
    }
    function interactForFloor() {
        $('#floorContent').unbind().on('click', function(e) {
            var key = e.target.getAttribute('data-id')
            var $item = $(e.target).parents('li')

            switch (key) {
                case 'hide':
                    $('#floorContent').unbind()
                    hideFloor({floor_id: $item.attr('data-id'), status: 0})
                    break;
                case 'show':
                    $('#floorContent').unbind()
                    showFloor({floor_id: $item.attr('data-id'), status: 1})
                    break;
                case 'edit':
                    location.href = '/site/carousel/goods?id=' + $item.attr('data-id')
                    break;
                case 'delete':
                    $('#floorContent').unbind()
                    showConfirmBox({
                        submit: function() {
                            delFloor({floor_id: $item.attr('data-id')})
                        }
                    })
                    break;
            }
        })
    }
    function addFloor(callback) {
        requestUrl('/site/floor/edit-floor', 'POST', '', callback)
    }
    function showFloor(opt) {
        requestUrl('/site/floor/set-floor-status', 'POST', opt, function() {
            getFloorList()
        })
    }
    function hideFloor(opt, callback) {
        requestUrl('/site/floor/set-floor-status', 'POST', opt, getFloorList)
    }
    function delFloor(opt) {
        requestUrl('/site/floor/delete-floor', 'POST', opt, getFloorList)
    }
    // 分类管理
    $('#titleContent + footer button').unbind().on('click', function() {
        var count = Number($('#titleCount').attr('data-count'))
        var maxCount = Number($('#maxTitleCount').attr('data-count'))

        if (count >= maxCount) {
            showTipBox('一级栏目上限为 ' + maxCount)
        } else {
            apex.classifyAddNewItemPanel.init({
                submit: function(name) {
                    if (/^\s*$/.test(name)) {
                        showTipBox('栏目名称不能为空')
                    } else {
                        saveTitleItem(name, function () {
                            var liIndex = $('#titleContent li.on').index()

                            apex.classifyAddNewItemPanel.remove()
                            getTitleList(function () {
                                $('#titleContent li').eq(liIndex).trigger('click')
                            })
                        })
                    }
                }
            })
        }
    })
    $('#groupContent + footer button').unbind().on('click', function() {
        if (!storagedData.firstId) {
            showTipBox('请选择一级分类')
            return
        }

        var count = Number($('#groupCount').attr('data-count'))
        var maxCount = Number($('#maxGroupCount').attr('data-count'))

        if (count >= maxCount) {
            showTipBox('下属分类上限为 ' + maxCount)
        } else {
            apex.classifyAddSecondaryItemPanel.init({
                submit: function(name, filename) {
                    if (/^\s*$/.test(name)) {
                        showTipBox('分类名称不能为空')
                        return
                    }
                    if (!filename) {
                        showTipBox('请上传图片')
                        return
                    }
                    var index = $('#groupContent li.on').index()
                    addGroupItem(storagedData.firstId, name, filename, function () {
                        apex.classifyAddSecondaryItemPanel.remove()
                        getGroupList(storagedData.firstId, function () {
                            $('#groupContent li').eq(index).trigger('click')
                        })
                    })
                }
            })
        }
    })
    $('#brandContent + footer button').unbind().on('click', function() {
        if (!storagedData.firstId) {
            showTipBox('请选择一级分类')
            return
        }

        var count = Number($('#brandCount').attr('data-count'))
        var maxCount = Number($('#maxBrandCount').attr('data-count'))

        if (count >= maxCount) {
            showTipBox('下属品牌上限为 ' + maxCount)
        } else {
            doStep()
        }
    })
    function getTitleList(callback) {
        requestUrl('/homepage/column/list', 'GET', null, function(list) {
            $('#titleCount').html(list.length).attr('data-count', list.length)
            $('.classify #titleContent').html(createTplForTitleList(list))
            interactForTitleList()
            typeof callback === 'function' && callback()
        })
    }
    function createTplForTitleList(list) {
        var tpl = ''

        if (list.length > 0) {
            tpl += '<ul>'

            for (var i = 0; i < list.length; i += 1) {
                var item = list[i]

                tpl += '<li data-id="' + item.id + '">'
                tpl += item.name
                tpl += '<div><button class="button secondary" data-id="edit">修改</button><button class="button primary" data-id="delete">删除</button></div>'
                tpl += '</li>'
            }

            tpl += '</ul>'
        } else {
            tpl += '<div style="text-align: center; color: #ccc;">暂无数据</div>'
        }

        return tpl
    }
    function interactForTitleList() {
        var prevItem = null
        $('#titleContent li').unbind().on('click', function(e) {
            var tag = e.target.tagName.toLocaleLowerCase()
            var key = e.target.getAttribute('data-id')

            switch (tag) {
                case 'li':
                    if (prevItem) {
                        $(prevItem).removeClass('on')
                    }
                    $(this).addClass('on')
                    prevItem = this
                    getGroupList(key)
                    getBrand(key)
                    getRelativeList()
                    storagedData.firstId = key
                    break;
                case 'button':
                    var name = e.target.parentNode.parentNode.childNodes[0].nodeValue
                    var id = $(e.target).parents('li').attr('data-id')

                    if (key === 'edit') {
                        apex.classifyAddNewItemPanel.init({
                            name: name,
                            edit: function(name) {
                                editTitleItem(id, name)
                            }
                        })
                    } else {
                        showConfirmBox({
                            submit: function() {
                                delTitleItem(id, function () {
                                    getTitleList(storagedData.firstId, function () {
                                        $('#titleContent li').eq($(e.target).parents('li').index()).trigger('click')
                                    })
                                })
                            }
                        })
                    }
                    break;
            }
        })
    }
    function editTitleItem(id, name) {
        requestUrl('/homepage/column/update', 'POST', {
            id: id,
            name: name
        }, function() {
            apex.classifyAddNewItemPanel.remove()
            getTitleList()
        })
    }
    function saveTitleItem(name, callback) {
        requestUrl('/homepage/column/add', 'POST', {
            name: name
        }, callback)
    }
    function delTitleItem(id, callback) {
        requestUrl('/homepage/column/delete', 'POST', {
            id: id
        }, callback)
    }
    function getGroupList(firstId, callback) {
        requestUrl('/homepage/column-item/index', 'GET', {
            column_id: firstId
        }, function(list) {
            $('#groupCount').html(list.length).attr('data-count', list.length)
            $('.classify #groupContent').html(createTplForGroupList(list))
            interactForGroupList()
            typeof callback === 'function' && callback()
        })
    }
    function createTplForGroupList(list) {
        var tpl = []

        if (list.length > 0) {
            tpl.push('<ul>')

            for (var i = 0; i < list.length; i += 1) {
                var item = list[i]

                tpl.push([
                    '<li data-id="' + item.id + '" data-cateid="' + item.cate_id + '" data-name="' + item.name + '" data-filename="' + item.img + '" data-img="' + item.img_url + '">',
                    (function() {
                        if (item.cate_id > 0) {
                            return '<span><i class="fa fa-link"></i></span>'
                        } else {
                            return '<span><i class="fa fa-unlink"></i></span>'
                        }
                    }()),
                    '<span style="background-image: url(' + item.img_url + ')"></span>',
                    '<span>' + item.name + '</span>',
                    '<div>',
                    '<button class="button secondary" data-id="edit">修改</button>',
                    '<button class="button primary" data-id="delete">删除</button>',
                    '</div>',
                    '</li>'
                ].join(''))
            }

            tpl.push('</ul>')
        }

        return tpl.join('')
    }
    function addGroupItem(column_id, name, img, callback) {
        requestUrl('/homepage/column-item/add', 'POST', {
            column_id: column_id,
            name: name,
            img: img
        }, callback)
    }
    function interactForGroupList() {
        var prevItem = null
        $('#groupContent li').unbind().on('click', function(e) {
            var tag = e.target.tagName.toLocaleLowerCase()
            var key = e.target.getAttribute('data-id')

            if (prevItem) {
                $(prevItem).removeClass('on')
            }

            $(this).addClass('on')

            switch (tag) {
                case 'li':
                    storagedData.currentGroupId = key
                    storagedData.cateId = e.target.getAttribute('data-cateid')

                    getRelativeList(function() {
                        $('#relationshipContent div[data-id=' + storagedData.cateId + ']')
                          .find('button')
                          .attr('data-key', 'unlink')
                          .html('取消关联')
                          .show()
                          .parents('ul')
                          .show()
                    })

                    break;
                case 'button':
                    var $item = $(e.target).parents('li')
                    var id = $item.attr('data-id')
                    var name = $item.attr('data-name')
                    var filename = $item.attr('data-filename')
                    var img = $item.attr('data-img')

                    if (key === 'edit') {
                        apex.classifyAddSecondaryItemPanel.init({
                            editable: true,
                            originFilename: filename,
                            originImage: img,
                            originName: name,
                            name: name,
                            submit: function(name, filename, imgUrl) {
                                if (/^\s*$/.test(name)) {
                                    showTipBox('分类名称不能为空')
                                    return
                                }
                                if (!filename) {
                                    showTipBox('请上传图片')
                                    return
                                }
                                editGroup(id, name, filename, function () {
                                    apex.classifyAddSecondaryItemPanel.remove()
                                    $item.attr('data-name', name).attr('data-filename', filename).attr('data-img', imgUrl)
                                    $item.find('span:nth-of-type(2)').css('background-image', 'url(' + imgUrl + ')')
                                    $item.find('span:nth-of-type(3)').html(name)
                                })
                            }
                        })
                    } else {
                        showConfirmBox({
                            submit: function() {
                                delGroup($(e.target).parents('li').attr('data-id'), function () {
                                    $(e.target).parents('li').remove()
                                    getRelativeList()
                                })
                            }
                        })
                    }
                    break;
            }

            prevItem = this
        })
    }
    function editGroup(id, name, filename, callback) {
        requestUrl('/homepage/column-item/update', 'POST', {
            id: id,
            name: name,
            img: filename
        }, callback)
    }
    function delGroup(id, callback) {
        requestUrl('/homepage/column-item/delete', 'POST', {
            id: id
        }, callback)
    }
    function getBrand(firstId, callback) {
        requestUrl('/homepage/column-brand/index', 'GET', {
            column_id: firstId
        }, function(list) {
            $('#brandCount').html(list.length).attr('data-count', list.length)
            $('#brandContent').html(createBrandTpl(list))
            interactForBrandList()
            typeof callback === 'function' && callback()
        })
    }
    function getBrandInfo(id, callback) {
        requestUrl('/homepage/column-brand/one', 'GET', {
            id: id
        }, callback)
    }
    function createBrandTpl(data) {
        var tpl = []

        if (data.length > 0) {
            tpl.push('<ul>')

            for (var i = 0; i < data.length; i += 1) {
                var item = data[i]

                tpl.push([
                    '<li data-id="' + item.id + '" data-brandname="' + item.brand_name + '" data-filename="' + item.img + '" data-img="' + item.img_url + '">',
                    '<span class="brand-thumbnail" style="background-image: url(' + item.img_url + ')"></span>',
                    '<div><button class="button secondary" data-id="edit">修改</button><button class="button primary" data-id="delete">删除</button></div>',
                    '</li>'
                ].join(''))
            }

            tpl.push('</ul>')
        } else {}

        return tpl.join('')
    }
    function interactForBrandList() {
        var prev = null

        $('#brandContent li').unbind().on('click', function(e) {
            var tag = e.target.tagName.toLocaleLowerCase()
            var key = e.target.getAttribute('data-id')
            var $el = $(e.target).parents('li')

            switch (tag) {
                case 'li':
                    if (prev) {
                        $(prev).removeClass('on')
                    }
                    this.className = 'on'
                    break;
                case 'button':
                    if (key === 'edit') {
                        getBrandInfo($el.attr('data-id'), function(data) {
                            var tempImg = data.img
                            apex.brandInfoPanel.init({
                                edit: true,
                                id: data.id,
                                brandName: data.brand_name,
                                supplyName: data.supply_company_name,
                                originImage: data.header_img,
                                displayImage: data.img_url,
                                changeBrand: function() {
                                    apex.brandInfoPanel.remove()
                                    doStep()
                                },
                                submit: function(targetBtn) {
                                    targetBtn.innerHTML = '提交中...'
                                    targetBtn.onclick = null
                                    updateBrand(storagedData.firstId, data.id, tempImg, function() {
                                        apex.brandInfoPanel.remove()
                                        getBrand(storagedData.firstId, function () {
                                          $('#brandContent li').eq($el.index()).trigger('click')
                                        })
                                    })
                                },
                                onfilechange: function(file, element) {
                                    apex.uploadImg(file, {
                                        loaded: function(data) {
                                            $(element).siblings('.progress-bar').css('width', '0').hide()
                                            $(element).parents('.add').css('background-image', 'url(' + data.url + ')')
                                            tempImg = data.filename
                                        },
                                        loading: function(progress) {
                                            $(element).siblings('.progress-bar').show().css('width', progress + '%')
                                        },
                                        error: function() {
                                            $(element).siblings('.progress-bar').css('width', '0').hide()
                                            showTipBox('图片格式有误')
                                        }
                                    })
                                }
                            })
                        })
                    } else {
                        showConfirmBox({
                            submit: function() {
                                delBrand($el.attr('data-id'), function () {
                                    $el.remove()
                                    getBrand(storagedData.firstId, function () {})
                                })
                            }
                        })
                    }
                    break;
            }

            prev = this
        })
    }
    function addBrand(firstId, brandId, img, callback, errorCallback) {
        requestUrl('/homepage/column-brand/add', 'POST', {
            column_id: firstId,
            img: img,
            brand_id: brandId
        }, callback, errorCallback)
    }
    function updateBrand(firstId, brandId, img, callback) {
        requestUrl('/homepage/column-brand/update', 'POST', {
            id: firstId,
            img: img,
            brand_id: brandId
        }, callback)
    }
    function delBrand(id, callback) {
        requestUrl('/homepage/column-brand/delete', 'POST', {
            id: id
        }, callback)
    }
    function getRelativeList(callback) {
        requestUrl('/homepage/cate/index', 'GET', null, function(data) {
            $('#relationshipContent').html(createTplForRelativeList(data))
            typeof callback === 'function' && callback()
            interactForRelativeList()
        })
    }
    function createTplForRelativeList(list) {
        var tpl = '<ul>'

        for (var i = 0; i < list.length; i += 1) {
            tpl += '<li>'
            tpl += '<div data-id="' + list[i].id + '" data-parent-id="' + list[i].parent_id + '" data-key="listItem">'

            if (list[i].is_end === 1) {
                tpl += '<button class="button primary" data-key="link" data-id="' + list[i].id + '" data-parent-id="' + list[i].parent_id + '">关联</button>'
            }

            tpl += '</div>'
            tpl += '<span>'

            if (list[i].is_end !== 1) {
                tpl += '<i class="fa fa-caret-right"></i>'
            }

            tpl += '<span>' + list[i].title + '</span>'
            tpl += '</span>'

            if (list[i].children && list[i].children.length > 0) {
                tpl += createTplForRelativeList(list[i].children)
            }

            tpl += '</li>'
        }

        tpl += '</ul>'
        return tpl
    }
    function interactForRelativeList() {
        // 上一个 link 元素
        var prev = $('#relationshipContent .button[data-key=unlink]')[0]

        $('#relationshipContent').unbind().on('click', function(e) {
            var key = e.target.getAttribute('data-key')
            var id = e.target.getAttribute('data-id')
            var parentId = e.target.getAttribute('data-parent-id')
            var $direct = $(e.target.parentNode).find('.fa').eq(0)
            var currentGroupItem = $('#groupContent li[data-id="' + storagedData.currentGroupId + '"]')[0]

            switch (key) {
                case 'listItem':
                    if ($direct.hasClass('fa-caret-right')) {
                        $direct.removeClass('fa-caret-right').addClass('fa-caret-down')
                        $(e.target).siblings('ul').show()
                    } else {
                        $direct.removeClass('fa-caret-down').addClass('fa-caret-right')
                        $(e.target).siblings('ul').hide()
                    }
                    break;
                case 'link':
                    if (prev) {
                        $(prev).html('关联').attr('data-key', 'link').css('display', 'none')
                    }

                    if (!storagedData.currentGroupId) {
                      showTipBox('请选择下属分类')
                      return
                    } else {
                      linkProd(id, storagedData.currentGroupId)
                    }

                    $(e.target).html('取消关联').attr('data-key', 'unlink').css('display', 'block')
                    $(currentGroupItem).find('.fa').removeClass('fa-unlink').addClass('fa-link')
                    currentGroupItem.setAttribute('data-cateid', id)
                    prev = e.target
                    break;
                case 'unlink':
                    $(e.target).html('关联').attr('data-key', 'link').css('display', 'none')
                    $(currentGroupItem).find('.fa').removeClass('fa-link').addClass('fa-unlink')
                    currentGroupItem.removeAttribute('data-cateid')
                    unlinkProd(storagedData.currentGroupId)
                    break;
            }
        })
    }
    function linkProd(id, cate_id) {
        if (!cate_id) {
            showTipBox('请选择分类')
            return
        }
        requestUrl('/homepage/column-item/bind', 'POST', {
            id: cate_id,
            cate_id: id
        }, function(data) {})
    }
    function unlinkProd(id) {
        requestUrl('/homepage/column-item/unbind', 'POST', {
            id: id
        }, function() {})
    }
    // 关键词
    function getKeywordList() {
        requestUrl('/homepage/keywords/index', 'GET', null, function(data) {
            $('.keywordsContentList').html(createTplForKeywordList(data))
            // 分页
            /*pagingBuilder.build($('#defineWrapPaginationOfNotice'), data.page, data.page_size, data.total_count)
      pagingBuilder.click($('#defineWrapPaginationOfNotice'), function(index) {
        storagedData.page = index
        getKeywordList(type, author)
      })*/
            removeKeywordsList()
            updateKeywordsList()
        })
    }
    function createTplForKeywordList(keywordList) {
        var tpl = ''

        // console.log(keywordList)
        if (keywordList.length > 0) {
            tpl += '<div style="border: 1px solid #ccc; border-radius: 8px">'
            tpl += '<table>'

            tpl += '<thead>'
            tpl += '<tr><th>序号</th><th>名称</th><th>操作</th></tr>'
            tpl += '</thead>'

            tpl += '<tbody>'

            for (var i = 0; i < keywordList.length; i++) {
                var keyword = keywordList[i]

                tpl += '<tr>'
                tpl += '<td data-id="' + keyword.id + '">' + (
                i + 1) + '</td>'
                tpl += '<td>' + keyword.name + '</td>'
                tpl += '<td>'
                tpl += '<button class="button secondary js-keyword-edit" data-id="' + keyword.id + '" data-name="' + encodeURIComponent(keyword.name) + '" data-sort="' + keyword.sort + '">编辑</button>'
                tpl += '<button class="button primary js-keyword-delete" data-id="' + keyword.id + '">删除</button>'
                tpl += '</td>'
                tpl += '</tr>'
            }

            tpl += '</tbody>'

            tpl += '</table>'
            tpl += '</div>'
        } else {
            tpl += '<div>暂无数据</div>'
        }

        return tpl
    }
    function addKeywordsPop() {
        $('.hot_keywords>button.primary').on('click', function() {
            $('.modify-keyword').html('新增关键词')
            $('.keywords-pop').removeClass('hidden')
            $('.addkeywordsContent input[type="text"]').val('')
        })
        $('.addkeywordsContent input[type="button"]').unbind('click').on('click', function(e) {
            $('.keywords-pop').addClass('hidden')
            var text = $('.addkeywordsContent input[type="text"]').val()
            text = text.replace(/\s*/g, '')

            if (text.toString().length <= 30) {
                if ($(this).hasClass('J-edit-btn')) {
                    requestUrl('/homepage/keywords/update', 'POST', {
                        name: $('.addkeywordsContent input[type="text"]').val().replace(/\s*/g, ''),
                        id: $(this).attr('data-id'),
                        sort: $(this).attr('data-sort')
                    }, function(data) {
                        getKeywordList()
                        $('.addkeywordsContent input[type="button"]').removeClass('J-edit-btn')
                    })
                } else {
                    var ark = []
                    $('.keywordsContentList table tbody').find('tr').each(function (index, it) {
                      ark.push(index)
                    })

                    if (ark.length >= 10) {
                        return showTipBox('请先删掉溢出关键词，再添加')
                    } else {
                        requestUrl('/homepage/keywords/create', 'POST', {
                            sort: '',
                            name: $('.addkeywordsContent input[type="text"]').val().replace(/\s*/g, '')
                        }, function(data) {
                            getKeywordList()
                        })
                    }
                }
            } else
                return showTipBox('关键词不能超过30个字符！')

            $('.addkeywordsContent input[type="text"]').val('')
        })
    }
    function removeKeywordsList() {
        $('.keywordsContentList table td>button.primary').unbind().on('click', function(e) {
            requestUrl('/homepage/keywords/delete', 'POST', {
                id: $(e.target).parents('tr').children('td').eq(0).attr('data-id')
            }, function(data) {
                $(e.target).parents('tr').css('display', 'none')
                getKeywordList()
            })
        })
    }
    var timer = null
    function updateKeywordsList() {
        $('.js-keyword-edit').unbind().on('click', function(e) {
            $('.modify-keyword').html('修改关键词')

            var id = this.getAttribute('data-id')
            var name = this.getAttribute('data-name')
            var sort = this.getAttribute('data-sort')

            $('.keywords-pop').removeClass('hidden')
            $('.addkeywordsContent input[type="text"]').val(decodeURIComponent(name))
            $('.addkeywordsContent input[type="button"]').attr('data-id', id)
            $('.addkeywordsContent input[type="button"]').attr('data-sort', sort)
            $('.addkeywordsContent input[type="button"]').addClass('J-edit-btn')
        })
    }
    $('.keywords-pop a.close').on('click', function() {
        $('.keywords-pop').addClass('hidden')
        clearTimeout(timer)
        timer = setTimeout(function() {
            $('.addkeywordsContent input[type="button"]').removeClass('J-edit-btn')
        }, 800)
    })
    // 公告
    $('.addNotice').on('click', function(e) {
        location.href = '/site/carousel/post'
        $('.img-upload-box').css('background-size','inherit')
    })
    $('.selecting select').on('change', function() {
        getNoticeList(this.value, $('.searching input').val())
    })
    $('.searching input').on('input', function() {
        var $this = $(this)

        if (storagedData.timer) {
            clearTimeout(storagedData.timer)
        }
        storagedData.timer = setTimeout(function() {
            getNoticeList($('.selecting select').val(), $this.val())
        }, 300)
    })
    function getNoticeList(type, author) {
        var data = {
            page: storagedData.page
        }

        if (Number(type) > 0) {
            data.type = type
        }
        if (!/^\s*$/.test(author)) {
            data.author = author
        }

        requestUrl('/homepage/post/index', 'GET', data, function(data) {
            $('.notice-content').html(createTplForNoticeList(data.items))
            noticeEdit()
            noticeDelete()
            // 分页
            pagingBuilder.build($('#defineWrapPaginationOfNotice'), data.page, data.page_size, data.total_count)
            pagingBuilder.click($('#defineWrapPaginationOfNotice'), function(index) {
                storagedData.page = index
                getNoticeList(type, author)
            })
        })
    }
    // Delete list
    function noticeDelete() {
        $('.notice-content button.primary').unbind().on('click', function(e) {
            var id = e.target.getAttribute('data-id')
            requestUrl('/homepage/post/delete', 'POST', {
                id: id
            }, function(data) {
                // console.log(data)
                getNoticeList($('.selecting select').val(), $('.searching input').val())
            })
        })
    }
    // modify list
    function noticeEdit(argument) {
        $('.notice-content button.secondary').unbind().on('click', function(e) {
            var id = $(this).attr('data-id')
            var title = $(this).attr('data-title')
            var type = $(this).attr('data-type')
            var img = $(this).attr('data-img')
            var url = $(this).attr('data-url')
            var content = $(this).attr('data-content')
            location.href = '/site/carousel/post?id=' + id + '&title=' + title + '&type=' + type + '&img=' + img + '&url=' + url + '&content=' + content
            /*requestUrl('/homepage/post/update',{id:1},function (data) {
        console.log(data)
        // return showTipBox('')
      })*/
        })
    }
    function createTplForNoticeList(noticeList) {
        var tpl = ''

        if (noticeList.length > 0) {
            tpl += '<div style="border: 1px solid #ccc; border-radius: 8px">'
            tpl += '<table>'

            tpl += '<thead>'
            tpl += '<tr><th>标题</th><th>类型</th><th>发布人</th><th>发布时间</th><th>操作</th></tr>'
            tpl += '</thead>'

            tpl += '<tbody>'

            for (var i = 0; i < noticeList.length; i++) {
                var notice = noticeList[i]

                tpl += '<tr>'
                tpl += '<td>' + notice.title + '</td>'
                tpl += '<td>' + ['网站公告', '外部链接'][notice.type - 1] + '</td>'
                tpl += '<td>' + notice.author + '</td>'
                tpl += '<td>' + notice.created_time + '</td>'
                tpl += '<td>'
                tpl += '<button class="button secondary" data-id="' + notice.id + '" data-title="' + notice.title + '" data-type="' + notice.type + '" data-img="' + notice.img + '"  data-url="' + notice.url + '"  data-content="' + notice.content + '">编辑</button>'
                tpl += '<button class="button primary" data-id="' + notice.id + '">删除</button>'
                tpl += '</td>'
                tpl += '</tr>'
            }

            tpl += '</tbody>'

            tpl += '</table>'
            tpl += '</div>'
        } else {
            tpl += '<div>暂无数据</div>'
        }

        return tpl
    }
    // 品牌
    function getBrandsList() {
      requestUrl('/homepage/brand/index', 'GET', null, function (data) {
        $('.brandsPages').html(createBrandsListTpl(data))
        interactForBrandsList()
      })
    }
    function createBrandsListTpl(data) {
      var tpl = ''

      for (var i = 0; i < data.length; i += 1) {
        var item = data[i]

        tpl += '<div class="js-draggable-brand-wrapper" draggable="true">'
        tpl += '<div class="brand-item" data-img="' + item.logo_url + '" data-logo-name="' + item.logo_name + '" data-id="' + item.id + '" data-name="' + item.name + '" data-brand-id="' + item.brand_id + '" style="background-image: url(' + item.logo_url + ')">'
        tpl += '<div class="brand-item-cover">'
        tpl += '<footer>'
        tpl += '<button class="button secondary">修改</button>'
        tpl += '<button class="button primary">删除</button>'
        tpl += '</footer>'
        tpl += '</div>'
        tpl += '</div>'
        tpl += '</div>'
      }

      tpl += '<div class="brand-item-add"></div>'

      return tpl
    }
    function interactForBrandsList() {
      var sourceElement = null
      // 修改
      $('.brand-item .secondary').unbind().on('click', function () {
        var $item = $(this).parents('.brand-item')
        var id = $item.attr('data-id')

        getBrandDetailInfo(id, function (data) {
          var tempImg = data.logo_name
          var tempImgUrl = data.logo_url

          apex.brandInfoPanel.init({
            id: data.id,
            brandName: data.brand_name,
            supplyName: data.supply_name,
            originImage: data.logo_origin,
            displayImage: data.logo_url,
            changeBrand: function () {
              apex.brandInfoPanel.remove()
              doStep('brand-edit', function (brandId, img) {
                  updateBrandItem(id, brandId, img)
              })
            },
            onfilechange: function (file, element) {
              apex.uploadImg(file, {
                loaded: function (data) {
                  $(element).siblings('.progress-bar').css('width', '0').hide()
                  $(element).parents('.add').css('background-image', 'url(' + data.url + ')')
                  tempImg = data.filename
                  tempImgUrl = data.url
                },
                loading: function (progress) {
                  $(element).siblings('.progress-bar').show().css('width', progress + '%')
                },
                error: function () {
                  $(element).siblings('.progress-bar').css('width', '0').hide()
                  showTipBox('图片格式有误')
                }
              })
            },
            submit: function (targetBtn) {
                targetBtn.innerHTML = '提交中...'
                targetBtn.onclick = null
                updateBrandItem(id, data.brand_id, tempImg)
            }
          })
        })
      })
      // 删除
      $('.brand-item .primary').unbind().on('click', function () {
          var $item = $(this).parents('.brand-item')
          var id = $item.attr('data-id')

          showConfirmBox({
              submit: function () {
                  delBrandItem(id, function () {
                      $item.parents('.js-draggable-brand-wrapper').remove()
                  })
              }
          })
      })
      // 添加新图片
      $('.brand-item-add').unbind().on('click', function () {
          doStep('brand')
      })
      // 拖拽排序
      $('.js-draggable-brand-wrapper')
        .unbind()
        .on('dragstart', function(e) {
            sourceElement = this
            e.originalEvent.dataTransfer.setData('text', 'go')
        })
        .on('dragover', function(e) {
            e.preventDefault()
        })
        .on('dragenter', function(e) {})
        .on('dragleave', function(e) {})
        .on('drop', function(e) {
              var sourceChild = sourceElement.children[0]
              var targetChild = this.children[0]

              var ary = []

              this.appendChild(sourceChild)
              sourceElement.appendChild(targetChild)

              e.originalEvent.dataTransfer.clearData()

              $('.brand-item').each(function(index, item) {
                  ary.push(item.getAttribute('data-id'))
              })

              swapBrandOrder(ary)
        })
    }
    function getBrandDetailInfo(id, callback) {
      requestUrl('/homepage/brand/one', 'GET', { id: id }, callback)
    }
    function addBrandItem(brandId, logoName, callback) {
      requestUrl('/homepage/brand/create', 'POST', {
        brand_id: brandId,
        logo_name: logoName
      }, callback)
    }
    function updateBrandItem(id, brandId, logoName) {
        requestUrl('/homepage/brand/update', 'POST', {
            id: id,
            brand_id: brandId,
            logo_name: logoName
        }, function () {
            apex.brandInfoPanel.remove()
            getBrandsList()
        })
    }
    function delBrandItem(id, callback) {
      requestUrl('/homepage/brand/delete', 'POST', {
        id: id
      }, callback)
    }
    function swapBrandOrder(ary) {
        requestUrl('/homepage/brand/sort', 'POST', { sort_items: ary }, function () {
            getBrandsList()
        })
    }
    // 公共方法
    function doStep(type, callback) {
        var temp = {}
        apex.brandPanel.init({
            submit: function(data) {
                if (!data) {
                    showTipBox('请选择一个品牌')
                    return
                }

                temp.brandId = data.id

                apex.brandPanel.remove()
                apex.brandInfoPanel.init({
                    id: data.id,
                    brandName: data.name,
                    supplyName: data.supplyName,
                    originImage: data.img,
                    changeBrand: function() {
                        apex.brandInfoPanel.remove()
                        doStep(type, callback)
                    },
                    submit: function(targetBtn) {
                        if (!temp.img) {
                            showTipBox('请上传展示LOGO')
                            return
                        }

                        targetBtn.innerHTML = '提交中...'
                        targetBtn.onclick = null

                        switch (type) {
                            case 'brand-edit':
                                typeof callback === 'function' && callback(temp.brandId, temp.img)
                                break;
                            case 'brand':
                                  var index = $('#brandContent li.on').index()
                                  addBrandItem(temp.brandId, temp.img, function () {
                                      apex.brandInfoPanel.remove()
                                      getBrandsList()
                                      $('#brandContent li').eq(index).trigger('click')
                                  })
                              break;
                            default:
                                  var index = $('#brandContent li.on').index()
                                  addBrand(storagedData.firstId, temp.brandId, temp.img, function(data) {
                                      apex.brandInfoPanel.remove()
                                      getBrand(storagedData.firstId, function () {
                                        $('#brandContent li').eq(index).trigger('click')
                                      })
                                  }, function (data) {
                                      showTipBox(data.data.errMsg)
                                      apex.brandInfoPanel.remove()
                                  })
                              break;
                        }

                    },
                    onfilechange: function(file, element) {
                        apex.uploadImg(file, {
                            loaded: function(data) {
                                $(element).siblings('.progress-bar').css('width', '0').hide()
                                $(element).parents('.add').css('background-image', 'url(' + data.url + ')')
                                temp.img = data.filename
                            },
                            loading: function(progress) {
                                $(element).siblings('.progress-bar').show().css('width', progress + '%')
                            },
                            error: function() {
                                $(element).siblings('.progress-bar').css('width', '0').hide()
                                showTipBox('上传图片格式有误')
                            }
                        })
                    }
                })
            }
        })
    }
    function showTipBox(msg) {
        $('#J_alert_content').html(msg)
        $('#apxModalAdminAlert').modal('show')
    }
    function showConfirmBox(opt) {
        var $el = $('#apxModalAdminConfrim')
        var $submit = $('#J_common_sure')

        $submit.unbind('click')
        $el.modal('show')

        $submit.on('click', function() {
            $el.modal('hide')
            typeof opt.submit === 'function' && opt.submit()
        })
    }
}());
