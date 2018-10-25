$(function() {
    // 加载优酷视频
    var url = {
        4: 'XMzYzNjgwNzQ3Ng==',
        6: 'XMzYzNjM5NDM2NA==',
        7: 'XMzYzNjUxMjA0MA==',
        11: 'XMzYzNjgxOTgyNA==',
        12: 'XMzYzNjQwNzIyMA==',
        15: 'XMzYzNjQxMTI0NA==',
    }
    $.each(url, function(i ,val) {
        new YKU.Player('youkuplayer-' + i,{
            styleid: '0',
            client_id: '73c46fdbebb30e76',
            vid: val,
            newPlayer: true,
            autoplay: false
        });
    })
})