$('#floating-layer-box').on('click',function(){
    $(this).removeClass('hidden');
});

//底部导航
var $pics = $('#operator-list').find('.operator-pic');
$pics.click(function(event) {
    for(var i=0; i<$pics.length; i++){
        $pics[i].src = $pics[i].src.replace(/_sele.png|_no.png/,'_no.png');
    }
    event.target.src = event.target.src.replace(/_sele.png|_no.png/,'_sele.png');
})