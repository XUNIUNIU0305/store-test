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


window.onload = function(){
    var oTop = document.getElementById("top");
    var screenw = document.documentElement.clientWidth || document.body.clientWidth;
    var screenh = document.documentElement.clientHeight || document.body.clientHeight;
    oTop.style.left = screenw - oTop.offsetWidth +"px";
    oTop.style.top = screenh - oTop.offsetHeight + "px";
    window.onscroll = function(){
        var scrolltop = document.documentElement.scrollTop || document.body.scrollTop;
        oTop.style.top = screenh - oTop.offsetHeight + scrolltop +"px";
    }
    oTop.onclick = function(){
        document.documentElement.scrollTop = document.body.scrollTop =0;
    }
}
