// fallbacks
(function() {
    var lastTime = 0;
    var vendors = ['webkit', 'moz'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame) {
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16.7 - (currTime - lastTime));
            var id = window.setTimeout(function() {
                callback(currTime + timeToCall);
            }, timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
    }
    if (!window.cancelAnimationFrame) {
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
    }
}());

var raf_scroll;

// scroll animation
function scrollTo(topVal) {
    var scrollArea = $('main.container')[0];
    var currentTop = scrollArea.scrollTop;
    var maxTop = $('main.container div').eq(0).height() - $('main.container').height();
    if(maxTop < topVal) topVal = maxTop;

    switch(true) {
        case topVal > currentTop:
            currentTop += 30;
            if (currentTop >= topVal) currentTop = topVal;
            scrollArea.scrollTop = currentTop;
            raf_scroll = requestAnimationFrame(function(){
                scrollTo(topVal);
            });
            break;
        case topVal < currentTop:
            currentTop -= 30;
            if (currentTop <= topVal) currentTop = topVal;
            scrollArea.scrollTop = currentTop;
            raf_scroll = requestAnimationFrame(function(){
                scrollTo(topVal);
            });
            break;
        default:
            raf_scroll && cancelAnimationFrame(raf_scroll);
            break;
    }
}

$(function(){
    // bind scrollTo evt 
    $('[data-link-section]').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        scrollTo(Math.floor($($(this).attr('href'))[0].offsetTop));
        // touch to stop 
        $('main.container').one('touchstart', function(){
            raf_scroll && cancelAnimationFrame(raf_scroll);
        })
        setTimeout(function() {
            $('main.container').touchstart()
        }, 1000);
    })
});