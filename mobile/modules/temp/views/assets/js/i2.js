// 响应式
(function(doc,win,fontSize) {
	var docEl = doc.documentElement,
	resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
	recalc = function(){
		var clientWidth = docEl.clientWidth;
		if (!clientWidth){
			return;
		}
		docEl.style.fontSize = fontSize * (clientWidth / 320) + 'px';
	};
	if(!doc.addEventListener){
		return;
	}
	win.addEventListener(resizeEvt,recalc,false);
	doc.addEventListener('DOMContentLoaded',recalc,false);
})(document,window,16);

// 保留两位数字
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

// 模拟商品id
var dataArray = ['2245','2248','2249','2250','2251','2253','2257','2259','2264','2265','2273'];
if(document.domain == 'm.9daye.com.cn'){
	dataArray = ['2245','2248','2249','2250','2251','2253','2257','2259','2264','2265','2273'];
}else if(document.domain == 'mobile.9daye.com' || document.domain == 'mobile.test' || document.domain == 'test.m.9daye.com.cn'){
	dataArray = ['2245','2248','2249','2250','2251','2253','2257','2259','2264','2265','2273'];
}else{
	dataArray = ['2245','2248','2249','2250','2251','2253','2257','2259','2264','2265','2273'];
}

// 主业务流程
$.each($('#mainList li'),function(index,item){
	var _this = $(this);
	var startTime;
	var endTime;
	var nowTime;
	_this.attr('id',dataArray[index]);
	_this.find('.active').attr('href','/goods/detail?id=' + dataArray[index]);
	_this.find('.end').attr('href','/goods/detail?id=' + dataArray[index]);
	_this.find('.wait').attr('href','/goods/detail?id=' + dataArray[index]);
	$.ajax({
		url: '/gpubs/api/get-gpubs-time',
		type: 'get',
		data: {product_id:_this.attr('id')},
		success: function(data){
			// 判断是否为拼购商品
			if(data.data.length == 0){
				_this.find('.wait').css('display','block');
				_this.find('.timeStmp').html('活动未开始');
				_this.find('.priceCon strong').html('￥???').css('color','#ff9641');
				_this.find('.priceCon span').html('￥???');
				_this.find('.s1 span').css('display','none');
				_this.css('display','block');
			}else{
				nowTime = new Date().getTime();
				startTime = new Date(data.data.activity_start_datetime.replace(/\-/g, "\/")).getTime();
				endTime = new Date(data.data.activity_end_datetime.replace(/\-/g, "\/")).getTime();
				// 判断拼购时间并排序
				if(nowTime > endTime){
					$('#mainList').append(_this);
				}
				if(nowTime > startTime && nowTime < endTime){
					$('#mainList').prepend(_this);
				}
				setInterval(function(){
					nowTime = new Date().getTime();
					if(nowTime < startTime){
						var leftTime = startTime - nowTime;
						var day = parseInt(leftTime / 1000 / 60 / 60 / 24);
						var hour = parseInt(leftTime / 1000 / 60 / 60 % 24);
						var minute = parseInt(leftTime / 1000 / 60 % 60);
						var seconds = parseInt(leftTime / 1000 % 60);
						if(day <= 0){
							_this.find('.timeStmp').html('距离开始还有<span>' + checkTime(hour) + ':' + checkTime(minute) + ':' + checkTime(seconds) + '</span>');
						}else{
							_this.find('.timeStmp').html('距离开始还有<span>' + day + '天' + checkTime(hour) + ':' + checkTime(minute) + ':' + checkTime(seconds) + '</span>');
						}
						_this.find('.wait').css('display','block');
						_this.find('.priceCon strong').html('￥???').css('color','#ff9641');
						_this.find('.priceCon span').html('￥???');
						_this.find('.s1 span').css('display','none');
					}else{
						var leftTime = endTime - nowTime;
						var day = parseInt(leftTime / 1000 / 60 / 60 / 24);
						var hour = parseInt(leftTime / 1000 / 60 / 60 % 24);
						var minute = parseInt(leftTime / 1000 / 60 % 60);
						var seconds = parseInt(leftTime / 1000 % 60);
						if(leftTime <= 0){
							_this.find('.end').css('display','block');
							_this.find('.timeStmp').html('活动已结束');
							_this.find('.priceCon strong').css('color','#909090');
							_this.find('.priceCon em').css('color','#909090');
							_this.find('.s1 span').css('display','none');
						}else{
							_this.find('.active').css('display','block');
							if(day <= 0){
								_this.find('.timeStmp span').html(checkTime(hour) + ':' + checkTime(minute) + ':' + checkTime(seconds));
							}else{
								_this.find('.timeStmp span').html(day + '天' + checkTime(hour) + ':' + checkTime(minute) + ':' + checkTime(seconds));
							}
						}
					}
					_this.css('display','block');
				},1000);
			}
		},
		error: function(){
			console.log('系统错误')
		}
	})
})

// 微信分享
function wechatShare() {
    var img = 'http://m.9daye.com.cn/images/event20180909/share.jpg';
    var title = '正品机油拼团购，打到骨折一起浪！';
    var desc = '我在九大爷平台和上万门店拼团买SK机油！工厂发货，正品保障！点击参团！';
    var _url = window.location.href;
    var share_url = 'http://m.9daye.com.cn/temp/betabet/i';
    if (location.host.search('test') != -1) {
    	img = 'http://test.m.9daye.com.cn/images/event20180909/share.jpg';
        share_url = 'http://test.m.9daye.com.cn/temp/betabet/i';
    }
    $.ajax({
        url: 'http://106.14.255.215/api/9daye',
        data: {
            m: 'm_js_sdk',
            url: _url
        },
        success: function(data) {
            var data = data.data
            wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: data.appId, // 必填，公众号的唯一标识
                timestamp: data.timestamp, // 必填，生成签名的时间戳
                nonceStr: data.nonceStr, // 必填，生成签名的随机串
                signature: data.signature,// 必填，签名，见附录1
                jsApiList: ['checkJsApi','getLocation','chooseImage','uploadImage', 'onMenuShareTimeline', 'onMenuShareWeibo', 'onMenuShareQZone',  'onMenuShareAppMessage', 'onMenuShareQQ']
            });
            wx.ready(function() {
                //分享到朋友圈
                wx.onMenuShareTimeline({
                    title: title, // 分享标题
                    link: share_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
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
                    desc: desc, // 分享描述
                    link: share_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
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
                    desc: desc, // 分享描述
                    link: share_url, // 分享链接
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
                    desc: desc, // 分享描述
                    link: share_url, // 分享链接
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
                    desc: desc, // 分享描述
                    link: share_url, // 分享链接
                    imgUrl: img, // 分享图标
                    success: function () {
                       // 用户确认分享后执行的回调函数
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
            })
        },
        error: function(data) {
            console.log(data)
        }
    })
}

wechatShare()