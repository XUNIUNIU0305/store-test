(function(doc, win) {
    $('.J_footer_menu').addClass('hidden');
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function() {
            var clientWidth = docEl.clientWidth;
            if(!clientWidth) return;
            if(clientWidth >= 750) {
                docEl.style.fontSize = '100px';
            } else {
                docEl.style.fontSize = 100 * (clientWidth / 750) + 'px';
            }
        };
    
    if(!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window)

function fontSize (className){
    var size = parseInt($('.' + className).css('font-size'));
    var width = parseInt($('.' + className).css('width'));
    // 行数
    var num = width / size * 2;     //2行
    // 每行字数
    var num1 = width / size;
    var num2 = width / size * 2 - 1; 
    $('.' + className).each(function() {
        if ($(this).text().length > num1) {
            $(this).html($(this).text().replace(/\s+/g, "").substr(0, num2) + "...")
        }
    })
}

$(function(){
    var startTime = Date.now();
    var endTime = new Date(2018, 11, 31, 23, 59, 59).getTime();
    var offsetTime =  Math.abs(endTime - startTime);
    var day = Math.floor(offsetTime / (1000 * 60 * 60 * 24 * 1));
    $('#top_date').text(day);
    
    getData({id:[1156,2148,1708,2204,2377,2403,1951,2384,2407,1209,1696,1210]});
    function getData(params){
        requestUrl('/product-recommend/goods','GET',params,function(data){
            $('#foot-list').html(juicer($('#foot-list-cont').html(),data));
            fontSize('characteristic');
        });
    }
})