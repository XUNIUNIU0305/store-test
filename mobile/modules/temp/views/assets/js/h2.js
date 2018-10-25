(function(doc, win) {
    $('.J_footer_menu').addClass('hidden')
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function() {
            var clientWidth = docEl.clientWidth;
            if(!clientWidth) return;
            if(clientWidth >= 640) {
                docEl.style.fontSize = '100px';
            } else {
                docEl.style.fontSize = 100 * (clientWidth / 640) + 'px';
            }
        };

    if(!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);

$(function(){

    // for(var i = 0; i < 8; i++) {
	// 	var html = `<div class="h-list">
    //     <div class="lis-pic">
    //         <img src="/images/huodong/2220.jpg" alt="">
    //     </div>
    //     <div class="lis-info">
    //         <div class="lis-num"><em> 纳米水晶1件*</em><i>每件24套</i> <span>（限购<em>一</em>单）</span></div>
    //         <a href="/goods/detail?id=" class="lis-buy">立即购买</a>
    //     </div>
    // </div>`
	// 	$("#lis-box").append(html)
    // }
   
    // 返回顶部
    $('#hdpage footer').click(function () {
       $("html,body").scrollTop(0);
    });

    // var ids = [2220,2222];

    // function getPrice(id) {
	// 	requestUrl('/product-recommend/goods', 'GET', { id: id }, function (data) {
	// 		console.log(data)
	// 	})
    // }
    // getPrice(ids)

})