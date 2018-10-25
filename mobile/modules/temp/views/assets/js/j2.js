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

// 初始化
var host = window.location.host;
var activity_id = 6
var local;
if(host == 'mobile.9daye.com'){
	local = 'http://mobile.9daye.com';
	activity_id = 6
}else if(host == 'test.m.9daye.com.cn'){
	local = 'http://test.m.9daye.com.cn';
	activity_id = 6
}else if(host == 'm.9daye.com.cn'){
	local = 'http://m.9daye.com.cn';
	activity_id = 22
}

// 列表
var dataArray = [];
var dataArray2 = [];
var navArray = '';
var allArray = '';
var listArray1 = '';
var listArray2 = '';
var listArray = '';
var areaArray = '';
var status;
var style;
var imgurl;
var bgHeight;
$.ajax({
	url: '/gpubs/api/activity-group-list',
	type: 'get',
	data: {activity_id:activity_id},
	success: function(data){
		dataArray2.push(data.data[Object.keys(data.data)[0]])
		$.each(data.data,function(index,item){
			dataArray.push(item);
		})
		$.each(dataArray,function(index,item){
			navArray = navArray + '<li class="" attr-number="' + index + '"><img src="/images/event20180909/pic5.png" />' + item.business_area_name + '</li>';
		});
		$('.navBar ul').html(navArray);
		$('.navBar ul li').eq(0).addClass('active');
		$('.navBar ul').css('width',(4 * ($('.navBar ul li').length - 1) + 7) + 'rem')
		function tabClick(id){
			dataArray2 = [];
			dataArray2.push(data.data[Object.keys(data.data)[id]])
			$.each(dataArray2,function(index,item){
				listArray = '';
				listArray1 = '';
				listArray2 = '';
				allArray = '';
				$.each(item.group,function(index2,item2){
					areaArray = '';
					if(item2.img == '' || item2.img == undefined || item2.img == null){
						imgurl = '/images/event20180909/t1.jpg'
					}else{
						imgurl = item2.img
					}
					$.each(item2.business_area,function(index3,item3){
						areaArray = areaArray + item3 + '/'
					})
					areaArray = areaArray.substring(0,areaArray.length - 1)
					if(item2.status == '1'){
						status = '拼团中...';
						style = '';
						listArray1 = listArray1 + '<li><div><img src="' + imgurl + '" /><ul><li>姓名：' + item2.consignee + '</li><li>电话：' + item2.mobile + '</li><li>区域团名：' + areaArray + '</li></ul><div><a href="/temp/betabet/k?group_id=' + item2.group_id + '">查看详情</a><a href="/gpubs/detail?id=' + item2.product_id + '&group_id=' + item2.group_id + '">点击参与</a></div><strong>' + status + '</strong><img src="/images/event20180909/pic1.png" class="over ' + style + '" /></div></li>'
					}else if(item2.status == '2'){
						status = '已成团';
						style = 'active';
						listArray2 = listArray2 + '<li><div><img src="' + imgurl + '" /><ul><li>姓名：' + item2.consignee + '</li><li>电话：' + item2.mobile + '</li><li>区域团名：' + areaArray + '</li></ul><div><a href="/temp/betabet/k?group_id=' + item2.group_id + '">查看详情</a><a href="/gpubs/detail?id=' + item2.product_id + '&group_id=' + item2.group_id + '">点击参与</a></div><strong>' + status + '</strong><img src="/images/event20180909/pic1.png" class="over ' + style + '" /></div></li>'
					}
				})
				listArray = listArray1 + listArray2;
				allArray = allArray + '<div class="mainCon bg2" id="spot' + parseInt(index+2) + '"><div class="titleBar"><span>///</span>' + item.business_area_name + '<span>///</span></div><ul class="mainList">' + listArray + '</ul></div>'
			});
			$('#main').html(allArray);
		}
		tabClick(0)
		bgHeight = $('#spot1').height();
		$('.navBar').css('top',bgHeight + 'px')
		$('.navBar ul li').click(function(){
			$('.navBar ul li').removeClass('active');
			$(this).addClass('active');
			tabClick($(this).attr('attr-number'))
		})
		$(window).scroll(function(){
			if($('body').scrollTop() > bgHeight){
				setTimeout(function(){
					$('.navBar').css({'position':'fixed','top':0,'left':0});
				},100)
			}else{
				setTimeout(function(){
					$('.navBar').css({'position':'absolute','top':bgHeight + 'px','left':0});
					$('#main').css('padding-top','3rem')
				},100)
			}
		})
	},
	error: function(){
		// window.location.href = '/member/login/index';
	}
})