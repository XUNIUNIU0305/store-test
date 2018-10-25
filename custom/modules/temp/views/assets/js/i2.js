// 保留两位数字
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

// 模拟商品id
var dataArray = ['2245','2248','2249','2250','2251','2253','2257','2259','2264','2265','2273'];
if(document.domain == 'www.9daye.com.cn'){
	dataArray = ['2245','2248','2249','2250','2251','2253','2257','2259','2264','2265','2273'];
}else if(document.domain == 'custom.9daye.com' || document.domain == 'custom.test' || document.domain == 'test.custom.9daye.com.cn'){
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
	_this.find('.active').attr('href','/product?id=' + dataArray[index]);
	_this.find('.end').attr('href','/product?id=' + dataArray[index]);
	_this.find('.wait').attr('href','/product?id=' + dataArray[index]);
	$.ajax({
		url: '/gpubs/api/get-gpubs-time',
		type: 'get',
		data: {product_id:_this.attr('id')},
		success: function(data){
			// 判断是否为拼购商品
			if(data.data.length == 0){
				_this.find('.wait').fadeIn(0);
				_this.find('.timeStmp').html('活动未开始');
				_this.find('.priceCon strong').html('???').css('color','#ff9641');
				_this.find('.priceCon em').css('color','#ff9641');
				_this.find('.priceCon span').html('￥???');
				_this.fadeIn(0);
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
						_this.find('.wait').fadeIn(0);
						_this.find('.priceCon strong').html('???').css('color','#ff9641');
						_this.find('.priceCon em').css('color','#ff9641');
						_this.find('.priceCon span').html('￥???');
					}else{
						var leftTime = endTime - nowTime;
						var day = parseInt(leftTime / 1000 / 60 / 60 / 24);
						var hour = parseInt(leftTime / 1000 / 60 / 60 % 24);
						var minute = parseInt(leftTime / 1000 / 60 % 60);
						var seconds = parseInt(leftTime / 1000 % 60);
						if(leftTime <= 0){
							_this.find('.end').fadeIn(0);
							_this.find('.timeStmp').html('活动已结束');
							_this.find('.priceCon strong').css('color','#909090');
							_this.find('.priceCon em').css('color','#909090');
						}else{
							_this.find('.active').fadeIn(0);
							if(day <= 0){
								_this.find('.timeStmp span').html(checkTime(hour) + ':' + checkTime(minute) + ':' + checkTime(seconds));
							}else{
								_this.find('.timeStmp span').html(day + '天' + checkTime(hour) + ':' + checkTime(minute) + ':' + checkTime(seconds));
							}
						}
					}
					_this.fadeIn(0);
				},1000);
			}
		},
		error: function(){
			console.log('系统错误')
		}
	})
})
	
