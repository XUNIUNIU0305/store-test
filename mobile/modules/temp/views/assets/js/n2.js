var cArr = ["p1", "p2", "p3"];
var id = '#child_1';
var index = 0;
(function () {
    $(".times a").click(function (e) {
        var sortid = $(this).data("id");
        e.currentTarget.style.color = '#E36147';
        e.currentTarget.style.backgroundColor = '#fff';
        resetStyle(sortid);
        clearInterval(interval);
        // getRTime(e.currentTarget.getAttribute('data-start-time'), e.currentTarget.getAttribute('data-end-time'));
        interval = setInterval(function () {
            getRTime(e.currentTarget.getAttribute('data-start-time'), e.currentTarget.getAttribute('data-end-time'));
        }, 100);
        cArr = ["p1", "p2", "p3"];
        id = "#child_" + sortid;
        $(".item-list-center").addClass("dn");
        $().removeClass("dn");
        slideImg()
    });

    $('#bac').click(function () {
        $('#bac').css('background', "red");
    }, function () {

    });

})();

function resetStyle(id) {
    var eleArr = $(".times a");
    for (var i = 0; i < eleArr.length; i++) {
        if (i + 1 != Number(id)) {
            eleArr[i].style.color = '#fff';
            eleArr[i].style.backgroundColor = '#E36147';
        }
    }
}

function resetTxtStyle(flag) {
    var eleArr = $(".item-list-center")
    for (var i = 0; i < eleArr.length; i++) {
        var display = window.getComputedStyle(eleArr[i], false).display;
        if (display != 'none' && flag) {
            $(eleArr[i]).find('.J_pro_title').css("color", "#b0b0b0");
            $(eleArr[i]).find('.J_pro_price').css("color", "#b0b0b0");
            $(eleArr[i]).find('a').attr("href", "javascript:void(0);");
        } else {
            var url =$(eleArr[i]).find('a')[i] ? $(eleArr[i]).find('a')[i].getAttribute('data-src') : '';
            $(eleArr[i]).find('.box-layout-txt').css("color", '');
            var item =  $(eleArr[i])[i];
            $(eleArr[i]).find(item).attr("href", url);
        }

    }

}

// 响应式
(function (doc, win, fontSize) {
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) {
                return;
            }
            docEl.style.fontSize = fontSize * (clientWidth / 320) + 'px';
        };
    if (!doc.addEventListener) {
        return;
    }
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window, 16);

function getRTime(startDate, endDate) {
    var t = 0;
    var startTime = new Date(startDate);
    var endTime = new Date(endDate); //截止时间
    var nowTime = new Date();
    if (endTime.valueOf() > nowTime.valueOf() && nowTime.valueOf() >= startTime.valueOf()) {
        $('#active-info').text('距离活动结束');
        $('#active-time').show();
        t = endTime.getTime() - nowTime.getTime();
        resetTxtStyle(false)
    } else if (nowTime.valueOf() < startTime.valueOf()) {
        $('#active-info').text('距离活动开始');
        $('#active-time').show();
        t = startTime.getTime() - nowTime.getTime();
        resetTxtStyle(true)
    } else if (nowTime.valueOf() >= endTime.valueOf()) {
        $('#active-info').text('活动已结束!');
        t = 0;
        $('#active-time').hide();
        resetTxtStyle(true)
    }
    if(nowTime.valueOf() > new Date('2018/09/29 00:00:00')){
        $('.floor0').css('display','none');
        $('.header-nav').find('li').eq(0).css('display','none');
    }
    /*var d=Math.floor(t/1000/60/60/24);
    t-=d*(1000*60*60*24);
    var h=Math.floor(t/1000/60/60);
    t-=h*60*60*1000;
    var m=Math.floor(t/1000/60);
    t-=m*60*1000;
    var s=Math.floor(t/1000);*/
    t = t > 0 ? t : 0
    var d = Math.floor(t / 1000 / 60 / 60 / 24);
    var h = Math.floor(t / 1000 / 60 / 60 % 24);
    var m = Math.floor(t / 1000 / 60 % 60);
    var s = Math.floor(t / 1000 % 60);

    if (d < 10) {
        d = "0" + d;
    }
    if (h < 10) {
        h = "0" + h;
    }
    if (m < 10) {
        m = "0" + m;
    }
    if (s < 10) {
        s = "0" + s;
    }
    document.getElementById("t_d").innerHTML = d;
    document.getElementById("t_h").innerHTML = h;
    document.getElementById("t_m").innerHTML = m;
    document.getElementById("t_s").innerHTML = s;
}
// getRTime('2018/09/19 10:00:00', '2018/09/19 23:59:59');
var interval = setInterval(function () {
    getRTime('2018/09/19 10:00:00', '2018/09/19 23:59:59');
}, 100)

function slideImg() {
    var id_arr = [];
    $.each($('.item[data-id]'), function (i, val) {
        id_arr.push($(val).data('id'))
    })

    function getPrice(id) {
        requestUrl(
            '/product-recommend/goods',
            'GET',
            {id: id},
            function (data) {
                $.each(data, function (index, val) {
                    $('.item[data-id="' + val.id + '"]').find('.J_pro_price').text('￥' + val.price.min) //.toFixed(2)
                    $('.item[data-id="' + val.id + '"]').find('.J_pro_title').text(val.title)
                    $('.item[data-id="' + val.id + '"]').find('.J_pro_img').attr('src', val.main_image)
                })
            }
        )
    }

    getPrice(id_arr);
}

slideImg()

$(".next").click(
    function () {
        nextimg();
    }
)
$(".prev").click(
    function () {
        previmg();
    }
)

//上一张
function previmg() {
    cArr.unshift(cArr[2]);
    cArr.pop();
    //i是元素的索引，从0开始
    //e为当前处理的元素
    //each循环，当前处理的元素移除所有的class，然后添加数组索引i的class
    $(id).find('li').each(function (i, e) {
        $(e).removeClass().addClass(cArr[i]);
    })
    index--;
    if (index < 0) {
        index = 2;
    }
}

//下一张
function nextimg() {
    cArr.push(cArr[0]);
    cArr.shift();
    $(id).find('li').each(function (i, e) {
        $(e).removeClass().addClass(cArr[i]);
    });
    index++;
    if (index > 2) {
        index = 0;
    }
}
