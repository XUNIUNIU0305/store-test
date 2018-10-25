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

var host = window.location.host;
var local;
if(host == 'mobile.9daye.com'){
    local = 'http://mobile.9daye.com';
}else if(host == 'test.m.9daye.com.cn'){
    local = 'http://test.m.9daye.com.cn';
}else if(host == 'm.9daye.com.cn'){
    local = 'http://m.9daye.com.cn';
}
$('#toTop').click(function(){
    $('body').scrollTop(0)
    $('html').scrollTop(0)
})

// 地图
var map = new AMap.Map("map", {
    resizeEnable: true
});
function geocoder() {
    var geocoder = new AMap.Geocoder({
        radius: 1000, //范围，默认：500,
        extensions:'base'
    });
    //传入地址进行地理编码,返回地理编码结果
    geocoder.getLocation(addr, function(status, result) {
        if (status === 'complete' && result.info === 'OK') {
            geocoder_CallBack(result);
            console.log(result);
        }
    });
}
function addMarker(i, d) {
    var marker = new AMap.Marker({
        map: map,
        position: [ d.location.getLng(),  d.location.getLat()]
    });
    var infoWindow = new AMap.InfoWindow({
        content: d.formattedAddress,
        offset: {x: 0, y: -30}
    });
    marker.on("mouseover", function(e) {
        infoWindow.open(map, marker.getPosition());
    });
}
//地理编码返回结果展示
function geocoder_CallBack(data) {
    var resultStr = "";
    //地理编码结果数组
    var geocode = data.geocodes;
    for (var i = 0; i < geocode.length; i++) {
        //拼接输出html
        resultStr += "<span style=\"font-size: 12px;padding:0px 0 4px 2px; border-bottom:1px solid #C1FFC1;\">" + "<b>地址</b>：" + geocode[i].formattedAddress + "" + "&nbsp;&nbsp;<b>的地理编码结果是:</b><b>&nbsp;&nbsp;&nbsp;&nbsp;坐标</b>：" + geocode[i].location.getLng() + ", " + geocode[i].location.getLat() + "" + "<b>&nbsp;&nbsp;&nbsp;&nbsp;匹配级别</b>：" + geocode[i].level + "</span>";
        addMarker(i, geocode[i]);
    }
    //根据地图上添加的覆盖物分布情况，自动缩放地图到合适的视野级别，参数overlayList默认为当前地图上添加的所有覆       盖物图层
    map.setFitView();
}

var addr= "";
var imgArray = '';
var imgurl;
var bottomHeight = $('.J_footer_menu').height();
$.ajax({
	url: '/gpubs/api/activity-group-detail',
	type: 'get',
	data: {group_id:window.location.href.substring(window.location.href.indexOf('group_id') + 9,window.location.href.length)},
	success: function(data){
		addr = data.data.address;
        if(data.data.img == '' || data.data.img == undefined || data.data.img == null){
            imgurl = '/images/event20180909/t1.jpg'
        }else{
            imgurl = data.data.img
        }
		$('.headerCon img').eq(1).attr('src',imgurl);
		$('.infoCon strong').html('热血团长：' + data.data.group_consignee);
		$('.infoCon span').html('电话：' + data.data.mobile);
		$('.infoCon em').html('提货地址：' + data.data.address);
        $('#toPub').attr('href','/gpubs/detail?id=' + data.data.gpubs_product_id + '&group_id=' + data.data.gpubs_id);
		geocoder()
        $('.backTo').css('bottom',bottomHeight + 'px')
	},
	error: function(){
		window.location.href = '/member/login/index';
	}
})