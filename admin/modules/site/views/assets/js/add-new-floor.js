(function() {
    var sourceElement = null
    // 插入确认框模版
    commonConfrim()

    apex.addScripts([
        'https://cdn.bootcss.com/purl/2.3.1/purl.min.js',
        'https://cdn.bootcss.com/jquery-minicolors/2.2.6/jquery.minicolors.js'
    ], function() {
        var urlParam = purl(location.href).param()
        var storagedData = {
            groupContainer: document.querySelector('div[data-id=groupContent]'),
            urlParam: purl(location.href).param(),
            floorId: ''
        }

        // 如果有楼层id为编辑
        if (storagedData.urlParam.id) {
            storagedData.floorId = storagedData.urlParam.id
        }

        init()

        function init() {
            getFloorInfo(function(data) {
                $('input[data-id=color]').minicolors({defaultValue: '#df483d'})
                $('.wrapper-for-new-floor').unbind().on('click', function(e) {
                    var key = e.target.getAttribute('data-id')

                    switch (key) {
                        case 'addNewGroup':
                            addGroup({
                                floor_id: storagedData.floorId
                            }, function(data) {
                                var tpl = createGroupTpl(data.group_id, data.group_name)
                                var tempDiv = document.createElement('div')
                                tempDiv.innerHTML = tpl
                                storagedData.groupContainer.appendChild(tempDiv.firstChild)
                            })
                            break;
                        case 'addGroupItem':
                            var groupId = $(e.target).parents('.group').attr('data-id')
                            apex.goodsPanel.init({
                                onsubmit: function(id) {
                                    if (!id) {
                                        showTipBox('请选择一个商品')
                                    } else {
                                        apex.goodsPanel.remove()
                                        apex.goodsInfoPanel.init({
                                            id: id,
                                            afterSubmit: function(data) {

                                                if (!data.indexImg) {
                                                    showTipBox('请选择首页图片')
                                                    return
                                                }

                                                apex.goodsInfoPanel.remove()
                                                data.groupId = groupId

                                                saveProd(data, function() {
                                                    init()
                                                })

                                            }
                                        })
                                    }
                                }
                            })
                            break;
                        case 'submit':
                            var data = {
                                floor_id: storagedData.floorId,
                                type: $('input[type=radio]:checked').val(),
                                name: $('input[data-id=name]').val(),
                                url: $('input[data-id=url]').val(),
                                color: $('input[data-id=color]').val()
                            }
                            saveFloor(data, function(data) {
                                location.href = '/site/carousel?tab=4'
                            })
                            break;
                        case 'cancel':
                            location.href = '/site/carousel?tab=4'
                            break;
                        case 'edit-group':
                            chgBtnStatus('edit', e.target)
                            break;
                        case 'not-save-group-name':
                            chgBtnStatus('cancel', e.target)
                            break;
                        case 'save-group-name':
                            var groupId = $(e.target).parents('.group').attr('data-id')
                            var groupName = $(e.target).parents('.group-top').find('input').val()

                            if (/^\s*$/.test(groupName)) {
                                showTipBox('商品名称不能为空')
                                return
                            }
                            saveGroupName(groupId, groupName, function() {
                                chgBtnStatus('save', e.target)
                            })
                            break;
                        case 'delete-group':
                            showConfirmBox({
                                submit: function() {
                                    delGroup($(e.target).parents('.group').attr('data-id'), function() {
                                        removeGroup($(e.target).parents('.group')[0])
                                    })
                                }
                            })
                            break;
                        case 'edit-group-item':
                            var groupId = $(e.target).parents('.group').attr('data-id')
                            apex.goodsInfoPanel.init({
                                id: $(e.target).parents('.group-item').attr('data-id'),
                                edit: true,
                                afterSubmit: function(data) {
                                    if (!data.indexImg) {
                                        showTipBox('请选择首页图片')
                                        return
                                    }

                                    apex.goodsInfoPanel.remove()

                                    data.groupId = groupId
                                    saveProd(data, function() {
                                        $(e.target).parents('.group-item').find('.group-item-content').css('background-image', 'url(' + data.indexImg + ')')
                                    })
                                }
                            })
                            break;
                        case 'delete-group-item':
                            showConfirmBox({
                                submit: function() {
                                    delProd($(e.target).parents('.group-item').attr('data-id'), function() {
                                        removeGroupItem($(e.target).parents('.js-drag-box')[0])
                                    })
                                }
                            })
                            break;
                        default:
                            break;
                    }
                })
                dragSwap()
            })
        }
        function chgBtnStatus(type, currentElement) {
            var inputEl = $(currentElement).parents('.group-top').find('input')

            switch (type) {
                case 'edit':
                    currentElement.innerHTML = '保存'
                    currentElement.setAttribute('data-id', 'save-group-name')
                    $(currentElement).siblings('.button.primary').attr('data-id', 'not-save-group-name').html('取消')
                    inputEl.prop('disabled', false)
                    break;
                case 'save':
                    currentElement.innerHTML = '编辑'
                    currentElement.setAttribute('data-id', 'edit-group')
                    $(currentElement).siblings('.button.primary').attr('data-id', 'delete-group').html('删除')
                    inputEl.prop('disabled', true)
                    break;
                case 'cancel':
                    currentElement.innerHTML = '删除'
                    currentElement.setAttribute('data-id', 'delete-group')
                    $(currentElement).siblings('.button.secondary').attr('data-id', 'edit-group').html('编辑')
                    inputEl.prop('disabled', true).val(inputEl.attr('data-name'))
                    break;
            }
        }
        function saveGroupName(groupId, groupName, callback) {
            requestUrl('/site/floor/edit-group', 'POST', {
                group_id: groupId,
                group_name: groupName
            }, function() {
                callback()
            })
        }
        function saveFloor(opt, callback) {
            requestUrl('/site/floor/edit-floor', 'POST', opt, callback)
        }
        function addGroup(opt, callback) {
            requestUrl('/site/floor/edit-group', 'POST', opt, callback)
        }
        function delGroup(groupId, callback) {
            requestUrl('/site/floor/delete-group', 'POST', {
                group_id: groupId
            }, callback)
        }
        function getFloorInfo(callback) {
            requestUrl('/site/floor/floor-info', 'GET', {
                floor_id: storagedData.floorId
            }, function(data) {
                $('input[data-id=name]').val(data.name || $('input[data-id=name]').val())
                $('input[data-id=url]').val(data.url || $('input[data-id=url]').val())
                $('input[data-id=color]').val(data.color)

                if (data.type == 1) {
                    $('#pc').prop('checked', true)
                } else {
                    $('#wap').prop('checked', true)
                }

                // 组与组内项
                if (data.group) {;
                    (function() {
                        var tplGroups = ''

                        for (var i = 0; i < data.group.length; i += 1) {
                            var group = data.group[i]
                            var tplGroupItems = ''

                            if (group.products.length > 0) {
                                for (var j = 0; j < group.products.length; j += 1) {
                                    tplGroupItems += createGroupItemTpl(group.products[j])
                                }
                            }

                            tplGroups += createGroupTpl(group.group_id, group.group_name, tplGroupItems)
                        }
                        $('div[data-id=groupContent]').html(tplGroups)
                    }());
                }

                callback()
            })
        }
        function addGroupItem(currentElement, data) {
            var tpl = createGroupItemTpl(data)
            var tempDiv = document.createElement('div')
            var container = currentElement.parentNode

            tempDiv.innerHTML = tpl
            container.insertBefore(tempDiv.firstChild, currentElement)

            dragSwap()
        }
        function removeGroupItem(groupItem) {
            $(groupItem).remove()
        }
        function removeGroup(group) {
            storagedData.groupContainer.removeChild(group)
        }
        function createGroupTpl(id, name, tplGroupItems) {
            var tpl = ''

            if (id) {
                tpl += '<div class="group" data-id="' + id + '">'
            } else {
                tpl += '<div class="group">'
            }

            tpl += '<div class="group-top">'
            tpl += '<div class="group-input">'

            if (name) {
                tpl += '<input data-name="' + name + '" type="text" value="' + name + '" disabled maxlength="15" />'
            } else {
                tpl += '<input type="text" maxlength="15" />'
            }

            tpl += '</div><div class="group-buttons">'
            tpl += '<button class="button secondary" data-id="edit-group">编辑</button><button class="button primary" data-id="delete-group">删除</button>'
            tpl += '</div>'
            tpl += '</div>'
            tpl += '<div class="group-body" data-control="groupBody">'

            if (tplGroupItems) {
                tpl += tplGroupItems
            }

            tpl += '<div class="group-item">'
            tpl += '<div class="group-item-content" data-id="addGroupItem"></div>'
            tpl += '</div>'

            tpl += '</div>'
            tpl += '</div>'
            tpl += '</div>'

            return tpl
        }
        function createGroupItemTpl(data) {
            var tpl = ''

            tpl += '<div class="js-drag-box" draggable="true" data-gid="' + data.gid + '">'
            tpl += '<div class="group-item" data-sellPoint="' + data.sell_point + '" data-title="' + data.title + '" data-id="' + data.id + '" data-gid="' + data.gid + '" data-original-id="' + data.original_id + '" data-original-title="' + data.original_title + '" data-original-sell-point="' + data.original_sell_point + '">'
            tpl += '<div class="group-item-content" style="background-image: url(' + data.index_image + ')"></div>'
            tpl += '<div class="group-item-cover">'
            tpl += '<div class="group-button">'
            tpl += '<button class="button secondary" data-id="edit-group-item">编辑</button><button class="button primary" data-id="delete-group-item">删除</button>'
            tpl += '</div>'
            tpl += '</div>'
            tpl += '</div>'
            tpl += '</div>'

            return tpl
        }
        function dragSwap() {
            $('.js-drag-box').unbind().on('dragstart', function(e) {
                sourceElement = this
                e.originalEvent.dataTransfer.setData('text', 'go')
            }).on('dragover', function(e) {
                e.preventDefault()
            }).on('dragenter', function(e) {}).on('dragleave', function(e) {}).on('drop', function(e) {
                if (sourceElement.getAttribute('data-gid') === this.getAttribute('data-gid')) {
                    var sourceChild = sourceElement.children[0]
                    var targetChild = this.children[0]
                    var ary = []

                    this.appendChild(sourceChild)
                    sourceElement.appendChild(targetChild)

                    $('.js-drag-box').find('.group-item').each(function(index, item) {
                        if (item.getAttribute('data-gid') === sourceChild.getAttribute('data-gid')) {
                            ary.push(item.getAttribute('data-id'))
                        }
                    })

                    swapOrder(ary)

                    e.originalEvent.dataTransfer.clearData()
                }
            })
        }
        function swapOrder(ary) {
            requestUrl('/site/floor/set-product-sort', 'POST', {
                product_sort: ary
            }, function() {})
        }
        function saveProd(opt, callback) {
            var data = {
                group_id: opt.groupId,
                product_original_id: opt.prodOriginalId,
                index_image: opt.indexImg,
                title: opt.title,
                sell_point: opt.sellPoint
            }

            if (opt.prodId !== opt.prodOriginalId) {
                data.product_id = opt.prodId
            }

            requestUrl('/site/floor/edit-product', 'POST', data, callback)
        }
        function delProd(prodId, callback) {
            requestUrl('/site/floor/delete-product', 'POST', {
                product_id: prodId
            }, callback)
        }
    })
    // 公共方法
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
