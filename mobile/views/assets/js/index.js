$(function(){
    var defaultImg = 'http://images.9daye.com.cn/a_9/9bb6dcf74c7230e40d675475a12b677dbb8704a21.jpg'
    var namespace_home = typeof namespace_home !== 'undefined' ? namespace_home : {}

    ;(function () {

      function callback() {
        return arguments[3](arguments[4])
      }

      function requestApi(option) {
        return {
            next: function (val, build) {
                if (val) {
                    option[0][val].push(build)
                    callback.apply(this, option[0][val])
                }
            }
        }
      }

      var group = [
      {
        '#jsShowSelection': ['/index/post', 'GET', { id: '' }, function (data) {

            var temp = ''

            if (data.show === '1' && data.type === '2') {
                temp += '<div class="index-floor product-gallary showSelection">'
                temp += '<div class="title"></div>'
                temp += '<div class="scroll">'
                temp += '<ul>'

                for (var i = 0, len = data.group[0].product.length; i< len; i++){
                    var build = data.group[0].product[i]

                    if (build) {
                        temp += '<li>'
                        temp += '<a href="/goods/detail?id=' + build.original_id + '">'
                        temp += '<div class="img-box jiang">'
                        temp += '<img src="' + resizeOssPicturePad(build.index_image, 100, 100) + '">'
                        temp += '</div>'
                        temp += '<p>' + build.title + '</p>'
                        temp += '</a>'
                        temp += '</li>'
                    }
                }

                temp += '</ul>'
                temp += '</div>'

                $('#jsShowSelection').html(temp)
            }
        }],
        '#jsNewProd': ['/index/post', 'GET', { id: '' }, function (data) {

            var temp = ''

            if (data.show === '1' && data.type === '2') {
                temp += '<div class="index-floor product-gallary new-arrival">'
                temp += '<div class="title"></div>'
                temp += '<div class="scroll">'
                temp += '<ul>'

                for (var i = 0, len = data.group[0].product.length; i< len; i++){
                    var build = data.group[0].product[i]

                    if (build) {
                        temp += '<li>'
                        temp += '<a href="/goods/detail?id=' + build.original_id + '">'
                        temp += '<div class="img-box jiang">'
                        temp += '<img src="' + resizeOssPicturePad(build.index_image, 100, 100) + '">'
                        temp += '</div>'
                        temp += '<p>' + build.title + '</p>'
                        temp += '</a>'
                        temp += '</li>'
                    }
                }

                temp += '</ul>'
                temp += '</div>'

                $('#jsNewProd').html(temp)
            }
        }],
        '.nav-group' : ['/index/post', 'GET', {},function (data) {
          var temp = ''
          temp += '<ul>'
          for ( var i = 0, temp = ''; i< data.length; i++ ){
            var build = data[i]
            temp += '<li>'
            temp += '<a href="' + build.url + '?id=' + build.id + '"><img src="' + build.img + '"></a>'
            temp += '</li>'
          }
          temp += '</ul>'

          $('.nav-group').html(temp)
        }],
        '#product-gallary' : ['/index/post', 'GET', {},function (data) {
          var temp = ''

            temp += '<div class="title"></div>'
            temp += '<div class="scroll">'
            temp += '<ul>'
          for ( var i = 0, len = data.length;  i< len; i++ ){
            var build = data[i]
            typeof build.ur === 'undefined' ? '':  build.ur
            typeof build.id === 'undefined' ? '' : build.id
            typeof build.img === 'undefined' ? '' : build.img
            typeof build.title === 'undefined' ? '' : build.title
            temp += '<li>'
            temp += '<a href="' + build.url + '?id=' + build.id + '">'
            temp += '<div class="img-box jiang">'
            temp += '<img src="' + build.img + '">'
            temp += '<p>'
            temp += build.title
            temp += '</p>'
            temp += '</div>'
            temp += '</a>'
            temp += '</li>'
          }
            temp += '</ul>'
            temp += '</div>'

          $('#product-gallary').html(temp)
        }],
        '#jsFreeWrapper' : ['/index/post', 'GET', {},function (data) {
            var item = ''
            var tpl = ''

            if (data.show === '1' && data.type === '2') {
                tpl += '<div class="index-floor free-delivery">'
                tpl += '<div class="title">'
                // tpl += '<a href="/temp/brand/z">好货包邮</a>'
                tpl += '</div>'
                tpl += '<div class="banner">'

                item = data.group[0]

                if (item && item.product[0]) {
                    tpl += '<a href="/goods/detail?id=' + item.product[0].original_id + '">'
                    tpl += '<img src="' + resizeOssPicturePad(item.product[0].index_image, 458, 146) + '">'
                    tpl += '</a>'
                } else {
                    tpl += '<a>'
                    tpl += '<img src="' + resizeOssPicturePad(defaultImg, 458, 146) + '">'
                    tpl += '</a>'
                }


                tpl += '</div>'

                tpl += '<ul>'

                if (data.group[1]) {
                    for (var i = 0; i < 4; i += 1) {
                        item = data.group[1].product[i]

                        if (item) {
                            tpl += '<li>'
                            tpl += '<a href="/goods/detail?id=' + item.original_id + '">'
                            tpl += '<div class="img-box">'
                            tpl += '<img src="' + resizeOssPicturePad(item.index_image, 136, 112) + '">'
                            tpl += '</div>'
                            tpl += '<p>' + item.title + '</p>'
                            tpl += '<small>' + item.sell_point + '</small>'
                            tpl += '</a>'
                            tpl += '</li>'
                        } else {
                            tpl += '<li>'
                            tpl += '<a>'
                            tpl += '<div class="img-box">'
                            tpl += '<img src="' + resizeOssPicturePad(defaultImg, 136, 112) + '">'
                            tpl += '</div>'
                            tpl += '<p></p>'
                            tpl += '<small></small>'
                            tpl += '</a>'
                            tpl += '</li>'
                        }
                    }
                }

                tpl += '</ul>'
                tpl += '</div>'

                $('#jsFreeWrapper').html(tpl)
            }
        }],
        '#jsExcellentWrapper' : ['/index/post', 'GET', {},function (data) {
            var temp = ''
            if (data.show === '1' && data.type === '2') {
                temp += '<div class="index-floor quality-products">'
                temp += '<div class="title"></div>'
                temp += '<ul>'

                for (var i = 0; i < 4; i += 1) {
                    var item = data.group[0].product[i]

                    if (item) {
                        temp += '<li>'
                        temp += '<a href="/goods/detail?id=' + item.original_id + '">'
                        temp += '<img src="' + resizeOssPicturePad(item.index_image, 198, 129) + '">'
                        temp += '</a>'
                        temp += '</li>'
                    } else {
                        temp += '<li>'
                        temp += '<a>'
                        temp += '<img src="' + resizeOssPicturePad(defaultImg, 198, 129) + '">'
                        temp += '</a>'
                        temp += '</li>'
                    }
                }

                temp += '</ul>'
                temp += '</div>'

                $('#jsExcellentWrapper').html(temp)
            }
        }],
        '#jsDecorationForCarWrapper' : ['/index/post', 'GET', {}, function (data) {
            var item = ''
            var tpl = ''
            if (data.show === '1' && data.type === '2') {
                tpl += '<div class="index-floor car-decoration">'
                tpl += '<div class="title"></div>'


                for (var i = 0; i < 3; i += 1) {
                    item = data.group[0].product[i]

                    if (item) {
                        tpl += '<a href="/goods/detail?id=' + item.original_id + '">'
                        tpl += '<div class="media">'
                        tpl += '<div class="media-img jiang">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 160, 138) + '">'
                        tpl += '</div>'
                        tpl += '<div class="media-body">'
                        tpl += '<p>' + item.title + '</p>'
                        tpl += '<div>' + item.sell_point + '</div>'
                        tpl += '</div>'
                        tpl += '</div>'
                        tpl += '</a>'
                    } else {
                        tpl += '<a>'
                        tpl += '<div class="media">'
                        tpl += '<div class="media-img jiang">'
                        tpl += '<img src="' + resizeOssPicturePad(defaultImg, 160, 138) + '">'
                        tpl += '</div>'
                        tpl += '<div class="media-body">'
                        tpl += '<p></p>'
                        tpl += '<div></div>'
                        tpl += '</div>'
                        tpl += '</div>'
                        tpl += '</a>'
                    }
                }

                tpl += '</div>'

                $('#jsDecorationForCarWrapper').html(tpl)
            }
        }],
        '#jsBeautifyWrapper' : ['/index/post', 'GET', {}, function (data) {

            var tpl = ''
            if (data.show === '1' && data.type === '2') {

                tpl += '<div class="index-floor auto-beauty">'
                tpl += '<div class="title"></div>'
                tpl += '<div class="scroll">'
                tpl += '<ul>'

                for (var i = 0; i < data.group[0].product.length; i += 1) {
                    var item = data.group[0].product[i]

                    if (item) {
                        tpl += '<li>'
                        tpl += '<a href="/goods/detail?id=' + item.original_id + '">'
                        tpl += '<div class="img-box">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 200, 200) + '">'
                        tpl += '</div>'
                        tpl += '<p>'
                        tpl += item.title
                        tpl += '</p>'
                        tpl += '<span>' + item.sell_point + '</span>'
                        tpl += '</a>'
                        tpl += '</li>'
                    }
                }

                tpl += '</ul>'
                tpl += '</div>'
                tpl += '</div>'

                $('#jsBeautifyWrapper').html(tpl)
            }
        }],
        '#jsDecorationForShopWrapper' : ['/index/post', 'GET', {},function (data) {
            var tpl = ''
            if (data.show === '1' && data.type === '2') {

                tpl += '<div class="index-floor store-decoration">'
                tpl += '<div class="title"></div>'

                for (var i = 0; i < 3; i += 1) {
                    var item = data.group[0].product[i]
                    if (item) {
                        tpl += '<a href="/goods/detail?id=' + item.original_id + '">'
                        tpl += '<div class="media">'
                        tpl += '<div class="media-img">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 125, 125) + '">'
                        tpl += '</div>'
                        tpl += '<div class="media-body">'
                        tpl += '<p>' + item.title + '</p>'
                        tpl += '<div>' + item.sell_point + '</div>'
                        tpl += '</div>'
                        tpl += '</div>'
                        tpl += '</a>'
                    } else {
                        tpl += '<a>'
                        tpl += '<div class="media">'
                        tpl += '<div class="media-img">'
                        tpl += '<img src="' + resizeOssPicturePad(defaultImg, 125, 125) + '">'
                        tpl += '</div>'
                        tpl += '<div class="media-body">'
                        tpl += '<p></p>'
                        tpl += '<div></div>'
                        tpl += '</div>'
                        tpl += '</div>'
                        tpl += '</a>'
                    }
                }

                tpl += '</div>'

                $('#jsDecorationForShopWrapper').html(tpl)
            }
        }],
        '#jsSaveWrapper' : ['/index/post', 'GET', {},function (data) {
            var item = ''
            var tpl = ''
            if (data.show === '1' && data.type === '2') {

                tpl += '<div class="index-floor safe-travel">'
                tpl += '<div class="title"></div>'

                tpl += '<div class="banner">'

                item = data.group[0]

                if (item && item.product[0]) {
                    tpl += '<a href="/goods/detail?id=' + item.product[0].original_id + '">'
                    tpl += '<div class="media-img jiang">'
                    tpl += '<img src="' + resizeOssPicturePad(item.product[0].index_image, 421, 134) + '">'
                    tpl += '</div>'
                    tpl += '</a>'
                } else {
                    tpl += '<a>'
                    tpl += '<div class="media-img jiang">'
                    tpl += '<img src="' + resizeOssPicturePad(defaultImg, 421, 134) + '">'
                    tpl += '</div>'
                    tpl += '</a>'
                }


                tpl += '</div>'

                tpl += '<ul>'

                for (var i = 0; i < 4; i += 1) {
                    item = data.group[1].product[i]
                    if (item) {
                        tpl += '<li>'
                        tpl += '<a href="/goods/detail?id=' + item.original_id + '">'
                        tpl += '<div class="img-box jiang">'
                        tpl += '<img src="' + resizeOssPicturePad(item.index_image, 126, 126) + '">'
                        tpl += '</div>'
                        tpl += '<p>' + item.title + '</p>'
                        tpl += '<small>' + item.sell_point + '</small>'
                        tpl += '</a>'
                        tpl += '</li>'
                    } else {
                        tpl += '<li>'
                        tpl += '<a>'
                        tpl += '<div class="img-box jiang">'
                        tpl += '<img src="' + resizeOssPicturePad(defaultImg, 126, 126) + '">'
                        tpl += '</div>'
                        tpl += '<p></p>'
                        tpl += '<small></small>'
                        tpl += '</a>'
                        tpl += '</li>'
                    }
                }

                tpl += '</ul>'
                tpl += '</div>'

                $('#jsSaveWrapper').html(tpl)
            }
        }],
        '#jsElectricWrapper' : ['/index/post', 'GET', {},function (data) {
            var tpl = ''
            if (data.show === '1' && data.type === '2') {

              tpl += '<div class="index-floor auto-electric">'
              tpl += '<div class="title"></div>'
              tpl += '<ul>'

              for (var i = 0; i < 3; i += 1) {
                  var item = data.group[0].product[i]

                if (item) {
                    tpl += '<li>'
                    tpl += '<a href="/goods/detail?id=' + item.original_id + '">'
                    tpl += '<div class="img-box jiang">'
                    tpl += '<img src="' + resizeOssPicturePad(item.index_image, 132, 132) + '">'
                    tpl += '</div>'
                    tpl += '<p>' + item.title + '</p>'
                    tpl += '<span>' + item.sell_point + '</span>'
                    tpl += '</a>'
                    tpl += '</li>'
                } else {
                    tpl += '<li>'
                    tpl += '<a>'
                    tpl += '<div class="img-box jiang">'
                    tpl += '<img src="' + resizeOssPicturePad(defaultImg, 132, 132) + '">'
                    tpl += '</div>'
                    tpl += '<p></p>'
                    tpl += '<span></span>'
                    tpl += '</a>'
                    tpl += '</li>'
                }
              }

              tpl += '</ul>'
              tpl += '</div>'

              $('#jsElectricWrapper').html(tpl)
          }
        }],
        '.automotive-interior' : ['/index/post', 'GET', {},function (data) {
            var temp = ''
            if (data.show === '1' && data.type === '2') {

              temp += '<div class="title"></div>'
              temp += '<div class="scroll" style="height: auto; padding-bottom: 10px">'
              temp += '<ul>'

              for(var i = 0; i < data.group[0].product.length; i++){
                  var tiny = data.group[0].product[i]

                  if (tiny) {
                      temp += '<li>'
                      temp += '<a href="/goods/detail?id=' + tiny.original_id + '">'
                      temp += '<div class="img-box jiang">'
                      temp += '<img src="' + resizeOssPicturePad(tiny.index_image, 100, 100) + '">'
                      temp += '</div>'
                      temp += '<p>' + tiny.title + '</p>'
                      temp += '<span style="display: block; width: 100%; overflow: hidden; text-overflow: ellipsis;">' + tiny.sell_point + '</span>'
                      temp += '</a>'
                      temp += '</li>'
                  } else {
                      temp += '<li>'
                      temp += '<a>'
                      temp += '<div class="img-box jiang">'
                      temp += '<img src="' + resizeOssPicturePad(defaultImg, 100, 100) + '">'
                      temp += '</div>'
                      temp += '<p></p>'
                      temp += '<span></span>'
                      temp += '</a>'
                      temp += '</li>'
                  }
              }

              temp += '</ul>'
              temp += '</div>'

              $('.automotive-interior').html(temp)
          }
        }],
        '.auto-maintenance' : ['/index/post', 'GET', {},function (data) {
            var temp = ''
            if (data.show === '1' && data.type === '2') {

              temp += '<div class="title"></div>'
              temp += '<ul>'

              for(var i = 0; i < 4; i++){
                  var tiny = data.group[0].product[i]

                  if (tiny) {
                      temp += '<li>'
                      temp += '<a href="/goods/detail?id=' + tiny.original_id + '">'
                      temp += '<img src="' + resizeOssPicturePad(tiny.index_image, 198, 129) + '">'
                      temp += '</a>'
                      temp += '</li>'
                  } else {
                      temp += '<li>'
                      temp += '<a>'
                      temp += '<img src="' + resizeOssPicturePad(defaultImg, 198, 129) + '">'
                      temp += '</a>'
                      temp += '</li>'
                  }
              }

              temp += '</ul>'

              $('.auto-maintenance').html(temp)
          }
        }]
      }]

      namespace_home = new requestApi(group)
    })()

    var reg = [
        '#jsShowSelection',
        '#jsNewProd',
        '#jsFreeWrapper',
        '#jsExcellentWrapper',
        '#jsDecorationForCarWrapper',
        '#jsBeautifyWrapper',
        '#jsDecorationForShopWrapper',
        '#jsSaveWrapper',
        '#jsElectricWrapper',
        '.automotive-interior',
        '.auto-maintenance'
    ]

    function resizeOssPicturePad(imgUrl, width, height) {
        return imgUrl + '?x-oss-process=image/resize,m_pad,h_' + height + ',w_' + width
    }

    apex.commonApi.getShop(function (list) {
      for(var d = 0, count = 0; d < list.length; d++) {
        var build = list[d];
        if(build.name === '') null;
        else if(build.name.search('-手机') >= 0) {
          switch (build.name){
            case '门店优选-手机':
              namespace_home.next(reg[0],build)
              break
            case '新品上市-手机':
              namespace_home.next(reg[1],build)
              break
            case '包邮专场-手机':
              namespace_home.next(reg[2],build)
              break
            case '发现好货-手机':
              namespace_home.next(reg[3],build)
              break
            case '汽车装饰-手机':
              namespace_home.next(reg[4],build)
              break
            case '美容用品-手机':
              namespace_home.next(reg[5],build)
              break
            case '门店装饰-手机':
              namespace_home.next(reg[6],build)
              break
            case '安全出行-手机':
              namespace_home.next(reg[7],build)
              break
            case '车载电器-手机':
              namespace_home.next(reg[8],build)
              break
            case '汽车内饰-手机':
              namespace_home.next(reg[9],build)
              break
            case '贴膜工具-手机':
              namespace_home.next(reg[10],build)
              break
          }
          count++
        }
      }
    })

    // 首页轮播
    ;(function () {
        getData(function (data) {
            $('#slide').html(createTpl(data))
            interact()
        })
        function createTpl(data) {
            var tpl = ''

            if (data.length > 0) {
                tpl += '<ul>'

                for (var i = 0; i < data.length; i += 1) {
                    var item = data[i]
                    tpl += '<li>'
                    tpl += '<a href="' + item.product_url + '"><img src="' + resizeOssPicturePad(item.img_url, 750, 400) + '"></a>'
                    tpl += '</li>'
                }
                tpl += '</ul>'

                tpl += '<div class="dot">'

                for (var i = 0; i < data.length; i += 1) {
                    if (i === 0) {
                        tpl += '<span class="cur"></span>'
                    } else {
                        tpl += '<span></span>'
                    }
                }

                tpl += '</div>'
            }

            return tpl
        }
        function getData(callback) {
            requestUrl('/index/carousel', 'GET', null, callback)
        }
        function interact() {
            $('.slide').swipeSlide({
                continuousScroll: false,
                speed: 5000,
                autoSwipe: 0,
                transitionType: 'cubic-bezier(0.22, 0.69, 0.72, 0.88)',
                callback: function(i) {
                    $('.slide').find('.dot').children().eq(i).addClass('cur').siblings().removeClass('cur');
                }
            });
        }
    }());

    // 热搜关键字
    ;(function () {
        getData(function (data) {
            $('.hot-contain').html(createTpl(data))
            interact()
        })
        function getData(callback) {
            apex.commonApi.getHotKeywords(callback)
        }
        function createTpl(list) {
            var tpl = ''

            for (var i = 0; i < list.length; i += 1) {
                var item = list[i]

                tpl += '<span>' + item.name + '</span>'
            }

            return tpl
        }
        function interact() {
            $('.hot-contain').on('click','span',function() {
                setData(this.innerHTML);
                window.location.href = "/search/index?keyword=" + $(this).text();
            })
        }
    }());

    $('.puban').click(function() {
      window.location.href = '/temp/spike';
    })

    $('main.container').on('scroll touchmove', function() {
        var scroll = $('main.container').scrollTop();
        if (scroll > 100) {
            $('.search-box').addClass('change')
        } else {
            $('.search-box').removeClass('change')
        }
    })
    /*历史纪录开始*/
    //获取焦点时显示历史页面
    $('.slide-search-bar input').focus(function() {
      $('.search-history').removeClass('hidden');
      $('.search-box').addClass('change check');
      $('main.index-container').css('overflow', 'hidden');
      getHistory();
    });
    var history = window.localStorage;

    // 获取localStorage;
    function getHistory () {
        var s = '';
        var histStr = history.getItem('histStr');
        if(histStr == null) {
          $('.uls-list').html('');
          return false;
        }
        var histArr = histStr.split('&');
        if(histArr.length === 0){return false};
         for (var i = 0; i < 5; i++) {
            if(histArr[i]){
                s += '<li>'+ histArr[i]+'</li>';
            }
        }
        if( s != '') {
          $('.uls-list').html(s);
        }
    }

    //添加事件、点击搜索
    $('.slide-search-bar i').on('click',function() {
        var ipt_val = $('.slide-search-bar input').val();
        if (ipt_val === '') {
            ipt_val = $('.slide-search-bar input').data('default');
            $('.slide-search-bar input').val(ipt_val);
        }
        setData(ipt_val);
        window.location.href = "/search/index?keyword=" + ipt_val;
        // $('.search-box').removeClass('change check');
        // $('.search-history').addClass('hidden');
    });
    $('.slide-search-bar input').on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $('.slide-search-bar i').click()
        }
    })
    //取消
    $('.btn-cancel').click(function() {
      $('.slide-search-bar input').val('');
      $('.search-box').removeClass('change check');
      $('.search-history').addClass('hidden');
      $('main.index-container').css('overflow', 'auto');
    });
    $('.slide-search-bar input').val('');
    //添加到LocalStorage
    function setData(data) {
      var histArr = [];
        var histStr = history.getItem('histStr'); //.split('&')
        if(histStr == null){
            history.setItem('histStr',data);
        } else {
            histArr = histStr.split('&');
            var data_str = sortData(data,histArr).join("&");
            history.setItem('histStr',data_str);
        }
    }
    //数据倒序去重
    function sortData(str,arr){
        arr.reverse().push(str);
        var arrb = arr.reverse();
        return unique(arrb);
    }
    //去重
    function unique(array){
        var n = [];
        for(var i = 0; i < array.length; i++){
        if (n.indexOf(array[i]) == -1) n.push(array[i]);
        }
        return n;
    }
    //清空历史记录
    $('.hist-cancel').click(function(e) {
        e.preventDefault()
        var histStr = history.getItem('histStr')

        if (histStr) {
            history.removeItem('histStr')
        }

        getHistory();
    });
    //点击历史记录搜索
    $('.uls-list').on('click','li',function() {
        $('.search-history').addClass('hidden');
        $('.search-box').removeClass('change check');
        $('.slide-search-bar input').val($(this).text());
        window.location.href = "/search/index?keyword=" + $(this).text();
    });

    // dismiss mask
    $('[data-dismiss="mask"]').click(function(){
        $(this).parents('.mask-container').removeClass('in');
        $('main.index-container').css('overflowY', 'auto');
    })

    //show oupashi_logo
    requestUrl('/index/get-user-status','GET','',function(res){
      if ( res.status === 1 && res.level === 4) {
        $('#opas,#jiudaye,#kashida_2,#bier_2').removeClass('hidden');
        $('#kashida_1,#bier_1') .addClass('hidden');
      }
    });

    /*加载随机商品*/
    var tpl_random = '{@each _ as it}'

    tpl_random += '<li>'
    tpl_random += '<a href="/goods/detail?id=${it.id}">'
    tpl_random += '<div class="img-box">'
    tpl_random += '<img src="${it.main_image}?x-oss-process=image/resize,w_185,h_185,limit_1,m_lfit">'
    tpl_random += '</div>'
    tpl_random += '<p>${it.title}</p>'
    tpl_random += '<span>${it.description}</span>'
    tpl_random += '</a>'
    tpl_random += '</li>'
    tpl_random += '{@/each}'

    ;(!function() {
        var flag = true,
        count = 0;
        $('main.container').on('scroll touchmove', function () {
            var totalheight = parseFloat($('main.container').height()) + parseFloat($('main.container').scrollTop());
            if ($('.wechat-index-remaster').height() - 1 <= totalheight) {
                if (flag) {
                    flag = false;
                    count++;
                    requestUrl('/product-recommend/rand', 'GET', {current_page: count}, function(data) {
                        $('#J_random_list').append(juicer(tpl_random, data));
                        if (data.length > 0) {
                            flag = true;
                        }
                    })
                }
            }
        })
    }());
})

