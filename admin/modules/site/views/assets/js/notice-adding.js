;(function () {
	// 插入确认框模版
  commonConfrim()
  var id = null
	var times = 0
	var nodeTagP = $('.unit-content').children('p:last-child')
	var el_em = nodeTagP.find('em')
	var el_i = nodeTagP.find('i')
	var el_ipt = nodeTagP.find('input')
	var filename

	$('#upload_img_input').on('change', function(e) {
		var filename = $(this).val();
		var $this = $(this);
		apex.uploadImg(this.files[0], {
			loaded: function (data) {
				$('#upload_img_input').attr('data-val',data.filename)
				$('.img-upload-box').css('background-image','url('+data.url+')')
			}
		})
	})

	$('.choice-unit span[data-toggle="tab"]').on('click',function (e) {
		e.preventDefault()
		transfromTab(e.target.id, e.target)
	})

	function transfromTab (id,target) {
		switch (id){
			case 'unit-web':
				$(target).addClass('tab-active').siblings('span').removeClass('tab-active')
				el_em.html('内容：')
				el_i.removeClass('hidden')
				el_ipt.addClass('hidden')
				nodeTagP.css('height','340px')
				$('.submit-btn').attr('data-event','unit-web')
				noticeSubmitEvent('unit-web')
				break
			case 'unit-domain':
				$(target).addClass('tab-active').siblings('span').removeClass('tab-active')
				el_em.html('链接：')
				el_i.addClass('hidden')
				el_ipt.removeClass('hidden')
				nodeTagP.css('height','30px')
				$('.submit-btn').attr('data-event','unit-domain')
				noticeSubmitEvent()
				break
		}
	}

	function noticeSubmitEvent( opt ) {
		$('.submit-btn').unbind().on('click',function (e) {
			e.preventDefault()
			var data = null

			if($(this).attr('data-event') === 'unit-web') data = valiteUnitweb()
			if($(this).attr('data-event') === 'unit-domain') data = valiteUnitDomain()

			if (data.type == 1) {
				if (!data.img) {
					return showTipBox('图片不能为空')
				}

				if (/^\s*$/.test(data.title)){
				 	return showTipBox('标题不能为空')
				}
				else {
					data.title = data.title.replace(/\s*/g,'')
					if (data.title.length > 30){
						return showTipBox('标题不能超过30个字符')
					}
				}

				if (!data.content) {
					return showTipBox('内容不能为空')
				}
				else {
					if(data.content.search('<span>')){
						data.content = data.content.replace(/(\<\span\>|\<\/\span\>)*/,'')
					}
					if (data.content.length > 300){
						return showTipBox('内容不能超过300个字符')
					}
				}
			}
			else {
				if (!data.img) {
					return showTipBox('图片不能为空')
				}

				if (/^\s*$/.test(data.title)){
					return showTipBox('标题不能为空')
				}
				else {
					data.title = data.title.replace(/\s*/g,'')
					if (data.title.length > 30){
						return showTipBox('标题内容不能超过30个字符')
					}
				}

				if (!data.url) {
					return showTipBox('链接不能为空')
				}
				else {
					if(/^(\http|\https)(\:\/\/)(.*){1,}/.test(data.url)) console.log('true')
					else return showTipBox('链接格式不正确')
				}
			}

			if($(this).hasClass('data-update')){
				requestUrl('/homepage/post/update', 'POST', data, function(data) {
				  clearTimeout(times)
				  times = setTimeout(function (e) {
						history.back('/site/carousel?tab=6')
				  },300)
				})
			}
			else {
				requestUrl('/homepage/post/add', 'POST', data, function(data) {
				  // showTipBox('提交成功！');
				  clearTimeout(times)
				  times = setTimeout(function (e) {
						history.back('/site/carousel?tab=6')
				  },300)
				})
			}

		})
	}

	noticeSubmitEvent('unit-web')

	function valiteUnitweb() {
		var type = 1
		var img = $('#upload_img_input').attr('data-val')
		var title = $('.title-txt').val()
		var content = editor.html()

		var data ={
			title : title.toString(),
			type : type,
			img : img,
			content : content.toString(),
			id: id
		}

		return data
	}

	function valiteUnitDomain() {
		var type = 2
		var img = $('#upload_img_input').attr('data-val')
		var title = $('.title-txt').val()
		var url = $('.link-txt').val()
		var data ={
			title : title.toString(),
			type : type,
			img : img,
			url : url.toString(),
			id : id
		}

		return data
	}

	$('.footer-unit .clear-btn').on('click',function (e) {
		location.href = '/site/carousel?tab=6'
	})

	var times = null
	function runtimePostPage() {
		var addr = location.href

		if(addr.indexOf('?') > 0){
			var clearPrefix = addr.replace(/^(http:\/\/)?(.*)(\?)/,'')
			var symbol = clearPrefix.split('&')

			symbol.forEach(function (fb) {
				var equal = fb.indexOf('=')
				var keys = fb.substr(0,equal)
				if(keys === 'title'||keys === 'content') symbol[keys] = decodeURI(fb.substr(equal+1))
				else symbol[keys] = fb.substr(equal+1)
			})
			
			id = symbol.id

			switch (symbol.type){
				case '1':
					transfromTab('unit-web',$('.choice-unit span[data-toggle="tab"]').eq(0))
					$('.unit-content').find('.img-upload-box').css('background-image','url(http://apex-platform-test.oss-cn-shanghai.aliyuncs.com/'+symbol.img+')')
					$('.unit-content').find('.title-txt').val(symbol.title)

					clearTimeout(times)
					times = setTimeout(function (e) {
						editor.html(''+symbol.content)
					},1000)

					$('#unit-domain').unbind('click')
					$('.submit-btn').addClass('data-update')
					break
				case '2':
					transfromTab('unit-domain',$('.choice-unit span[data-toggle="tab"]').eq(1))
					$('.unit-content').find('.img-upload-box').css('background-image','url(http://apex-platform-test.oss-cn-shanghai.aliyuncs.com/'+symbol.img+')')
						$('.unit-content').find('.title-txt').val(symbol.title)
					$('.unit-content').find('.link-txt').val(symbol.title)
					$('#unit-web').unbind('click')
					$('.submit-btn').addClass('data-update')
					break
			}
		}
		else return void null
	}

  runtimePostPage()

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

    $submit.on('click', function () {
      $el.modal('hide')
      typeof opt.submit === 'function' && opt.submit()
    })
  }

}());
