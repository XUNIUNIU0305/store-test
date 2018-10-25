(function () {
  var storagedData = {
    limitCount: 6,
    page: 1,
    pageSize: 10,
    name: '',
    ids: [],
    timer: null,
    tipData: null
  }

  function getExpressList() {
    requestUrl('/express/index/page', 'GET', {
      page: storagedData.page,
      page_size: storagedData.pageSize,
      name: storagedData.name
    }, function (data) {
      // 解绑释放内存
      $('input[type=checkbox]').unbind()
      $('button[data-type=set]').unbind()
      $('button[data-type=muti]').unbind()
      $('#search > button').unbind()
      // 插入模版
      $('#settingForExpress').html(tplForExpressList(data.items));
      // 全选操作
      $('input[type=checkbox][data-id=all]').on('click', function () {
        $('input[type=checkbox][data-id!=all]')
          .prop('checked', this.checked)

        if (this.checked) {
          $('input[type=checkbox][data-id!=all]').each(function (index, checkbox) {
            storagedData.ids.push(checkbox.id)
          })
        } else {
          storagedData.ids = []
        }

        $('button[data-type=muti]')[storagedData.ids.length > 0 ? 'show' : 'hide']()
      })
      // 复选操作
      $('input[type=checkbox][data-id!=all]').on('click', function () {
        if (this.checked) {
          storagedData.ids.push(this.id)
        } else {
          storagedData.ids.splice(_.indexOf(storagedData.ids, this.id), 1)
        }
        $('input[type=checkbox][data-id=all]').prop('checked', storagedData.ids.length === storagedData.pageSize ? true : false)
        $('button[data-type=muti]')[storagedData.ids.length > 0 ? 'show' : 'hide']()
      })
      // 批量操作
      $('button[data-type=muti]').on('click', function () {
        setExpressToUse(storagedData.ids)
      })
      // 单一操作
      $('button[data-type=set]').on('click', function () {
        setExpressToUse([this.getAttribute('data-id')])
      })
      $('button[data-type=cancel]').on('click', function () {
        setExpressToUnUse([this.getAttribute('data-id')])
      })
      // 搜索
      $('#search > button').on('click', function () {
        storagedData.name = $('#search input').val()
        getExpressList()
      })
      // 搜索提示
      $('#search input')
      .on('focus', function () {
        if (storagedData.timer) {
          clearTimeout(storagedData.timer)
        }
        storagedData.timer = setTimeout(function () {
          getExpressListForTips()
        }, 100)
      })
      .on('keyup', function () {
        storagedData.name = _.trim(this.value)

        if (storagedData.timer) {
          clearTimeout(storagedData.timer)
        }
        storagedData.timer = setTimeout(function () {
          getExpressListForTips()
        }, 500)
      })
      // 分页组建
      pagingBuilder.build($('#wrapPaginationOfExpress'), storagedData.page, storagedData.pageSize, data.total_count)
      pagingBuilder.click($('#wrapPaginationOfExpress'), function(index) {
        storagedData.page = index
        getExpressList()
      })
    })
  }
  function tplForExpressList(items) {
    var tpl = '<table>'

    tpl += '<thead>'
    tpl += '<tr style="background-color: #eee;">'
    tpl += '<th>物流公司<span style="color: #df483d;">（*最多可设置 6 家常用物流）</span></th>'
    tpl += '<th style="text-align: center">操作</th>'
    tpl += '</tr>'
    tpl += '<tr>'
    tpl += '<td colspan="2">'
    tpl += '<label>'
    tpl += '<input type="checkbox" data-id="all" />'
    tpl += '<span>全选</span>'
    tpl += '</label>'
    tpl += '<button data-type="muti" style="display: none;">设置为常用物流</button>'
    tpl += '</td>'
    tpl += '</tr>'
    tpl += '</thead>'
    tpl += '<tbody>'

    for (var i in items) {
      var item = items[i];

      tpl += '<tr>'
      tpl += '<td>'
      tpl += '<label for="' + item.id + '">'

      if (!item.is_common) {
        tpl += '<input id="' + item.id + '" type="checkbox" />'
      } else {
        tpl += '<span style="margin-left: 23px;"></span>'
      }

      tpl += '<span>' + item.name + '</span>'

      if (item.is_common) {
        tpl += '<span style="color: #df483d;">（常用物流）</span>'
      }

      tpl += '</label>'
      tpl += '</td>'
      tpl += '<td style="text-align: center">'

      if (!Boolean(item.is_common)) {
        tpl += '<button data-type="set" data-id="' + item.id + '">设置为常用物流</button>'
      } else {
        tpl += '<button data-type="cancel" data-id="' + item.id + '" style="border: none; color: #f4511e;">取消</button>'
      }

      tpl += '</td>'
      tpl += '</tr>'
    }

    tpl += '</tbody>'
    tpl += '</table>'

    return tpl
  }
  function getExpressListForTips() {
    requestUrl('/express/index/search', 'GET', {
      name: storagedData.name
    }, function (data) {

      storagedData.tipData = data

      $('#search > div').unbind().on('click', function (e) { e.stopPropagation() })

      $('#search > div > div')
        .unbind()
        .html(data.length > 0 ? tplForTips(data) : '')
        .find('li')
        .on('click', function () {
          storagedData.name = this.getAttribute('data-value')
          getExpressList()
          removeTips()
          storagedData.name = $('#search > div > input').val()
        })
    })
  }
  function tplForTips(items) {
    var tpl = '<ul>'

    _.each(items, function (item) {
      var words = item.name.split('')
      var noMatchedWord = _.differenceWith(words, storagedData.name.split(''), function (v1, v2) {
        return v1.toLocaleLowerCase() === v2.toLocaleLowerCase()
      })

      tpl += '<li data-value="' + item.name + '">' + _.map(item.name.split(''), function (value) {
        if (_.indexOf(noMatchedWord, value) === -1) {
          return '<span style="color: #df483d">' + value + '</span>'
        }
        return value
      }).join('') + '</li>'
    })

    tpl += '</ul>'

    return tpl
  }
  function getUsedExpress(callback) {
    requestUrl('/order/express', 'GET', null, function (data) {
      callback(data)
    })
  }
  function setExpressToUse(expressIds) {
    getUsedExpress(function (data) {
      if (data.common.length < storagedData.limitCount) {
        requestUrl('/express/index/add', 'POST', {
          items: expressIds
        }, function (data) {
          getExpressList()
        })
      } else {
        alert('您已设置 6 个常用物流，无法继续设置')
      }
    })
  }
  function setExpressToUnUse(expressIds) {
    requestUrl('/express/index/delete', 'POST', {
      items: expressIds
    }, function (data) {
      getExpressList()
    })
  }
  function removeTips() {
    $('#search > div > div').html('')
  }

  $(window).on('click', function () {
    removeTips()
  })

  getExpressList()
}());
