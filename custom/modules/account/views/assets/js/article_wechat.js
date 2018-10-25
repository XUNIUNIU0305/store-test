$(function() {
	var img = '',
		title = '';
	function getArticleDetail(id, footer_id) {
		requestUrl('/account/article/get-open-detail', 'GET', {id: id, footer_id: footer_id}, function(data) {
			if (data.footer != '') {
				isNew = false
			}
			$('#J_article_title').html(data.title);
			$('#J_article_content').html(data.content);
			$('#J_article_footer').html(data.footer);
			title = data.title;
			img = data.path;
		})
	}
	getArticleDetail(url('?id'), url('?footer_id'));
	var meta = document.getElementsByTagName('meta');
	meta[1].content = 'width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0';
	requestUrl('http://106.14.255.215/api/9daye', 'GET', {m: 'js_sdk', id: url('?id'), footer_id: url('?footer_id')}, function(data) {
		wx.config({
	        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	        appId: data.appId, // 必填，公众号的唯一标识
	        timestamp: data.timestamp, // 必填，生成签名的时间戳
	        nonceStr: data.nonceStr, // 必填，生成签名的随机串
	        signature: data.signature,// 必填，签名，见附录1
	        jsApiList: ['checkJsApi','getLocation','chooseImage','uploadImage', 'onMenuShareWeibo', 'onMenuShareQZone', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ'] 
	    });
		wx.ready(function() {
			//分享到朋友圈
		    wx.onMenuShareTimeline({
		        title: title, // 分享标题
		        link: location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		        imgUrl: img, // 分享图标
		        success: function () { 
		            // 用户确认分享后执行的回调函数
		        },
		        cancel: function () { 
		            // 用户取消分享后执行的回调函数
		        }
		    });
		    //分享给朋友
		    wx.onMenuShareAppMessage({
			    title: title, // 分享标题
			    desc: title, // 分享描述
			    link: location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
			    imgUrl: img, // 分享图标
			    type: '', // 分享类型,music、video或link，不填默认为link
			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			//分享到QQ
			wx.onMenuShareQQ({
			    title: title, // 分享标题
			    desc: title, // 分享描述
			    link: location.href, // 分享链接
			    imgUrl: img, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			       // 用户取消分享后执行的回调函数
			    }
			});
			//分享到QQ微博
			wx.onMenuShareWeibo({
			    title: title, // 分享标题
			    desc: title, // 分享描述
			    link: location.href, // 分享链接
			    imgUrl: img, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			//分享到QQ空间
			wx.onMenuShareQZone({
			    title: title, // 分享标题
			    desc: title, // 分享描述
			    link: location.href, // 分享链接
			    imgUrl: img, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
		})
	})
})