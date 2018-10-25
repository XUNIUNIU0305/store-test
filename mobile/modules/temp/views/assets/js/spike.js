/*! echo-js v1.7.3 | (c) 2016 @toddmotto | https://github.com/toddmotto/echo */
(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(function() {
      return factory(root);
    });
  } else if (typeof exports === 'object') {
    module.exports = factory;
  } else {
    root.echo = factory(root);
  }
})(this, function (root) {

  'use strict';

  var echo = {};

  var callback = function () {};

  var offset, poll, delay, useDebounce, unload;

  var isHidden = function (element) {
    return (element.offsetParent === null);
  };
  
  var inView = function (element, view) {
    if (isHidden(element)) {
      return false;
    }

    var box = element.getBoundingClientRect();
    return (box.right >= view.l && box.bottom >= view.t && box.left <= view.r && box.top <= view.b);
  };

  var debounceOrThrottle = function () {
    if(!useDebounce && !!poll) {
      return;
    }
    clearTimeout(poll);
    poll = setTimeout(function(){
      echo.render();
      poll = null;
    }, delay);
  };

  echo.init = function (opts) {
    opts = opts || {};
    var offsetAll = opts.offset || 0;
    var offsetVertical = opts.offsetVertical || offsetAll;
    var offsetHorizontal = opts.offsetHorizontal || offsetAll;
    var optionToInt = function (opt, fallback) {
      return parseInt(opt || fallback, 10);
    };
    offset = {
      t: optionToInt(opts.offsetTop, offsetVertical),
      b: optionToInt(opts.offsetBottom, offsetVertical),
      l: optionToInt(opts.offsetLeft, offsetHorizontal),
      r: optionToInt(opts.offsetRight, offsetHorizontal)
    };
    delay = optionToInt(opts.throttle, 250);
    useDebounce = opts.debounce !== false;
    unload = !!opts.unload;
    callback = opts.callback || callback;
    echo.render();
    if (document.addEventListener) {
      root.addEventListener('scroll', debounceOrThrottle, false);
      root.addEventListener('touchmove', debounceOrThrottle, false);
      root.addEventListener('load', debounceOrThrottle, false);
    } else {
      root.attachEvent('onscroll', debounceOrThrottle);
      root.attachEvent('ontouchmove', debounceOrThrottle);
      root.attachEvent('onload', debounceOrThrottle);
    }
  };

  echo.render = function (context) {
    var nodes = (context || document).querySelectorAll('[data-echo], [data-echo-background]');
    var length = nodes.length;
    var src, elem;
    var view = {
      l: 0 - offset.l,
      t: 0 - offset.t,
      b: (root.innerHeight || document.documentElement.clientHeight) + offset.b,
      r: (root.innerWidth || document.documentElement.clientWidth) + offset.r
    };
    for (var i = 0; i < length; i++) {
      elem = nodes[i];
      if (inView(elem, view)) {

        if (unload) {
          elem.setAttribute('data-echo-placeholder', elem.src);
        }

        if (elem.getAttribute('data-echo-background') !== null) {
          elem.style.backgroundImage = 'url(' + elem.getAttribute('data-echo-background') + ')';
        }
        else if (elem.src !== (src = elem.getAttribute('data-echo'))) {
          elem.src = src;
        }

        if (!unload) {
          elem.removeAttribute('data-echo');
          elem.removeAttribute('data-echo-background');
        }

        callback(elem, 'load');
      }
      else if (unload && !!(src = elem.getAttribute('data-echo-placeholder'))) {

        if (elem.getAttribute('data-echo-background') !== null) {
          elem.style.backgroundImage = 'url(' + src + ')';
        }
        else {
          elem.src = src;
        }

        elem.removeAttribute('data-echo-placeholder');
        callback(elem, 'unload');
      }
    }
    if (!length) {
      echo.detach();
    }
  };

  echo.detach = function () {
    if (document.removeEventListener) {
      root.removeEventListener('scroll', debounceOrThrottle);
      root.removeEventListener('touchmove', debounceOrThrottle);
    } else {
      root.detachEvent('onscroll', debounceOrThrottle);
      root.detachEvent('ontouchmove', debounceOrThrottle);
    }
    clearTimeout(poll);
  };

  return echo;

});
$(function() {
  echo.init({
        offset: 100,
        offsetTop: 100,
        throttle: 250,
        unload: false,
        callback: function (element, op) {
        }
    });
    
    // seckill
    function timerFunc(intDiff, cb){
        seckill_interval = window.setInterval(function() {
            var day = 0,
                hour = 0,
                minute = 0,
                second = 0; //时间默认值      
            if (intDiff > 0) {
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (day <= 9) day = '0' + day;
            if (hour <= 9) hour = '0' + hour;
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('.seckill-time .cd-d s').each(function(idx, el){
                $(el).text((day + '').substring(idx, idx + 1));
            });
            $('.seckill-time .cd-h s').each(function(idx, el){
                $(el).text((hour + '').substring(idx, idx + 1));
            });
            $('.seckill-time .cd-m s').each(function(idx, el){
                $(el).text((minute + '').substring(idx, idx + 1));
            });
            $('.seckill-time .cd-s s').each(function(idx, el){
                $(el).text((second + '').substring(idx, idx + 1));
            });
            intDiff--;
            if(intDiff < 0) {
                clearInterval(seckill_interval);
                seckillRun();
            }
        }, 1000);
    }
    function seckillRun(){
        currentTStamp = new Date().getTime();
        // logic before active time
        if (activeTStamp1 > currentTStamp) {
            timerFunc((activeTStamp1 - currentTStamp)/1000);
        } else if (activeTStamp1 < currentTStamp && expireTStamp1 > currentTStamp) {
            $('[class*="seckill-pro-"]').css('display', 'none');
            $('.seckill-pro-1').css('display', 'block');
            $('.title-1').css('display', 'block');
            $('.title-2').css('display', 'none');
            $('.seckill-time .count-down').addClass('active');
            $('.doing img').attr('src', '/images/seckill/seckilling.png');
            $('.btn').text('立即购买');
            timerFunc((expireTStamp1 - currentTStamp)/1000);
        }
        if (activeTStamp2 > currentTStamp && expireTStamp1 < currentTStamp) {
            $('.seckill-time .count-down').removeClass('active');
            $('.btn').text('抢先看');
            timerFunc((activeTStamp2 - currentTStamp)/1000);
        } else if (activeTStamp2 < currentTStamp && expireTStamp2 > currentTStamp) {
            $('[class*="seckill-pro-"]').css('display', 'none');
            $('.seckill-pro-2').css('display', 'block');
            $('.title-2').css('display', 'block');
            $('.title-1').css('display', 'none');
            $('.seckill-time .count-down').addClass('active');
            $('.doing img').attr('src', '/images/seckill/seckilling.png');
            $('.btn').text('立即购买');
            timerFunc((expireTStamp2 - currentTStamp)/1000);
        }
        if (activeTStamp3 > currentTStamp && expireTStamp2 < currentTStamp) {
            $('.seckill-time .count-down').removeClass('active');
            $('.btn').text('抢先看');
            timerFunc((activeTStamp3 - currentTStamp)/1000);
        } else if (activeTStamp3 < currentTStamp && expireTStamp3 > currentTStamp) {
            $('[class*="seckill-pro-"]').css('display', 'none');
            $('.seckill-pro-3').css('display', 'block');
            $('.title-1').css('display', 'block');
            $('.title-2').css('display', 'none');
            $('.seckill-time .count-down').addClass('active');
            $('.doing img').attr('src', '/images/seckill/seckilling.png');
            $('.btn').text('立即购买');
            timerFunc((expireTStamp3 - currentTStamp)/1000);
        }
        if (activeTStamp4 > currentTStamp && expireTStamp3 < currentTStamp) {
            $('.seckill-time .count-down').removeClass('active');
            $('.btn').text('抢先看');
            timerFunc((activeTStamp4 - currentTStamp)/1000);
        } else if (activeTStamp4 < currentTStamp && expireTStamp4 > currentTStamp) {
            $('[class*="seckill-pro-"]').css('display', 'none');
            $('.seckill-pro-4').css('display', 'block');
            $('.title-2').css('display', 'block');
            $('.title-1').css('display', 'none');
            $('.seckill-time .count-down').addClass('active');
            $('.doing img').attr('src', '/images/seckill/seckilling.png');
            $('.btn').text('立即购买');
            timerFunc((expireTStamp4 - currentTStamp)/1000);
        }
        if (activeTStamp5 > currentTStamp && expireTStamp4 < currentTStamp) {
            $('.seckill-time .count-down').removeClass('active');
            $('.btn').text('抢先看');
            timerFunc((activeTStamp5 - currentTStamp)/1000);
        } else if (activeTStamp5 < currentTStamp && expireTStamp5 > currentTStamp) {
            $('[class*="seckill-pro-"]').css('display', 'none');
            $('.seckill-pro-5').css('display', 'block');
            $('.title-1').css('display', 'block');
            $('.title-2').css('display', 'none');
            $('.seckill-time .count-down').addClass('active');
            $('.doing img').attr('src', '/images/seckill/seckilling.png');
            $('.btn').text('立即购买');
            timerFunc((expireTStamp5 - currentTStamp)/1000);
        }
        if (activeTStamp6 > currentTStamp && expireTStamp5 < currentTStamp) {
            $('.seckill-time .count-down').removeClass('active');
            $('.btn').text('抢先看');
            timerFunc((activeTStamp6 - currentTStamp)/1000);
        } else if (activeTStamp6 < currentTStamp && expireTStamp6 > currentTStamp) {
            $('[class*="seckill-pro-"]').css('display', 'none');
            $('.seckill-pro-6').css('display', 'block');
            $('.title-2').css('display', 'block');
            $('.title-1').css('display', 'none');
            $('.seckill-time .count-down').addClass('active');
            $('.doing img').attr('src', '/images/seckill/seckilling.png');
            $('.btn').text('立即购买');
            timerFunc((expireTStamp6 - currentTStamp)/1000);
        }
        if (currentTStamp > (expireTStamp6 - 0 + 60000)) {
            $('body').html('<h1 style="text-align: center; color: #fff;">本次活动已结束!</h1>')
        }
    }
    var seckill_interval; 
    var activeTStamp1 = new Date('2017/6/29 10:00:00').getTime(),  // active count down time
        expireTStamp1 = new Date('2017/6/29 11:59:59').getTime(),  // expired time
        activeTStamp2 = new Date('2017/6/29 12:00:01').getTime(),  // active count down time
        expireTStamp2 = new Date('2017/6/29 13:59:59').getTime(),
        activeTStamp3 = new Date('2017/6/29 14:00:01').getTime(),  // active count down time
        expireTStamp3 = new Date('2017/6/29 15:59:59').getTime(),
        activeTStamp4 = new Date('2017/6/29 16:00:01').getTime(),  // active count down time
        expireTStamp4 = new Date('2017/6/29 17:59:59').getTime(),
        activeTStamp5 = new Date('2017/6/29 18:00:01').getTime(),  // active count down time
        expireTStamp5 = new Date('2017/6/29 19:59:59').getTime(),
        activeTStamp6 = new Date('2017/6/29 20:00:01').getTime(),  // active count down time
        expireTStamp6 = new Date('2017/6/29 22:00:00').getTime(),
        currentTStamp; // current time
    seckillRun();
    //切换模块
    $('.table').on('click', function() {
        var top = $(".container").scrollTop();
        if (top > 256) {
          $(".container").scrollTop('256')
        } else {
          $(".container").scrollTop('1660')
        }
    })
})