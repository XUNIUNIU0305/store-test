// 初始化
var host = window.location.host;
var activity_id = 6
var local;
if(host == 'custom.9daye.com'){
	local = 'http://mobile.9daye.com';
	activity_id = 6
}else if(host == 'test.custom.9daye.com.cn'){
	local = 'http://test.m.9daye.com.cn';
	activity_id = 6
}else if(host == 'www.9daye.com.cn'){
	local = 'http://m.9daye.com.cn';
	activity_id = 22
}
function init(){
	// 侧边栏导航
	var scrollArray = [];
	var bannerHeight = $('.bg1').height();
	scrollArray.push(bannerHeight);
	$.each($('.bg2'),function(index,item){
		bannerHeight += $(this).height();
		scrollArray.push(bannerHeight);
	})
	$(window).scroll(function(){
		/*
		if($(window).scrollTop() >= 800){
			$('.navBar').fadeIn(0);
		}else{
			$('.navBar').fadeOut(0);
		}
		*/
		$.each(scrollArray,function(index,item){
			if($(window).scrollTop() >= scrollArray[index] && $(window).scrollTop() < scrollArray[index+1]){
				$('.navBar ul li').removeClass('active').eq(index).addClass('active');
			}
		});
	});
	$('.navBar div').hover(function(){
		$('.navBar').stop().animate({'margin-right':0},200)
	});
	$('.navBar span').click(function(){
		$('.navBar').stop().animate({'margin-right':'-100px'},200)
	});
	$('.navBar ul li a').click(function(){
		$('.navBar ul li').removeClass('active');
		$(this).parent('li').addClass('active');
	});
	$('#backTotop').click(function(){
		$('.navBar ul li').removeClass('active');
		$('.navBar ul li').eq(0).addClass('active');
	})

	// 点击扫码
	$('.mainList li div div span').click(function(){
		$('.alertBox h2').html($(this).attr('attr-user') + '的团');
		$('#erweima').html('')
		new QRCode(document.getElementById('erweima'),$(this).attr('attr-url'));
		$('.layer').fadeIn(0);
		$('.alertBox').fadeIn(0);
	});

	// 关闭窗口
	$('.alertBox strong').click(function(){
		$('.layer').fadeOut(0);
		$('.alertBox').fadeOut(0);
	})
}

// 列表
var dataArray = [];
var navArray = '';
var allArray = '';
var listArray1 = '';
var listArray2 = '';
var listArray = '';
var areaArray = '';
var status;
var style;
var imgurl;
$.ajax({
	url: '/gpubs/api/activity-group-list',
	type: 'get',
	data: {activity_id:activity_id},
	success: function(data){
		$.each(data.data,function(index,item){
			dataArray.push(item)
		})
		$.each(dataArray,function(index,item){
			navArray = navArray + '<li><a href="#spot' + parseInt(index+2) + '">' + item.business_area_name + '</a></li>';
			listArray = '';
			listArray1 = '';
			listArray2 = '';
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
					listArray1 = listArray1 + '<li><div><img src="' + imgurl + '" /><ul><li>姓名：' + item2.consignee + '</li><li>电话：' + item2.mobile + '</li><li>区域团名：' + areaArray + '</li></ul><div><a href="/temp/betabet/k?group_id=' + item2.group_id + '">查看详情</a><span attr-user="' + item2.consignee + '" attr-url="' + local + '/gpubs/detail?id=' + item2.product_id + '&group_id=' + item2.group_id + '">扫码参加</span></div><strong>' + status + '</strong><em class="' + style + '"></em></div></li>'
				}else if(item2.status == '2'){
					status = '已成团';
					style = 'active';
					listArray2 = listArray2 + '<li><div><img src="' + imgurl + '" /><ul><li>姓名：' + item2.consignee + '</li><li>电话：' + item2.mobile + '</li><li>区域团名：' + areaArray + '</li></ul><div><a href="/temp/betabet/k?group_id=' + item2.group_id + '">查看详情</a><span attr-user="' + item2.consignee + '" attr-url="' + local + '/gpubs/detail?id=' + item2.product_id + '&group_id=' + item2.group_id + '">扫码参加</span></div><strong>' + status + '</strong><em class="' + style + '"></em></div></li>'
				}
			})
			listArray = listArray1 + listArray2;
			allArray = allArray + '<div class="mainCon bg2" id="spot' + parseInt(index+2) + '"><div class="titleBar"><span>///</span>' + item.business_area_name + '<span>///</span></div><ul class="mainList">' + listArray + '</ul></div>'
		});
		$('.navBar ul').html(navArray);
		$('.navBar').css('margin-top',-$('.navBar').height()/2 + 'px');
		$('.navBar ul li').eq(0).addClass('active');
		$('#main').html(allArray);
		init();
	},
	error: function(){
		// window.location.href = '/login';
	}
})