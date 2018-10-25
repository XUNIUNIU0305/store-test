$(function() {
    // 加载优酷视频
    var url = {
        2: 'XMzYzNjUwNjY2MA==',
        9: 'XMzYzNjgwOTc4NA==',
        10: 'XMzYzNjgxNjI3Mg==',
        11: 'XMzYzNjk3ODA4OA==',
        13: 'XMzYzNjgyMDU0NA==',
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