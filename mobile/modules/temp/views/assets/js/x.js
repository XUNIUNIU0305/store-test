$(function(){
    /*注册juicer自定义函数*/
    function price(data) {
        if (data.min === data.max) {
            return parseFloat(data.min).toFixed(2)
        } else {
            return parseFloat(data.min).toFixed(2) + '-' + parseFloat(data.max).toFixed(2)
        }
    }
    juicer.register('price', price);

    var seckillTimer = {
    	container: $('#J_timer_box'),
        timerDom: $('#J_seckill_timer'),
        is_start: false,
        startT: '',
        endT: '',
    	seckillRun: function(intDiff, callback) {
    		var _this = this;
    		jdy.seckill.timerFun(intDiff / 1000, function(date) {
                var _day = date.day * 24;
                var _hour = parseInt(date.hour) + parseInt(_day);
                if (_hour < 10) {
                    _hour = '0' + _hour;
                }
                $('#timer_hour').html(_hour);
    			$('#timer_minute').html(date.minute);
    			$('#timer_second').html(date.second);
    		}, function() {
                typeof callback === 'function' && callback();
    		})
    	},
    	product: {
    		box: $('#J_product_box'),
    		tpl: $('#J_tpl_pro').html(),
            data: [],
            id: [
                1055, 1631, 1569, 1632, 1633
            ],
    		getPro: function() {
    			var _this = this;
    			requestUrl('/product-recommend/goods', 'GET', {id: _this.id}, function(data) {
                    seckillTimer.data = data;
                    _this.box.html(juicer(_this.tpl, { data: data, start: true}));
    			})
    		}
        },
        unstart: function(timer) {
            this.seckillRun(timer, function () {
                $('#timer_text').html('距离结束还剩：');
                seckillTimer.is_start = true;
                seckillTimer.product.box.html(juicer(seckillTimer.product.tpl, {
                    data: seckillTimer.data,
                    start: seckillTimer.is_start
                }));
                seckillTimer.start(seckillTimer.endT- new Date().getTime());
            });
        },
        start: function(timer) {
            seckillTimer.seckillRun(timer, function () {
                seckillTimer.is_start = false;
                seckillTimer.product.box.html(juicer(seckillTimer.product.tpl, {
                    data: seckillTimer.data,
                    start: seckillTimer.is_start
                }));
                seckillTimer.end();
            })
        },
        end: function () {
            $('#timer_hour').html('00');
            $('#timer_minute').html('00');
            $('#timer_second').html('00');
        },
    	init: function() {
            var _this = this;
            var _now = new Date().getTime();
            var _start = new Date('2018/04/20 00:00:00').getTime();
            var _end = new Date('2018/04/30 23:59:59').getTime();
            this.startT = _start;
            this.endT = _end;
            if (_start - _now > 0) {
                _this.unstart(_start - _now);
            } else if (_now > _start && _end > _now) {
                this.is_start = true;
                this.start(_end - _now);
                $('#timer_text').html('距离结束还剩：');
            } else {
                this.is_start = false;
                this.end();
            }
            this.product.getPro();
    	}
    }
    seckillTimer.product.getPro();
    
});