// 弹窗
$(function() {
    var now = new Date().getTime();
    var start1 = new Date('2018/09/05 00:00:00').getTime();
    var end1 = new Date('2018/09/08 23:59:59').getTime();
    var start2 = new Date('2018/09/09 00:00:00').getTime();
    var end2 = new Date('2018/09/10 23:59:59').getTime();

    if(now >= start1 && now <= end1){
        $('#J_index_modal_1').addClass('in')
        $('.wechat-index-remaster .nav-group').css('background','url(/images/event20180909/nav-hot.jpg) no-repeat center/cover')
        $('#hot0909banner').css('display','block')
        $('.sub-content').find('img').click(function(){
            $('#J_index_modal_1').removeClass('in')
        })
    }else if(now >= start2 && now <= end2){
        $('#J_index_modal_2').addClass('in')
        $('.wechat-index-remaster .nav-group').css('background','url(/images/event20180909/nav-event.jpg) no-repeat center/cover')
        $('#event0909banner').css('display','block')
        $('.sub-content').find('img').click(function(){
            window.location.href = '/temp/betabet/j'
        })
    }
    // 9/12活动 弹窗
    var start3 = new Date('2018/09/13 00:00:00').getTime();
    var end3 = new Date('2018/09/14 23:59:59').getTime();

    if(now >= start3 && now <= end3){
        $('.index-activity-tankuang-wrap').css('display','block');
        $('.index-activity-tankuang-wrap .index-activity-tankuang .activity-close').css({'background':'url(/images/180911/close.png) no-repeat center/cover'});
        $('.index-activity-tankuang-wrap .index-activity-tankuang .activity-close').on('click',function(event){
            event.stopPropagation();
            $('.index-activity-tankuang-wrap').css('display','none');
        })
        $('.index-activity-tankuang-wrap .index-activity-tankuang').on('click',function(){
            window.location.href = '/temp/betabet/l'
        })

    }else{
        $('.index-activity-tankuang-wrap').css('display','none');
    }

    // 9/17活动 弹窗
    var start4 = new Date('2018/10/26 00:00:00').getTime();
    var end4 = new Date('2018/10/27 23:59:59').getTime();

    if(now >= start4 && now <= end4){
        $('.index-activity-popup-wrap').css('display','block');
        $('.index-activity-popup-wrap .index-activity-popup .activity-popup-close').css({'background':'url(/images/20181015/an.png) no-repeat center/cover'});
        $('.index-activity-popup-wrap .rush-buy').css({'background':'url(/images/180917/3.png)center no-repeat','background-size': '100%'});
        $('.index-activity-popup-wrap .index-activity-popup .activity-popup-close').on('click',function(event){
            event.stopPropagation();
            $('.index-activity-popup-wrap').css('display','none');
        })
        $('.index-activity-popup img').on('click',function(){
            window.location.href = '/temp/betabet/o'
        })

    }else{
        $('.index-activity-popup-wrap').css('display','none');
    }

    // 10/25活动 弹窗
    var start10 = new Date('2018/10/28 00:00:00').getTime();
    var end10 = new Date('2018/10/31 23:59:59').getTime();

    if(now >= start10 && now <= end10){
        $('.index-activity-1025-wrap').css('display','block');
        $('.index-activity-1025-wrap .index-activity-1025 .activity-close').css({'background':'url(/images/180911/close.png) no-repeat center/cover'});
        $('.index-activity-1025-wrap .index-activity-1025 .activity-close').on('click',function(event){
            event.stopPropagation();
            $('.index-activity-1025-wrap').css('display','none');
        })
        $('.index-activity-1025-wrap .index-activity-1025').on('click',function(){
            window.location.href = '/temp/betabet/u'
        })

    }else{
        $('.index-activity-1025-wrap').css('display','none');
    }
    // var _stage = {
    //     stage2: {
    //         url: '/shop?id=55',
    //         src: '/images/modal/615/6.18-0-12.png'
    //     },
    //     stage3: {
    //         url: '/shop?id=54',
    //         src: '/images/modal/615/6.18wap12-24.png'
    //     },
    //     stage4: {
    //         url: '/shop?id=8',
    //         src: '/images/modal/615/wap_619-0-12.png'
    //     },
    //     stage5: {
    //         url: '/shop?id=24',
    //         src: '/images/modal/615/wap_619-2412-24.png'
    //     },
    //     stage6: {
    //         url: '/shop?id=9',
    //         src: '/images/modal/615/6.20wap0-12.png'
    //     },
    //     stage7: {
    //         url: '/shop?id=97',
    //         src: '/images/modal/615/6.20wap12-18.png'
    //     },
    //     stage8: {
    //         url: '/shop?id=33',
    //         src: '/images/modal/615/6.20wap18-24.png'
    //     },
    //     stage9: {
    //         url: '/shop?id=59',
    //         src: '/images/modal/615/6.21WAP0-12-1.png'
    //     },
    //     stage10: {
    //         url: '/shop?id=75',
    //         src: '/images/modal/615/6.21WAP12-24-1.png'
    //     },
    //     stage11: {
    //         url: '/goods/detail?id=1773',
    //         src: '/images/modal/615/wap_622-17730-12.png'
    //     },
    //     stage12: {
    //         url: '/goods/detail?id=1209',
    //         src: '/images/modal/615/wap_622-120912-18.png'
    //     },
    //     stage13: {
    //         url: '/goods/detail?id=1878',
    //         src: '/images/modal/615/wap_622-187818-24.png'
    //     },
    //     stage14: {
    //         url: '/temp/brand/a',
    //         src: '/images/modal/615/6.23-6.25wap.png'
    //     },
    //     stage15: {
    //         url: '/goods/detail?id=1903',
    //         src: '/images/modal/615/6.26wap0-12.png'
    //     },
    //     stage16: {
    //         url: '/goods/detail?id=1672',
    //         src: '/images/modal/615/6.26wap12-24.png'
    //     },
    //     stage17: {
    //         url: '/temp/betabet/a',
    //         src: '/images/modal/629.png'
    //     }
    // }
    // function changeModal(item) {
    //     $('#J_modal_main_link').attr('href', _stage[item].url)
    //     $('#J_modal_main_img').attr('src', _stage[item].src)
    // }
    // var stage1 = new Date('2018/06/16 00:00:00').getTime();
    // var stage2 = new Date('2018/06/18 00:00:00').getTime();
    // var stage3 = new Date('2018/06/18 12:00:00').getTime();
    // var stage4 = new Date('2018/06/19 00:00:00').getTime();
    // var stage5 = new Date('2018/06/19 12:00:00').getTime();
    // var stage6 = new Date('2018/06/20 00:00:00').getTime();
    // var stage7 = new Date('2018/06/20 12:00:00').getTime();
    // var stage8 = new Date('2018/06/20 18:00:00').getTime();
    // var stage9 = new Date('2018/06/21 00:00:00').getTime();
    // var stage10 = new Date('2018/06/21 12:00:00').getTime();
    // var stage11 = new Date('2018/06/22 00:00:00').getTime();
    // var stage12 = new Date('2018/06/22 12:00:00').getTime();
    // var stage13 = new Date('2018/06/22 18:00:00').getTime();
    // var stage14 = new Date('2018/06/23 00:00:00').getTime();
    // var stage15 = new Date('2018/06/26 00:00:00').getTime();
    // var stage16 = new Date('2018/06/26 12:00:00').getTime();
    // var stage17 = new Date('2018/06/27 00:00:00').getTime();
    // if (now < stage1 || now > stage2) {
    //     $('#J_index_modal_1').addClass('in')
    //     $('.wechat-index-remaster').css('overflow', 'hidden')
    // }
    // if (now > stage2 && now < stage3) {
    //     changeModal('stage2')
    // }
    // if (now > stage3 && now < stage4) {
    //     changeModal('stage3')
    // }
    // if (now > stage4 && now < stage5) {
    //     changeModal('stage4')
    // }
    // if (now > stage5 && now < stage6) {
    //     changeModal('stage5')
    // }
    // if (now > stage6 && now < stage7) {
    //     changeModal('stage6')
    // }
    // if (now > stage7 && now < stage8) {
    //     changeModal('stage7')
    // }
    // if (now > stage8 && now < stage9) {
    //     changeModal('stage8')
    // }
    // if (now > stage9 && now < stage10) {
    //     changeModal('stage9')
    // }
    // if (now > stage10 && now < stage11) {
    //     changeModal('stage10')
    // }
    // if (now > stage11 && now < stage12) {
    //     changeModal('stage11')
    // }
    // if (now > stage12 && now < stage13) {
    //     changeModal('stage12')
    // }
    // if (now > stage13 && now < stage14) {
    //     changeModal('stage13')
    // }
    // if (now > stage14 && now < stage15) {
    //     changeModal('stage14')
    // }
    // if (now > stage15 && now < stage16) {
    //     changeModal('stage15')
    // }
    // if (now > stage16 && now < stage17) {
    //     changeModal('stage16')
    // }
    // if (now > stage17 && now < end) {
    //     changeModal('stage17')
    // }
    // if (now > end) {
    //     $('#J_index_modal_1').addClass('hidden')
    // }

    $('.close-btn').on('click', function() {
        $('.wechat-index-remaster').css('overflow', 'auto')
    })

    $('.wechat-activity-0904-mask').on('touchmove', function(e) {
        e.stopPropagation()
        e.preventDefault()
    })
})
